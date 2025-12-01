<?php

use App\Http\Controllers\PacienteController;
use App\Http\Controllers\EstudioController;
use App\Http\Controllers\ReporteController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login.index');
});

Route::get('/login', function () {
    if (session()->has('usuario_id')) {
        return redirect()->route('pacientes.create');
    }
    return view('login.index');
})->name('login.index');

Route::post('/login', function (Request $request) {
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $usuario = User::where('usuario', $request->username)->first();

    if ($usuario && Hash::check($request->password, $usuario->password_hash)) {
        session(['usuario_id' => $usuario->id_usuario]);
        return redirect()->route('pacientes.create');
    }

    return back()->with('error', 'Credenciales incorrectas');
})->name('login.auth');

Route::middleware('auth.session')->group(function () {
    
    Route::post('/logout', function () {
        session()->flush();
        return redirect()->route('login.index');
    })->name('logout');

    // Pacientes - RUTAS COMPLETAS
    Route::get('/pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
    Route::get('/pacientes/crear', [PacienteController::class, 'create'])->name('pacientes.create');
    Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
    Route::get('/pacientes/{id}', [PacienteController::class, 'show'])->name('pacientes.show');
    Route::get('/pacientes/{id}/editar', [PacienteController::class, 'edit'])->name('pacientes.edit');
    Route::put('/pacientes/{id}', [PacienteController::class, 'update'])->name('pacientes.update');
    Route::delete('/pacientes/{id}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');

    // Estudios
    Route::get('/estudios/{id}', [EstudioController::class, 'show'])->name('estudios.show');

    // Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/pdf/{id}', [ReporteController::class, 'exportPDF'])->name('reportes.pdf');
    Route::get('/reportes/excel/{id}', [ReporteController::class, 'exportExcel'])->name('reportes.excel');
    
    // NUEVAS RUTAS: Reporte General
    Route::get('/reportes/general/pdf', [ReporteController::class, 'exportGeneralPDF'])->name('reportes.general-pdf');
    Route::get('/reportes/general/excel', [ReporteController::class, 'exportGeneralExcel'])->name('reportes.general-excel');
});