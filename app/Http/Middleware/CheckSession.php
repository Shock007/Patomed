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

        // Verificar tiempo de última actividad (opcional, para seguridad adicional)
        $lastActivity = session('last_activity_time');
        $timeout = 5 * 60; // 5 minutos en segundos
        
        if ($lastActivity && (time() - $lastActivity > $timeout)) {
            // Sesión expirada por inactividad
            session()->flush();
            session()->regenerate();
            
            return redirect()->route('login.index')
                           ->with('info', 'Su sesión ha expirado por inactividad. Por favor, inicie sesión nuevamente.');
        }
        
        // Actualizar tiempo de última actividad
        session(['last_activity_time' => time()]);

        // Regenerar el ID de sesión periódicamente para seguridad
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