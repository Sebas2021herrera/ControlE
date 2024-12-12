<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles_vista_pdf_ingresos.css') }}">
    <title>Reportes ingresos PDF</title>
    <style>
        body {
            font-family: 'Work Sans', sans-serif;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .fecha-generacion {
            text-align: right;
            margin-bottom: 20px;
            font-size: 10px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: center;
            font-size: 10px; /* Reducido para el contenido de la tabla */

        }

        thead th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
</head>
<body>
    
    <div class="header">
        <h1>Reporte de Ingresos</h1>
    </div>

    <div class="fecha-generacion">
        <p>Fecha de generación: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>NOMBRE CENTRO</th>
                <th>NÚMERO DOCUMENTO</th>
                <th>FECHA INGRESO</th>
                <th>FECHA EGRESO</th>
                <th>ESTADO</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ingresos as $ingreso)
                <tr>
                    <td>{{ $ingreso['ID'] }}</td>
                    <td>{{ $ingreso['NOMBRE_CENTRO'] }}</td>
                    <td>{{ $ingreso['NUMERO_DOCUMENTO'] }}</td>
                    <td>{{ $ingreso['FECHA_INGRESO'] }}</td>
                    <td>{{ $ingreso['FECHA_EGRESO'] }}</td>
                    <td>{{ $ingreso['ESTADO'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No hay registros disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} 2024 Control E. Todos los derechos reservados</p>
    </div>
</body>
</html>