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
    <title>Reportes Usuarios</title>
</head>
<body>
    <!-- Header -->
    <header class="header">
        Reportes de Ingresos de usuarios
    </header>

    <!-- Contenido principal -->
    <div class="container">
        <!-- Formulario de búsqueda -->
        <form id="reportesForm" class="box">
            <div class="columns">
                <div class="column">
                    <label for="fechaInicio" class="label">Fecha Inicio</label>
                    <input type="datetime-local" id="fechaInicio" name="fechaInicio" class="input" required>
                </div>
                <div class="column">
                    <label for="fechaFinal" class="label">Fecha Final</label>
                    <input type="datetime-local" id="fechaFinal" name="fechaFinal" class="input" required>
                </div>
                <div class="column">
                    <label for="numeroDocumento" class="label">Número de Documento</label>
                    <input type="text" id="numeroDocumento" name="numeroDocumento" class="input" placeholder="Opcional">
                </div>
            </div>
            <div class="field is-grouped is-justify-content-flex-end">
                <div class="control">
                    <button type="reset" class="button is-light">Limpiar</button>
                </div>
                <div class="control">
                    <button type="submit" class="button is-primary">Consultar</button>
                </div>
            </div>
        </form>

        <!-- Tabla de resultados -->
        <div class="table-container">
            <table class="table is-striped is-fullwidth">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Usuario</th>
                        <th>Ingreso/Egreso</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody id="resultados">
                    <!-- Aquí se cargarán los resultados dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/reportes_ingresos.js') }}"></script>
</body>
</html>
