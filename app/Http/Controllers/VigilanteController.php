<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\ControlIngreso;
use App\Models\Elemento;
use App\Models\Sub_Control_Ingreso;
use App\Models\SubControlIngreso; // Asegúrate de que esta línea esté presente
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


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

        $elementos = $usuario->elementos;

        $registros = ControlIngreso::where('usuario_id', $usuario->id)
            ->orderBy('fecha_ingreso', 'asc')
            ->get();

        $ultimoRegistro = ControlIngreso::where('usuario_id', $usuario->id)->latest()->first();
        $controlIngresoId = $ultimoRegistro ? $ultimoRegistro->id : null;

        return view('index.vistacontrol', compact('usuario', 'elementos', 'registros', 'vigilante', 'controlIngresoId'));
    }


    public function nuevoRegistro(Request $request)
    {
        Log::info('Solicitud recibida en nuevoRegistro', $request->all());

        // Validar la entrada
        if (!$request->has(['documento_vigilante', 'usuario_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'Faltan datos necesarios para procesar la solicitud.',
            ], 400);
        }

        $validated = $request->validate([
            'documento_vigilante' => 'required|string|max:255',
            'usuario_id' => 'required|integer',
        ]);

        Log::info('Datos validados correctamente', $validated);

        // Verificar el vigilante
        $vigilante = Usuario::where('numero_documento', $request->input('documento_vigilante'))->first();
        if (!$vigilante) {
            return response()->json([
                'success' => false,
                'message' => 'Vigilante no encontrado.',
            ], 404);
        }

        // Verificar el usuario
        $usuario = Usuario::find($request->input('usuario_id'));
        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado para el registro.',
            ], 404);
        }

        // Verificar si hay un registro abierto
        $registroAbierto = ControlIngreso::where('usuario_id', $usuario->id)
            ->where('estado', 0)
            ->latest('fecha_ingreso')
            ->first();

        if ($registroAbierto) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede crear un nuevo registro porque el último registro del usuario no está cerrado.',
                'registro_abierto' => $registroAbierto,
            ], 400);
        }

        // Crear el nuevo registro
        try {
            $nuevoRegistro = ControlIngreso::create([
                'usuario_id' => $usuario->id,
                'centros_id' => 1, // Ajustar según corresponda
                'fecha_ingreso' => Carbon::now('America/Bogota'),
                'estado' => 0, // 0 para "Abierto"
                'id_persona_control' => $vigilante->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el registro: ' . $e->getMessage(),
            ], 500);
        }

        // Cargar las relaciones necesarias del registro recién creado
        $nuevoRegistro->load('centro');

        return response()->json([
            'success' => true,
            'message' => 'Ingreso registrado exitosamente.',
            'registro' => $nuevoRegistro,  // Solo el nuevo registro
            'nuevoRegistroId' => $nuevoRegistro->id,
        ]);
    }



    public function cerrarRegistro(Request $request, $id)
    {
        Log::info('Intentando cerrar el registro con ID: ' . $id);

        try {
            // Buscar el registro
            $registro = ControlIngreso::find($id);

            if (!$registro) {
                Log::warning('Registro no encontrado con ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'El registro no existe.',
                ], 404);
            }

            // Verificar si ya está cerrado
            if ($registro->estado == 1) {
                Log::info('El registro ya está cerrado con ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'El registro ya está cerrado.',
                ], 400);
            }

            // Establecer el estado a cerrado y asignar la fecha de salida
            $registro->estado = 1; // 1 significa cerrado
            $registro->fecha_salida = now(); // Asignar la fecha y hora de salida
            $registro->save();

            Log::info('Registro cerrado exitosamente con ID: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Registro cerrado exitosamente.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cerrar el registro con ID: ' . $id . ' - ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno en el servidor.',
            ], 500);
        }
    }


    // Método para mostrar la vista de control
    public function mostrarVistaControl()
    {
        // Obtener el vigilante autenticado
        $vigilante = Auth::user();

        // Verificar si el vigilante está autenticado
        if (!$vigilante) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        // Obtener el último registro de control de ingreso, si existe
        $control_ingresos = ControlIngreso::latest()->first(); // Obtiene el registro más reciente
        $controlIngresoId = $control_ingresos ? $control_ingresos->id : null;

        // Si es necesario, obtén los elementos (puede variar según tu lógica)
        $elementos = Elemento::all(); // O ajusta según tu necesidad, por ejemplo, obtener elementos filtrados por usuario.

        // Cargar la vista con los datos
        return view('index.vistacontrol', compact('vigilante', 'controlIngresoId', 'elementos'));
    }


    public function registrarElementoEnSubControl(Request $request)
    {
        $request->validate([
            'control_ingreso_id' => 'required|integer',
            'elemento_id' => 'required|integer',
        ]);

        $controlIngreso = ControlIngreso::find($request->input('control_ingreso_id'));
        if (!$controlIngreso) {
            return response()->json([
                'success' => false,
                'message' => 'Registro de control de ingreso no encontrado.',
            ], 404);
        }

        if ($controlIngreso->estado == 1) {
            return response()->json([
                'success' => false,
                'message' => 'No se pueden agregar elementos a un registro cerrado.',
            ], 400);
        }

        $elemento = Elemento::find($request->input('elemento_id'));
        if (!$elemento) {
            return response()->json([
                'success' => false,
                'message' => 'Elemento no encontrado.',
            ], 404);
        }

        // Verificar si el elemento ya está registrado
        $existe = Sub_Control_Ingreso::where('control_ingreso_id', $controlIngreso->id)
            ->where('elemento_id', $elemento->id)
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Este elemento ya ha sido registrado en este control de ingreso.',
            ], 400);
        }

        try {
            $subControlIngreso = Sub_Control_Ingreso::create([
                'control_ingreso_id' => $controlIngreso->id,
                'elemento_id' => $elemento->id,
            ]);

            // Devolver el registro completo del sub_control_ingreso
            return response()->json([
                'success' => true,
                'message' => 'Elemento registrado exitosamente.',
                'elemento' => [
                    'id' => $subControlIngreso->id, // ID de sub_control_ingresos
                    'control_ingreso_id' => $subControlIngreso->control_ingreso_id,
                    'elemento_id' => $subControlIngreso->elemento_id,
                    'foto' => $elemento->foto,
                    'serie' => $elemento->serie,
                    'marca' => $elemento->marca,
                    'categoria' => [
                        'id' => $elemento->categoria->id,
                        'nombre' => $elemento->categoria->nombre,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el elemento: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function obtenerElementosPorRegistro($registroId)
    {
        // Buscar el control de ingreso por el ID
        $controlIngreso = ControlIngreso::find($registroId);

        if (!$controlIngreso) {
            return response()->json([
                'success' => false,
                'message' => 'Registro de control de ingreso no encontrado.',
            ], 404);
        }

        // Obtener los elementos asociados al registro
        $elementos = $controlIngreso->subControlIngresos()
            ->with('elemento.categoria')
            ->get()
            ->pluck('elemento')
            ->map(function ($elemento) {
                // Ajusta la URL de la imagen para que sea accesible desde el frontend
                $elemento->imagen_url = url('storage/' . $elemento->ruta_imagen);
                return $elemento;
            });

        if ($elementos->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron elementos.',
            ]);
        }

        return response()->json([
            'success' => true,
            'elementos' => $elementos,
        ]);
    }




    public function destroy($id)
    {
        try {
            $subControlIngreso = Sub_Control_Ingreso::findOrFail($id);

            // Verificar si el registro asociado está cerrado
            $registroPadre = $subControlIngreso->controlIngreso; // Relación con ControlIngreso

            if (!$registroPadre) {
                // Si no se encuentra el registro asociado
                Log::error("Error: El sub-control ingreso con ID $id no tiene un registro padre asociado.");
                return response()->json([
                    'success' => false,
                    'message' => 'El elemento no está asociado a un registro válido.'
                ], 500);
            }

            if ($registroPadre->estado == 1) { // 1 indica cerrado
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar elementos de un registro cerrado.'
                ], 403);
            }

            // Proceder a eliminar si el registro no está cerrado
            $subControlIngreso->delete();

            return response()->json(['success' => true, 'message' => 'Elemento eliminado correctamente.']);
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error("Error al eliminar el sub-control ingreso con ID $id: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor al intentar eliminar el elemento.'
            ], 500);
        }
    }

















    // Función privada para obtener elementos con relaciones
    private function fetchSubControlElements($controlIngresoId = null)
    {
        $query = Sub_Control_Ingreso::with(['controlIngreso', 'elemento.categoria']);

        // Filtrar por control_ingreso_id si se proporciona
        if ($controlIngresoId) {
            $query->where('control_ingreso_id', $controlIngresoId);
        }

        return $query->get()->map(function ($subControl) {
            return [
                'id' => $subControl->id, // ID de la tabla sub_control_ingresos
                'control_ingreso_id' => $subControl->control_ingreso_id,
                'elemento_id' => $subControl->elemento_id,
                'foto' => $subControl->elemento->foto ?? null,
                'serie' => $subControl->elemento->serie ?? null,
                'marca' => $subControl->elemento->marca ?? null,
                'categoria' => [
                    'nombre' => $subControl->elemento->categoria->nombre ?? null,
                ],
            ];
        });
    }
}
