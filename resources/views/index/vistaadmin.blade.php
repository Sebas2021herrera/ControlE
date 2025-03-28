<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administrador</title>
    <link rel="stylesheet" href="{{ asset('css/styles_vista_admin.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <!-- Enlaza el archivo CSS de Bulma -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="nav-left-group">
                <div class="logo-header">
                    <img src="{{ asset('imagenes/Logo-Control-E.png') }}" alt="Control E Logo" class="logo-header-img">
                    <span class="header-title">Control E Administrador</span>
                </div>
            </div>

            <nav class="nav">
                <div class="dropdown is-hoverable">
                    <div class="dropdown-trigger">
                        <a href="#" class="nav-options">
                            <i class="fas fa-cog"></i> Opciones
                            <span class="icon is-small">
                                <i class="fas fa-angle-down" aria-hidden="true"></i>
                            </span>
                        </a>
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu4" role="menu">
                        <div class="dropdown-content">
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                                    <i class="fas fa-sign-out-alt"></i>Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>


<div class="admin-dashboard">
    {{-- <div class="sidebar" id="sidebar">
        <h2>Bienvenido(a), Admin</h2>
        <p>{{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}</p>
        <div class="image-container" style="margin: 20px auto;">
            @if (Auth::user()->foto && file_exists(storage_path('app/public/fotos_perfil/' . Auth::user()->foto)))
                <img src="{{ asset('storage/fotos_perfil/' . Auth::user()->foto) }}" 
                     alt="Foto de perfil" 
                     class="foto-perfil">
            @else
                <img src="{{ asset('imagenes/sin_foto_perfil.webp') }}" 
                     alt="Foto de perfil predeterminada" 
                     class="foto-perfil">
            @endif
        </div>
    </div> --}}

    <div class="main-content">
        <!-- Nueva sección de cards al estilo Google Admin -->
        <div class="admin-cards-container">
            <!-- Card para Usuarios -->
            <div class="admin-card" id="usuarios-card">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Usuarios</h3>
                <p>Registra y administra usuarios del sistema</p>
                <div class="card-actions">
                    <button class="action-btn" onclick="document.getElementById('registerModal').style.display='block'">
                        <i class="fas fa-user-plus"></i> Registrar
                    </button>
                    <button class="action-btn" onclick="document.getElementById('consultUsersModal').style.display='block'">
                        <i class="fas fa-search"></i> Consultar
                    </button>
                </div>
            </div>

            <!-- Card para Elementos -->
            <div class="admin-card" id="elementos-card">
                <div class="card-icon">
                    <i class="fas fa-box"></i>
                </div>
                <h3>Elementos</h3>
                <p>Registra elementos a usuarios registrados en el sistema</p>
                <div class="card-actions">
                    <button class="action-btn" onclick="document.getElementById('elementsModal').style.display='block'">
                        <i class="fas fa-plus-circle"></i> Registrar
                    </button>
                </div>
            </div>

            <!-- Card para Informes -->
            <div class="admin-card" id="informes-card">
                <div class="card-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Informes</h3>
                <p>Accede a reportes de ingresos y elementos</p>
                <div class="card-actions">
                    <a href="{{ route('admin.reportes.ingresos') }}" class="action-btn">
                        <i class="fas fa-users"></i> Ingresos
                    </a>
                    <a href="{{ route('admin.reportes.elementos') }}" class="action-btn">
                        <i class="fas fa-cubes"></i> Elementos
                    </a>
                </div>
            </div>
        </div>

        <!-- Aquí va la sección para consultar un usuario -->
        <div class="resultado-busqueda" id="resultadoBusqueda" style="display: none;">
            <div class="contenido-superior">
                <div class="contenedor-intermedio">
                    <div class="usuario-info">
                        <!-- Logo del SENA y descripción -->
                        <div class="foto-logo">
                            <img src="{{ asset('imagenes/logo-del-sena-01.png') }}" alt="Logo del SENA" class="logo-sena">
                            <p class="verde" id="semifooter">
                                <i class="fas fa-map-marker-alt"></i> Regional Casanare | Centro Agroindustrial y Fortalecimiento Empresarial del Casanare
                            </p>
                        </div>
                
                        <!-- Información del Usuario -->
                        <div class="info-text">
                            <p class="verde usuario-nombre"><i class="fas fa-user-circle"></i> Nombre del Usuario</p>
                            <p class="verde usuario-apellidos"><i class="fas fa-user-circle"></i> Apellido del Usuario</p>
                            <p><i class="fas fa-id-card"></i> <strong>Doc:</strong> <span class="usuario-documento">123456789</span></p>
                            <p><i class="fas fa-phone"></i> <strong>Cel:</strong> <span class="usuario-telefono">3101234567</span></p>
                            <p><i class="fas fa-heart"></i> <strong>RH:</strong> <span class="usuario-rh">O+</span></p>
                            <p><i class="fas fa-user-tag"></i> <strong>Rol:</strong> <span class="usuario-rol">Aprendiz</span></p>
                            <p><i class="fas fa-clipboard"></i> <strong>Ficha:</strong> <span class="usuario-ficha">24680</span></p>
                        </div>
                
                        <!-- Foto del Usuario -->
                        <div class="foto-usuario">
                            <img src="{{ asset('imagenes/sin_foto_perfil.jpg') }}" class="foto-perfil-usuario">
                        </div>
                    </div>
                </div>
        
                <div class="contenido-elementos">
                    <div class="card-container">
                        <!-- Aquí se cargarán dinámicamente las cards de elementos -->
                    </div>
                </div>
            </div>
            <!-- Botones para limpiar la consulta, editar y eliminar usuario -->
            <div class="field" style="margin-top: 10px; display: flex; gap: 10px; justify-content: flex-start;">
                <button id="limpiarConsultaBtn" class="button is-danger">
                    <i class="fas fa-redo"></i> Limpiar Consulta
                </button>
                <button id="editarUsuarioBtn" class="button is-warning">
                    <i class="fas fa-edit"></i> Editar Usuario
                </button>
                <button id="eliminarUsuarioBtn" class="button is-danger">
                    <i class="fas fa-trash"></i> Eliminar Usuario
                </button>
            </div>
        </div>
        {{-- Acá termina la secicón de consultar usuarios --}}
    </div>
</div>

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


    <!-- Modal de Registro de Usuarios -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <div class="ventana-formulario">
                
                <figure class="image is-flex is-justify-content-center mb-4">
                    <img src="{{ asset('imagenes/logo-del-sena-01.png') }}" alt="Logo SENA" style="max-width: 200px;">
                </figure>

                <h2>Registro de usuarios</h2>
                <form id="registroForm" method="POST" action="{{ route('admin.usuarios.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="field">
                        <label class="label" for="nombres">Nombres:</label>
                        <div class="control">
                            <input class="input @error('nombres') is-danger @enderror" 
                                type="text" 
                                id="nombres"
                                name="nombres" 
                                value="{{ old('nombres') }}" 
                                required 
                                placeholder="Ingresa los nombres del nuevo usuario">
                        </div>
                        @error('nombres')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div class="field">
                        <label class="label" for="apellidos">Apellidos:</label>
                        <div class="control">
                            <input class="input @error('apellidos') is-danger @enderror" 
                                type="text" 
                                id="apellidos"
                                name="apellidos" 
                                value="{{ old('apellidos') }}" 
                                required 
                                placeholder="Ingresa los apellidos del nuevo usuario">
                        </div>
                        @error('apellidos')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
            
                        <div class="field">
                            <label class="label" for="tipo_documento">Tipo de Documento:</label>
                            <div class="control">
                                <div class="select @error('tipo_documento') is-danger @enderror">
                                    <select id="tipo_documento" name="tipo_documento" required>
                                        <option value="" disabled selected>Seleccione un tipo de documento</option>
                                        <option value="CC">Cédula de Ciudadanía</option>
                                        <option value="TI">Tarjeta de Identidad</option>
                                        <option value="CE">Cédula de Extranjería</option>
                                        <option value="PP">Pasaporte</option>
                                        <option value="RC">Registro Civil</option>
                                    </select>
                                </div>
                            </div>
                            @error('tipo_documento')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- tipo de sangre -->
                    <div class="field">
                        <label class="label" for="rh">Tipo de Sangre (RH):</label>
                        <div class="control">
                            <div class="select @error('rh') is-danger @enderror">
                                <select id="rh" name="rh" required>
                                    <option value="" disabled selected>Seleccione su tipo de sangre</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                </select>
                            </div>
                        </div>
                        @error('rh')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="label" for="numero_documento">Número de Documento:</label>
                        <div class="control">
                            <input class="input @error('numero_documento') is-danger @enderror" type="text"
                                id="numero_documento" name="numero_documento" value="{{ old('numero_documento') }}"
                                required maxlength="11" required placeholder="Ingresa el número de documento"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11)">
                        </div>
                        @error('numero_documento')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
    
                    <div class="field">
                        <label class="label" for="correo_personal">Correo Personal:</label>
                        <div class="control">
                            <input class="input @error('correo_personal') is-danger @enderror" type="email"
                                id="correo_personal" name="correo_personal" value="{{ old('correo_personal') }}"
                                required placeholder="Ingresar correo personal del nuevo usuario. Ejemplo: juan@gmail.com">
                        </div>
                        @error('correo_personal')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div class="field">
                        <label class="label" for="correo_institucional">Correo Institucional:</label>
                        <div class="control">
                            <input class="input @error('correo_institucional') is-danger @enderror" type="email"
                                id="correo_institucional" name="correo_institucional"
                                value="{{ old('correo_institucional') }}" required placeholder="Roles 1, 2 y 4: juan@sena.edu.co. Rol 3 Aprendiz: paul@soy.sena.edu.co">
                        </div>
                        @error('correo_institucional')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div class="field">
                        <label class="label" for="contraseña">Nueva Contraseña:</label>
                        <div class="control has-icons-right">
                            <input class="input @error('contraseña') is-danger @enderror" 
                                type="password"
                                id="contraseña" 
                                name="contraseña" 
                                required
                                placeholder="Mínimo 6 caracteres">
                                <span class="icon is-small is-right" style="pointer-events: all; cursor: pointer;"
                                    onclick="togglePassword('contraseña')">
                                    <i class="fas fa-eye" id="contraseña-icon"></i>
                                </span>
                        </div>
                        <p id="contraseaError" class="help is-danger"></p>
                        @error('contraseña')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div class="field">
                        <label class="label" for="contraseña_confirmation">Confirmar Contraseña:</label>
                        <div class="control has-icons-right">
                            <input class="input @error('contraseña_confirmation') is-danger @enderror" 
                                type="password"
                                id="contraseña_confirmation" 
                                name="contraseña_confirmation" 
                                required
                                placeholder="Confirmar contraseña">
                            <span class="icon is-small is-right" style="pointer-events: all; cursor: pointer;">
                                <i class="fas fa-eye" id="contraseña_confirmation-icon"></i>
                            </span>
                        </div>
                        @error('contraseña_confirmation')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div class="field">
                        <label class="label" for="telefono">Teléfono:</label>
                        <div class="control">
                            <input class="input @error('telefono') is-danger @enderror" type="number" id="telefono"
                                name="telefono" value="{{ old('telefono') }}" required maxlength="10"
                                placeholder="Ingresa teléfono del nuevo usuario"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10)">
                        </div>
                        <small class="text-muted">Máximo 10 dígitos</small>
                        @error('telefono')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div class="field">
                        <label class="label" for="rol">Rol:</label>
                        <div class="control">
                            <div class="select @error('rol') is-danger @enderror">
                                <select id="rol" name="rol" required>
                                    <option value="1" {{ old('rol') == 1 ? 'selected' : '' }}>Administrador</option>
                                    <option value="2" {{ old('rol') == 2 ? 'selected' : '' }}>Control</option>
                                    <option value="3" {{ old('rol') == 3 ? 'selected' : '' }}>Aprendiz</option>
                                    <option value="4" {{ old('rol') == 4 ? 'selected' : '' }}>Visitante</option>
                                    <option value="5" {{ old('rol') == 5 ? 'selected' : '' }}>Funcionario</option>
                                </select>
                            </div>
                        </div>
                        @error('rol')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div class="field" id="numeroFichaField">
                        <label class="label" for="numero_ficha">Número de Ficha:</label>
                        <div class="control">
                            <input class="input @error('numero_ficha') is-danger @enderror" type="number"
                                id="numero_ficha" name="numero_ficha" value="{{ old('numero_ficha') }}"
                                placeholder="Ingresa número de ficha">
                        </div>
                        @error('numero_ficha')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="label" for="foto">Foto (máximo 6MB):</label>
                        <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                        <small class="text-muted">Formatos permitidos: JPG, JPEG, PNG, GIF. Tamaño máximo: 5MB</small>
                        <div class="mt-2">
                            <img id="previewUsuario" src="#" alt="Vista previa de la foto"
                                style="display: none; max-width: 200px; height: auto;">
                        </div>
                    </div>
                        
            
                    <div class="field">
                        <div class="control">
                            <button class="button is-success" type="submit">Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Registro de Elementos -->
    <div id="elementsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <div class="ventana-formulario">

                <figure class="image is-flex is-justify-content-center mb-4">
                    <img src="{{ asset('imagenes/logo-del-sena-01.png') }}" alt="Logo SENA" style="max-width: 200px;">
                </figure>

                <h2>Registro de elementos</h2>
                    <!-- Aquí puedes agregar los campos para registrar elementos -->
                    <form action="{{ route('admin.elementos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf             
                        <div class="mb-3">
                            <!-- Campo de Número Documento Usuario -->
                            <label for="documento" class="form-label">Número documento</label>
                            <input type="text" id="documento" name="documento"  required placeholder="Ingresa el número de documento del usuario registrado" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11)" class="form-control" required>
                            <!-- Campo para seleccionar categoría del elemento -->
                            <label for="categoria_id" class="form-label">Categoría</label>
                            <select id="categoria_id" name="categoria_id" class="form-select" required>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" id="descripcion" name="descripcion"  placeholder="Haz una descripción del elemento a registrar" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" id="marca" name="marca" required placeholder="Ingresa la marca del elemento" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" id="modelo" name="modelo" required placeholder="Ingresa el modelo del elemento" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="serie" class="form-label">Número de Serie</label>
                            <input type="text" id="serie" name="serie" required placeholder="Ingresa el número de serie del elemento" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="especificaciones_tecnicas" class="form-label">Especificaciones
                                Técnicas</label>
                            <textarea id="especificaciones_tecnicas" name="especificaciones_tecnicas" placeholder="Escribe las especificaciones técnicas del nuevo elemento" class="form-control" rows="2"
                                required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="fotoElemento" class="form-label">Foto</label>
                            <input type="file" id="fotoElemento" name="foto" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <img id="previewElemento" src="#" alt="Previsualización de la imagen"
                                 style="display: none; max-width: 100%; height: auto;">
                        </div>
                        
                        <div class="field">
                            <div class="control">
                                <button class="button is-success" type="submit">Registrar</button>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>


