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
    <link rel="stylesheet" href="{{ asset('css/styles_reportes_ingresos_elementos.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Reportes Elementos</title>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container-header">
            <div class="nav-left-group">
                <div class="logo-header">
                    <img src="{{ asset('imagenes/Logo-Control-E.png') }}" alt="Control E Logo" class="logo-header-img">
                </div>
            </div>
            <span class="header-title">Reportes de Elementos</span>
        </div>
    </header>

    <!-- Contenido principal -->
    <div class="container">
        <!-- Formulario de búsqueda -->
        <form id="formReporteElementos" method="GET" action="{{ route('reportes.elementos.consulta') }}">
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
                    <input type="text" id="numeroDocumento" name="numero_documento" class="input" placeholder="Buscar usuario con un elemento específico">
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
                    <button id="generarPDF" type="button" class="button is-primary" data-url="{{ route('admin.reportes.elementos.pdf') }}">
                        Generar PDF
                    </button>
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
                        <th>NÚMERO DOCUMENTO</th>
                        <th>CATEGORÍA</th>
                        <th>SERIAL</th>
                        <th>MARCA</th>
                        <th>FECHA INGRESO</th>
                        <th>FECHA EGRESO</th>
                        <th>ESTADO</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody id="resultados">
                    <!-- Los resultados se cargarán dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Usuario -->
    <div class="modal" id="modalUsuario">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Información del Usuario</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <div class="usuario-info">
                    <div class="info-text">
                        <h3><span id="nombreUsuario"></span> <span id="apellidosUsuario"></span></h3>
                        <p><i class="fas fa-id-card"></i> <strong>Doc:</strong> <span id="documentoUsuario"></span></p>
                        <p><i class="fas fa-phone"></i> <strong>Cel:</strong> <span id="telefonoUsuario"></span></p>
                        <p><i class="fas fa-heart"></i> <strong>RH:</strong> <span id="rhUsuario"></span></p>
                        <p><i class="fas fa-user-tag"></i> <strong>Rol:</strong> <span id="rolUsuario"></span></p>
                        <p class="ficha-container"><i class="fas fa-clipboard"></i> <strong>Ficha:</strong> <span id="fichaUsuario"></span></p>
                    </div>
                    <div class="foto-usuario">
                        <img id="fotoUsuario" src="" alt="Foto de perfil" class="foto-perfil-usuario">
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Modal Elementos -->
    <div class="modal" id="modalElementos">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Elementos del Usuario</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <div class="table-container">
                    <table class="table is-striped is-fullwidth">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody id="elementosUsuario">
                            <!-- Los elementos se cargarán aquí dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/reportes_ingresos_elementos.js') }}"></script>
</body>
</html>