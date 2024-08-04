<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Elementos - SENA</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> <!-- Asegúrate de que tu archivo CSS esté en public/css/app.css -->
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">SENA</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <!-- Aquí puedes agregar otros elementos de navegación si es necesario -->
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="navbar-text">
                        {{ Auth::user()->nombres }}
                    </span>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Registrar Elemento
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('elementos.create') }}">Nuevo Elemento</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content') <!-- Aquí se mostrará el contenido de las vistas que extiendan este layout -->
    </div>

    <script src="{{ asset('js/app.js') }}"></script> <!-- Asegúrate de que tu archivo JS esté en public/js/app.js -->
</body>
</html>
