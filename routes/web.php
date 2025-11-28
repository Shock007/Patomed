<?php

use App\Models\Paciente;
use App\Models\Estudio;
use App\Models\User;   // <-- tu modelo real DEBE llamarse User.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Redirección principal
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login.index');
});

/*
|--------------------------------------------------------------------------
| Middleware para proteger rutas
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->group(function () {

    // ===========================
    // LOGIN - GET
    // ===========================
    Route::get('/login', function () {
        // si ya hay usuario logueado → manda al panel
        if (session()->has('usuario_id')) {
            return redirect()->route('panel_inicio.ingreso');
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

        // Buscar usuario con el campo "usuario"
        $usuario = User::where('usuario', $request->username)->first();

        if ($usuario && Hash::check($request->password, $usuario->password_hash)) {

            session(['usuario_id' => $usuario->id_usuario]);

            return redirect()->route('panel_inicio.ingreso');
        }

        return back()->with('error', 'Credenciales incorrectas');
    })->name('login.auth');


    // ===========================
    // LOGOUT
    // ===========================
    Route::post('/logout', function () {
        session()->forget('usuario_id');
        return redirect()->route('login.index');
    })->name('logout');


    /*
    |--------------------------------------------------------------------------
    | RUTAS PROTEGIDAS
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth.session')->group(function () {

        // ===========================
        // PANEL DE INGRESO
        // ===========================
        Route::get('panel_inicio/ingreso', function () {
            return view('panel_inicio.ingreso');
        })->name('panel_inicio.ingreso');



        // ===========================
        // GUARDAR PACIENTE + ESTUDIO
        // ===========================
        Route::post('panel_inicio/ingreso', function (Request $request) {

            $request->validate([
                'codigo' => 'required|string|max:50|unique:pacientes,codigo',
                'nombre' => 'required|string|max:100',
                'apellidos' => 'required|string|max:100',
                'cedula' => 'required|string|max:50',
                'fecha_nacimiento' => 'nullable|date',
                'eps' => 'nullable|string|max:100',
                'sexo' => 'required|in:Masculino,Femenino',

                'descripcion_macroscopica' => 'nullable|string',
                'descripcion_microscopica' => 'nullable|string',
                'diagnostico' => 'nullable|string',
                'resultado' => 'required|in:Positivo,Negativo',
            ]);

            try {

                // ===========================
                // 1. Crear paciente
                // ===========================
                $paciente = Paciente::create([
                    'codigo' => $request->codigo,
                    'nombre' => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'cedula' => $request->cedula,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'eps' => $request->eps,
                    'sexo' => $request->sexo === 'Masculino' ? 'm' : 'f'
                ]);


                // ===========================
                // 2. Crear estudio
                // ===========================
                $codigoEstudio = $request->codigo . '-EST-' . now()->format('His');

                Estudio::create([
                    'id_paciente' => $paciente->id_paciente,
                    'codigo_estudio' => $codigoEstudio,
                    'descripcion_macro' => $request->descripcion_macroscopica,
                    'descripcion_micro' => $request->descripcion_microscopica,
                    'diagnostico' => $request->diagnostico,
                    'resultado' => $request->resultado === 'Positivo' ? 1 : 0,
                    'estado' => 1, // validado automáticamente
                    'id_usuario' => session('usuario_id')
                ]);

                return redirect()->route('panel_inicio.busqueda')
                                 ->with('success', 'Paciente y estudio registrados correctamente');

            } catch (\Exception $e) {
                return back()->with('error', 'Error: ' . $e->getMessage());
            }

        })->name('panel_inicio.ingreso.store');



        // ===========================
        // BÚSQUEDA
        // ===========================
        Route::get('panel_inicio/busqueda', function () {
            $pacientes = Paciente::with('estudios')->get();
            return view('panel_inicio.busqueda', compact('pacientes'));
        })->name('panel_inicio.busqueda');



        // ===========================
        // REPORTES
        // ===========================
        Route::get('panel_inicio/reportes', function () {
            return view('panel_inicio.reportes');
        })->name('panel_inicio.reportes');
    });
});
