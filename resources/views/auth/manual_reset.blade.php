<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles_create.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <h2 class="title is-4">Restablecer Contraseña</h2>

                <!-- Mostrar mensaje de éxito -->
                @if (session('success'))
                    <div class="notification is-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Mostrar errores de validación -->
                @if ($errors->any())
                    <div class="notification is-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.manual-reset') }}">
                    @csrf
                    <!-- Correo personal -->
                    <div class="field">
                        <label class="label" for="correo_personal">Correo personal:</label>
                        <div class="control">
                            <input class="input @error('correo_personal') is-danger @enderror" type="email" id="correo_personal" name="correo_personal" required>
                        </div>
                        @error('correo_personal')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botón de restablecer contraseña -->
                    <div class="field">
                        <div class="control">
                            <button class="button is-primary" type="submit">Restablecer contraseña</button>
                            
                        </div>
                    </div>
                    
                </form>
                <br>
                <a href="{{ route('login') }}" class="">Volver a Login</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        $(document).ready(function() {
            const successMessage = $('.notification.is-success');
            if (successMessage.length) {
                setTimeout(() => {
                    successMessage.fadeOut(500); // Desvanecer el mensaje de éxito en 0.5 segundos
                }, 5000); // Mostrar el mensaje por 5 segundos antes de desvanecerlo
            }
        });
    </script>
</body>

</html>
