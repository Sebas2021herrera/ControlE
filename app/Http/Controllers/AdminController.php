<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use App\Models\Categoria;
use App\Models\Elemento;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        // Obtener todas las categorías
        $categorias = Categoria::all();

        // Retornar la vista con las categorías
        return view('index.vistaadmin', compact('categorias'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // Log inicial
        Log::info('Método store llamado.');
    
        // Registrar la solicitud en los logs
        Log::info('Datos del formulario:', $request->all());
    
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'tipo_documento' => 'required|string|max:255',
            'numero_documento' => 'required|string|max:255|unique:usuarios',
            'rh' => 'required|string|max:7',
            'correo_personal' => 'required|email|max:255|unique:usuarios',
            'correo_institucional' => 'required|email|max:255|unique:usuarios',
            'telefono' => 'required|string|max:20',
            'contraseña' => 'required|string|min:6|confirmed',
            'rol' => 'required|exists:roles,id',
            'numero_ficha' => $request->input('rol') == 3 ? 'required|string|max:255' : 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400',
        ]);
    
        if ($validator->fails()) {
            Log::info('Errores de validación:', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        try {
            // Crear una nueva instancia del modelo Usuario
            $usuario = new Usuario();
            $usuario->nombres = $request->nombres;
            $usuario->apellidos = $request->apellidos;
            $usuario->tipo_documento = $request->tipo_documento;
            $usuario->numero_documento = $request->numero_documento;
            $usuario->rh = $request->rh;
            $usuario->correo_personal = $request->correo_personal;
            $usuario->correo_institucional = $request->correo_institucional;
            $usuario->telefono = $request->telefono;
            $usuario->numero_ficha = $request->input('numero_ficha');
            $usuario->contraseña = Hash::make($request->contraseña);
            $usuario->roles_id = $request->rol; // Asigna el rol seleccionado
    
            // Manejo del archivo de foto si se sube una
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $path = $file->store('public/fotos_perfil');
                $usuario->foto = basename($path);
            }
    
            // Guardar el usuario en la base de datos
            $usuario->save();
    
            // Redirigir al administrador a la vista admin con un mensaje de éxito
            return redirect()->route('admin.panel')->with('success', '¡Usuario registrado exitosamente!');
        } catch (QueryException $e) {
            Log::error('Error al registrar el usuario: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Ha ocurrido un error al registrar el usuario.'])->withInput();
        }

        // Validación de registros de elementos

        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'categoria_id' => 'required|integer|exists:categorias,id',
            'descripcion' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'serie' => 'nullable|string|max:255',
            'especificaciones_tecnicas' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB por imagen
        ]);

        // Crear un nuevo elemento
        $elemento = new Elemento();
        $elemento->categoria_id = $validatedData['categoria_id'];
        $elemento->descripcion = $validatedData['descripcion'];
        $elemento->marca = $validatedData['marca'];
        $elemento->modelo = $validatedData['modelo'];
        $elemento->serie = $validatedData['serie'] ?? null;
        $elemento->especificaciones_tecnicas = $validatedData['especificaciones_tecnicas'] ?? null;
        $elemento->usuario_id = Auth::id(); // Asignar el ID del usuario autenticado

        // Guardar la foto si está presente
        if ($request->hasFile('foto')) {
            $elemento->foto = $request->file('foto')->store('fotos', 'public');
        }

        $elemento->save();

        // Redireccionar con un mensaje de éxito
        return redirect()->route('user.panel')->with('success', '¡Elemento registrado exitosamente!');

    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
