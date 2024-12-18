<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acerca de Control E</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles_about.css') }}" rel="stylesheet">
</head>
<body>
    <div class="section-content">
        <div class="container text-center">
            <img src="" alt="">
            <h1 class="display-4 fw-bold mb-3">Acerca de Control E</h1>
        </div>
        <p class="lead">Lorem ipsum dolor sit amet consectetur adipiscing elit odio dis facilisis, auctor arcu semper nulla eleifend dui gravida porttitor.</p>
    </div>

    <div class="container">
        <!-- Sección Principal -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-tasks card-icon"></i>
                        <h3 class="card-title">¿Qué es Control E?</h3>
                        <p class="card-text">Control E es un sistema de gestión diseñado específicamente para el control eficiente de elementos en la Regional Casanare - SENA. Esta plataforma permite realizar un seguimiento detallado de los recursos institucionales.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-bullseye card-icon"></i>
                        <h3 class="card-title">Propósito</h3>
                        <p class="card-text">Nuestro objetivo es facilitar el monitoreo y control de recursos en la institución, optimizando los procesos de gestión y mejorando la eficiencia en el manejo de elementos institucionales.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-chart-line card-icon"></i>
                        <h3 class="card-title">Impacto</h3>
                        <p class="card-text">Mejoramos la eficiencia operativa, reducimos tiempos de gestión y garantizamos un control preciso de los recursos institucionales.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección Características -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Características Principales</h2>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card text-center p-3">
                    <i class="fas fa-desktop mb-3" style="font-size: 2rem; color: #0d6efd;"></i>
                    <h4>Lorem ipsum</h4>
                    <p>Lorem ipsum Lorem ipsum</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card text-center p-3">
                    <i class="fas fa-shield-alt mb-3" style="font-size: 2rem; color: #0d6efd;"></i>
                    <h4>Lorem ipsum</h4>
                    <p>Lorem ipsum Lorem ipsum</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card text-center p-3">
                    <i class="fas fa-chart-pie mb-3" style="font-size: 2rem; color: #0d6efd;"></i>
                    <h4>Lorem ipsum</h4>
                    <p>Lorem ipsum Lorem ipsum</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card text-center p-3">
                    <i class="fas fa-sync mb-3" style="font-size: 2rem; color: #0d6efd;"></i>
                    <h4>Tiempo Real</h4>
                    <p>Actualizaciones instantáneas</p>
                </div>
            </div>
        </div>

        <!-- Sección Equipo -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Nuestro Equipo</h2>
                <p class="lead">Desarrollado por aprendices del SENA Regional Casanare</p>
            </div>
            <div class="col-md-4">
                <div class="team-member">
                    <div class="team-member-placeholder" style="width: 150px; height: 150px; background-color: #0d6efd; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem; margin: 0 auto;">
                        DP
                    </div>
                    <h4>Desarrollador</h4>
                    <p>Aprendiz SENA Regional Casanare</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="team-member">
                    <div class="team-member-placeholder" style="width: 150px; height: 150px; background-color: #198754; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem; margin: 0 auto;">
                        DP
                    </div>
                    <h4>Desarrollador</h4>
                    <p>Aprendiz SENA Regional Casanare</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">© {{ date('Y') }} Control E - SENA Regional Casanare</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="{{ url('/') }}" class="btn btn-outline-light">Volver al inicio</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Botón Volver Arriba -->
    <button class="btn btn-primary back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </button>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>