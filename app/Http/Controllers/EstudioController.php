<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudio;
use App\Models\Paciente;
use Illuminate\Support\Facades\Auth;

class EstudioController extends Controller
{
    public function create($pacienteId)
    {
        $paciente = Paciente::findOrFail($pacienteId);
        return view('estudios.create', compact('paciente'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'id_paciente'=>'required|integer|exists:pacientes,id_paciente',
            'codigo_estudio'=>'required|string|max:50|unique:estudios,codigo_estudio',
            'descripcion_macro'=>'nullable|string',
            'descripcion_micro'=>'nullable|string',
            'diagnostico'=>'nullable|string',
            'resultado'=>'required|boolean',
            'estado'=>'required|in:0,1'
        ]);

        $data['id_usuario'] = Auth::user()->id_usuario;
        $estudio = Estudio::create($data);

        return redirect()->route('estudios.show', $estudio->id_estudio)
                         ->with('success','Estudio guardado');
    }

    public function show($id)
    {
        $estudio = Estudio::with('paciente','usuario')->findOrFail($id);
        return view('estudios.show', compact('estudio'));
    }
}
