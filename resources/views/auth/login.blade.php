<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!-- Botón de modo noche -->
    <div class="mode-toggle" id="modeToggle">
        <i class="fas fa-moon" id="modeIcon"></i>
        <span id="modeText">Modo Noche</span>
    </div>

    <div class="container d-flex flex-column align-items-center justify-content-center min-vh-100">
        <div class="logo-container mb-4 text-center">
            <img src="{{ asset('imagenes/logo-del-sena-01.png') }}" alt="SENA Logo" class="img-fluid logo-sena">
        </div>
        <div class="card custom-card shadow-sm" style="max-width: 400px; width: 100%;">
            <div class="card-header text-center font-weight-bold">
                CONTROL E
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="correo_institucional" class="form-label">Correo Institucional</label>
                        <input type="email" class="form-control" id="correo_institucional" name="correo_institucional" placeholder="Institucional" required value="{{ old('correo_institucional') }}">
                    </div>
                    <div class="form-group">
                        <label for="contraseña" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder="*******" required>
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="togglePassword('contraseña')" style="cursor: pointer;">
                                    <i class="fas fa-eye" id="contraseña-icon"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark btn-block">Iniciar sesión</button>
                </form>
                <div class="text-center mt-3">
                    <a href="{{ route('create') }}"> ¿Aún no tienes cuenta?: ¡Regístrate! </a>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('resetpass') }}"> ¿Olvidaste tu contraseña?</a>
                </div>
            </div>
        </div>
        <p class="text-center footer-text mt-4">
            <img src="{{ asset('imagenes/logo_copyrigth.png') }}" class="bombilla"> Derechos reservados al SENA Regional Casanare, 2024.
        </p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Inicializar modo noche si está activado en localStorage
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const modeToggle = document.getElementById('modeToggle');
            const modeIcon = document.getElementById('modeIcon');
            const modeText = document.getElementById('modeText');

            if (localStorage.getItem('dark-mode') === 'true') {
                body.classList.add('dark-mode');
                modeIcon.classList.replace('fa-moon', 'fa-sun');
                modeText.textContent = 'Modo Claro';
            }

            modeToggle.addEventListener('click', () => {
                body.classList.toggle('dark-mode');
                const isDarkMode = body.classList.contains('dark-mode');
                localStorage.setItem('dark-mode', isDarkMode);

                if (isDarkMode) {
                    modeIcon.classList.replace('fa-moon', 'fa-sun');
                    modeText.textContent = 'Modo Claro';
                } else {
                    modeIcon.classList.replace('fa-sun', 'fa-moon');
                    modeText.textContent = 'Modo Noche';
                }
            });
        });

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
