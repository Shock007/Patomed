<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;

class PacienteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::orderBy('id_paciente','desc')->paginate(25);
        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'codigo'=>'required|string|max:50|unique:pacientes,codigo',
            'nombre'=>'required|string|max:100',
            'apellidos'=>'required|string|max:100',
            'cedula'=>'required|string|max:50',
            'fecha_nacimiento'=>'nullable|date',
            'eps'=>'nullable|string|max:100',
            'sexo'=>'required|in:m,f'
        ]);

        $paciente = Paciente::create($data);
        return redirect()->route('pacientes.show', $paciente->id_paciente)
                         ->with('success','Paciente creado');
    }

    public function show($id)
    {
        $paciente = Paciente::findOrFail($id);
        return view('pacientes.show', compact('paciente'));
    }
}
