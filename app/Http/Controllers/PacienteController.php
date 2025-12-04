<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Estudio;
use App\Http\Requests\StorePacienteRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    /**
     * Listado con búsqueda por código, cédula, nombre Y FECHA
     */
    public function index(Request $request)
    {
        $query = Paciente::with(['ultimoEstudio.usuario']);
        
        // Búsqueda dinámica por código/cédula/nombre
        if ($request->filled('busqueda')) {
            $termino = $request->busqueda;
            $tipoBusqueda = $request->tipo_busqueda ?? 'codigo';
            
            switch ($tipoBusqueda) {
                case 'cedula':
                    $query->where('cedula', 'LIKE', "%{$termino}%");
                    break;
                case 'nombre':
                    $query->where(function($q) use ($termino) {
                        $q->where('nombre', 'LIKE', "%{$termino}%")
                          ->orWhere('apellidos', 'LIKE', "%{$termino}%")
                          ->orWhereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$termino}%"]);
                    });
                    break;
                default: // codigo
                    $query->where('codigo', 'LIKE', "%{$termino}%");
            }
        }

        // NUEVO: Filtro por fecha
        if ($request->filled('tipo_fecha') && $request->filled('valor_fecha')) {
            $tipoFecha = $request->tipo_fecha;
            $valorFecha = $request->valor_fecha;

            switch ($tipoFecha) {
                case 'fecha_especifica':
                    $query->whereDate('fecha_creacion', $valorFecha);
                    break;
                    
                case 'mes':
                    // Formato: YYYY-MM
                    if (preg_match('/^\d{4}-\d{2}$/', $valorFecha)) {
                        $query->whereYear('fecha_creacion', substr($valorFecha, 0, 4))
                              ->whereMonth('fecha_creacion', substr($valorFecha, 5, 2));
                    }
                    break;
                    
                case 'anio':
                    // Formato: YYYY
                    if (preg_match('/^\d{4}$/', $valorFecha)) {
                        $query->whereYear('fecha_creacion', $valorFecha);
                    }
                    break;
            }
        }
        
        $pacientes = $query->orderBy('fecha_creacion', 'desc')->paginate(15);
        
        return view('panel_inicio.busqueda', compact('pacientes'));
    }

    public function create()
    {
        return view('panel_inicio.ingreso');
    }

    public function store(StorePacienteRequest $request)
    {
        try {
            DB::beginTransaction();

            $sexoCode = $request->sexo === 'Masculino' || $request->sexo === 'm' ? 'm' : 'f';

            $paciente = Paciente::create([
                'codigo' => $request->codigo,
                'nombre' => strtoupper($request->nombre),
                'apellidos' => strtoupper($request->apellidos),
                'cedula' => $request->cedula,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'eps' => $request->eps,
                'sexo' => $sexoCode,
            ]);

            $contadorEstudios = Estudio::where('id_paciente', $paciente->id_paciente)->count() + 1;
            $codigoEstudio = sprintf('%s-EST-%04d', $request->codigo, $contadorEstudios);

            Estudio::create([
                'id_paciente' => $paciente->id_paciente,
                'id_usuario' => session('usuario_id'),
                'codigo_estudio' => $codigoEstudio,
                'fecha' => now(),
                'descripcion_macro' => $request->descripcion_macroscopica,
                'descripcion_micro' => $request->descripcion_microscopica,
                'diagnostico' => $request->diagnostico,
                'resultado' => $request->resultado,
                'estado' => 0, // Siempre inicia como NO VALIDADO
            ]);

            DB::commit();

            return redirect()->route('pacientes.index')
                           ->with('success', 'Paciente y estudio registrados correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $paciente = Paciente::with(['estudios' => function($query) {
            $query->orderBy('fecha_registro', 'desc');
        }, 'estudios.usuario'])->findOrFail($id);

        return view('panel_inicio.detalle', compact('paciente'));
    }

    public function edit($id)
    {
        $paciente = Paciente::with('ultimoEstudio')->findOrFail($id);
        return view('panel_inicio.editar', compact('paciente'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellidos' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'cedula' => 'required|string|max:50|regex:/^[0-9]+$/',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'eps' => 'nullable|string|max:100',
            'sexo' => 'required|in:m,f',
            'descripcion_macroscopica' => 'nullable|string|max:5000',
            'descripcion_microscopica' => 'nullable|string|max:5000',
            'diagnostico' => 'nullable|string|max:2000',
            'resultado' => 'required|in:0,1',
        ]);

        try {
            DB::beginTransaction();

            $paciente = Paciente::findOrFail($id);

            $paciente->update([
                'nombre' => strtoupper($request->nombre),
                'apellidos' => strtoupper($request->apellidos),
                'cedula' => $request->cedula,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'eps' => $request->eps,
                'sexo' => $request->sexo,
            ]);

            // Actualizar último estudio (mantener estado de validación)
            $ultimoEstudio = $paciente->ultimoEstudio;
            if ($ultimoEstudio) {
                $ultimoEstudio->update([
                    'descripcion_macro' => $request->descripcion_macroscopica,
                    'descripcion_micro' => $request->descripcion_microscopica,
                    'diagnostico' => $request->diagnostico,
                    'resultado' => $request->resultado,
                ]);
            }

            DB::commit();

            return redirect()->route('pacientes.index')
                           ->with('success', 'Paciente actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $paciente = Paciente::findOrFail($id);
            $codigoPaciente = $paciente->codigo;

            $paciente->delete();

            DB::commit();

            return redirect()->route('pacientes.index')
                           ->with('success', "Paciente {$codigoPaciente} eliminado exitosamente");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    /**
     * NUEVO: Validar paciente (captura fecha de validación)
     */
    public function validar($id)
    {
        try {
            $paciente = Paciente::with('ultimoEstudio')->findOrFail($id);

            if (!$paciente->ultimoEstudio) {
                return back()->with('error', 'El paciente no tiene estudios registrados');
            }

            $paciente->ultimoEstudio->update([
                'estado' => 1, // Validado
                'fecha_validacion' => now()
            ]);

            return back()->with('success', "Paciente {$paciente->codigo} validado correctamente");

        } catch (\Exception $e) {
            return back()->with('error', 'Error al validar: ' . $e->getMessage());
        }
    }

    /**
     * NUEVO: Invalidar paciente (permite edición)
     */
    public function invalidar($id)
    {
        try {
            $paciente = Paciente::with('ultimoEstudio')->findOrFail($id);

            if (!$paciente->ultimoEstudio) {
                return back()->with('error', 'El paciente no tiene estudios registrados');
            }

            $paciente->ultimoEstudio->update([
                'estado' => 0, // Parcial
                'fecha_validacion' => null
            ]);

            return back()->with('success', "Paciente {$paciente->codigo} marcado como no validado");

        } catch (\Exception $e) {
            return back()->with('error', 'Error al invalidar: ' . $e->getMessage());
        }
    }
}