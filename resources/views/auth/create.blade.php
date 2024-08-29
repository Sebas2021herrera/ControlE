<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Usuarios</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles_create.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <h2 class="title is-4">Buscar Usuario por Número de Documento</h2>

                @if (session('error'))
                    <div class="notification is-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('buscar.usuario') }}">
                    @csrf
                    <div class="field">
                        <label class="label" for="numero_documento_buscar">Número de Documento:</label>
                        <div class="control">
                            <input class="input" type="text" id="numero_documento_buscar" name="numero_documento" required>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <button class="button is-primary" type="submit">Buscar</button>
                        </div>
                    </div>
                </form>

                @if (isset($usuario))
                    <div class="box">
                        <h3 class="title is-5">Resultados de la Búsqueda:</h3>
                        <p><strong>Nombres:</strong> {{ $usuario->nombres }}</p>
                        <p><strong>Apellidos:</strong> {{ $usuario->apellidos }}</p>
                        <p><strong>Número de Documento:</strong> {{ $usuario->numero_documento }}</p>
                        <p><strong>Correo Personal:</strong> {{ $usuario->correo_personal }}</p>
                        <p><strong>Correo Institucional:</strong> {{ $usuario->correo_institucional }}</p>
                        <p><strong>Teléfono:</strong> {{ $usuario->telefono }}</p>
                        <p><strong>Rol:</strong> {{ $usuario->rol->nombre }}</p>
                        <img src="{{ asset('storage/fotos/' . $usuario->foto) }}" alt="Foto de Perfil" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                @elseif(isset($mensaje))
                    <div class="notification is-warning">
                        {{ $mensaje }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        $(document).ready(function() {
            const successMessage = $('.alert-success');
            if (successMessage.length) {
                setTimeout(() => {
                    successMessage.fadeOut(500); 
                }, 5000);
            }
        });
    </script>
</body>
</html>