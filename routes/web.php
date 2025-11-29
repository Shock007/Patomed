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

    // Pacientes
    Route::get('/pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
    Route::get('/pacientes/crear', [PacienteController::class, 'create'])->name('pacientes.create');
    Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
    Route::get('/pacientes/{id}', [PacienteController::class, 'show'])->name('pacientes.show');

    // Estudios
    Route::get('/estudios/{id}', [EstudioController::class, 'show'])->name('estudios.show');

    // Reportes - NUEVAS RUTAS
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/pdf/{id}', [ReporteController::class, 'exportPDF'])->name('reportes.pdf');
    Route::get('/reportes/excel/{id}', [ReporteController::class, 'exportExcel'])->name('reportes.excel');
});