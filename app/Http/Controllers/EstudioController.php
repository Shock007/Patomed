<?php

namespace App\Http\Controllers;

use App\Models\Estudio;
use Illuminate\Http\Request;

class EstudioController extends Controller
{
    public function show($id)
    {
        $estudio = Estudio::with(['paciente', 'usuario'])->findOrFail($id);
        
        return view('panel_inicio.reporte_detallado', compact('estudio'));
    }
}