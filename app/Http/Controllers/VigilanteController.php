<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class VigilanteController extends Controller
{
    // Consulta del número de documento del aprendiz
    public function buscarPorDocumento(Request $request)
    {
        $request->validate([
            'documento' => 'required|string|max:255',
        ]);

        // Buscar el usuario por su número de documento
        $usuario = Usuario::where('numero_documento', $request->input('documento'))->first();

        // Si no se encuentra el usuario, retornar la vista sin datos
        if (!$usuario) {
            return view('index.vistacontrol')->with('error', 'Usuario no encontrado.');
        }

        // Obtener los elementos relacionados solo si existe un usuario
        $elementos = $usuario->elementos;

        return view('index.vistacontrol', compact('usuario', 'elementos'));
    }
}
