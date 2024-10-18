<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\ControlIngreso;
use Illuminate\Support\Facades\Auth;

class VigilanteController extends Controller
{
    // Método para buscar un usuario por número de documento
    public function buscarPorDocumento(Request $request)
    {
        $request->validate([
            'documento' => 'required|string|max:255',
        ]);

        // Obtener el usuario autenticado (vigilante)
        $vigilante = Auth::user();

        if (!$vigilante) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        // Buscar el usuario por su número de documento
        $usuario = Usuario::where('numero_documento', $request->input('documento'))->first();

        if (!$usuario) {
            return view('index.vistacontrol')->with('error', 'Usuario no encontrado.');
        }

        // Obtener los elementos relacionados y los registros del usuario
        $elementos = $usuario->elementos;
        $registros = ControlIngreso::where('usuario_id', $usuario->id)->get();

        // Retornar la vista con los datos
        return view('index.vistacontrol', compact('usuario', 'elementos', 'registros', 'vigilante'));
    }




    // Método para registrar un nuevo ingreso
    // Método para registrar un nuevo ingreso
    public function nuevoRegistro(Request $request)
    {
        $request->validate([
            'documento_vigilante' => 'required|string|max:255',
            'usuario_id' => 'required|integer',
        ]);

        // Buscar al usuario por ID
        $usuario = Usuario::find($request->input('usuario_id'));

        if (!$usuario) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado para el registro.'], 404);
        }

        // Buscar al vigilante por número de documento para obtener su ID
        $vigilante = Usuario::where('numero_documento', $request->input('documento_vigilante'))->first();

        if (!$vigilante) {
            return response()->json(['success' => false, 'message' => 'Vigilante no encontrado.'], 404);
        }

        // Definir el ID del centro
        $centros_id = 1;

        // Crear el registro de ingreso
        $nuevoRegistro = ControlIngreso::create([
            'usuario_id' => $usuario->id,
            'centros_id' => $centros_id,
            'fecha_ingreso' => now(),
            'estado' => 0, // Guardar como 0 para 'Abierto'
            'id_persona_control' => $vigilante->id, // Almacenar el ID del vigilante
        ]);

        // Obtener el registro con la relación del centro
        $registroConCentro = ControlIngreso::with('centro')->find($nuevoRegistro->id);
        $registroConCentro->estado_texto = $registroConCentro->estado == 0 ? 'Abierto' : 'Cerrado';

        // Retornar el nuevo registro como respuesta JSON
        return response()->json(['success' => true, 'message' => 'Ingreso registrado exitosamente.', 'registro' => $registroConCentro]);
    }







    // Método para mostrar la vista de control
    public function mostrarVistaControl()
    {
        // Obtener el vigilante autenticado
        $vigilante = Auth::user();

        if (!$vigilante) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        // Cargar la vista con los datos del vigilante
        return view('index.vistacontrol', compact('vigilante'));
    }
}
