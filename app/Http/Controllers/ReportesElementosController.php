<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Elemento;
use App\Models\ReportesControlIngresos;
use App\Models\ControlIngreso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReportesElementosController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver los reportes.');
        }

        $elementos = Elemento::with(['categoria'])->get();
        return view('PDF.reportes_elementos', compact('elementos'));
    }

    public function consultaElementos(Request $request)
    {
        try {
            $validated = $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
                'categoria_id' => 'nullable|integer',
                'serie' => 'nullable|string',
                'marca' => 'nullable|string'
            ]);

            $query = ControlIngreso::with(['usuario', 'reportesIngresos.elemento.categoria'])
                ->whereBetween('fecha_ingreso', [
                    $validated['fecha_inicio'],
                    $validated['fecha_final']
                ]);

            if (!empty($validated['serie'])) {
                $query->whereHas('usuario', function($q) use ($validated) {
                    $q->where('numero_documento', 'like', '%' . $validated['serie'] . '%');
                });
            }

            $ingresos = $query->get();
            $elementosFormateados = collect();

            foreach ($ingresos as $ingreso) {
                foreach ($ingreso->reportesIngresos as $reporte) {
                    if ($reporte->elemento) {
                        // Aplicar filtros adicionales
                        if (!empty($validated['categoria_id']) && 
                            $reporte->elemento->categoria_id != $validated['categoria_id']) {
                            continue;
                        }

                        if (!empty($validated['marca']) && 
                            !str_contains(strtolower($reporte->elemento->marca), strtolower($validated['marca']))) {
                            continue;
                        }

                        $elementosFormateados->push([
                            'ID' => $reporte->elemento->id,
                            'NUMERO_DOCUMENTO' => $ingreso->usuario->numero_documento ?? 'N/A',
                            'CATEGORIA' => $reporte->elemento->categoria->nombre ?? 'N/A',
                            'SERIE' => $reporte->elemento->serie ?? 'N/A',
                            'MARCA' => $reporte->elemento->marca ?? 'N/A',
                            'FECHA_INGRESO' => $ingreso->fecha_ingreso,
                            'FECHA_EGRESO' => $ingreso->fecha_salida ?? 'N/A',
                            'ESTADO' => $ingreso->estado == 0 ? 'Abierto' : 'Cerrado'
                        ]);
                    }
                }
            }

            if ($elementosFormateados->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'error' => 'No se encontraron elementos para los criterios dados.'
                ], 200);
            }

            return response()->json([
                'success' => true,
                'elementos' => $elementosFormateados
            ]);

        } catch (\Exception $e) {
            Log::error('Error en consultaElementos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generarPDF(Request $request)
{
    try {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para generar el reporte.');
        }

        if (Auth::user()->roles_id !== 1) {
            return redirect()->back()
                ->with('error', 'No tienes permisos para generar este reporte.');
        }

        $query = ControlIngreso::with(['usuario', 'reportesIngresos.elemento.categoria'])
            ->select('control_ingresos.*');

        if ($request->filled(['fecha_inicio', 'fecha_final'])) {
            \Log::info('Aplicando filtro de fechas:', [
                'inicio' => $request->fecha_inicio,
                'final' => $request->fecha_final
            ]);
            
            $query->whereBetween('fecha_ingreso', [
                $request->fecha_inicio,
                $request->fecha_final
            ]);
        }

        if ($request->filled('serie')) {
            $query->whereHas('usuario', function($q) use ($request) {
                $q->where('numero_documento', 'like', '%' . $request->serie . '%');
            });
        }

        $ingresos = $query->get();
        $elementosFormateados = collect();

        foreach ($ingresos as $ingreso) {
            foreach ($ingreso->reportesIngresos as $reporte) {
                if ($reporte->elemento) {
                    // Aplicar filtro de categoría si se proporciona
                    if ($request->filled('categoria_id') && 
                        $reporte->elemento->categoria_id != $request->categoria_id) {
                        continue;
                    }

                    // Aplicar filtro de marca si se proporciona
                    if ($request->filled('marca') && 
                        !str_contains(strtolower($reporte->elemento->marca), strtolower($request->marca))) {
                        continue;
                    }

                    $elementosFormateados->push([
                        'ID' => $reporte->elemento->id,
                        'NUMERO_DOCUMENTO' => $ingreso->usuario->numero_documento ?? 'N/A',
                        'CATEGORIA' => $reporte->elemento->categoria->nombre ?? 'N/A',
                        'SERIE' => $reporte->elemento->serie ?? 'N/A',
                        'MARCA' => $reporte->elemento->marca ?? 'N/A',
                        'FECHA_INGRESO' => Carbon::parse($ingreso->fecha_ingreso)->format('Y-m-d H:i:s'),
                        'FECHA_EGRESO' => $ingreso->fecha_salida 
                            ? Carbon::parse($ingreso->fecha_salida)->format('Y-m-d H:i:s')
                            : 'N/A',
                        'ESTADO' => $ingreso->estado == 0 ? 'Abierto' : 'Cerrado'
                    ]);
                }
            }
        }
        
        \Log::info('Elementos encontrados: ' . $elementosFormateados->count());

        $pdf = PDF::loadView('PDF.pdf_elementos', [
            'elementos' => $elementosFormateados->toArray(),
            'fechaInicio' => $request->fecha_inicio,
            'fechaFinal' => $request->fecha_final
        ]);

        return $pdf->stream('reporte_elementos_' . date('Y-m-d_H-i-s') . '.pdf');
        
    } catch (\Exception $e) {
        \Log::error('Error generando PDF: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        return redirect()->back()
            ->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
}
