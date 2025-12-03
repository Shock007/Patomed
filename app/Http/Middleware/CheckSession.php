<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    /**
     * Tiempo máximo de inactividad en segundos (2 minutos = 120 segundos)
     */
    const INACTIVITY_TIMEOUT = 120;

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
            
            // Redirigir al login con mensaje
            return redirect()->route('login.index')
                           ->with('info', 'Su sesión ha expirado por inactividad.');
        }

        // **NUEVO: Sistema de Timeout por Inactividad**
        $lastActivity = session('last_activity_time', time());
        $currentTime = time();
        $inactiveTime = $currentTime - $lastActivity;

        // Si la inactividad supera 2 minutos, cerrar sesión
        if ($inactiveTime >= self::INACTIVITY_TIMEOUT) {
            // Destruir completamente la sesión
            session()->flush();
            session()->regenerate();
            
            // Redirigir al login con mensaje de timeout
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Sesión cerrada por inactividad',
                    'redirect' => route('login.index')
                ], 401);
            }
            
            return redirect()->route('login.index')
                           ->with('info', 'Su sesión se cerró por inactividad (2 minutos sin uso).');
        }

        // **ACTUALIZAR el timestamp de última actividad**
        session(['last_activity_time' => $currentTime]);

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