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
            <!-- Contenedor intermedio -->
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
                    <div class="image-container">
                        @if ($usuario->foto && file_exists(storage_path('app/public/fotos_perfil/' . $usuario->foto)))
                            <img id="left-panel-img" src="{{ asset('storage/fotos_perfil/' . $usuario->foto) }}"
                                alt="Foto de perfil" class="foto-perfil">
                        @else
                            <img id="left-panel-img" src="{{ asset('imagenes/sin_foto_perfil.webp') }}"
                                alt="Foto de perfil predeterminada" class="foto-perfil">
                        @endif
                    </div>
                @else
                    <p>No se ha seleccionado ningún usuario.</p>
                @endif
            </div>

            <div class="derecho">
                <!-- Contenedor derecho (ancho fijo de 820px) -->
                @if (isset($elementos) && $elementos->isNotEmpty())
                    <div class="card-container">
                        @foreach ($elementos as $elemento)
                            <div class="card">
                                <img src="{{ asset('storage/fotos_elementos/' . $elemento->foto) }}"
                                    alt="Foto del elemento" class="elemento-foto">
                                <h3>{{ $elemento->descripcion }}</h3>
                                <p><strong>Marca:</strong> {{ $elemento->marca }}</p>
                                <p><strong>Modelo:</strong> {{ $elemento->modelo }}</p>
                                <p><strong>Serie:</strong> {{ $elemento->serie }}</p>
                                <p><strong>Categoría:</strong> {{ $elemento->categoria->nombre }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No hay elementos asociados a este usuario.</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contenedores = document.querySelectorAll('.contenido .izquierdo, .contenido .derecho');

            contenedores.forEach(contenedor => {
                contenedor.addEventListener('wheel', function(e) {
                    if (e.deltaY > 0) {
                        contenedor.scrollBy(0, 100);
                    } else {
                        contenedor.scrollBy(0, -100);
                    }
                    e.preventDefault();
                });
            });
        });
    </script>

</body>

</html>
