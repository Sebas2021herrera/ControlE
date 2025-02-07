<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles_formulario_elementos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles_vistausuario.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    .field-icon {
        position: absolute;
        right: 10px;
        top: 35px;
        cursor: pointer;
    }

    /* Estilo para reducir el tamaño del nombre en el carnet digital si es muy largo */
    .nombre-carnet h5 {
        font-size: 1.2rem;
        /* Tamaño de fuente por defecto */
    }

    .nombre-carnet h5.long-name {
        font-size: 1rem;
        /* Tamaño de fuente reducido para nombres largos */
    }
</style>

<body>

    <nav class="navbar" style="background-color: #00324d;>
        <div class="container-fluid d-flex align-items-center
        justify-content-between">
        <!-- Contenedor del logo y nombre de usuario -->
        <div class="d-flex align-items-center">
            <h4 class="navbar-brand text-white" href="#">Control E</h4>
            <span class="text-white"> </span>
        </div>

        <!-- Dropdown de Opciones -->
        <div class="dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 1rem;">
                <strong style="font-size: 19px">Opciones</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" style="background-color: #00324d;">
                <li>
                    <a class="dropdown-item text-white" href="#" data-bs-toggle="modal"
                        data-bs-target="#carnetDigital" style="transition: background-color 0.3s;">
                        Carnet Digital
                    </a>
                </li>
                <li>
                    <a class="dropdown-item text-white" href="#" data-bs-toggle="modal"
                        data-bs-target="#registroModal" style="transition: background-color 0.3s;">
                        Registrar Elementos
                    </a>
                </li>
                <li>
                    <a class="dropdown-item text-white" href="#" data-bs-toggle="modal"
                        data-bs-target="#editarPerfilModal" style="transition: background-color 0.3s;">
                        Editar Perfil
                    </a>
                </li>
                <li>
                    <a class="dropdown-item text-white" href="#" data-bs-toggle="modal"
                        data-bs-target="#editarContraseñaModal" style="transition: background-color 0.3s;">
                        Editar Contraseña
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider" style="border-color: #39a900;">
                </li>
                <li>
                    <a class="dropdown-item text-white" href="#" id="toggle-dark-mode"
                        style="transition: background-color 0.3s;">
                        Modo Noche
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider" style="border-color: #39a900;">
                </li>
                <li>
                    <a class="dropdown-item text-white" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        style="transition: background-color 0.3s;">
                        Cerrar Sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        </div>
    </nav>


    {{-- mensaje para alertar que la foto debe ser menos de 5 megas --}}
    @if ($errors->has('foto'))
        <div class="alert alert-danger">
            {{ $errors->first('foto') }}
        </div>
    @endif


    <!-- Bloque para mostrar mensajes de éxito -->
    @if (session('success'))
        <div id="success-message" class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <!-- Bloque para mostrar mensajes de error -->
    @if (session('error'))
        <div id="error-message" class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="container-fluid mt-4">
        <div class="left-panel">
            <div class="welcome-text" id="welcomeMessage">
                <strong> Bienvenido</strong> <br />
            </div><br>
            <div class="nombre-usuario">
                {{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}
            </div>
            <!-- Mostrar la foto de perfil -->
            <div class="image-container" style="margin-top: 15px;">
                @if (isset($usuario) && $usuario->foto && file_exists(storage_path('app/public/fotos_perfil/' . $usuario->foto)))
                    <img id="left-panel-img" src="{{ asset('storage/fotos_perfil/' . $usuario->foto) }}"
                        alt="Foto de perfil" class="foto-perfil" style="justity-content:center">
                @else
                    <img id="left-panel-img" src="{{ asset('imagenes/sin_foto_perfil.webp') }}"
                        alt="Foto de perfil predeterminada" class="foto-perfil"
                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 8px;">
                @endif
            </div>

            <div class="other-welcome">
                <br /><strong>Estos son tus elementos:</strong>
            </div>
        </div>
        <!--cards de los elementos-->
        <div class="right-panel">
            @foreach ($elementos as $elemento)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><strong>{{ $elemento->categoria->nombre }}</strong></h5>
                        @if ($elemento->foto)
                            <img src="{{ asset('storage/' . $elemento->foto) }}" alt="Foto del elemento"
                                class="img-fluid mt-3">
                        @endif
                        <p class="card-text"><strong>Serial:</strong> {{ $elemento->serie }}</p>
                        <p class="card-text"><strong>Marca:</strong> {{ $elemento->marca }}</p>
                        <a href="#" class="btn btn-link" data-bs-toggle="modal"
                            data-bs-target="#modal-{{ $elemento->id }}">
                            Ver más
                        </a>
                    </div>
                    <div class="spacer"></div>
                </div>

                <!-- Modal para ver más detalles y editar -->
                <div class="modal fade" id="modal-{{ $elemento->id }}" tabindex="-1"
                    aria-labelledby="modalLabel-{{ $elemento->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-custom-width"> <!-- Clase personalizada para el ancho -->
                        <div class="modal-content"> <!-- Sin centrado total del texto -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel-{{ $elemento->id }}">Detalles del Elemento
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Vista de detalles -->
                                <div id="details-view-{{ $elemento->id }}" class="details-view">
                                    @if ($elemento->foto)
                                        <img src="{{ asset('storage/' . $elemento->foto) }}" alt="Foto del elemento"
                                            class="img-fluid mb-3">
                                    @endif
                                    <!-- Texto alineado a la izquierda -->
                                    <p><strong>Categoría:</strong> {{ $elemento->categoria->nombre }}</p>
                                    <p><strong>Descripción:</strong> {{ $elemento->descripcion }}</p>
                                    <p><strong>Marca:</strong> {{ $elemento->marca }}</p>
                                    <p><strong>Modelo:</strong> {{ $elemento->modelo }}</p>
                                    <p><strong>Serial:</strong> {{ $elemento->serie }}</p>
                                    <p><strong>Especificaciones Técnicas:</strong>
                                        {{ $elemento->especificaciones_tecnicas }}</p>
                                </div>

                                <!-- Vista de edición (oculta por defecto) -->
                                <div id="edit-view-{{ $elemento->id }}" class="edit-view d-none">
                                    <form action="{{ route('elementos.update', $elemento->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="categoria_id-{{ $elemento->id }}"
                                                class="form-label">Categoría</label>
                                            <select id="categoria_id-{{ $elemento->id }}" name="categoria_id"
                                                class="form-select" required>
                                                @foreach ($categorias as $categoria)
                                                    <option value="{{ $categoria->id }}"
                                                        {{ $categoria->id == $elemento->categoria_id ? 'selected' : '' }}>
                                                        {{ $categoria->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="descripcion-{{ $elemento->id }}"
                                                class="form-label">Descripción</label>
                                            <input type="text" id="descripcion-{{ $elemento->id }}"
                                                name="descripcion" class="form-control"
                                                value="{{ $elemento->descripcion }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="marca-{{ $elemento->id }}" class="form-label">Marca</label>
                                            <input type="text" id="marca-{{ $elemento->id }}" name="marca"
                                                class="form-control" value="{{ $elemento->marca }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="modelo-{{ $elemento->id }}" class="form-label">Modelo</label>
                                            <input type="text" id="modelo-{{ $elemento->id }}" name="modelo"
                                                class="form-control" value="{{ $elemento->modelo }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="serie-{{ $elemento->id }}" class="form-label">Número de
                                                Serie</label>
                                            <input type="text" id="serie-{{ $elemento->id }}" name="serie"
                                                class="form-control" value="{{ $elemento->serie }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="especificaciones_tecnicas-{{ $elemento->id }}"
                                                class="form-label">Especificaciones Técnicas</label>
                                            <textarea id="especificaciones_tecnicas-{{ $elemento->id }}" name="especificaciones_tecnicas" class="form-control"
                                                rows="3" required>{{ $elemento->especificaciones_tecnicas }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="foto-{{ $elemento->id }}" class="form-label">Foto</label>
                                            <div class="mb-3">
                                                <label for="foto-{{ $elemento->id }}" class="form-label">Foto del
                                                    Elemento</label>
                                                <input type="file" id="foto-{{ $elemento->id }}" name="foto"
                                                    class="form-control" accept="image/*"
                                                    onchange="previewImage(event, 'previewImagen-{{ $elemento->id }}')">
                                                <img id="previewImagen-{{ $elemento->id }}"
                                                    src="{{ asset('storage/' . $elemento->foto) }}"
                                                    alt="Foto del elemento" class="img-fluid mt-3">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Botón para eliminar elemento -->
                                <form action="{{ route('elementos.destroy', $elemento->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                                <!-- Botón para guardar cambios (oculto por defecto) -->
                                <button type="button" class="btn btn-primary d-none"
                                    id="save-changes-btn-{{ $elemento->id }}"
                                    onclick="saveChanges({{ $elemento->id }})">Guardar Cambios</button>
                                <!-- Botón para editar elementos -->
                                <button type="button" class="btn btn-warning"
                                    onclick="editElement({{ $elemento->id }})">Editar</button>
                                <!-- Botón para cerrar el modal -->
                                <button type="button" class="btn btn-secondary"
                                    onclick="closeModal({{ $elemento->id }})">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para registrar elementos -->
    <div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-custom-width"> <!-- Clase personalizada para el ancho -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registroModalLabel">Registrar Elementos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('elementos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">Categoría</label>
                            <select id="categoria_id" name="categoria_id" class="form-select" required>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" id="descripcion" name="descripcion" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" id="marca" name="marca" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" id="modelo" name="modelo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="serie" class="form-label">Número de Serie</label>
                            <input type="text" id="serie" name="serie" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="especificaciones_tecnicas" class="form-label">Especificaciones
                                Técnicas</label>
                            <textarea id="especificaciones_tecnicas" name="especificaciones_tecnicas" class="form-control" rows="3"
                                required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="fotoElemento" class="form-label">Foto</label>
                            <input type="file" id="fotoElemento" name="foto" class="form-control"
                                accept="image/*" onchange="previewImage(event, 'previewElemento')">
                        </div>
                        <div class="mb-3">
                            <img id="previewElemento" src="#" alt="Previsualización de la imagen"
                                style="display: none; max-width: 100%; height: auto;">
                        </div>

                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- modal para ver carnet digital --}}
    <div class="modal fade" id="carnetDigital" tabindex="-1" aria-labelledby="registroModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-custom-width">
            <!-- Clase personalizada para altura fija -->
            <div class="modal-content modal-custom-fixed">
                <div class="modal-header">
                    <h5 class="modal-title" id="registroModalLabel">Carnet Digital</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="logoSena">
                        <img src="{{ asset('imagenes/logo-del-sena-01.png') }}" alt="Logo Sena">
                    </div>
                    <div class="image-container" style="margin-top: 15px;">
                        @if (isset($usuario) && $usuario->foto && file_exists(storage_path('app/public/fotos_perfil/' . $usuario->foto)))
                            <img id="left-panel-img" src="{{ asset('storage/fotos_perfil/' . $usuario->foto) }}"
                                alt="Foto de perfil" class="foto-perfil">
                        @else
                            <img src="{{ asset('imagenes/sin_foto_perfil.webp') }}"
                                alt="Foto de perfil predeterminada" class="foto-perfil"
                                style="width: 170px; height: 170px; object-fit: cover; border-radius: 8px;">
                        @endif
                    </div>
                    <div class="rol">
                        <h4>
                            <strong>{{ $usuario->role->nombre }}</strong>
                        </h4>
                    </div>
                    <hr class="linea-separadora-carnet">
                    <div class="nombre-carnet">
                        @if (isset($usuario))
                            <h5><strong>{{ $usuario->nombres }} <br>{{ $usuario->apellidos }}</strong></h5>
                        @endif
                    </div><br>
                    <div class="dni-carnet">
                        <p><strong>{{ $usuario->tipo_documento }} {{ $usuario->numero_documento }} <br>
                                RH:{{ $usuario->rh }}</strong></p>
                    </div>
                    <div class="qr">
                        <div id="qr-code">
                        </div>
                    </div>
                    <hr class="separadora-carnet">
                    <p class="regional">
                        Regional Casanare
                    </p>
                    <p class="cafec">
                        Centro Agroindustrial y Fortalecimiento del Casanare
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para editar perfil -->
    <div class="modal fade" id="editarPerfilModal" tabindex="-1" aria-labelledby="editarPerfilModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-custom-width"> <!-- Mismo ancho personalizado que el modal de registro -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarPerfilModalLabel">Editar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editarPerfilForm" method="POST" action="{{ route('updateProfile') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nombres" class="form-label">Nombres:</label>
                            <input type="text" id="nombres" name="nombres" class="form-control"
                                value="{{ Auth::user()->nombres }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellidos" class="form-label">Apellidos:</label>
                            <input type="text" id="apellidos" name="apellidos" class="form-control"
                                value="{{ Auth::user()->apellidos }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_documento" class="form-label">Tipo de Documento:</label>
                            <select id="tipo_documento" name="tipo_documento" class="form-select" required>
                                <option value="CC" {{ Auth::user()->tipo_documento == 'CC' ? 'selected' : '' }}>
                                    Cédula de Ciudadanía</option>
                                <option value="TI" {{ Auth::user()->tipo_documento == 'TI' ? 'selected' : '' }}>
                                    Tarjeta de Identidad</option>
                                <option value="CE" {{ Auth::user()->tipo_documento == 'CE' ? 'selected' : '' }}>
                                    Cédula de Extranjería</option>
                                <option value="PP" {{ Auth::user()->tipo_documento == 'PP' ? 'selected' : '' }}>
                                    Pasaporte</option>
                                <option value="RC" {{ Auth::user()->tipo_documento == 'RC' ? 'selected' : '' }}>
                                    Registro Civil</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="numero_documento" class="form-label">Número de Documento:</label>
                            <input type="text" id="numero_documento" name="numero_documento" class="form-control"
                                value="{{ Auth::user()->numero_documento }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="rh" class="form-label">Tipo de Sangre (RH):</label>
                            <input type="text" id="rh" name="rh" class="form-control"
                                value="{{ Auth::user()->rh }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="tel" id="telefono" name="telefono" class="form-control"
                                value="{{ Auth::user()->telefono }}" required>
                        </div>

                        @if (Auth::check() && Auth::user()->roles_id == 3)
                            <div class="mb-3" id="numeroFichaFieldModal">
                                <label for="numero_ficha" class="form-label">Número de Ficha:</label>
                                <input type="text" id="numero_ficha_modal" name="numero_ficha"
                                    class="form-control" value="{{ Auth::user()->numero_ficha }}">
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto de Perfil:</label>
                            <!-- Contenedor para la previsualización de la foto -->
                            <div class="mb-3">
                                @if (Auth::user()->foto && file_exists(storage_path('app/public/fotos_perfil/' . Auth::user()->foto)))
                                    <img id="previewPerfil"
                                        src="{{ asset('storage/fotos_perfil/' . Auth::user()->foto) }}"
                                        alt="Foto de perfil actual" style="max-width: 100px; height: auto;">
                                @else
                                    <img id="previewPerfil" src="{{ asset('imagenes/sin_foto_perfil.webp') }}"
                                        alt="Foto de perfil predeterminada" style="max-width: 100px; height: auto;">
                                @endif
                            </div>
                            <input type="file" id="foto" name="foto" class="form-control"
                                accept="image/*" onchange="previewImage(event, 'previewPerfil')">
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar contraseña -->
    <div class="modal fade" id="editarContraseñaModal" tabindex="-1" aria-labelledby="editarContraseñaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-custom-width">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarContraseñaModalLabel">Editar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editarContraseñaForm" method="POST" action="{{ route('updatePassword') }}">
                        @csrf
                        <div class="mb-3 position-relative">
                            <label for="current_password" class="form-label">Contraseña Actual:</label>
                            <input type="password" id="current_password" name="current_password"
                                class="form-control" required>
                            <span toggle="#current_password"
                                class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        </div>
                        <div class="mb-3 position-relative">
                            <label for="new_password" class="form-label">Nueva Contraseña:</label>
                            <input type="password" id="new_password" name="new_password" class="form-control"
                                required>
                            <span toggle="#new_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            <ul id="password-requirements" class="mt-2">
                                <li id="length" class="invalid text-danger">Mínimo 6 caracteres</li>
                                <li id="uppercase" class="invalid text-danger">Al menos una mayúscula</li>
                                <li id="number" class="invalid text-danger">Al menos un número</li>
                                <li id="symbol" class="invalid text-danger">Al menos un símbolo (!@#$%^&*)</li>
                            </ul>
                        </div>
                        <div class="mb-3 position-relative">
                            <label for="new_password_confirmation" class="form-label">Confirmar Nueva
                                Contraseña:</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                class="form-control" required>
                            <span toggle="#new_password_confirmation"
                                class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

<!-- 1. Cargar jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- 2. Luego cargar Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- 3. Cargar tus scripts personalizados -->
<script src="{{ asset('js/vista_usuario.js') }}"></script>


<!-- Script personalizado -->
<script>
    $(document).ready(function() {
        console.log("jQuery cargado");
    });
</script>
<script src="{{ asset('js/vista_usuario.js') }}"></script>
<!-- Librería QRCode -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
<script>
    // Datos en formato de texto legible
    const qrData = `
        Nombre: {{ $usuario->nombres }} {{ $usuario->apellidos }}
        Documento: {{ $usuario->tipo_documento }} {{ $usuario->numero_documento }}
        RH: {{ $usuario->rh }}
        Rol: {{ $usuario->role->nombre }}
    `.trim();

    // Generar el QR y agregarlo al contenedor
    const qr = qrcode(0, 'L'); // Crear instancia del generador
    qr.addData(qrData); // Agregar texto al QR
    qr.make(); // Generar QR

    // Insertar el QR como HTML en el contenedor
    document.getElementById('qr-code').innerHTML = qr.createImgTag(6, 6); // Escala ajustable
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const newPasswordInput = document.getElementById('new_password');
        const requirements = {
            length: document.getElementById('length'),
            uppercase: document.getElementById('uppercase'),
            number: document.getElementById('number'),
            symbol: document.getElementById('symbol')
        };

        newPasswordInput.addEventListener('input', () => {
            const value = newPasswordInput.value;
            requirements.length.classList.toggle('invalid', value.length < 6);
            requirements.length.classList.toggle('text-danger', value.length < 6);
            requirements.length.classList.toggle('text-success', value.length >= 6);

            requirements.uppercase.classList.toggle('invalid', !/[A-Z]/.test(value));
            requirements.uppercase.classList.toggle('text-danger', !/[A-Z]/.test(value));
            requirements.uppercase.classList.toggle('text-success', /[A-Z]/.test(value));

            requirements.number.classList.toggle('invalid', !/\d/.test(value));
            requirements.number.classList.toggle('text-danger', !/\d/.test(value));
            requirements.number.classList.toggle('text-success', /\d/.test(value));

            requirements.symbol.classList.toggle('invalid', !/[!@#$%^&*]/.test(value));
            requirements.symbol.classList.toggle('text-danger', !/[!@#$%^&*]/.test(value));
            requirements.symbol.classList.toggle('text-success', /[!@#$%^&*]/.test(value));
        });

        document.querySelectorAll('.toggle-password').forEach(item => {
            item.addEventListener('click', function() {
                const input = document.querySelector(this.getAttribute('toggle'));
                if (input.getAttribute('type') === 'password') {
                    input.setAttribute('type', 'text');
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                } else {
                    input.setAttribute('type', 'password');
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                }
            });
        });

        const toggleDarkModeButton = document.getElementById('toggle-dark-mode');
        toggleDarkModeButton.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');

            // Cambiar texto del botón
            if (document.body.classList.contains('dark-mode')) {
                toggleDarkModeButton.textContent = 'Modo Día';
            } else {
                toggleDarkModeButton.textContent = 'Modo Noche';
            }
        });

        // Reducir el tamaño del nombre en el carnet digital si es muy largo
        const nombreCarnet = document.querySelector('.nombre-carnet h5');
        if (nombreCarnet && nombreCarnet.textContent.length > 20) {
            nombreCarnet.classList.add('long-name');
        }
    });
</script>



</html>
