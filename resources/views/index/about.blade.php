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
            <h1 class="display-4 fw-bold mb-3">Acerca de Control E</h1>
        </div>
        <p class="lead">El proyecto nace de una necesidad identificada por los aprendices del SENA Regional Casanare, 
            buscando optimizar y asegurar el proceso de ingreso y egreso de elementos a la institución. 
            Control E representa una solución innovadora desarrollada por y para la comunidad SENA.
        </p>
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
                        <p class="card-text">Control E mejora significativamente la eficiencia operativa del SENA, 
               facilitando el trabajo del personal de seguridad, administrativos y aprendices. El sistema 
               garantiza un control preciso y transparente de los recursos institucionales. </p>
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
                    <i class="fas fa-object-group mb-3" ></i>
                    <h4>Registro y edición de elementos</h4>
                    <p>Aprendices, funcionarios y contratistas pueden registrar y editar elementos de manera eficiente.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card text-center p-3">
                    <i class="fas fa-shield-alt mb-3"></i>
                    <h4>Consulta de usuarios y registro de ingresos y egresos de usuarios y elementos</h4>
                    <p>Con este sistema, las labores del personal de seguridad serán más cómodas y rápidas</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card text-center p-3">
                    <i class="fas fa-moon mb-3"></i>
                    <h4>Modo noche</h4>
                    <p>Modo noche para los aprendices, funcionarios y administrativos de la jornada nocturna</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card text-center p-3">
                    <i class="fas fa-file-alt mb-3"></i>
                    <h4>Reportes de ingresos de usuarios y de elementos</h4>
                    <p>Los administradores pueden generar reportes desde fecha y hora en específico en formato PDF</p>
                </div>
            </div>            
        </div>

        <!-- Sección Equipo -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Nuestro Equipo</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="team-member">
                        <img src="{{ asset('imagenes/sin_foto_perfil.jpg') }}" alt="Foto de miembro del equipo" class="img-fluid">
                        <h2>Michael Sebastián Herrera Cristiano</h2>
                        <h4>Ingeniero en Sistemas</h4>
                        <p>Dinamizador TICs SENA Regional Casanare</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="team-member">
                        <img src="{{ asset('imagenes/sin_foto_perfil.jpg') }}" alt="Foto de miembro del equipo" class="img-fluid">
                        <h2>Cristofer Falla Villarreal</h2>
                        <h4>Desarrollador de software</h4>
                        <p>Aprendiz SENA Regional Casanare</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="team-member">
                        <img src="{{ asset('imagenes/sin_foto_perfil.jpg') }}" alt="Foto de miembro del equipo" class="img-fluid">
                        <h2>Darley Julieth Cuberos Rojas</h2>
                        <h4>Desarrollador de software</h4>
                        <p>Aprendiz SENA Regional Casanare</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="team-member">
                        <img src="{{ asset('imagenes/sin_foto_perfil.jpg') }}" alt="Foto de miembro del equipo" class="img-fluid">
                        <h2>Jaider Fabián Sánchez Delgado</h2>
                        <h4>Desarrollador de software</h4>
                        <p>Aprendiz SENA Regional Casanare</p>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Sección Videos Tutoriales -->
<div class="row mb-5">
    <div class="col-12 text-center mb-4">
        <h2 class="fw-bold">Videos Tutoriales</h2>
        <p class="text-muted">Explora los tutoriales para aprender a usar cada sección de Control E.</p>
    </div>
    <div class="row g-4 justify-content-center">
        <div class="col-md-6 col-lg-3">
            <div class="card text-center h-100 p-4">
                <div class="card-body">
                    <i class="fas fa-user-circle fa-3x mb-3 text-success"></i>
                    <h5 class="card-title">Página Usuario</h5>
                    <p class="card-text">Aprende a navegar y gestionar tu cuenta y tus elementos como usuario de Control E.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center h-100 p-4">
                <div class="card-body">
                    <i class="fas fa-cogs fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">Página Control</h5>
                    <p class="card-text">Descubre cómo manejar y supervisar elementos de manera eficiente.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center h-100 p-4">
                <div class="card-body">
                    <i class="fas fa-user-shield fa-3x mb-3 text-warning"></i>
                    <h5 class="card-title">Página Admin</h5>
                    <p class="card-text">Guía completa para los administradores del sistema Control E.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center h-100 p-4">
                <div class="card-body">
                    <i class="fas fa-file-alt fa-3x mb-3 text-danger"></i>
                    <h5 class="card-title">Reportes Ingresos y Elementos</h5>
                    <p class="card-text">Aprende a generar reportes detallados en formato PDF.</p>
                </div>
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
                    <a href="{{ route('welcome') }}" class="btn btn-outline-light">Volver al inicio</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Botón Volver Arriba -->
    <button class="btn btn-primary back-to-top" id="backBottom">
        <i class="fas fa-arrow-up"></i>
    </button>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar/ocultar botón volver arriba
        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.querySelector('.back-to-top').style.display = "block";
            } else {
                document.querySelector('.back-to-top').style.display = "none";
            }
        };

        // Funcionalidad botón volver arriba
        document.querySelector('.back-to-top').addEventListener('click', function() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    </script>
</body>
</html>