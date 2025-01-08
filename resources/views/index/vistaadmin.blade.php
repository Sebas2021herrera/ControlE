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
                <div class="dropdown is-hoverable">
                    <div class="dropdown-trigger">
                        <a href="#" id="registerLink" class="registrar">Registro</a>
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu" role="menu">
                        <div class="dropdown-content">
                            <a href="#" class="dropdown-item" id="registerUsers">Registrar usuarios</a>
                            <a href="#" class="dropdown-item" id="registerElements">Registrar elementos</a>
                            <a href="#" class="dropdown-item" id="consultUsers">Consultar usuarios</a>
                        </div>
                    </div>
                </div>

                <div class="dropdown is-hoverable">
                    <div class="dropdown-trigger">
                        <a href="#" id="reportsLink" class="registrar">Reportes</a>
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu" role="menu">
                        <div class="dropdown-content">
                            <a href="{{ route('admin.reportes.ingresos') }}" class="dropdown-item">Reportes ingresos</a> 
                            <a href="{{ route('admin.reportes.elementos') }}" class="dropdown-item">Reportes elementos</a>
                        </div>
                    </div>
                </div>
            </div>

            <nav class="nav">
                <div class="dropdown is-hoverable">
                    <div class="dropdown-trigger">
                        <a href="#" class="nav-options">
                            Opciones
                            <span class="icon is-small">
                                <i class="fas fa-angle-down" aria-hidden="true"></i>
                            </span>
                        </a>
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu4" role="menu">
                        <div class="dropdown-content">
                            {{-- <a href="#" class="dropdown-item">Editar perfil</a> --}}
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                                    Cerrar sesión
                                </button>
                            </form>
                            <button type="button" class="dropdown-item" 
                                    onclick="$('#editarPerfilAdminModal').modal('show')" 
                                    style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                                Editar perfil
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>