<!-- Modal de Consulta de Usuarios -->
<div id="consultUsersModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <div class="ventana-formulario">
            <h2>Consultar usuarios</h2>
            <form action="{{ route('admin.usuarios.consultar') }}" method="GET" class="search-form">
                <div class="search-wrapper">
                    <input type="text" 
                           name="documento" 
                           class="search-input" 
                           placeholder="Buscar por Documento..."
                           required>
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            <div class="field" style="margin-top: 10px;">
                <a href="{{ route('admin.usuarios.consultaMasiva') }}" class="button is-info">
                    <i class="fas fa-users "></i> Consulta general
                </a>
            </div>
        </div>
    </div>
</div>
  
@foreach ($elementos as $elemento)
<!-- Modal para ver más detalles y editar en vista admin -->
<div class="modal fade" id="modal-detalles-{{ $elemento->id }}" tabindex="-1" aria-labelledby="modalDetallesLabel-{{ $elemento->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetallesLabel-{{ $elemento->id }}">Detalles del Elemento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Vista de detalles -->
                <div id="details-view-admin-{{ $elemento->id }}" class="details-view">
                    @if ($elemento->foto)
                        <img src="{{ asset('storage/' . $elemento->foto) }}" alt="Foto del elemento" class="img-fluid mb-3">
                    @endif
                    <p><strong>Categoría:</strong> {{ $elemento->categoria->nombre }}</p>
                    <p><strong>Descripción:</strong> {{ $elemento->descripcion }}</p>
                    <p><strong>Marca:</strong> {{ $elemento->marca }}</p>
                    <p><strong>Modelo:</strong> {{ $elemento->modelo }}</p>
                    <p><strong>Serial:</strong> {{ $elemento->serie }}</p>
                    <p><strong>Especificaciones Técnicas:</strong> {{ $elemento->especificaciones_tecnicas }}</p>
                </div>

                <!-- Vista de edición (oculta por defecto) -->
                <div id="edit-view-admin-{{ $elemento->id }}" class="edit-view d-none">
                    <form action="{{ route('admin.elementos.update', $elemento->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="categoria_id-{{ $elemento->id }}" class="form-label">Categoría</label>
                            <select id="categoria_id-{{ $elemento->id }}" name="categoria_id" class="form-select" required>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ $categoria->id == $elemento->categoria_id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion-{{ $elemento->id }}" class="form-label">Descripción</label>
                            <input type="text" id="descripcion-{{ $elemento->id }}" name="descripcion" class="form-control" value="{{ $elemento->descripcion }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="marca-{{ $elemento->id }}" class="form-label">Marca</label>
                            <input type="text" id="marca-{{ $elemento->id }}" name="marca" class="form-control" value="{{ $elemento->marca }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="modelo-{{ $elemento->id }}" class="form-label">Modelo</label>
                            <input type="text" id="modelo-{{ $elemento->id }}" name="modelo" class="form-control" value="{{ $elemento->modelo }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="serie-{{ $elemento->id }}" class="form-label">Número de Serie</label>
                            <input type="text" id="serie-{{ $elemento->id }}" name="serie" class="form-control" value="{{ $elemento->serie }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="especificaciones_tecnicas-{{ $elemento->id }}" class="form-label">Especificaciones Técnicas</label>
                            <textarea id="especificaciones_tecnicas-{{ $elemento->id }}" name="especificaciones_tecnicas" class="form-control" rows="3" required>{{ $elemento->especificaciones_tecnicas }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="foto-{{ $elemento->id }}" class="form-label">Foto del Elemento</label>
                            <input type="file" id="foto-{{ $elemento->id }}" name="foto" class="form-control" accept="image/*" onchange="previewImage(event, 'preview-imagen-{{ $elemento->id }}')">
                            <div class="mt-2">
                                <img id="preview-imagen-{{ $elemento->id }}" 
                                     src="{{ asset('storage/' . $elemento->foto) }}" 
                                     alt="Foto del elemento" 
                                     class="img-fluid mt-2" 
                                     style="max-height: 200px"
                                     onerror="this.src='/imagenes/sin_foto_elemento.webp'">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Botón para eliminar elemento -->
                <form action="{{ route('admin.elementos.destroy', $elemento->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
                <!-- Botón para guardar cambios (oculto por defecto) -->
                <button type="button" class="btn btn-primary d-none" id="save-changes-btn-admin-{{ $elemento->id }}" onclick="saveChangesAdmin({{ $elemento->id }})">Guardar Cambios</button>
                <!-- Botón para editar elementos -->
                <button type="button" class="btn btn-warning" onclick="editElementAdmin({{ $elemento->id }})">
                    Editar
                </button>
                <!-- Botón para cerrar el modal -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal de Reportes de Ingresos -->
<div id="reportIngresosModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <div class="ventana-formulario">
            <h2>Reportes de Ingresos de Usuarios</h2>
            <form action="{{ route('admin.usuarios.pdf') }}" method="POST" class="search-form">
                @csrf
                <div class="search-wrapper">
                    <input type="text" 
                           name="documento" 
                           class="search-input" 
                           placeholder="Ingresa Documento Usuario..." 
                           required>
                    <button type="submit" class="search-button-reports">
                        <i class="fas fa-file-pdf"></i> Generar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal para editar perfil del admin -->
<div class="modal fade" id="editarPerfilAdminModal" tabindex="-1" aria-labelledby="editarPerfilAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-custom-width">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarPerfilAdminModalLabel">Editar Perfil Administrador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarPerfilAdminForm" method="POST" action="{{ route('admin.updateProfile') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="nombres" class="form-label">Nombres:</label>
                        <input type="text" id="nombres" name="nombres" class="form-control" value="{{ Auth::user()->nombres }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos:</label>
                        <input type="text" id="apellidos" name="apellidos" class="form-control" value="{{ Auth::user()->apellidos }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_documento" class="form-label">Tipo de Documento:</label>
                        <select id="tipo_documento" name="tipo_documento" class="form-select" required>
                            <option value="CC" {{ Auth::user()->tipo_documento == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                            <option value="TI" {{ Auth::user()->tipo_documento == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                            <option value="CE" {{ Auth::user()->tipo_documento == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                            <option value="PP" {{ Auth::user()->tipo_documento == 'PP' ? 'selected' : '' }}>Pasaporte</option>
                            <option value="RC" {{ Auth::user()->tipo_documento == 'RC' ? 'selected' : '' }}>Registro Civil</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="numero_documento" class="form-label">Número de Documento:</label>
                        <input type="text" id="numero_documento" name="numero_documento" class="form-control" value="{{ Auth::user()->numero_documento }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="rh" class="form-label">Tipo de Sangre (RH):</label>
                        <input type="text" id="rh" name="rh" class="form-control" value="{{ Auth::user()->rh }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono:</label>
                        <input type="tel" id="telefono" name="telefono" class="form-control" value="{{ Auth::user()->telefono }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto de Perfil:</label>
                        <div class="mb-3">
                            @if (Auth::user()->foto && file_exists(storage_path('app/public/fotos_perfil/' . Auth::user()->foto)))
                                <img id="previewPerfilAdmin" src="{{ asset('storage/fotos_perfil/' . Auth::user()->foto) }}" 
                                     alt="Foto de perfil actual" style="max-width: 100px; height: auto;">
                            @else
                                <img id="previewPerfilAdmin" src="{{ asset('imagenes/sin_foto_perfil.jpg') }}" 
                                     alt="Foto de perfil predeterminada" style="max-width: 100px; height: auto;">
                            @endif
                        </div>
                        <input type="file" id="fotoPerfilAdmin" name="foto" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Mantener la referencia a Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    window.categorias = {!! json_encode($categorias) !!};

    const AdminForms = {
        init() {
            this.initializeInputValidations();
            this.initializePasswordValidation();
            this.initializeImagePreviews();
            this.initializeModals();
            this.initializeRoleFields();
            this.initializeFormValidation();
            this.initializeConsultaUsuarios();
            this.initializeMessages();
        },

        initializeInputValidations() {
            // Validación para nombres y apellidos (solo letras y espacios)
            const soloLetras = document.querySelectorAll('#nombres, #apellidos');
            const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;

            soloLetras.forEach(input => {
                ['keypress', 'paste'].forEach(event => {
                    input.addEventListener(event, (e) => {
                        if (event === 'paste') {
                            e.preventDefault();
                            const text = (e.clipboardData || window.clipboardData).getData('text');
                            if (regex.test(text)) {
                                input.value = text;
                            }
                        } else {
                            const char = String.fromCharCode(e.keyCode || e.which);
                            if (!regex.test(char)) {
                                e.preventDefault();
                            }
                        }
                    });
                });

                // Limpieza en tiempo real
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
                });
            });

            // Validación para documento y teléfono
            const soloNumeros = {
                'numero_documento': { min: 6, max: 12 },
                'telefono': { min: 7, max: 10 }
            };

            Object.entries(soloNumeros).forEach(([id, limits]) => {
                const input = document.getElementById(id);
                if (input) {
                    input.addEventListener('input', function() {
                        this.value = this.value.replace(/[^0-9]/g, '').substring(0, limits.max);
                    });
                }
            });
        },

        initializePasswordValidation() {
            // Configuración de campos de contraseña
            const passwordFields = [
                { inputId: 'contraseña', errorId: 'contraseaError' },
                { inputId: 'contraseña_confirmation', errorId: 'confirmarContrasenaError' }
            ];

            passwordFields.forEach(field => {
                const input = document.getElementById(field.inputId);
                const error = document.getElementById(field.errorId);

                if (input) {
                    // Validación de contraseña
                    input.addEventListener('input', function() {
                        if (field.inputId === 'contraseña') {
                            const value = this.value;
                            const requirements = {
                                length: { met: value.length >= 6, text: 'Mínimo 6 caracteres' },
                                uppercase: { met: /[A-Z]/.test(value), text: 'Al menos una mayúscula' },
                                lowercase: { met: /[a-z]/.test(value), text: 'Al menos una minúscula' },
                                number: { met: /\d/.test(value), text: 'Al menos un número' },
                                symbol: { met: /[@$!%*?&]/.test(value), text: 'Al menos un símbolo (@$!%*?&)' }
                            };

                            const failedRequirements = Object.values(requirements)
                                .filter(req => !req.met)
                                .map(req => req.text);

                            if (error) {
                                error.textContent = failedRequirements.join(', ');
                                error.style.color = failedRequirements.length ? '#ff3860' : '#48c774';
                            }
                        }
                    });

                    // Configurar el toggle de visibilidad para ambos campos
                    const icon = document.getElementById(`${field.inputId}-icon`);
                    if (icon) {
                        icon.parentElement.addEventListener('click', function() {
                            const type = input.type === 'password' ? 'text' : 'password';
                            input.type = type;
                            icon.classList.toggle('fa-eye', type === 'password');
                            icon.classList.toggle('fa-eye-slash', type === 'text');
                        });
                    }
                }
            });
        },

        initializeImagePreviews() {
            // Configuración de previsualización de imágenes para diferentes formularios
            const imagePreviewConfig = [
                { inputId: 'foto', previewId: 'previewUsuario' },
                { inputId: 'fotoElemento', previewId: 'previewElemento' },
                { inputId: 'fotoPerfilAdmin', previewId: 'previewPerfilAdmin' }
            ];

            imagePreviewConfig.forEach(config => {
                const input = document.getElementById(config.inputId);
                const preview = document.getElementById(config.previewId);

                if (input && preview) {
                    input.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        const maxSize = 5 * 1024 * 1024; // 5MB

                        if (file) {
                            // Validar tamaño
                            if (file.size > maxSize) {
                                alert('El archivo no debe superar los 5MB');
                                this.value = '';
                                preview.style.display = 'none';
                                return;
                            }

                            // Validar tipo
                            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                            if (!validTypes.includes(file.type)) {
                                alert('Por favor, seleccione un archivo JPG, JPEG, PNG o GIF');
                                this.value = '';
                                preview.style.display = 'none';
                                return;
                            }

                            // Mostrar vista previa
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                                preview.style.display = 'block';
                            };
                            reader.readAsDataURL(file);
                        } else {
                            preview.style.display = 'none';
                        }
                    });
                }
            });
        },

        initializeModals() {
            // Configuración de los modales y sus botones
            const modalConfig = {
                'registerUsers': 'registerModal',
                'registerElements': 'elementsModal',
                'consultUsers': 'consultUsersModal'
            };

            // Configurar cada modal
            Object.entries(modalConfig).forEach(([buttonId, modalId]) => {
                const button = document.getElementById(buttonId);
                const modal = document.getElementById(modalId);
                
                if (button && modal) {
                    // Abrir modal
                    button.addEventListener('click', (e) => {
                        e.preventDefault();
                        modal.style.display = 'block';
                    });

                    // Cerrar con X
                    const closeBtn = modal.querySelector('.close-btn');
                    if (closeBtn) {
                        closeBtn.addEventListener('click', () => {
                            modal.style.display = 'none';
                        });
                    }

                    // Cerrar al hacer clic fuera
                    window.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            modal.style.display = 'none';
                        }
                    });
                } else {
                    console.warn(`Modal setup incomplete: Button: ${buttonId}, Modal: ${modalId}`);
                }
            });

            // Manejar el modal de editar perfil (que usa Bootstrap)
            const editarPerfilBtn = document.querySelector('[onclick*="editarPerfilAdminModal"]');
            if (editarPerfilBtn) {
                editarPerfilBtn.onclick = function(e) {
                    e.preventDefault();
                    const modal = document.getElementById('editarPerfilAdminModal');
                    if (modal) {
                        new bootstrap.Modal(modal).show();
                    }
                };
            }
        },

        initializeRoleFields() {
            const rolSelect = document.getElementById('rol');
            const numeroFichaField = document.getElementById('numero_ficha');
            
            if (rolSelect && numeroFichaField) {
                const numeroFichaDiv = numeroFichaField.closest('.field');
                
                const toggleNumeroFicha = () => {
                    const isAprendiz = rolSelect.value == '3';
                    numeroFichaDiv.style.display = isAprendiz ? 'block' : 'none';
                    numeroFichaField.required = isAprendiz;
                };
    
                rolSelect.addEventListener('change', toggleNumeroFicha);
                toggleNumeroFicha(); // Estado inicial
            }
        },

        initializeFormValidation() {
            const form = document.getElementById('registroForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const contraseña = document.getElementById('contraseña');
                    const contraseñaConfirm = document.getElementById('contraseña_confirmation');
                    
                    if (contraseña.value !== contraseñaConfirm.value) {
                        e.preventDefault();
                        document.getElementById('confirmarContrasenaError').textContent = 'Las contraseñas no coinciden';
                        return false;
                    }

                    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/;
                    if (!passwordRegex.test(contraseña.value)) {
                        e.preventDefault();
                        return false;
                    }
                });
            }
        },

        initializeConsultaUsuarios() {
            const searchForm = document.querySelector('#consultUsersModal .search-form');
            const resultadoBusqueda = document.getElementById('resultadoBusqueda');
            
            if (searchForm) {
                searchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const documento = searchForm.querySelector('input[name="documento"]').value;
                    const url = `${searchForm.getAttribute('action')}?documento=${documento}`;

                    // Mostrar que está cargando
                    const searchButton = searchForm.querySelector('.search-button');
                    searchButton.disabled = true;
                    searchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Restaurar el botón
                        searchButton.disabled = false;
                        searchButton.innerHTML = '<i class="fas fa-search"></i>';

                        if (data.success) {
                            // Ocultar el modal de consulta
                            document.getElementById('consultUsersModal').style.display = 'none';
                            
                            const usuarioData = data.usuario;
                            
                            // Función para obtener el nombre completo del tipo de documento
                            const getTipoDocumentoNombre = (tipo) => {
                                const tipos = {
                                    'CC': 'Cédula de Ciudadanía',
                                    'TI': 'Tarjeta de Identidad',
                                    'CE': 'Cédula de Extranjería',
                                    'PP': 'Pasaporte',
                                    'RC': 'Registro Civil'
                                };
                                return tipos[tipo] || tipo;
                            };

                            // Actualizar la información del usuario
                            document.querySelector('.usuario-nombre').textContent = usuarioData.nombres;
                            document.querySelector('.usuario-apellidos').textContent = usuarioData.apellidos;
                            document.querySelector('.usuario-documento').textContent = 
                                `${getTipoDocumentoNombre(usuarioData.tipo_documento)} ${usuarioData.numero_documento}`;
                            document.querySelector('.usuario-telefono').textContent = usuarioData.telefono;
                            document.querySelector('.usuario-rh').textContent = usuarioData.rh;
                            document.querySelector('.usuario-rol').textContent = usuarioData.role ? usuarioData.role.nombre : 'N/A';
                            document.querySelector('.usuario-ficha').textContent = usuarioData.numero_ficha || 'N/A';
                            
                            // Actualizar la foto del usuario
                            const fotoUsuario = document.querySelector('.foto-perfil-usuario');
                            fotoUsuario.src = usuarioData.foto 
                                ? `/storage/fotos_perfil/${usuarioData.foto}` 
                                : '/imagenes/sin_foto_perfil.webp';

                            // Mostrar los elementos si existen
                            const cardContainer = document.querySelector('.card-container');
                            cardContainer.innerHTML = ''; // Limpiar contenedor
                            
                            if (data.elementos && data.elementos.length > 0) {
                                data.elementos.forEach(elemento => {
                                    const card = `
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title"><strong>${elemento.categoria.nombre}</strong></h5>
                                                <img src="${elemento.foto ? '/storage/' + elemento.foto : '/imagenes/sin_foto_elemento.webp'}" 
                                                     alt="${elemento.descripcion}"
                                                     class="img-fluid mt-3">
                                                <p class="card-text"><strong>Serial:</strong> ${elemento.serie || 'N/A'}</p>
                                                <p class="card-text"><strong>Marca:</strong> ${elemento.marca}</p>
                                                <button type="button" class="btn btn-link" 
                                                        onclick='window.mostrarDetallesElemento(${JSON.stringify(elemento).replace(/'/g, "\\'")})'">
                                                    Ver más
                                                </button>
                                            </div>
                                            <div class="spacer"></div>
                                        </div>
                                    `;
                                    cardContainer.innerHTML += card;
                                });
                            }

                            // Mostrar la sección de resultado
                            resultadoBusqueda.style.display = 'block';

                            // Configurar el botón de editar usuario
                            const editarBtn = document.getElementById('editarUsuarioBtn');
                            if (editarBtn) {
                                editarBtn.onclick = () => {
                                    AdminForms.showEditarUsuarioModal(usuarioData);
                                };
                            }

                            // Configurar el botón de limpiar consulta
                            const limpiarBtn = document.getElementById('limpiarConsultaBtn');
                            if (limpiarBtn) {
                                limpiarBtn.onclick = () => {
                                    resultadoBusqueda.style.display = 'none';
                                    searchForm.reset();
                                    document.getElementById('consultUsersModal').style.display = 'block';
                                };
                            }

                            // Configurar el botón de eliminar usuario
                            const eliminarBtn = document.getElementById('eliminarUsuarioBtn');
                            if (eliminarBtn) {
                                eliminarBtn.onclick = () => {
                                    if (confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.')) {
                                        fetch(`/admin/usuarios/${usuarioData.id}`, {
                                            method: 'DELETE',
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                                'Accept': 'application/json',
                                                'Content-Type': 'application/json'
                                            },
                                            credentials: 'same-origin'
                                        })
                                        .then(response => {
                                            if (!response.ok) {
                                                console.error('Status:', response.status);
                                                console.error('StatusText:', response.statusText);
                                                return response.text().then(text => {
                                                    console.error('Response:', text);
                                                    throw new Error(`Error del servidor: ${response.status}`);
                                                });
                                            }
                                            return response.json();
                                        })
                                        .then(data => {
                                            if (data.success) {
                                                alert('Usuario eliminado exitosamente');
                                                resultadoBusqueda.style.display = 'none';
                                                searchForm.reset();
                                                document.getElementById('consultUsersModal').style.display = 'block';
                                            } else {
                                                throw new Error(data.mensaje || 'Error al eliminar el usuario');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error completo:', error);
                                            alert(error.message || 'Error al eliminar el usuario');
                                        });
                                    }
                                };
                            }
                        } else {
                            alert(data.mensaje || 'Usuario no encontrado');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        searchButton.disabled = false;
                        searchButton.innerHTML = '<i class="fas fa-search"></i>';
                        alert('Error al buscar el usuario');
                    });
                }.bind(this));
            }
        },

        showEditarUsuarioModal(usuario) {
            const modalHTML = `
                <div class="modal fade" id="editarUsuarioConsultadoModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-custom-width">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Usuario</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editarUsuarioConsultadoForm" method="POST" action="/admin/usuarios/actualizar/${usuario.id}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="nombres" class="form-label">Nombres:</label>
                                        <input type="text" id="nombres" name="nombres" class="form-control" value="${usuario.nombres}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="apellidos" class="form-label">Apellidos:</label>
                                        <input type="text" id="apellidos" name="apellidos" class="form-control" value="${usuario.apellidos}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tipo_documento" class="form-label">Tipo de Documento:</label>
                                        <select id="tipo_documento" name="tipo_documento" class="form-select" required>
                                            <option value="CC" ${usuario.tipo_documento === 'CC' ? 'selected' : ''}>Cédula de Ciudadanía</option>
                                            <option value="TI" ${usuario.tipo_documento === 'TI' ? 'selected' : ''}>Tarjeta de Identidad</option>
                                            <option value="CE" ${usuario.tipo_documento === 'CE' ? 'selected' : ''}>Cédula de Extranjería</option>
                                            <option value="PP" ${usuario.tipo_documento === 'PP' ? 'selected' : ''}>Pasaporte</option>
                                            <option value="RC" ${usuario.tipo_documento === 'RC' ? 'selected' : ''}>Registro Civil</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="numero_documento" class="form-label">Número de Documento:</label>
                                        <input type="text" id="numero_documento" name="numero_documento" class="form-control" value="${usuario.numero_documento}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="rh" class="form-label">Tipo de Sangre (RH):</label>
                                        <select id="rh" name="rh" class="form-select" required>
                                            <option value="O+" ${usuario.rh === 'O+' ? 'selected' : ''}>O+</option>
                                            <option value="O-" ${usuario.rh === 'O-' ? 'selected' : ''}>O-</option>
                                            <option value="A+" ${usuario.rh === 'A+' ? 'selected' : ''}>A+</option>
                                            <option value="A-" ${usuario.rh === 'A-' ? 'selected' : ''}>A-</option>
                                            <option value="B+" ${usuario.rh === 'B+' ? 'selected' : ''}>B+</option>
                                            <option value="B-" ${usuario.rh === 'B-' ? 'selected' : ''}>B-</option>
                                            <option value="AB+" ${usuario.rh === 'AB+' ? 'selected' : ''}>AB+</option>
                                            <option value="AB-" ${usuario.rh === 'AB-' ? 'selected' : ''}>AB-</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label">Teléfono:</label>
                                        <input type="tel" id="telefono" name="telefono" class="form-control" value="${usuario.telefono}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="foto" class="form-label">Foto de Perfil:</label>
                                        <div class="mb-3">
                                            <img id="previewPerfilUsuarioConsultado" 
                                                 src="${usuario.foto ? '/storage/fotos_perfil/' + usuario.foto : '/imagenes/sin_foto_perfil.jpg'}" 
                                                 alt="Foto de perfil" 
                                                 style="max-width: 100px; height: auto;">
                                        </div>
                                        <input type="file" id="fotoPerfilUsuarioConsultado" name="foto" class="form-control" accept="image/*">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Eliminar cualquier instancia previa del modal
            const modalAnterior = document.getElementById('editarUsuarioConsultadoModal');
            if (modalAnterior) {
                modalAnterior.remove();
            }

            // Agregar el nuevo modal al DOM
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            
            // Mostrar el modal usando Bootstrap
            const modal = new bootstrap.Modal(document.getElementById('editarUsuarioConsultadoModal'));
            modal.show();

            // Configurar la previsualización de la imagen
            const input = document.getElementById('fotoPerfilUsuarioConsultado');
            const preview = document.getElementById('previewPerfilUsuarioConsultado');
            
            if (input && preview) {
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Manejar el envío del formulario
            const form = document.getElementById('editarUsuarioConsultadoForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modal.hide();
                        // Actualizar la vista del usuario
                        location.reload();
                    } else {
                        alert('Error al actualizar el usuario');
                    }
                });
            });
        },

        initializeMessages() {
            // Manejar mensajes de éxito
            const successMessage = document.querySelector('.alert-success, .notification.is-success');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transition = 'opacity 0.5s ease-out';
                    successMessage.style.opacity = '0';
                    setTimeout(() => {
                        successMessage.remove();
                    }, 500);
                }, 3000);
            }
        },

        mostrarDetallesElemento(elemento) {
            const modalHTML = `
                <div class="modal fade" id="modal-detalles-${elemento.id}" tabindex="-1" aria-labelledby="modalDetallesLabel-${elemento.id}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalDetallesLabel-${elemento.id}">Detalles del Elemento</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Vista de detalles -->
                                <div id="details-view-admin-${elemento.id}" class="details-view">
                                    ${elemento.foto ? 
                                        `<img src="/storage/${elemento.foto}" alt="Foto del elemento" class="img-fluid mb-3">` 
                                        : ''}
                                    <p><strong>Categoría:</strong> ${elemento.categoria.nombre}</p>
                                    <p><strong>Descripción:</strong> ${elemento.descripcion}</p>
                                    <p><strong>Marca:</strong> ${elemento.marca}</p>
                                    <p><strong>Modelo:</strong> ${elemento.modelo}</p>
                                    <p><strong>Serial:</strong> ${elemento.serie}</p>
                                    <p><strong>Especificaciones Técnicas:</strong> ${elemento.especificaciones_tecnicas}</p>
                                </div>

                                <!-- Vista de edición (oculta por defecto) -->
                                <div id="edit-view-admin-${elemento.id}" class="edit-view d-none">
                                    <form action="/admin/elementos/${elemento.id}" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                        <input type="hidden" name="_method" value="PUT">
                                        <div class="mb-3">
                                            <label for="categoria_id-${elemento.id}" class="form-label">Categoría</label>
                                            <select id="categoria_id-${elemento.id}" name="categoria_id" class="form-select" required>
                                                ${window.categorias.map(categoria => `
                                                    <option value="${categoria.id}" ${categoria.id === elemento.categoria_id ? 'selected' : ''}>
                                                        ${categoria.nombre}
                                                    </option>
                                                `).join('')}
                                            </select>
                                        </div>
                                <div class="mb-3">
                                    <label for="descripcion-${elemento.id}" class="form-label">Descripción</label>
                                    <input type="text" id="descripcion-${elemento.id}" name="descripcion" class="form-control" value="${elemento.descripcion}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="marca-${elemento.id}" class="form-label">Marca</label>
                                    <input type="text" id="marca-${elemento.id}" name="marca" class="form-control" value="${elemento.marca}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="modelo-${elemento.id}" class="form-label">Modelo</label>
                                    <input type="text" id="modelo-${elemento.id}" name="modelo" class="form-control" value="${elemento.modelo}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="serie-${elemento.id}" class="form-label">Número de Serie</label>
                                    <input type="text" id="serie-${elemento.id}" name="serie" class="form-control" value="${elemento.serie}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="especificaciones_tecnicas-${elemento.id}" class="form-label">Especificaciones Técnicas</label>
                                    <textarea id="especificaciones_tecnicas-${elemento.id}" name="especificaciones_tecnicas" class="form-control" rows="3" required>${elemento.especificaciones_tecnicas}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="foto-${elemento.id}" class="form-label">Foto del Elemento</label>
                                    <input type="file" id="foto-${elemento.id}" name="foto" class="form-control" accept="image/*" onchange="previewImage(event, 'preview-imagen-${elemento.id}')">
                                    <div class="mt-2">
                                        <img id="preview-imagen-${elemento.id}" 
                                             src="${elemento.foto ? '/storage/' + elemento.foto : '/imagenes/sin_foto_elemento.webp'}" 
                                             alt="Foto del elemento" 
                                             class="img-fluid mt-2" 
                                             style="max-height: 200px"
                                             onerror="this.src='/imagenes/sin_foto_elemento.webp'">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <form action="/admin/elementos/${elemento.id}" method="POST" class="d-inline">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                        <button type="button" class="btn btn-primary d-none" id="save-changes-btn-admin-${elemento.id}" 
                                onclick="saveChangesAdmin(${elemento.id})">Guardar Cambios</button>
                        <button type="button" class="btn btn-warning" onclick="editElementAdmin(${elemento.id})">
                            Editar
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Eliminar modal anterior si existe
    const modalAnterior = document.getElementById(`modal-detalles-${elemento.id}`);
    if (modalAnterior) {
        modalAnterior.remove();
    }

    // Agregar el nuevo modal al DOM
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById(`modal-detalles-${elemento.id}`));
    modal.show();
}
    };
    
    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', () => AdminForms.init());

    // También necesitamos estas funciones disponibles globalmente
    window.editElementAdmin = function(elementoId) {
        const detailsView = document.getElementById(`details-view-admin-${elementoId}`);
        const editView = document.getElementById(`edit-view-admin-${elementoId}`);
        const saveButton = document.getElementById(`save-changes-btn-admin-${elementoId}`);
        
        detailsView.classList.add('d-none');
        editView.classList.remove('d-none');
        saveButton.classList.remove('d-none');
    };

    window.saveChangesAdmin = function(elementoId) {
        const form = document.querySelector(`#edit-view-admin-${elementoId} form`);
        form.submit();
    };

    // Asegúrate de que esta función esté definida fuera del objeto AdminForms
    window.mostrarDetallesElemento = function(elemento) {
        const modalHTML = `
            <div class="modal fade" id="modal-detalles-${elemento.id}" tabindex="-1" aria-labelledby="modalDetallesLabel-${elemento.id}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDetallesLabel-${elemento.id}">Detalles del Elemento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Vista de detalles -->
                            <div id="details-view-admin-${elemento.id}" class="details-view">
                                ${elemento.foto ? 
                                    `<img src="/storage/${elemento.foto}" alt="Foto del elemento" class="img-fluid mb-3">` 
                                    : ''}
                                <p><strong>Categoría:</strong> ${elemento.categoria.nombre}</p>
                                <p><strong>Descripción:</strong> ${elemento.descripcion}</p>
                                <p><strong>Marca:</strong> ${elemento.marca}</p>
                                <p><strong>Modelo:</strong> ${elemento.modelo}</p>
                                <p><strong>Serial:</strong> ${elemento.serie}</p>
                                <p><strong>Especificaciones Técnicas:</strong> ${elemento.especificaciones_tecnicas}</p>
                            </div>

                            <!-- Vista de edición (oculta por defecto) -->
                            <div id="edit-view-admin-${elemento.id}" class="edit-view d-none">
                                <form action="/admin/elementos/${elemento.id}" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="mb-3">
                                        <label for="categoria_id-${elemento.id}" class="form-label">Categoría</label>
                                        <select id="categoria_id-${elemento.id}" name="categoria_id" class="form-select" required>
                                            ${window.categorias.map(categoria => `
                                                <option value="${categoria.id}" ${categoria.id === elemento.categoria_id ? 'selected' : ''}>
                                                    ${categoria.nombre}
                                                </option>
                                            `).join('')}
                                        </select>
                                    </div>
                                <div class="mb-3">
                                    <label for="descripcion-${elemento.id}" class="form-label">Descripción</label>
                                    <input type="text" id="descripcion-${elemento.id}" name="descripcion" class="form-control" value="${elemento.descripcion}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="marca-${elemento.id}" class="form-label">Marca</label>
                                    <input type="text" id="marca-${elemento.id}" name="marca" class="form-control" value="${elemento.marca}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="modelo-${elemento.id}" class="form-label">Modelo</label>
                                    <input type="text" id="modelo-${elemento.id}" name="modelo" class="form-control" value="${elemento.modelo}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="serie-${elemento.id}" class="form-label">Número de Serie</label>
                                    <input type="text" id="serie-${elemento.id}" name="serie" class="form-control" value="${elemento.serie}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="especificaciones_tecnicas-${elemento.id}" class="form-label">Especificaciones Técnicas</label>
                                    <textarea id="especificaciones_tecnicas-${elemento.id}" name="especificaciones_tecnicas" class="form-control" rows="3" required>${elemento.especificaciones_tecnicas}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="foto-${elemento.id}" class="form-label">Foto del Elemento</label>
                                    <input type="file" id="foto-${elemento.id}" name="foto" class="form-control" accept="image/*" onchange="previewImage(event, 'preview-imagen-${elemento.id}')">
                                    <div class="mt-2">
                                        <img id="preview-imagen-${elemento.id}" 
                                             src="${elemento.foto ? '/storage/' + elemento.foto : '/imagenes/sin_foto_elemento.webp'}" 
                                             alt="Foto del elemento" 
                                             class="img-fluid mt-2" 
                                             style="max-height: 200px"
                                             onerror="this.src='/imagenes/sin_foto_elemento.webp'">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <form action="/admin/elementos/${elemento.id}" method="POST" class="d-inline">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                        <button type="button" class="btn btn-primary d-none" id="save-changes-btn-admin-${elemento.id}" 
                                onclick="saveChangesAdmin(${elemento.id})">Guardar Cambios</button>
                        <button type="button" class="btn btn-warning" onclick="editElementAdmin(${elemento.id})">
                            Editar
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
`;

        // Eliminar modal anterior si existe
        const modalAnterior = document.getElementById(`modal-detalles-${elemento.id}`);
        if (modalAnterior) {
            modalAnterior.remove();
        }

        // Agregar el nuevo modal al DOM
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById(`modal-detalles-${elemento.id}`));
        modal.show();
    };

    // Función para cerrar modales
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener todos los botones de cierre
        const closeButtons = document.querySelectorAll('.close-btn');
        
        // Agregar evento de click a cada botón
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('.modal');
                if (modal) {
                    modal.style.display = 'none';
                }
            });
        });

        // Cerrar modal al hacer click fuera de él
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        });
    });
</script>

<div id="modales-container"></div>

</body>
</html>
