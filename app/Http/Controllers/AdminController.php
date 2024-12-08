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
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las categorías
        $categorias = Categoria::all();
 
        // Obtener todos los elementos
        $elementos = Elemento::all(); // o filtrar por usuario si es necesario
 
        // Retornar la vista con categorías y elementos
        return view('index.vistaadmin', compact('categorias', 'elementos'));
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
    public function storeUsuario(Request $request) {
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
            'foto' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:5120',  // 5MB
                function ($attribute, $value, $fail) {
                    if ($value && $value->getSize() > 5120 * 1024) {
                        $fail('La imagen no debe pesar más de 5MB. Por favor, comprima la imagen o seleccione otra.');
                    }
                },
            ] 
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
    }

    
    public function storeElemento(Request $request) {
        // VALIDACIÓN DE REGISTROS DE ELEMENTOS

        // Validar los datos del formulario
        $validatedData = $request->validate([
            'documento' => 'required|string|exists:usuarios,numero_documento', // Número de documento del usuario
            'categoria_id' => 'required|integer|exists:categorias,id',
            'descripcion' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'serie' => 'nullable|string|max:255',
            'especificaciones_tecnicas' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            // 'usuario_id' => 'required|integer|exists:usuarios,id', Asegúrate de que se reciba el ID del usuario
    ]);

    // Buscar al usuario por su número de documento
    $usuario = Usuario::where('numero_documento', $validatedData['documento'])->first();

    // Verificar si el usuario existe
    if (!$usuario) {
        return redirect()->back()->with('error', 'El usuario con ese número de documento no existe.');
    }

    // Crear un nuevo elemento asociado al usuario encontrado
    $elemento = new Elemento();
    $elemento->categoria_id = $validatedData['categoria_id'];
    $elemento->descripcion = $validatedData['descripcion'];
    $elemento->marca = $validatedData['marca'];
    $elemento->modelo = $validatedData['modelo'];
    $elemento->serie = $validatedData['serie'] ?? null;
    $elemento->especificaciones_tecnicas = $validatedData['especificaciones_tecnicas'] ?? null;
    $elemento->usuario_id = $usuario->id; // Asignar el ID del usuario obtenido

    // Guardar la foto si está presente
    if ($request->hasFile('foto')) {
        $elemento->foto = $request->file('foto')->store('fotos', 'public');
    }

    // Guardar el elemento en la base de datos
    $elemento->save();

    // Redireccionar con un mensaje de éxito
    return redirect()->route('admin.panel')->with('success', '¡Elemento registrado exitosamente!');
    }
    
    public function consultarUsuario(Request $request)
{
    $request->validate([
        'documento' => 'required|string|max:255',
    ]);

    try {
        // Buscar el usuario por su número de documento con relaciones
        $usuario = Usuario::with(['role', 'elementos.categoria'])
            ->where('numero_documento', $request->documento)
            ->first();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Usuario no encontrado',
            ], 404);
        }

        // Formatear los datos del usuario de manera más simple
        $usuarioFormateado = [
            'id' => $usuario->id,
            'nombres' => $usuario->nombres,
            'apellidos' => $usuario->apellidos,
            'numero_documento' => $usuario->numero_documento,
            'telefono' => $usuario->telefono,
            'rh' => $usuario->rh,
            'role' => $usuario->role ? [
                'id' => $usuario->role->id,
                'nombre' => $usuario->role->nombre
            ] : null,
            'numero_ficha' => $usuario->numero_ficha,
            'foto' => $usuario->foto
        ];

        // Formatear los elementos de manera más simple
        $elementos = $usuario->elementos->map(function ($elemento) {
            return [
                'id' => $elemento->id,
                'categoria' => [
                    'id' => $elemento->categoria->id,
                    'nombre' => $elemento->categoria->nombre
                ],
                'descripcion' => $elemento->descripcion,
                'marca' => $elemento->marca,
                'modelo' => $elemento->modelo,
                'serie' => $elemento->serie,
                'especificaciones_tecnicas' => $elemento->especificaciones_tecnicas,
                'foto' => $elemento->foto
            ];
        });

        // Obtener todas las categorías
        $categorias = Categoria::select('id', 'nombre')->get();

        // Agregar logs para depuración
        \Log::info('Consulta de usuario:', [
            'documento' => $request->documento,
            'usuario_encontrado' => $usuario ? 'sí' : 'no',
            'elementos_count' => $elementos->count(),
            'categorias_count' => $categorias->count()
        ]);

        return response()->json([
            'success' => true,
            'usuario' => $usuarioFormateado,
            'elementos' => $elementos,
            'categorias' => $categorias
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en consultarUsuario:', [
            'mensaje' => $e->getMessage(),
            'linea' => $e->getLine(),
            'archivo' => $e->getFile()
        ]);

        return response()->json([
            'success' => false,
            'mensaje' => 'Error al buscar el usuario',
            'error' => $e->getMessage()
        ], 500);
    }
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
    public function edit($id)
    {
        $elemento = Elemento::findOrFail($id);
        $categorias = Categoria::all();

        return view('admin.edit', compact('elemento', 'categorias')); // Solo si requieres una vista independiente
    }

    
    public function generarReporteIngresosUsuario(Request $request)
    {
    $numeroDocumento = $request->input('documento');

    // Buscar al usuario con sus elementos asociados
    $usuario = Usuario::with('elementos.categoria')
                ->where('numero_documento', $numeroDocumento)
                ->firstOrFail(); // Retorna 404 si no se encuentra el usuario

    // Generar el PDF usando la vista reports.blade.php
    $pdf = Pdf::loadView('pdf.vistareportes', compact('usuario'));

    // Retornar el PDF para descarga o visualizar en el navegador
    return $pdf->download('reportes_' . $numeroDocumento . '.pdf');
    }

    public function updateElemento(Request $request, $id)
    {
        try {
            // Validación de los datos del formulario
            $validatedData = $request->validate([
                'categoria_id' => 'required|integer|exists:categorias,id',
                'descripcion' => 'required|string|max:255',
                'marca' => 'required|string|max:255',
                'modelo' => 'required|string|max:255',
                'serie' => 'nullable|string|max:255',
                'especificaciones_tecnicas' => 'nullable|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            ]);

            // Buscar el elemento
            $elemento = Elemento::findOrFail($id);

            // Actualizar los campos
            $elemento->categoria_id = $validatedData['categoria_id'];
            $elemento->descripcion = $validatedData['descripcion'];
            $elemento->marca = $validatedData['marca'];
            $elemento->modelo = $validatedData['modelo'];
            $elemento->serie = $validatedData['serie'] ?? null;
            $elemento->especificaciones_tecnicas = $validatedData['especificaciones_tecnicas'] ?? null;

            // Manejar la foto si se subió una nueva
            if ($request->hasFile('foto')) {
                // Eliminar la foto anterior
                if ($elemento->foto) {
                    Storage::delete('public/' . $elemento->foto);
                }
                // Guardar la nueva foto
                $elemento->foto = $request->file('foto')->store('fotos', 'public');
            }

            // Guardar cambios
            $elemento->save();

            // Redirigir de vuelta con mensaje de éxito
            return redirect()->route('admin.panel')
                            ->with('success', '¡Elemento actualizado exitosamente!');

        } catch (\Exception $e) {
            \Log::error('Error al actualizar elemento:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            // Redirigir de vuelta con mensaje de error
            return redirect()->route('admin.panel')
                            ->with('error', 'Error al actualizar el elemento: ' . $e->getMessage());
        }
    }

    public function destroyElemento($id)
    {
        try {
            // Buscar el elemento
            $elemento = Elemento::findOrFail($id);

            // Eliminar la foto si existe
            if ($elemento->foto) {
                Storage::delete('public/' . $elemento->foto);
            }

            // Eliminar el elemento
            $elemento->delete();

            // Redirigir con mensaje de éxito
            return redirect()->route('admin.panel')
                            ->with('success', '¡Elemento eliminado exitosamente!');

        } catch (\Exception $e) {
            \Log::error('Error al eliminar elemento:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            // Redirigir con mensaje de error
            return redirect()->route('admin.panel')
                            ->with('error', 'Error al eliminar el elemento: ' . $e->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'nombres' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'tipo_documento' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Obtener el usuario autenticado
            $usuario = Auth::user();

            // Actualizar los campos básicos
            $usuario->nombres = $validatedData['nombres'];
            $usuario->apellidos = $validatedData['apellidos'];
            $usuario->tipo_documento = $validatedData['tipo_documento'];
            $usuario->telefono = $validatedData['telefono'];

            // Manejar la foto si se subió una nueva
            if ($request->hasFile('foto')) {
                // Eliminar la foto anterior si existe
                if ($usuario->foto) {
                    Storage::delete('public/fotos_perfil/' . $usuario->foto);
                }
                
                // Guardar la nueva foto
                $path = $request->file('foto')->store('public/fotos_perfil');
                $usuario->foto = basename($path);
            }

            // Guardar los cambios
            $usuario->save();

            // Redireccionar con mensaje de éxito
            return redirect()->route('admin.panel')
                            ->with('success', '¡Perfil actualizado exitosamente!');

        } catch (\Exception $e) {
            \Log::error('Error al actualizar perfil:', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            // Redireccionar con mensaje de error
            return redirect()->route('admin.panel')
                            ->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

}