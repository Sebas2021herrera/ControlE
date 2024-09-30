<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Elemento; 
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Mostrar el panel de usuario
    public function userPanel()
    {
        $usuario = Auth::user(); // Obtener el usuario autenticado
        $elementos = $usuario->elementos; // Obtener los elementos del usuario autenticado 
        $categorias = Categoria::all(); // Obtener todas las categorías
        return view('index.vistausuario', compact('elementos', 'categorias', 'usuario'));
    }

    // Mostrar la vista del perfil del usuario por ID
    public function show($id)
    {
        $usuario = Usuario::findOrFail($id); // Buscar el usuario por su ID
        return view('index.vistausuario', ['usuario' => $usuario]);
    }
  
    // Mostrar el perfil del usuario autenticado
    public function mostrarPerfil()
    {
        $usuario = Auth::user(); // Obtener el usuario autenticado
        return view('index.vistausuario', compact('usuario'));
    }

    // Consulta del número de documento del aprendiz

    // Consulta del número de documento del usuario
    public function buscarPorDocumento(Request $request)
    {
        $request->validate([
            'documento' => 'required|string|max:255',
        ]);
    
        // Buscar el usuario por su número de documento
        $usuario = Usuario::where('numero_documento', $request->input('documento'))->first();
    
        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }
    
        // Obtener los elementos relacionados con el usuario
        $elementos = $usuario->elementos; // Asegúrate de que la relación está bien definida en el modelo Usuario
    
        // Pasamos la información del usuario y los elementos a la vista
        return view('index.vistacontrol', compact('usuario', 'elementos'));
    }
    

    public function categoriasElementosVistaAdmin()
    {
        $categorias = Categoria::all(); // O la consulta adecuada para obtener las categorías
        return view('index.vistaadmin', compact('categorias'));
    }

    public function consultarUsuario(Request $request)
{
    $documento = $request->input('documento');
    
    // Consulta el usuario por su número de documento
    $usuario = Usuario::where('numero_documento', $documento)->first();

    if ($usuario) {
        return response()->json([
            'success' => true,
            'usuario' => [
                'nombres' => $usuario->nombres,
                'apellidos' => $usuario->apellidos,
                'documento' => $usuario->numero_documento, // Ajustar el nombre del campo si es necesario
                'celular' => $usuario->celular,
                'rol' => $usuario->rol,
                'ficha' => $usuario->ficha, // si tiene ficha
                'foto' => $usuario->foto_perfil, // asumiendo que tienes el campo foto_perfil
            ]
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ]);
    }
}
}