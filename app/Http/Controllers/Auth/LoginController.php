<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // crea esta vista si no existe
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => ['required','string'],
            'password' => ['required','string'],
        ]);

        // intenta autenticación usando 'usuario' y la contraseña normal
        if (Auth::attempt(['usuario' => $credentials['usuario'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); // ajuste a tu ruta
        }

        return back()->withErrors([
            'usuario' => 'Credenciales incorrectas.'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
