<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista del Vigilante</title>
    <link rel="stylesheet" href="{{ asset('css/styles_vistacontrol.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">   
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
            <div class="usuario-info">
                <!-- Logo y barra -->
                <div class="foto-logo">
                    <!-- Logo del SENA -->
                    <img src="{{ asset('imagenes/logo-del-sena-01.png') }}" alt="Logo del SENA" class="logo-sena">
                    <div class="barra-separadora"></div>
                </div>
                @if (isset($usuario))
                    <div class="info-text">
                        <p class="verde">{{ $usuario->nombres }}</p>
                        <p class="verde"> {{ $usuario->apellidos }}</p>
                        <p><strong> Doc: {{ $usuario->numero_documento }} </strong></p>
                        <p><strong>Cel:{{ $usuario->telefono }}</strong></p><br />
                        <p><strong> {{ $usuario->role->nombre }}</strong></p>
                        <p><strong> Ficha:{{ $usuario->numero_ficha }}</strong></p>
                        <p class="verde" id="semifooter"> Regional Casanare | Centro Agroindustrial y Fortalecimiento
                            Empresarial del Casanare</p>
                    </div>

                    <!-- Foto del usuario -->
                    <div class="foto-usuario">
                        @if ($usuario->foto && file_exists(storage_path('app/public/fotos_perfil/' . $usuario->foto)))
                            <img src="{{ asset('storage/fotos_perfil/' . $usuario->foto) }}" alt="Foto de perfil"
                                class="foto-perfil-usuario">
                        @else
                            <img src="{{ asset('imagenes/sin_foto_perfil.webp') }}" alt="Foto de perfil predeterminada"
                                class="foto-perfil-usuario">
                        @endif
                    </div>
                @else
                    <p>No se ha seleccionado ningún usuario.</p>
                @endif
            </div>
        </div>

        <div class="contenido">
            <div class="elementos">
                @if (isset($elementos) && $elementos->isNotEmpty())
                    <div class="card-container">
                        @foreach ($elementos as $elemento)
                            <div class="card">
                                <img src="{{ asset('storage/fotos_elementos/' . $elemento->foto) }}"
                                    alt="Foto del elemento" class="elemento-foto">

                                <h3>{{ $elemento->categoria->nombre }}</h3>
                                <p><strong>Marca:</strong> {{ $elemento->marca }}</p>
                                <p><strong>Modelo:</strong> {{ $elemento->modelo }}</p>
                                <p><strong>Serie:</strong> {{ $elemento->serie }}</p>
                                <p><strong>Especificaciones:</strong> {{ $elemento->especificaciones_tecnicas }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No hay elementos disponibles.</p>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
