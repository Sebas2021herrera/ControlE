<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\ControlIngreso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReportesIngresosController extends Controller
{
    public function index()
    {
        // Solo muestra los reportes si el usuario está autenticado
        if (Auth::check()) {
            $usuario = Auth::user();

            // Obtener reportes con las nuevas columnas
            $ingresos = ControlIngreso::where('usuario_id', $usuario->id)
                ->select('id', 'nombre_centro', 'fecha_ingreso', 'fecha_egreso', 'estado')
                ->get();

            return view('reportes_ingresos', compact('ingresos'));
        }

        return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver los reportes.');
    }

    public function generarPDF()
    {
        if (Auth::check()) {
            $usuario = Auth::user();

            $ingresos = ControlIngreso::where('usuario_id', $usuario->id)
                ->select('id', 'nombre_centro', 'fecha_ingreso', 'fecha_egreso', 'estado')
                ->get();

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

    // Consultar ingresos con las nuevas columnas y filtros
    $query = ControlIngreso::whereBetween('fecha_ingreso', [$validated['fecha_inicio'], $validated['fecha_final']])
        ->select('id', 'nombre_centro', 'fecha_ingreso', 'fecha_egreso', 'estado')
        ->with(['usuario:id,numero_documento,nombre,apellido']);

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

    // Transformar datos para el frontend
    $transformados = $resultados->map(function ($ingreso) {
        return [
            'id' => $ingreso->id,
            'nombre_centro' => $ingreso->nombre_centro,
            'fecha_ingreso' => $ingreso->fecha_ingreso,
            'fecha_egreso' => $ingreso->fecha_egreso,
            'estado' => $ingreso->estado,
        ];
    });

    return response()->json(['success' => true, 'ingresos' => $transformados]);
}
}
