<?php

use App\Http\Controllers\PacienteController;
use App\Http\Controllers\EstudioController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// Redirección principal
Route::get('/', function () {
    return redirect()->route('login.index');
});

// ===========================
// LOGIN - GET
// ===========================
Route::get('/login', function () {
    if (session()->has('usuario_id')) {
        return redirect()->route('pacientes.create');
    }
    return view('login.index');
})->name('login.index');

// ===========================
// LOGIN - POST
// ===========================
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

// ===========================
// RUTAS PROTEGIDAS
// ===========================
Route::middleware('auth.session')->group(function () {
    
    // Logout
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

    // Reportes
    Route::get('/reportes', function() {
        return view('panel_inicio.reportes');
    })->name('reportes.index');

    // Alias para compatibilidad
    Route::get('/panel_inicio/ingreso', function() {
        return redirect()->route('pacientes.create');
    });
    
    Route::get('/panel_inicio/busqueda', function() {
        return redirect()->route('pacientes.index');
    });
    
    Route::get('/panel_inicio/reportes', function() {
        return redirect()->route('reportes.index');
    });
});