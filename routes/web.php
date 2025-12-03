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
    // Si ya tiene sesión activa, redirigir al panel
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
        // Regenerar sesión para prevenir fijación de sesión
        $request->session()->regenerate();
        
        // **NUEVO: Inicializar timestamp de última actividad**
        session([
            'usuario_id' => $usuario->id_usuario,
            'last_activity_time' => time(),
            'last_regeneration' => now()
        ]);
        
        return redirect()->route('pacientes.create')
                       ->with('success', 'Bienvenido al sistema Patomed');
    }

    return back()->with('error', 'Credenciales incorrectas');
})->name('login.auth');

Route::middleware('auth.session')->group(function () {
    
    // **ÚNICO LOGOUT: Manual (botón de cerrar sesión)**
    Route::post('/logout', function (Request $request) {
        // Limpiar completamente la sesión
        session()->flush();
        
        // Invalidar la sesión actual
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login.index')
                       ->with('success', 'Ha cerrado sesión correctamente');
    })->name('logout');

    // **ELIMINAR /logout-auto** - Ya no es necesario

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
    
    // Reporte General
    Route::get('/reportes/general/pdf', [ReporteController::class, 'exportGeneralPDF'])->name('reportes.general-pdf');
    Route::get('/reportes/general/excel', [ReporteController::class, 'exportGeneralExcel'])->name('reportes.general-excel');
});