<div class="admin-dashboard">
    <div class="sidebar" id="sidebar">
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
    </div>

    <div class="main-content">
        <div class="logo-container" id="logo-container">
            <img src="{{ asset('imagenes/Logo-Control-E.png') }}" alt="Control E Logo" class="logo-app">
        </div>
        <p class="text-control">Control de elementos para la Regional Casanare - SENA.</p>

        <!-- Aquí va la sección para consultar un usuario -->
        <div class="resultado-busqueda" id="resultadoBusqueda" style="display: none;">
            <div class="contenido-superior">
                <div class="contenedor-intermedio">
                    <div class="usuario-info">
                        <div class="foto-logo">
                            <img src="{{ asset('imagenes/logo-del-sena-01.png') }}" alt="Logo del SENA" class="logo-sena">
                            <div class="barra-separadora"></div>
                        </div>
                        <div class="info-text">
                            <p class="verde usuario-nombre"></p>
                            <p class="verde usuario-apellidos"></p>
                            <p><strong>Doc: </strong><span class="usuario-documento"></span></p>
                            <p><strong>Cel: </strong><span class="usuario-telefono"></span></p>
                            <p><strong>RH: </strong><span class="usuario-rh"></span></p>
                            <p><strong>Rol: </strong><span class="usuario-rol"></span></p>
                            <p><strong>Ficha: </strong><span class="usuario-ficha"></span></p>
                            <p class="verde" id="semifooter">Regional Casanare | Centro Agroindustrial y Fortalecimiento Empresarial del Casanare</p>
                        </div>
                        <div class="foto-usuario">
                            <img src="" alt="Foto de perfil" class="foto-perfil-usuario">
                        </div>
                    </div>
                </div>
        
                <div class="contenido-elementos">
                    <div class="card-container">
                        <!-- Aquí se cargarán dinámicamente las cards de elementos -->
                    </div>
                </div>
            </div>
            <!-- Botón para limpiar la consulta -->
            <div class="field" style="margin-top: 10px;">
                <button id="limpiarConsultaBtn" class="button is-danger">
                    <i class="fas fa-redo"></i> Limpiar Consulta
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
                <h2>Registro de usuarios</h2>
                <form id="registroForm" method="POST" action="{{ route('admin.usuarios.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="field">
                        <label class="label" for="nombres">Nombres:</label>
                        <div class="control">
                            <input class="input @error('nombres') is-danger @enderror" type="text" id="nombres"
                                name="nombres" value="{{ old('nombres') }}" required placeholder="Ingresa los nombres del nuevo usuario">
                        </div>
                        @error('nombres')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div class="field">
                        <label class="label" for="apellidos">Apellidos:</label>
                        <div class="control">
                            <input class="input @error('apellidos') is-danger @enderror" type="text" id="apellidos"
                                name="apellidos" value="{{ old('apellidos') }}" required placeholder="Ingresa los apellidos del nuevo usuario">
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
                                required placeholder="Ingresa su correo personal">
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
                                value="{{ old('correo_institucional') }}" required placeholder="Ingresa correo institucional">
                        </div>
                        @error('correo_institucional')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div class="field">
                        <label class="label" for="contraseña">Nueva Contraseña:</label>
                        <div class="control">
                            <input class="input @error('contraseña') is-danger @enderror" type="password"
                                id="contraseña" name="contraseña" required
                                placeholder="Mínimo 6 caracteres, al menos una mayúscula, un número y un símbolo (!@#$%^&*)">
                        </div>
                        @error('contraseña')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div class="field">
                        <label class="label" for="contraseña_confirmation">Confirmar Contraseña:</label>
                        <div class="control">
                            <input class="input @error('contraseña_confirmation') is-danger @enderror" type="password"
                                id="contraseña_confirmation" name="contraseña_confirmation" required
                                placeholder="Repita la contraseña">
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
                        <label class="label" for="foto">Foto (máximo 5MB):</label>
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
                <h2>Registro de elementos</h2>
                    <!-- Aquí puedes agregar los campos para registrar elementos -->
                    <!-- Campo de Número Documento Usuario -->
                   
                    <form action="{{ route('admin.elementos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf             
                        <div class="mb-3">
                            <label for="documento" class="form-label">Número documento</label>
                            <input type="text" id="documento" name="documento" class="form-control" required>
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
                                <img id="previewPerfilAdmin" src="{{ asset('imagenes/sin_foto_perfil.webp') }}" 
                                     alt="Foto de perfil predeterminada" style="max-width: 100px; height: auto;">
                            @endif
                        </div>
                        <input type="file" id="foto" name="foto" class="form-control" accept="image/*" 
                               onchange="previewImage(event, 'previewPerfilAdmin')">
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Código Javascripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Función para previsualizar imagen en el registro de usuarios
            const fotoInput = document.getElementById('foto');
            if (fotoInput) {
                fotoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewUsuario = document.getElementById('previewUsuario');
                            if (previewUsuario) {
                                previewUsuario.src = e.target.result;
                                previewUsuario.style.display = 'block';
                            }
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Función para previsualizar imagen en el registro de elementos
            const fotoElementoInput = document.getElementById('fotoElemento');
            if (fotoElementoInput) {
                fotoElementoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewElemento = document.getElementById('previewElemento');
                            if (previewElemento) {
                                previewElemento.src = e.target.result;
                                previewElemento.style.display = 'block';
                            }
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });

        // Función general para previsualizar imágenes (para otros formularios)
        function previewImage(event, previewId) {
            const file = event.target.files[0];
            const preview = document.getElementById(previewId);
            
            if (file && preview) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var rolSelect = document.getElementById('rol');
            var numeroFichaField = document.getElementById('numero_ficha'); // Actualizado
            var numeroFichaDiv = numeroFichaField.closest('.field');

        function toggleNumeroFicha() {
            if (rolSelect.value == 3) { // Aprendiz
                numeroFichaDiv.style.display = 'block';
                numeroFichaField.setAttribute('required', 'required');
            } else {
                numeroFichaDiv.style.display = 'none';
                numeroFichaField.removeAttribute('required');
            }
        }

        rolSelect.addEventListener('change', toggleNumeroFicha);

        // Llamar a la función para inicializar el estado correcto en caso de que el rol esté preseleccionado
        toggleNumeroFicha();
    });
    </script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Selección de elementos
            const registerUsersLink = document.getElementById('registerUsers');
            const registerElementsLink = document.getElementById('registerElements');
            const registerModal = document.getElementById('registerModal');
            const elementsModal = document.getElementById('elementsModal');
            const closeBtns = document.querySelectorAll('.close-btn');

            // Función para abrir modal
            function openModal(modal) {
                if (modal) {
                    modal.style.display = 'block';
                }
            }

            // Función para cerrar modal
            function closeModal(modal) {
                if (modal) {
                    modal.style.display = 'none';
                }
            }

            // Event listener para abrir modal de usuarios
            if (registerUsersLink) {
                registerUsersLink.addEventListener('click', (event) => {
                    event.preventDefault();
                    openModal(registerModal);
                });
            }

            // Event listener para abrir modal de elementos
            if (registerElementsLink) {
                registerElementsLink.addEventListener('click', (event) => {
                    event.preventDefault();
                    openModal(elementsModal);
                });
            }

            // Event listeners para cerrar modales con el botón X
            closeBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = btn.closest('.modal');
                    closeModal(modal);
                });
            });

            // Event listener para cerrar modales al hacer clic fuera
            window.addEventListener('click', (event) => {
                if (event.target.classList.contains('modal')) {
                    closeModal(event.target);
                }
            });

            // Mantener el resto de la funcionalidad de validación de contraseña
            document.getElementById('registroForm').addEventListener('submit', function (e) {
                var password = document.getElementById('contraseña').value;
                var confirmPassword = document.getElementById('contraseña_confirmation').value;
                var errorElement = document.getElementById('confirmarContrasenaError');

                if (password !== confirmPassword) {
                    e.preventDefault();
                    errorElement.textContent = 'Las contraseñas no coinciden';
                } else {
                    errorElement.textContent = '';
                }
            });
        });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Selección de elementos para el modal de consulta
        const consultUsersLink = document.getElementById('consultUsers');
        const consultUsersModal = document.getElementById('consultUsersModal');
        const closeBtns = document.querySelectorAll('.close-btn');

        // Event listener para abrir modal de consulta de usuarios
        if (consultUsersLink) {
            consultUsersLink.addEventListener('click', function(event) {
                event.preventDefault();
                if (consultUsersModal) {
                    consultUsersModal.style.display = 'block';
                }
            });
        }

        // Event listeners para cerrar modales
        closeBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = btn.closest('.modal');
                if (modal) {
                    modal.style.display = 'none';
                }
            });
        });

        // Cerrar modal al hacer clic fuera
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        });
    });
