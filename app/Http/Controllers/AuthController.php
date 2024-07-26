<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

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

    public function createpost(Request $request)
{
    // Validar los datos del formulario
    $validator = Validator::make($request->all(), [
        'nombres' => 'required|string|max:255',
        'apellidos' => 'required|string|max:255',
        'tipoDocumento' => 'required|string|max:255',
        'numeroDocumento' => 'required|string|max:255|unique:usuarios,numero_documento',
        'correo_personal' => 'required|email|max:255|unique:usuarios,correo_personal',
        'correo_institucional' => 'required|email|max:255|unique:usuarios,correo_institucional',
        'contrasena' => 'required|string|min:6|confirmed',
        'telefono' => 'required|string|max:20',
        'rol' => 'required|integer|in:3,4,5',
        'numeroFicha' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
                         ->withErrors($validator)
                         ->withInput();
    }

    try {
        // Crear el nuevo usuario
        $user = new User();
        $user->nombres = $request->input('nombres');
        $user->apellidos = $request->input('apellidos');
        $user->tipo_documento = $request->input('tipoDocumento');
        $user->numero_documento = $request->input('numeroDocumento');
        $user->correo_personal = $request->input('correo_personal');
        $user->correo_institucional = $request->input('correo_institucional');
        $user->contraseña = Hash::make($request->input('contrasena'));
        $user->telefono = $request->input('telefono');
        $user->roles_id = $request->input('rol');
        $user->numero_ficha = $request->input('numeroFicha');
        $user->save();

        return redirect()->route('login')->with('success', 'Registro exitoso, por favor inicie sesión.');
    } catch (QueryException $e) {
        if ($e->errorInfo[0] == '23505') { // Código de error para violación de unicidad en PostgreSQL
            return redirect()->back()->with('error', 'Error: El documento o correo ya está registrado.')
                                     ->withInput();
        }

        // Otra lógica de manejo de errores
        return redirect()->back()->with('error', 'Ha ocurrido un error al registrar el usuario.')
                                 ->withInput();
    }
}


}
