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
     * Listado con búsqueda por código, cédula o nombre
     */
    public function index(Request $request)
    {
        $query = Paciente::with(['ultimoEstudio.usuario']);
        
        // Búsqueda dinámica
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
                'estado' => 1,
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

    /**
     * NUEVA: Mostrar formulario de edición
     */
    public function edit($id)
    {
        $paciente = Paciente::with('ultimoEstudio')->findOrFail($id);
        return view('panel_inicio.editar', compact('paciente'));
    }

    /**
     * NUEVA: Actualizar paciente y último estudio
     */
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
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.regex' => 'El nombre solo puede contener letras',
            'apellidos.required' => 'Los apellidos son obligatorios',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras',
            'cedula.required' => 'La cédula es obligatoria',
            'cedula.regex' => 'La cédula solo puede contener números',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'sexo.required' => 'El sexo es obligatorio',
            'resultado.required' => 'El resultado es obligatorio',
        ]);

        try {
            DB::beginTransaction();

            $paciente = Paciente::findOrFail($id);

            // Actualizar datos del paciente
            $paciente->update([
                'nombre' => strtoupper($request->nombre),
                'apellidos' => strtoupper($request->apellidos),
                'cedula' => $request->cedula,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'eps' => $request->eps,
                'sexo' => $request->sexo,
            ]);

            // Actualizar último estudio si existe
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

    /**
     * NUEVA: Eliminar paciente y estudios relacionados
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $paciente = Paciente::findOrFail($id);
            $codigoPaciente = $paciente->codigo;

            // La eliminación en cascada está configurada en la BD
            // Esto eliminará automáticamente todos los estudios relacionados
            $paciente->delete();

            DB::commit();

            return redirect()->route('pacientes.index')
                           ->with('success', "Paciente {$codigoPaciente} eliminado exitosamente");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}