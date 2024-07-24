<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    // Validar las credenciales de entrada
    $request->validate([
        'correo_institucional' => 'required|email',
        'contraseña' => 'required|string|min:6',
    ]);

    // Las credenciales de autenticación
    $credentials = [
        'correo_institucional' => $request->correo_institucional,
        'password' => $request->contraseña,
    ];
    Log::info('Intento de inicio de sesión', ['credentials' => $credentials]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        Log::info('Usuario autenticado', ['user' => $user]);

        // Redirigir según el rol del usuario
        switch ($user->roles_id) {
            case 1:
                return redirect()->route('admin.panel');
            case 2:
                return redirect()->route('control.panel');
            case 3:
                return redirect()->route('user.panel');
            default:
                return redirect('/')->with('error', 'Rol no reconocido');
        }
    } else {
        return redirect()->back()->withErrors(['correo_institucional' => 'Las credenciales no coinciden con nuestros registros.']);
    }
}
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    public function create()
    {
        return view('auth.create');
    }
}
