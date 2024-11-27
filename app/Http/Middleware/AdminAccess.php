<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Verifica si el usuario está autenticado
        if (!$user) {
            return redirect('login')->withErrors(['error' => 'Debes iniciar sesión para acceder a esta página.']);
        }

        // Verifica si el usuario tiene el rol de administrador
        // (Asegúrate de que el ID del rol de administrador sea 1, ajústalo según tu base de datos)
        if ($user->roles_id !== 1) {
            return redirect('/')->withErrors(['error' => 'No tienes permisos para acceder a esta página.']);
        }

        return $next($request);
    }
}
