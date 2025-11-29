<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Estudio;
use App\Http\Requests\StorePacienteRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::with(['ultimoEstudio.usuario'])
                            ->orderBy('fecha_creacion', 'desc')
                            ->paginate(15);
        
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

            // Convertir sexo de texto a código
            $sexoCode = $request->sexo === 'Masculino' || $request->sexo === 'm' ? 'm' : 'f';

            // 1. Crear paciente
            $paciente = Paciente::create([
                'codigo' => $request->codigo,
                'nombre' => strtoupper($request->nombre),
                'apellidos' => strtoupper($request->apellidos),
                'cedula' => $request->cedula,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'eps' => $request->eps,
                'sexo' => $sexoCode,
            ]);

            // 2. Generar código único para el estudio
            $contadorEstudios = Estudio::where('id_paciente', $paciente->id_paciente)->count() + 1;
            $codigoEstudio = sprintf('%s-EST-%04d', $request->codigo, $contadorEstudios);

            // 3. Crear estudio
            Estudio::create([
                'id_paciente' => $paciente->id_paciente,
                'id_usuario' => session('usuario_id'),
                'codigo_estudio' => $codigoEstudio,
                'fecha' => now(),
                'descripcion_macro' => $request->descripcion_macroscopica,
                'descripcion_micro' => $request->descripcion_microscopica,
                'diagnostico' => $request->diagnostico,
                'resultado' => $request->resultado,
                'estado' => 1, // Validado automáticamente
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
}