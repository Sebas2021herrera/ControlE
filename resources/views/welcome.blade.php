<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control E - Bienvenida</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navbar 
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Control e</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">  
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('create') }}">Registrarse</a>
                </li>
            </ul>
        </div>
    </nav> -->

    <!-- Hero Section -->
    <header class="hero d-flex align-items-center justify-content-center text-center text-white">
        <div class="hero-content">
            {{-- <h1 class="display-4 animated fadeIn">Bienvenido a Control E</h1> --}}
            <div class="logo-container">
                <img src="{{ asset('imagenes/Logo-Control-E-sin-palabras.png') }}" alt="Control E Logo" class="img-fluid-logo-sena">
            </div>
            <p class="lead animated fadeIn">Control de elementos para la Regional Casanare - SENA.</p>
            <div class="buttons mt-4">
                <a class="btn btn-light btn-lg animated bounceIn" href="{{ route('login') }}" role="button">Iniciar sesión</a>
                <a class="btn btn-secondary btn-lg ml-3 animated bounceIn" href="{{ route('create') }}" role="button">Registrarse</a>
            </div>
        </div>
    </header>
    

    <!-- Footer -->
    <footer class="footer bg-dark text-center text-white py-4">
        <div class="container">
            <p class="m-0">© {{ date('Y') }} Control e. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></script>
</body>
</html>
