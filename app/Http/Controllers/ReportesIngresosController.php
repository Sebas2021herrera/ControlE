<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\ControlIngreso;
use App\Models\Sub_Control_Ingreso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReportesIngresosController extends Controller
{
    public function index()
    {
        // Solo muestra los reportes si el usuario está autenticado
        if (Auth::check()) {
            $usuario = Auth::user();
            
            // Obtiene los ingresos relacionados con el usuario autenticado
            $ingresos = ControlIngreso::where('usuario_id', $usuario->id)->with('centro', 'subControlIngresos.elemento')->get();

            return view('reportes_ingresos', compact('ingresos'));
        }

        return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver los reportes.');
    }

    public function generarPDF()
    {
        if (Auth::check()) {
            $usuario = Auth::user();
            
            $ingresos = ControlIngreso::where('usuario_id', $usuario->id)->with('centro', 'subControlIngresos.elemento')->get();

            $pdf = Pdf::loadView('reportes.pdf_ingresos', compact('usuario', 'ingresos'));
            return $pdf->download('reporte_ingresos.pdf');
        }

        return redirect()->route('login')->with('error', 'Debes iniciar sesión para generar el reporte.');
    }

    public function consultaIngresos(Request $request)
{
    // Validar los datos de entrada
    $validated = $request->validate([
        'fecha_inicio' => 'required|date',
        'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
        'documento_usuario' => 'nullable|string|max:20',
    ]);

    // Consultar ingresos con filtros
    $query = ControlIngreso::whereBetween('fecha_ingreso', [$validated['fecha_inicio'], $validated['fecha_final']])
        ->with(['usuario:id,numero_documento,nombre,apellido', 'centro:id,nombre', 'subControlIngresos.elemento']);

    // Filtrar por número de documento si se proporciona
    if (!empty($validated['documento_usuario'])) {
        $query->whereHas('usuario', function ($q) use ($validated) {
            $q->where('numero_documento', $validated['documento_usuario']);
        });
    }

    $resultados = $query->get();

    // Verificar si no hay resultados
    if ($resultados->isEmpty()) {
        return response()->json(['error' => 'No se encontraron resultados para los criterios dados.'], 404);
    }

    // Transformar datos para una mejor estructura en el frontend
    $transformados = $resultados->map(function ($ingreso) {
        return [
            'fecha_ingreso' => $ingreso->fecha_ingreso,
            'usuario' => [
                'numero_documento' => $ingreso->usuario->numero_documento,
                'nombre' => $ingreso->usuario->nombre,
                'apellido' => $ingreso->usuario->apellido,
            ],
            'centro' => $ingreso->centro->nombre,
            'elementos' => $ingreso->subControlIngresos->map(function ($subIngreso) {
                return [
                    'descripcion' => $subIngreso->elemento->descripcion,
                    'marca' => $subIngreso->elemento->marca,
                ];
            }),
        ];
    });

    return response()->json(['success' => true, 'ingresos' => $transformados]);
    
    }

}