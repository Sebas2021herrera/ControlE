<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Reportes Elementos PDF</title>
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

        .logo-container {
            text-align: center;
            margin-bottom: 10px;
        }
        
        .logo-container img {
            max-width: 120px;
            height: auto;
        }

        .regional-text {
            text-align: center;
            font-size: 12px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 500;
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
            font-size: 10px;
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
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="{{ public_path('imagenes/logo-del-sena-01.png') }}" alt="Logo SENA">
    </div>
    
    <div class="regional-text">
        Regional Casanare | Centro Agroindustrial y Fortalecimiento Empresarial del Casanare
    </div>
    
    <div class="header">
        <h1>Reporte de Elementos</h1>
    </div>

    <div class="fecha-generacion">
        <p>Fecha de generación: {{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->isoFormat('DD [de] MMMM [de] YYYY, h:mm A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>NÚMERO DOCUMENTO</th>
                <th>CATEGORÍA</th>
                <th>SERIAL</th>
                <th>MARCA</th>
                <th>FECHA INGRESO</th>
                <th>FECHA SALIDA</th>
                <th>ESTADO</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($elementos as $elemento)
                <tr>
                    <td>{{ $elemento->ID }}</td>
                    <td>{{ $elemento->NUMERO_DOCUMENTO }}</td>
                    <td>{{ $elemento->CATEGORIA }}</td>
                    <td>{{ $elemento->SERIE }}</td>
                    <td>{{ $elemento->MARCA }}</td>
                    <td>{{ $elemento->FECHA_INGRESO }}</td>
                    <td>{{ $elemento->FECHA_EGRESO ?? 'N/A' }}</td>
                    <td>{{ $elemento->ESTADO }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">No hay registros disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Control E. Todos los derechos reservados</p>
    </div>
</body>
</html>