<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista del Vigilante</title>
    <link rel="stylesheet" href="{{ asset('css/styles_vistacontrol.css') }}"> <!-- Enlaza el archivo CSS personalizado -->
</head>

<body>

    <div class="container">
        <!-- Formulario para buscar por número de documento -->
        <div class="buscador">
            <form action="{{ route('user.panel') }}" method="GET">
                <input type="text" name="documento" placeholder="Buscar por número de documento...">
                <button type="submit">Buscar</button>
            </form>
        </div>

        <div class="contenedor-intermedio">
            <!-- Nuevo contenedor intermedio -->
            @if (session('error'))
                <div class="notification is-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <div class="contenido">
            <div class="izquierdo">
                <!-- Contenedor izquierdo (ancho fijo de 400px) -->
                @if (isset($usuario))
                    <h2>{{ $usuario->nombres }} {{ $usuario->apellidos }}</h2>
                    <p>Número de Documento: {{ $usuario->numero_documento }}</p>
                    <p>Correo Personal: {{ $usuario->correo_personal }}</p>
                    <!-- Agrega más campos según sea necesario -->
                @else
                    <p>No se ha seleccionado ningún usuario.</p>
                @endif
            </div>
            <div class="derecho">
                <!-- Contenedor derecho (ancho fijo de 820px) -->
            </div>
        </div>
    </div>

</body>

</html>