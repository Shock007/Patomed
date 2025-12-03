<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si existe la sesión del usuario
        if (!session()->has('usuario_id')) {
            
            // Si es una petición AJAX, retornar JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Sesión expirada',
                    'redirect' => route('login.index')
                ], 401);
            }
            
            // Limpiar cualquier resto de sesión
            session()->flush();
            session()->regenerate();
            
            // Redirigir al login con mensaje
            return redirect()->route('login.index')
                           ->with('info', 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.');
        }

        // NOTA: El timeout de inactividad se maneja ahora desde JavaScript
        // en session-monitor.js, no desde el middleware para evitar cierres
        // prematuros al navegar entre pestañas

        // Regenerar el ID de sesión periódicamente para seguridad (cada 30 minutos)
        if (!$request->session()->has('last_regeneration')) {
            $request->session()->put('last_regeneration', now());
            $request->session()->regenerate();
        } else {
            $lastRegeneration = $request->session()->get('last_regeneration');
            if (now()->diffInMinutes($lastRegeneration) > 30) {
                $request->session()->regenerate();
                $request->session()->put('last_regeneration', now());
            }
        }

        return $next($request);
    }
}