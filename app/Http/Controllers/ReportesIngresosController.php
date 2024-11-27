<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Elemento;
use App\Models\Categoria;
use Illuminate\Database\QueryException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth; // Añadido para autenticación

class ReportesIngresosController extends Controller
{
    public function index()
    {
        // Solo muestra los reportes si el usuario está autenticado
        if (Auth::check()) {
            $usuario = Auth::user();
            $ingresos = $usuario->ingresos; // Supongamos que tienes una relación con ingresos

            return view('reportes_ingresos', compact('ingresos'));
        }

        return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver los reportes.');
    }

    public function generarPDF()
    {
        if (Auth::check()) {
            $usuario = Auth::user();
            $ingresos = $usuario->ingresos;

            $pdf = Pdf::loadView('reportes.pdf_ingresos', compact('usuario', 'ingresos'));
            return $pdf->download('reporte_ingresos.pdf');
        }

        return redirect()->route('login')->with('error', 'Debes iniciar sesión para generar el reporte.');
    }
}
