<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('usuario_id')) {
            return redirect()->route('login.index')
                           ->with('error', 'Debe iniciar sesión para acceder');
        }

        return $next($request);
    }
}