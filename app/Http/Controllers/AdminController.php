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
        // Buscar el usuario por su número de documento
        $usuario = Usuario::with(['role', 'elementos.categoria'])
            ->where('numero_documento', $request->documento)
            ->first();

        // Obtener todas las categorías
        $categorias = Categoria::all();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Usuario no encontrado',
            ], 404);
        }

        // Formatear los elementos para asegurar que todos los campos estén presentes
        $elementos = $usuario->elementos->map(function ($elemento) {
            return [
                'id' => $elemento->id,
                'categoria' => [
                    'id' => $elemento->categoria->id,
                    'nombre' => $elemento->categoria->nombre
                ],
                'categoria_id' => $elemento->categoria_id,
                'descripcion' => $elemento->descripcion,
                'marca' => $elemento->marca,
                'modelo' => $elemento->modelo,
                'serie' => $elemento->serie,
                'especificaciones_tecnicas' => $elemento->especificaciones_tecnicas,
                'foto' => $elemento->foto,
                'usuario_id' => $elemento->usuario_id
            ];
        });

        // Formatear las categorías para el select
        $categoriasFormateadas = $categorias->map(function ($categoria) {
            return [
                'id' => $categoria->id,
                'nombre' => $categoria->nombre
            ];
        });

        // Retornar usuario, elementos y categorías en formato JSON
        return response()->json([
            'success' => true,
            'usuario' => $usuario,
            'elementos' => $elementos,
            'categorias' => $categoriasFormateadas
        ]);

    } catch (\Exception $e) {
        \Log::error('Error al buscar usuario: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'mensaje' => 'Error al buscar el usuario',
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

    /**
     * Update the specified resource in storage.
     */
    // Método para actualizar un elemento en AdminController
public function update(Request $request, $id)
{
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

        // Buscar el elemento por su ID
        $elemento = Elemento::findOrFail($id);

        // Actualizar los campos del elemento
        $elemento->categoria_id = $validatedData['categoria_id'];
        $elemento->descripcion = $validatedData['descripcion'];
        $elemento->marca = $validatedData['marca'];
        $elemento->modelo = $validatedData['modelo'];
        $elemento->serie = $validatedData['serie'] ?? null;
        $elemento->especificaciones_tecnicas = $validatedData['especificaciones_tecnicas'] ?? null;

        // Manejar la foto, si se sube una nueva
        if ($request->hasFile('foto')) {
            // Eliminar la foto anterior si existe
            if ($elemento->foto) {
                Storage::delete('public/' . $elemento->foto);
            }
            // Guardar la nueva foto
            $elemento->foto = $request->file('foto')->store('fotos', 'public');
        }

        // Guardar los cambios en la base de datos
        $elemento->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('admin.panel')->with('success', '¡Elemento actualizado exitosamente!');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $elemento = Elemento::findOrFail($id);

        // Elimina el archivo de la foto si existe
        if ($elemento->foto) {
            Storage::delete('public/' . $elemento->foto);
        }

        $elemento->delete();

        return redirect()->route('admin.panel')->with('success', '¡Elemento eliminado exitosamente!');
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

}