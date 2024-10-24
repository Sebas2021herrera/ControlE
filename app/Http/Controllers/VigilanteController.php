<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\ControlIngreso;
use App\Models\Elemento;
use App\Models\Sub_Control_Ingreso;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


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

        // Obtener el último registro de control de ingreso del usuario
        $ultimoRegistro = ControlIngreso::where('usuario_id', $usuario->id)->latest()->first();
        $controlIngresoId = $ultimoRegistro ? $ultimoRegistro->id : null;

        // Retornar la vista con los datos
        return view('index.vistacontrol', compact('usuario', 'elementos', 'registros', 'vigilante', 'controlIngresoId'));
    }




    public function nuevoRegistro(Request $request)
    {
        $request->validate([
            'documento_vigilante' => 'required|string|max:255',
            'usuario_id' => 'required|integer',
        ]);

        // Buscar al vigilante por su número de documento
        $vigilante = Usuario::where('numero_documento', $request->input('documento_vigilante'))->first();

        if (!$vigilante) {
            return response()->json([
                'success' => false,
                'message' => 'Vigilante no encontrado.',
            ]);
        }

        // Buscar al usuario por ID
        $usuario = Usuario::find($request->input('usuario_id'));

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado para el registro.',
            ]);
        }

        // Definir el ID del centro (puedes ajustarlo según corresponda)
        $centros_id = 1;

        // Crear el registro de ingreso
        $nuevoRegistro = ControlIngreso::create([
            'usuario_id' => $usuario->id,
            'centros_id' => $centros_id,
            'fecha_ingreso' => Carbon::now('America/Bogota'), // Forzar la hora correcta
            'estado' => 0, // Guardar como 0 para 'Abierto'
            'id_persona_control' => $vigilante->id, // Guardar el ID del vigilante
        ]);

        // Obtener todos los registros del usuario para actualizar la vista
        $registros = ControlIngreso::with('centro')->where('usuario_id', $usuario->id)->get();

        // Responder con los registros actualizados
        return response()->json([
            'success' => true,
            'message' => 'Ingreso registrado exitosamente.',
            'registros' => $registros,
        ]);
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




    
    public function registrarElementoEnSubControl(Request $request)
    {
        $request->validate([
            'control_ingreso_id' => 'required|integer',
            'elemento_id' => 'required|integer',
        ]);

        // Verificar si el registro de control de ingreso existe
        $controlIngreso = ControlIngreso::find($request->input('control_ingreso_id'));
        if (!$controlIngreso) {
            return response()->json([
                'success' => false,
                'message' => 'Registro de control de ingreso no encontrado.',
            ], 404);
        }

        // Verificar si el elemento existe
        $elemento = Elemento::find($request->input('elemento_id'));
        if (!$elemento) {
            return response()->json([
                'success' => false,
                'message' => 'Elemento no encontrado.',
            ], 404);
        }

        // Crear el registro en sub_control_ingresos
        try {
            Sub_Control_Ingreso::create([
                'control_ingreso_id' => $controlIngreso->id,
                'elemento_id' => $elemento->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Elemento registrado exitosamente en sub_control_ingresos.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el elemento: ' . $e->getMessage(),
            ], 500);
        }
    }
    
}
 

