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
        $user = Auth::user(); // Obtener el usuario autenticado
        $elementos = $user->elementos; // Obtener los elementos del usuario autenticado
        $categorias = Categoria::all(); // Obtener todas las categorías

        return view('index.vistausuario', compact('elementos', 'categorias'));
    }
}
