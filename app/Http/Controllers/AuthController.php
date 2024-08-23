<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    // Mostrar formulario de inicio de sesión
    public function showLogin()
    {
        return view('auth.login');
    }

    // Mostrar formulario de registro
    public function create()
    {
        return view('auth.create');
    }

    // Manejar el registro de un nuevo usuario
    public function createpost(Request $request)
    {
        Log::info('Datos del formulario:', $request->all());

        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'tipo_documento' => 'required|string|max:255',
            'numero_documento' => 'required|string|max:255|unique:usuarios',
            'correo_personal' => 'required|email|max:255|unique:usuarios',
            'correo_institucional' => 'required|email|max:255|unique:usuarios',
            'telefono' => 'required|string|max:20',
            'contraseña' => 'required|string|min:6|confirmed',
            'rol' => 'required|exists:roles,id',
            'numero_ficha' => $request->input('rol') == 3 ? 'required|string|max:255' : 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:6144'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $usuario = new Usuario();
            $usuario->nombres = $request->nombres;
            $usuario->apellidos = $request->apellidos;
            $usuario->tipo_documento = $request->tipo_documento;
            $usuario->numero_documento = $request->numero_documento;
            $usuario->correo_personal = $request->correo_personal;
            $usuario->correo_institucional = $request->correo_institucional;
            $usuario->telefono = $request->telefono;
            $usuario->numero_ficha = $request->input('numero_ficha');
            $usuario->contraseña = Hash::make($request->contraseña);
            $usuario->roles_id = $request->rol;  // Asigna el rol seleccionado

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $path = $file->store('public/fotos_perfil');
                $usuario->foto = basename($path);
            }

            $usuario->save();

            Auth::login($usuario);

            return redirect()->route('user.panel')->with('success', 'Registro exitoso. ¡Bienvenido!');
        } catch (QueryException $e) {
            Log::error('Error al registrar el usuario: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Ha ocurrido un error al registrar el usuario.'])->withInput();
        }
    }

    // Manejar el inicio de sesión
    public function login(Request $request)
    {
        $request->validate([
            'correo_institucional' => 'required|email',
            'contraseña' => 'required|string|min:6',
        ]);

        $credentials = [
            'correo_institucional' => $request->correo_institucional,
            'password' => $request->contraseña,
        ];

        if (Auth::guard('web')->attempt($credentials)) {
            $usuario = Auth::user();
            Log::info('Usuario autenticado', ['user' => $usuario]);

            switch ($usuario->roles_id) {
                case 3:
                    return redirect()->route('user.panel');
                case 4:
                    return redirect()->route('user.panel');
                case 5:
                    return redirect()->route('user.panel');
                default:
                    return redirect('/')->with('error', 'Rol no reconocido');
            }
        } else {
            return redirect()->back()->withErrors(['correo_institucional' => 'Las credenciales no coinciden con nuestros registros.']);
        }
    }

    // Manejar el cierre de sesión
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    // Actualizar el perfil del usuario autenticado
    public function updateProfile(Request $request)
    {
        $usuario = Auth::user();
        $esAdmin = $usuario->roles_id == 1;

        if (!$esAdmin) {
            // Mantén los valores actuales del usuario si no es admin
            $request->merge([
                'correo_personal' => $usuario->correo_personal,
                'correo_institucional' => $usuario->correo_institucional,
                'contraseña' => $usuario->contraseña,
                'tipo_documento' => $usuario->tipo_documento,
                'numero_documento' => $usuario->numero_documento,
                'numero_ficha' => $usuario->numero_ficha,
            ]);
        }

        // Definir las reglas de validación
        $rules = [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'tipo_documento' => 'required|string|max:255',
            'numero_documento' => 'required|string|max:255',
            'correo_personal' => 'required|email|max:255',
            'correo_institucional' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'numero_ficha' => 'nullable|required_if:rol,3|string|max:255', // Requerido solo si el rol es 'Aprendiz'
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        // Validar los datos del request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        try {
            // Manejo del archivo de foto
            if ($request->hasFile('foto')) {
                if ($usuario->foto) {
                    Storage::delete('public/fotos_perfil/' . $usuario->foto);
                }

                $file = $request->file('foto');
                $path = $file->store('public/fotos_perfil');
                $usuario->foto = basename($path);
            }

            // Actualizar el perfil del usuario
            $usuario->update($request->except('foto'));

            // Devolver una respuesta con los datos actualizados
            return response()->json([
                'success' => 'Perfil actualizado con éxito.',
                'user' => $usuario
            ]);
        } catch (QueryException $e) {
            Log::error('Error al actualizar el perfil: ' . $e->getMessage());
            return response()->json(['error' => 'Ha ocurrido un error al actualizar el perfil.'], 500);
        }
    }
}
