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
use Illuminate\Support\Arr;
use Illuminate\Pagination\Paginator;
Paginator::useBootstrapFive();

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
    
        // Validar los datos del formulario con reglas más estrictas
        $validator = Validator::make($request->all(), [
            'nombres' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'apellidos' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'tipo_documento' => [
                'required',
                'string',
                'in:CC,TI,CE,PP,RC'
            ],
            'numero_documento' => [
                'required',
                'numeric',
                'digits_between:6,12',
                'unique:usuarios,numero_documento'
            ],
            'rh' => [
                'required',
                'string',
                'in:O+,O-,A+,A-,B+,B-,AB+,AB-'
            ],
            'correo_personal' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:usuarios,correo_personal'
            ],
            'correo_institucional' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:usuarios,correo_institucional',
                function ($attribute, $value, $fail) use ($request) {
                    $dominio = substr(strrchr($value, "@"), 1);
                    $rol = (int)$request->rol;
    
                    if ($rol === 3) { // Aprendiz
                        if ($dominio !== 'soy.sena.edu.co') {
                            $fail("El correo institucional para aprendices debe terminar en '@soy.sena.edu.co'.");
                        }
                    } else { // Otros roles
                        if ($dominio !== 'sena.edu.co') {
                            $fail("El correo institucional debe terminar en '@sena.edu.co' para este rol.");
                        }
                    }
                }
            ],
            'telefono' => [
                'required',
                'numeric',
                'digits_between:7,10'
            ],
            'contraseña' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            'rol' => [
                'required',
                'integer',
                'exists:roles,id'
            ],
            'numero_ficha' => [
                'required_if:rol,3',
                'nullable',
                'string',
                'max:20',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->rol == 3 && empty($value)) {
                        $fail('El número de ficha es obligatorio para aprendices.');
                    }
                }
            ],
            'foto' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:6144' // 6MB
            ]
        ], [
            // Mensajes personalizados de error
            'nombres.regex' => 'Los nombres solo pueden contener letras y espacios',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios',
            'numero_documento.unique' => 'Este número de documento ya está registrado',
            'numero_documento.numeric' => 'El número de documento debe contener solo números',
            'numero_documento.digits_between' => 'El número de documento debe tener entre 6 y 12 dígitos',
            'correo_institucional.unique' => 'Este correo institucional ya está registrado',
            'contraseña.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un símbolo (@$!%*?&)',
            'telefono.numeric' => 'El teléfono debe contener solo números',
            'telefono.digits_between' => 'El teléfono debe tener entre 7 y 10 dígitos',
            'foto.max' => 'La foto no debe superar los 6MB'
        ]);
    
        if ($validator->fails()) {
            Log::info('Errores de validación:', $validator->errors()->all());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        try {
            // Crear el usuario
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
            $usuario->roles_id = $request->rol;
    
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $path = $file->store('public/fotos_perfil');
                $usuario->foto = basename($path);
            }
    
            $usuario->save();
    
            Log::info('Usuario registrado exitosamente:', ['id' => $usuario->id]);
    
            return redirect()->route('admin.panel')->with('success', '¡Usuario registrado exitosamente!');
        } catch (QueryException $e) {
            Log::error('Error al registrar el usuario:', ['mensaje' => $e->getMessage()]);
            return redirect()->back()
                ->withErrors(['error' => 'Ha ocurrido un error al registrar el usuario.'])
                ->withInput();
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
    

    // Consultar Usuarios
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
            'tipo_documento' => $usuario->tipo_documento,
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
        $validatedData = $request->validate([
            'categoria_id' => 'required|integer|exists:categorias,id',
            'descripcion' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'serie' => 'nullable|string|max:255',
            'especificaciones_tecnicas' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $elemento = Elemento::findOrFail($id);
        $elemento->update(Arr::except($validatedData, ['foto']));

        if ($request->hasFile('foto')) {
            if ($elemento->foto && Storage::exists('public/' . $elemento->foto)) {
                Storage::delete('public/' . $elemento->foto);
            }
            $elemento->foto = $request->file('foto')->store('fotos', 'public');
            $elemento->save();
        }

        return redirect()->route('admin.panel')->with('success', '¡Elemento actualizado exitosamente!');

    } catch (\Exception $e) {
        \Log::error('Error al actualizar elemento:', [
            'id' => $id,
            'error' => $e->getMessage()
        ]);

        return redirect()->route('admin.panel')->with('error', 'Error al actualizar el elemento: ' . $e->getMessage());
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

    public function actualizarUsuario(Request $request, $id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            
            // Validar y actualizar los datos
            $usuario->update($request->except(['foto']));

            // Manejar la foto si se subió una nueva
            if ($request->hasFile('foto')) {
                // Eliminar la foto anterior si existe
                if ($usuario->foto) {
                    Storage::delete('public/fotos_perfil/' . $usuario->foto);
                }
                
                // Guardar la nueva foto
                $foto = $request->file('foto');
                $nombreFoto = time() . '_' . $foto->getClientOriginalName();
                $foto->storeAs('public/fotos_perfil', $nombreFoto);
                $usuario->foto = $nombreFoto;
                $usuario->save();
            }

            return response()->json([
                'success' => true,
                'mensaje' => 'Usuario actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al actualizar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyUsuario($id)
    {
        try {
            // Buscar el usuario
            $usuario = Usuario::findOrFail($id);

            // Verificar si el usuario tiene elementos asociados
            if ($usuario->elementos()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'No se puede eliminar el usuario porque tiene elementos asociados.'
                ], 400);
            }

            // Eliminar la foto si existe
            if ($usuario->foto) {
                Storage::delete('public/fotos_perfil/' . $usuario->foto);
            }

            // Eliminar el usuario
            $usuario->delete();

            return response()->json([
                'success' => true,
                'mensaje' => 'Usuario eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al eliminar usuario:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'mensaje' => 'Error al eliminar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar todos los usuarios registrados.
     */
    public function consultaMasiva(Request $request)
{
    try {
        // Iniciar la consulta con las relaciones necesarias
        $query = Usuario::with(['role', 'elementos.categoria']);

        // Aplicar filtros si se ingresaron nombres o apellidos
        if ($request->filled('nombre')) {
            $query->where('nombres', 'like', '%' . $request->nombre . '%');
        }
        if ($request->filled('apellido')) {
            $query->where('apellidos', 'like', '%' . $request->apellido . '%');
        }

        // Obtener los resultados paginados con orden por fecha de creación
        $usuarios = $query->orderBy('created_at', 'asc')->paginate(10);

        // Agregar log para depuración
        \Log::info('Consulta masiva ejecutada', [
            'cantidad_usuarios' => $usuarios->count(),
            'filtros' => $request->only('nombre', 'apellido')
        ]);

        return view('index.consultaMasiva', compact('usuarios'));
    } catch (\Exception $e) {
        \Log::error('Error en consulta masiva:', [
            'mensaje' => $e->getMessage(),
            'linea' => $e->getLine()
        ]);

        return redirect()->route('admin.panel')
            ->with('error', 'Error al cargar la consulta masiva: ' . $e->getMessage());
    }
}

    // Agregar este método para obtener los elementos de un usuario
    public function obtenerElementosUsuario($id)
    {
        $elementos = Usuario::findOrFail($id)->elementos()->with('categoria')->get();
        return response()->json(['elementos' => $elementos]);
    }

}