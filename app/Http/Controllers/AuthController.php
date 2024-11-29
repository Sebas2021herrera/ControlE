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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
    public function resetpass()
    {
        return view('auth.manual_reset');
    }

    //funcion para restablecer contraseña

    public function manualResetPassword(Request $request)
{
    $request->validate([
        'correo_personal' => 'required|email|exists:usuarios,correo_personal',
    ]);

    try {
        // Buscar el usuario por su correo personal
        $usuario = Usuario::where('correo_personal', $request->correo_personal)->first();

        if (!$usuario) {
            return back()->withErrors(['correo_personal' => 'El correo no está registrado.']);
        }

        // Generar una nueva contraseña
        $nuevaContraseña = Str::random(8);

        // Actualizar la contraseña en la base de datos
        $usuario->contraseña = Hash::make($nuevaContraseña);
        $usuario->save();

        // Enviar la nueva contraseña al correo del usuario
        Mail::raw("Hola {$usuario->nombres}, tu nueva contraseña es: {$nuevaContraseña}", function ($message) use ($usuario) {
            $message->to($usuario->correo_personal)
                    ->subject('Restablecimiento de contraseña');
        });

        return back()->with('success', 'Se ha enviado la nueva contraseña a tu correo.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Ocurrió un error al restablecer la contraseña.']);
    }
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
            'rh' => 'required|string|max:7', // Validar que el campo rh sea obligatorio
            'correo_personal' => 'required|email|max:255|unique:usuarios',
            'correo_institucional' => 'required|email|max:255|unique:usuarios',
            'telefono' => 'required|string|max:20',
            'contraseña' => 'required|string|min:6|confirmed',
            'rol' => 'required|exists:roles,id', // Verificar que el rol exista
            'numero_ficha' => $request->input('rol') == 3 ? 'required|string|max:255' : 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400' // Validación de la imagen
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Crear una nueva instancia del modelo Usuario
            $usuario = new Usuario();
            $usuario->nombres = $request->nombres;
            $usuario->apellidos = $request->apellidos;
            $usuario->tipo_documento = $request->tipo_documento;
            $usuario->numero_documento = $request->numero_documento;
            $usuario->rh = $request->rh; // Asignar el valor del campo rh
            $usuario->correo_personal = $request->correo_personal;
            $usuario->correo_institucional = $request->correo_institucional;
            $usuario->telefono = $request->telefono;
            $usuario->numero_ficha = $request->input('numero_ficha');
            $usuario->contraseña = Hash::make($request->contraseña);
            $usuario->roles_id = $request->rol; // Asigna el rol seleccionado

            // Manejo del archivo de foto si se sube una
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $path = $file->store('public/fotos_perfil'); // Guarda la foto en el almacenamiento público
                $usuario->foto = basename($path); // Guarda solo el nombre del archivo en la base de datos
            }

            // Guardar el usuario en la base de datos
            $usuario->save();

            // Autenticar al usuario recién registrado
            Auth::login($usuario);

            // Redirigir al panel de usuario con un mensaje de éxito
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

        // Verificar credenciales
        if (Auth::guard('web')->attempt($credentials)) {
            $usuario = Auth::user();
            Log::info('Usuario autenticado', ['user' => $usuario]);

            // Redirigir al panel según el rol
            switch ($usuario->roles_id) {
                case 1:
                    return redirect()->route('admin.panel');
                case 2:
                    return redirect()->route('control.panel');
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
        $esAprendiz = $usuario->roles_id == 3; // Verifica si el usuario es Aprendiz

        // Mantén los valores actuales del usuario para los campos que no deben ser editados
        $request->merge([
            'numero_documento' => $usuario->numero_documento, // No se puede editar el número de documento
            'correo_personal' => $usuario->correo_personal,
            'correo_institucional' => $usuario->correo_institucional,
        ]);

        // Definir las reglas de validación
        $rules = [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'tipo_documento' => 'required|string|max:255', // Ahora es editable
            'telefono' => 'required|string|max:20',
            'rh' => 'required|string|max:7', // Validación del campo rh en el perfil
            'numero_ficha' => $esAprendiz ? 'required|string|max:255' : 'nullable|string|max:255', // Obligatorio si es Aprendiz
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400'
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
            $usuario->rh = $request->rh; // Actualizar el campo rh
            $usuario->save();

            // Devolver una respuesta con los datos actualizados
            return response()->json([
                'success' => 'Perfil actualizado con éxito.',
                'user' => $usuario,
                'newPhotoUrl' => $usuario->foto ? Storage::url('public/fotos_perfil/' . $usuario->foto) : null
            ]);
        } catch (QueryException $e) {
            Log::error('Error al actualizar el perfil: ' . $e->getMessage());
            return response()->json(['error' => 'Ha ocurrido un error al actualizar el perfil.'], 500);
        }
    }
}
