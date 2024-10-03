<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Verifica si el usuario está autenticado
        if (!$user) {
            return redirect('login')->withErrors(['error' => 'Debes iniciar sesión para acceder a esta página.']);
        }

        // Verifica si el rol del usuario está permitido
        if (!in_array($user->roles_id, $roles)) {
            return redirect('/')->withErrors(['error' => 'No tienes permisos para acceder a esta página.']);
        }

        return $next($request);
    }
}