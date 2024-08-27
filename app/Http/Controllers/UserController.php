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

        // Retornar la vista 'index.vistausuario' con los datos del usuario
        return view('index.vistausuario', ['usuario' => $usuario]);
    }
  
    // Mostrar el perfil del usuario autenticado
    public function mostrarPerfil()
    {
        $usuario = Auth::user(); // Obtener el usuario autenticado
        return view('index.vistausuario', compact('usuario'));
    }

    // Consulta del número de documento del aprendiz
    public function buscarPorDocumento(Request $request)
    {
        $request -> validate([
            'documento' => 'required|string|max:255',
        ]);

        $usuario = User :: where('numero_documento', $request -> input('documento')) -> first();

        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        return view('usuario.detalles', compact('usuario'));
    }
}