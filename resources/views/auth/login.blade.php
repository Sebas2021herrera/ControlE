<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <!-- Enlace a Bootstrap para estilos -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
    <!-- Enlace a archivo de estilos personalizados -->
 
</head>

<body>
    <!-- Contenedor principal -->
    <div class="container d-flex flex-column align-items-center justify-content-center min-vh-100">
        <!-- Contenedor del logo del SENA -->
        <div class="logo-container">
            <img src="{{ asset('imagenes/logo_sena.png') }}" alt="SENA Logo" class="img-fluid mb-4 logo-sena">
        </div>
        <!-- Tarjeta personalizada para el formulario -->
        <div class="custom-card">
            <div class="custom-card-header">
                CONTROL E
            </div>
            <form>
                <!-- Grupo de entrada para el correo -->
                <div class="form-group">
                    <label for="email" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="email" placeholder="Institucional">
                </div>
                <!-- Grupo de entrada para la contraseña -->
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" placeholder="*******">
                </div>
                <!-- Botón de inicio de sesión -->
                <button type="submit" class="btn btn-dark btn-block">Iniciar sesión</button>
            </form>
            <!-- Enlace para recordar contraseña -->
            <div class="remember-link">
                <a href="#">Recordar contraseña</a>
            </div>
        </div>
        <!-- Texto del pie de página -->
        <p class="text-center footer-text"><img src="{{asset('imagenes/logo_copyrigth.png') }}" class="bombilla"> Derechos
            reservados al
            SENA Regional Casanare, 2024.</p>
    </div>
</body>

</html>
