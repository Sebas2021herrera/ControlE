<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\ControlIngreso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\ReportesControlIngresos;

class ReportesIngresosController extends Controller
{
    public function index()
{
    if (Auth::check()) {
        $usuario = Auth::user();

        // Modificar la consulta para obtener el nombre del centro a través de la relación
        $ingresos = ControlIngreso::with('centro')
            ->select('control_ingresos.id', 'centros_id', 'fecha_ingreso', 'fecha_salida', 'estado')
            ->get()
            ->map(function($ingreso) {
                return [
                    'id' => $ingreso->id,
                    'nombre_centro' => $ingreso->centro->nombre ?? 'Centro no definido',
                    'fecha_ingreso' => $ingreso->fecha_ingreso,
                    'fecha_salida' => $ingreso->fecha_salida,
                    'estado' => $ingreso->estado
                ];
            });

        return view('PDF.reportes_ingresos', compact('ingresos'));
    }

    return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver los reportes.');
}

public function generarPDF()
{
    if (Auth::check()) {
        $usuario = Auth::user();

        $ingresos = ControlIngreso::with(['centro', 'usuario'])
            ->select('control_ingresos.id', 'centros_id', 'usuario_id', 'fecha_ingreso', 'fecha_salida', 'estado')
            ->get()
            ->map(function($ingreso) {
                return [
                    'ID' => $ingreso->id,
                    'NOMBRE_CENTRO' => $ingreso->centro->nombre ?? 'Centro no definido',
                    'NUMERO_DOCUMENTO' => $ingreso->usuario->numero_documento ?? 'N/A',
                    'FECHA_INGRESO' => $ingreso->fecha_ingreso,
                    'FECHA_EGRESO' => $ingreso->fecha_salida ?? 'N/A',
                    'ESTADO' => $ingreso->estado == 0 ? 'Abierto' : 'Cerrado'
                ];
            });

            $pdf = Pdf::loadView('PDF.pdf_ingresos', compact('usuario', 'ingresos'));
            return $pdf->download('reporte_ingresos.pdf');
    }

    return redirect()->route('login')->with('error', 'Debes iniciar sesión para generar el reporte.');
}

    public function consultaIngresos(Request $request)
{
    try {
        $validated = $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
            'documento_usuario' => 'nullable|string|max:20',
        ]);

        $query = ControlIngreso::whereBetween('fecha_ingreso', [
                $validated['fecha_inicio'], 
                $validated['fecha_final']
            ])
            ->with(['centro', 'usuario', 'reportesIngresos.elemento']);

        if (!empty($validated['documento_usuario'])) {
            $query->whereHas('usuario', function ($q) use ($validated) {
                $q->where('numero_documento', $validated['documento_usuario']);
            });
        }

        $resultados = $query->get();

        if ($resultados->isEmpty()) {
            return response()->json([
                'success' => false,
                'error' => 'No se encontraron resultados para los criterios dados.'
            ], 200);
        }

        $data = $resultados->map(function ($registro) {
            return [
                'ID' => $registro->id,
                'NOMBRE_CENTRO' => $registro->centro->nombre ?? 'Centro no definido',
                'NUMERO_DOCUMENTO' => $registro->usuario->numero_documento ?? 'N/A',
                'FECHA_INGRESO' => $registro->fecha_ingreso,
                'FECHA_EGRESO' => $registro->fecha_salida ?? 'N/A',
                'ESTADO' => $registro->estado == 0 ? 'Abierto' : 'Cerrado',
            ];
        });

        return response()->json([
            'success' => true,
            'ingresos' => $data
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en consultaIngresos: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Error en el servidor: ' . $e->getMessage()
        ], 500);
    }
}
}