</script>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('form[action="{{ route("admin.usuarios.consultar") }}"]');
    const resultadoBusqueda = document.getElementById('resultadoBusqueda');
    
    if (searchForm) {
        searchForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const documento = new FormData(this).get('documento');
            
            try {
                // Realizar la petición
                const url = `/admin/usuarios/consultar?documento=${documento}`;
                console.log('Realizando petición a:', url);
                
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.success && data.usuario) {
                    // Ocultar sidebar
                    document.getElementById('sidebar').style.display = 'none';
                    
                    // Mostrar resultados
                    resultadoBusqueda.style.display = 'block';
                    
                    // Actualizar información del usuario
                    document.querySelector('.usuario-nombre').textContent = data.usuario.nombres;
                    document.querySelector('.usuario-apellidos').textContent = data.usuario.apellidos;
                    document.querySelector('.usuario-documento').textContent = data.usuario.numero_documento;
                    document.querySelector('.usuario-telefono').textContent = data.usuario.telefono;
                    document.querySelector('.usuario-rh').textContent = data.usuario.rh;
                    document.querySelector('.usuario-rol').textContent = data.usuario.role?.nombre || 'N/A';
                    document.querySelector('.usuario-ficha').textContent = data.usuario.numero_ficha || 'N/A';
                    
                    // Actualizar foto del usuario
                    const fotoPerfil = document.querySelector('.foto-perfil-usuario');
                    if (fotoPerfil) {
                        fotoPerfil.src = data.usuario.foto 
                            ? `/storage/fotos_perfil/${data.usuario.foto}`
                            : '/imagenes/sin_foto_perfil.webp';
                        fotoPerfil.onerror = () => fotoPerfil.src = '/imagenes/sin_foto_perfil.webp';
                    }
                    
                    // Actualizar elementos si existen
                    if (data.elementos && data.elementos.length > 0) {
                        const cardContainer = document.querySelector('.card-container');
                        cardContainer.innerHTML = data.elementos.map(elemento => `
                            <div class="card">
                                <h5 class="cabeza">${elemento.categoria.nombre}</h5>
                                <img src="/storage/${elemento.foto}" 
                                     alt="Foto del elemento" 
                                     class="elemento-foto"
                                     onerror="this.src='/imagenes/sin_foto_elemento.webp'">
                                <p><strong>Serial:</strong> ${elemento.serie}</p>
                                <p><strong>Marca:</strong> ${elemento.marca}</p>
                                <button type="button" 
                                        class="btn btn-link link-ver-mas" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modal-detalles-${elemento.id}">
                                    Ver más
                                </button>
                            </div>
                        `).join('');

                        // Generar modales
                        const modalesContainer = document.getElementById('modales-container');
                        modalesContainer.innerHTML = data.elementos.map(elemento => `
                            <div class="modal fade" id="modal-detalles-${elemento.id}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detalles del Elemento</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Vista de detalles -->
                                            <div id="details-view-${elemento.id}" class="details-view">
                                                <img src="/storage/${elemento.foto}" alt="Foto del elemento" class="img-fluid mb-3">
                                                <p><strong>Categoría:</strong> ${elemento.categoria.nombre}</p>
                                                <p><strong>Descripción:</strong> ${elemento.descripcion}</p>
                                                <p><strong>Marca:</strong> ${elemento.marca}</p>
                                                <p><strong>Modelo:</strong> ${elemento.modelo}</p>
                                                <p><strong>Serial:</strong> ${elemento.serie}</p>
                                                <p><strong>Especificaciones Técnicas:</strong> ${elemento.especificaciones_tecnicas}</p>
                                            </div>
                                            
                                            <!-- Vista de edición -->
                                            <div id="edit-view-${elemento.id}" class="edit-view d-none">
                                                <form id="edit-form-${elemento.id}" action="/admin/elementos/${elemento.id}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label class="form-label">Categoría</label>
                                                        <select name="categoria_id" class="form-select" required>
                                                            ${elemento.categoria ? `
                                                                <option value="${elemento.categoria.id}" selected>
                                                                    ${elemento.categoria.nombre}
                                                                </option>
                                                            ` : ''}
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Descripción</label>
                                                        <input type="text" name="descripcion" class="form-control" value="${elemento.descripcion}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Marca</label>
                                                        <input type="text" name="marca" class="form-control" value="${elemento.marca}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Modelo</label>
                                                        <input type="text" name="modelo" class="form-control" value="${elemento.modelo}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Número de Serie</label>
                                                        <input type="text" name="serie" class="form-control" value="${elemento.serie}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Especificaciones Técnicas</label>
                                                        <textarea name="especificaciones_tecnicas" class="form-control" rows="3" required>${elemento.especificaciones_tecnicas}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Foto</label>
                                                        <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewImage(event, 'preview-${elemento.id}')">
                                                        <img id="preview-${elemento.id}" src="/storage/${elemento.foto}" class="img-fluid mt-2" style="max-height: 200px">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-warning" onclick="editElementAdmin(${elemento.id})">
                                                Editar
                                            </button>
                                            <button type="button" class="btn btn-primary d-none" id="save-changes-btn-${elemento.id}" onclick="saveChangesAdmin(${elemento.id})">
                                                Guardar Cambios
                                            </button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cerrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    }
                    
                    // Cerrar modal de consulta
                    const consultUsersModal = document.getElementById('consultUsersModal');
                    if (consultUsersModal) {
                        const modal = bootstrap.Modal.getInstance(consultUsersModal);
                        if (modal) modal.hide();
                    }
                } else {
                    alert(data.mensaje || 'Usuario no encontrado');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }
});

// Funciones auxiliares
function editElementAdmin(id) {
    console.log('Editando elemento:', id);
    
    // Usar los IDs correctos que están en el HTML
    const detailsView = document.getElementById(`details-view-admin-${id}`);
    const editView = document.getElementById(`edit-view-admin-${id}`);
    const saveBtn = document.getElementById(`save-changes-btn-admin-${id}`);
    
    if (!detailsView || !editView) {
        console.error('No se encontraron las vistas:', {
            detailsView: !!detailsView,
            editView: !!editView
        });
        return;
    }

    // Ocultar vista de detalles y mostrar vista de edición
    detailsView.classList.add('d-none');
    editView.classList.remove('d-none');
    
    // Mostrar botón de guardar
    if (saveBtn) {
        saveBtn.classList.remove('d-none');
    }

    console.log('Vista de edición activada');
}

function saveChangesAdmin(id) {
    console.log('Guardando cambios para elemento:', id);
    const form = document.getElementById(`edit-view-admin-${id}`).querySelector('form');
    if (form) {
        form.submit();
    }
}

// Función para previsualizar imagen
function previewImage(event, previewId) {
        const file = event.target.files[0];
        const preview = document.getElementById(previewId);
        
        if (file && preview) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            
            reader.readAsDataURL(file);
        }
    }

    // Inicializar los eventos cuando el documento esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Para el modal de edición de perfil
        const fotoInput = document.querySelector('#editarPerfilAdminModal input[type="file"]');
        if (fotoInput) {
            fotoInput.addEventListener('change', function(e) {
                previewImage(e, 'previewPerfilAdmin');
            });
        }

        // Para otros formularios (si los hay)
        const otrosInputs = document.querySelectorAll('input[type="file"]');
        otrosInputs.forEach(input => {
            const previewId = input.getAttribute('data-preview');
            if (previewId) {
                input.addEventListener('change', function(e) {
                    previewImage(e, previewId);
                });
            }
        });
    });

// Corregir el onclick en el botón de editar
document.addEventListener('DOMContentLoaded', function() {
    // Corregir los botones de editar que usan template literals incorrectamente
    document.querySelectorAll('[onclick*="${elemento.id}"]').forEach(button => {
        const id = button.getAttribute('onclick').match(/\d+/)[0];
        button.setAttribute('onclick', `editElementAdmin(${id})`);
    });
});

</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Selecciona el modal y los botones
        const reportIngresosModal = document.getElementById("reportIngresosModal");
        const openModalBtn = document.querySelector(".open-report-ingresos-modal"); // Asegúrate que el botón tenga esta clase
        const closeModalBtn = reportIngresosModal.querySelector(".close-btn");
 
        // Función para abrir el modal
        openModalBtn.addEventListener("click", function(event) {
            event.preventDefault(); // Evita redireccionamientos
            reportIngresosModal.style.display = "block";
        });
 
        // Función para cerrar el modal al hacer clic en el botón de cerrar
        closeModalBtn.addEventListener("click", function() {
            reportIngresosModal.style.display = "none";
        });
 
        // Cierra el modal si el usuario hace clic fuera del contenido del modal
        window.addEventListener("click", function(event) {
            if (event.target === reportIngresosModal) {
                reportIngresosModal.style.display = "none";
            }
        });
    });



    document.addEventListener('DOMContentLoaded', function () {
    const resultadoBusqueda = document.getElementById('resultadoBusqueda');
    const sidebar = document.querySelector('.sidebar');
    const logoContainer = document.querySelector('.logo-container');
    const limpiarConsultaBtn = document.getElementById('limpiarConsultaBtn');

    document.addEventListener('showResult', function () {
        resultadoBusqueda.style.display = 'block';
        sidebar.style.display = 'none'; 
        logoContainer.style.display = 'none';
    });

    limpiarConsultaBtn.addEventListener('click', function () {
        resultadoBusqueda.style.display = 'none';
        sidebar.style.display = 'block';
        logoContainer.style.display = 'flex';
    });
});

 </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<div id="modales-container"></div>

<!-- Manejo de mensajes de éxito y error -->
<script>
    // Esperar a que el documento esté completamente cargado
    $(document).ready(function() {
        // Función para manejar los mensajes
        function handleMessages() {
            const successMessage = $('#success-message');
            const errorMessage = $('#error-message');

            if (successMessage.length > 0) {
                console.log('Mensaje de éxito encontrado');
                setTimeout(function() {
                    successMessage.fadeOut('slow');
                }, 5000);
            }

            if (errorMessage.length > 0) {
                console.log('Mensaje de error encontrado');
                setTimeout(function() {
                    errorMessage.fadeOut('slow');
                }, 5000);
            }
        }

        // Ejecutar la función cuando la página carga
        handleMessages();

        // También ejecutar cuando hay cambios en el DOM (para mensajes dinámicos)
        const observer = new MutationObserver(function(mutations) {
            handleMessages();
        });

        // Observar cambios en el body
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
</script>

<script>
    // Código Javascript para programar la funcionalidad de escribir solo números en los inputs de número de documento y teléfono
     const soloNumeros = document.querySelectorAll('#numero_documento, #telefono');
        soloNumeros.forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
</script>

<script>
    // Validación y mostrar/ocultar contraseñas

document.addEventListener("DOMContentLoaded", function () {
    // Obtener elementos de la página
    const nuevaContraseña = document.getElementById("nueva_contraseña");
    const confirmarContraseña = document.getElementById("confirmar_contraseña");
    const contraseñaError = document.getElementById("contraseña_error");

    // Función para actualizar el mensaje de validación
    function validarContraseña(input) {
        const value = input.value;
        let mensajes = [];

        if (value.length < 6) mensajes.push("Mínimo 6 caracteres");
        if (!/[A-Z]/.test(value)) mensajes.push("Al menos una mayúscula");
        if (!/[0-9]/.test(value)) mensajes.push("Al menos un número");
        if (!/[!@#$%^&*]/.test(value)) mensajes.push("Al menos un símbolo (!@#$%^&*)");

        // Actualizar el mensaje de error
        contraseñaError.textContent = mensajes.join(", ");
        contraseñaError.style.color = mensajes.length > 0 ? "#ff3860" : "#48c774";
    }

    // Agregar evento para la validación en tiempo real
    nuevaContraseña.addEventListener("input", function () {
        validarContraseña(this);
    });

    // Función para alternar mostrar/ocultar contraseñas
    function togglePasswordVisibility(input, toggleIcon) {
        const isPassword = input.type === "password";
        input.type = isPassword ? "text" : "password";

        // Cambiar el icono según el estado
        toggleIcon.classList.toggle("fa-eye");
        toggleIcon.classList.toggle("fa-eye-slash");
    }

    // Configurar iconos de mostrar/ocultar contraseñas
    const toggleNuevaContraseña = document.querySelector("#toggle_nueva_contraseña");
    const toggleConfirmarContraseña = document.querySelector("#toggle_confirmar_contraseña");

    toggleNuevaContraseña.addEventListener("click", function () {
        togglePasswordVisibility(nuevaContraseña, this);
    });

    toggleConfirmarContraseña.addEventListener("click", function () {
        togglePasswordVisibility(confirmarContraseña, this);
    });

    // Verificar que ambas contraseñas coincidan
    confirmarContraseña.addEventListener("input", function () {
        const errorMensaje = document.getElementById("coincidencia_error");
        if (nuevaContraseña.value !== confirmarContraseña.value) {
            errorMensaje.textContent = "Las contraseñas no coinciden";
            errorMensaje.style.color = "#ff3860";
        } else {
            errorMensaje.textContent = "";
        }
    });
});

</script>
</body>
</html>
