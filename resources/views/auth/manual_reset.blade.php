<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
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
                RESTABLECER CONTRASEÑA
            </div>
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.manual-reset') }}">
                    @csrf
                    <div class="form-group">
                        <label for="correo_personal" class="form-label">Correo Personal</label>
                        <input type="email"
                               class="form-control @error('correo_personal') is-invalid @enderror"
                               id="correo_personal" name="correo_personal"
                               required placeholder="ejemplo@correo.com">
                        <small class="form-text text-muted">Se enviará una nueva contraseña a este correo.</small>
                        @error('correo_personal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-dark btn-block">
                        <i class="fas fa-paper-plane mr-1"></i> Restablecer contraseña
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>

        <p class="text-center footer-text mt-4">
            <img src="{{ asset('imagenes/logo_copyrigth.png') }}" class="bombilla">
            Derechos reservados al SENA Regional Casanare, 2024.
        </p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            const msg = $('.alert-success');
            if (msg.length) setTimeout(() => msg.fadeOut(500), 5000);
        });

        document.addEventListener('DOMContentLoaded', () => {
            const body      = document.body;
            const modeToggle = document.getElementById('modeToggle');
            const modeIcon  = document.getElementById('modeIcon');
            const modeText  = document.getElementById('modeText');

            if (localStorage.getItem('dark-mode') === 'true') {
                body.classList.add('dark-mode');
                modeIcon.classList.replace('fa-moon', 'fa-sun');
                modeText.textContent = 'Modo Claro';
            }

            modeToggle.addEventListener('click', () => {
                body.classList.toggle('dark-mode');
                const isDark = body.classList.contains('dark-mode');
                localStorage.setItem('dark-mode', isDark);
                if (isDark) {
                    modeIcon.classList.replace('fa-moon', 'fa-sun');
                    modeText.textContent = 'Modo Claro';
                } else {
                    modeIcon.classList.replace('fa-sun', 'fa-moon');
                    modeText.textContent = 'Modo Noche';
                }
            });
        });
    </script>
</body>

</html>
