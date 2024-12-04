<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles_reportes_ingresos.css') }}">
    <title>Reportes Ingresos</title>
</head>
<body>
    <!-- Header -->
    <header class="header">
        Reportes de Ingresos
    </header>

    <!-- Contenido principal -->
    <div class="container">
        <!-- Formulario de búsqueda -->
        <form id="formReporteIngresos" method="GET" action="{{ route('reportes.ingresos.consulta') }}">
            <div class="columns">
                <div class="column">
                    <label for="fechaInicio" class="label">Fecha Inicio</label>
                    <input type="datetime-local" id="fechaInicio" name="fecha_inicio" class="input" required>
                </div>
                <div class="column">
                    <label for="fechaFinal" class="label">Fecha Final</label>
                    <input type="datetime-local" id="fechaFinal" name="fecha_final" class="input" required>
                </div>
                <div class="column">
                    <label for="numeroDocumento" class="label">Número de Documento</label>
                    <input type="text" id="numeroDocumento" name="documento_usuario" class="input" placeholder="Buscar usuario específico">
                </div>
            </div>
            <div class="field is-grouped is-justify-content-flex-end">
                <div class="control">
                    <button type="reset" class="button is-light">Limpiar</button>
                </div>
                <div class="control">
                    <button type="submit" class="button is-primary">Consultar</button>
                </div>
                <div class="mt-4">
                    <button id="generarPDF" method="GET" class="button is-primary" data-url="{{ route('admin.reportes.ingresos.pdf') }}">Generar PDF</button>
                </div>
            </div>
        </form>

        <!-- Mensajes y Spinner -->
        <div id="spinnerCarga" style="display: none;" class="has-text-centered">
            <p>Cargando...</p>
        </div>
        <div id="mensajeError" class="notification is-danger is-hidden"></div>

        <!-- Tabla de resultados -->
        <div class="table-container">
            <table class="table is-striped is-fullwidth">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th class="centro">NOMBRE CENTRO</th>
                        <th>NÚMERO DOCUMENTO</th>
                        <th>FECHA INGRESO</th>
                        <th>FECHA EGRESO</th>
                        <th>ESTADO</th>
                    </tr>
                </thead>
                <tbody id="resultados">
                    @if (isset($ingresos) && $ingresos->isNotEmpty())
                        @foreach ($ingresos as $ingreso)
                            <tr>
                                <td>{{ $ingreso->ID }}</td>
                                <td>{{ $ingreso->NOMBRE_CENTRO }}</td>
                                <td>{{ $ingreso->NUMERO_DOCUMENTO }}</td>
                                <td>{{ $ingreso->FECHA_INGRESO }}</td>
                                <td>{{ $ingreso->FECHA_EGRESO ?? 'N/A' }}</td>
                                <td>{{ $ingreso->ESTADO }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="has-text-centered">No hay registros disponibles.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/reportes_ingresos.js') }}"></script>
</body>
</html>