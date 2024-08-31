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
            <form action="{{ route('vigilante.buscar') }}" method="GET">
                <input type="text" name="documento" placeholder="Buscar por documento...">
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
                <h2>Información del Usuario</h2>
                <p><strong>Nombre:</strong> {{ $usuario->nombres }}</p>
                <p><strong>Apellidos:</strong> {{ $usuario->apellidos }}</p>
                <p><strong>Número de Documento:</strong> {{ $usuario->numero_documento }}</p>
                <p><strong>Rol:</strong> {{ $usuario->role->nombre }}</p>
                <img src="{{ asset('path_to_profile_images/' . $usuario->foto_perfil) }}" alt="Foto de perfil" width="100">
                <div class="image-container" style="margin-top: 15px;">
                    @if ($usuario->foto && file_exists(storage_path('app/public/fotos_perfil/' . $usuario->foto)))
                        <img id="left-panel-img" src="{{ asset('storage/fotos_perfil/' . $usuario->foto) }}"
                            alt="Foto de perfil" class="foto-perfil"
                            style="width: 150px; height: 150px; object-fit: cover; border-radius: 20px;">
                    @else
                    <img id="left-panel-img" src="{{ asset('imagenes/sin_foto_perfil.webp') }}"
                    alt="Foto de perfil predeterminada" class="foto-perfil"
                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 20px;">
                    @endif
                </div>
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
    <script>
        const izquierdo = document.querySelector('.izquierdo');
        izquierdo.addEventListener('wheel', function(e) {
            if (e.deltaY > 0) {
                izquierdo.scrollBy(0, 100);
            } else {
                izquierdo.scrollBy(0, -100);
            }
            e.preventDefault();
        });
    </script>
</body>

</html>