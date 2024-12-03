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
        $validated = $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
            'documento_usuario' => 'nullable|string|max:20',
        ]);
    
        $query = ControlIngreso::whereBetween('fecha_ingreso', [$validated['fecha_inicio'], $validated['fecha_final']])
            ->with('centro', 'usuario', 'subControlIngresos.elemento');
    
        if (!empty($validated['documento_usuario'])) {
            $query->whereHas('usuario', function ($q) use ($validated) {
                $q->where('numero_documento', $validated['documento_usuario']);
            });
        }
    
        $resultados = $query->get();
    
        if ($resultados->isEmpty()) {
            return response()->json(['error' => 'No se encontraron resultados para los criterios dados.'], 404);
        }
    
        // Prepara la respuesta con las columnas necesarias
        $data = $resultados->map(function ($registro) {
            return [
                'ID' => $registro->id,
                'NOMBRE_CENTRO' => $registro->centro->nombre ?? 'Centro no definido',
                'FECHA_INGRESO' => $registro->fecha_ingreso,
                'FECHA_EGRESO' => $registro->fecha_salida ?? 'N/A',
                'ESTADO' => $registro->estado == 0 ? 'Abierto' : 'Cerrado',
            ];
        });
    
        return response()->json(['success' => true, 'ingresos' => $data]);
    }
    
}
