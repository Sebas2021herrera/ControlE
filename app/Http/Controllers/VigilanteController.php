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
    
        $usuario = Usuario::where('numero_documento', $request->input('documento'))->first();
    
        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }
    
        $elementos = $usuario->elementos; // Obtener los elementos relacionados con el usuario
    
        return view('index.vistacontrol', compact('usuario', 'elementos'));
    }
}

