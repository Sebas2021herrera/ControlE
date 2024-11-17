<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administrador</title>
    <link rel="stylesheet" href="{{ asset('css/styles_vista_admin.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <!-- Enlaza el archivo CSS de Bulma -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="dropdown is-hoverable" style="margin-right: 20px;">
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
    
            <div class="dropdown is-hoverable" style="margin-right: 20px;">
                <div class="dropdown-trigger">
                    <a href="#" id="reportsLink" class="registrar">Reportes</a>
                </div>
                <!-- Aquí puedes agregar un dropdown para reportes si es necesario -->
                <div class="dropdown-menu" id="dropdown-menu" role="menu">
                    <div class="dropdown-content">
                        <a href="#" class="open-report-ingresos-modal">Reportes ingresos</a>
                        <a href="#" class="open-report-ingresos-modal">Reportes usuarios</a>
                        <a href="#" class="open-report-ingresos-modal">Reportes elementos</a>
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
                            <a href="#" class="dropdown-item">Editar perfil</a>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

<!-- Después del header y antes de los modales -->
<div class="resultado-busqueda" style="display: none;">
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

        <div class="contenido elementos">
            <div class="card-container">
                <!-- Aquí se cargarán dinámicamente las cards de elementos -->
            </div>
        </div>
    </div>
    <!-- Botón para limpiar la consulta -->
    <div class="field" style="margin-top: 10px;">
        <button id="clearSearch" class="button is-danger">
            <i class="fas fa-redo"></i> Limpiar Consulta
        </button>
    </div>
</div>

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
                                    name="nombres" value="{{ old('nombres') }}" required>
                            </div>
                            @error('nombres')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>
            
                        <div class="field">
                            <label class="label" for="apellidos">Apellidos:</label>
                            <div class="control">
                                <input class="input @error('apellidos') is-danger @enderror" type="text" id="apellidos"
                                    name="apellidos" value="{{ old('apellidos') }}" required>
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
                                    required>
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
                                    required>
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
                                    value="{{ old('correo_institucional') }}" required>
                            </div>
                            @error('correo_institucional')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>
            
                        <div class="field">
                            <label class="label" for="contraseña">Contraseña:</label>
                            <div class="control">
                                <input class="input @error('contraseña') is-danger @enderror" type="password"
                                    id="contraseña" name="contraseña" required>
                            </div>
                            <p id="contrasenaError" class="help is-danger"></p>
                            @error('contraseña')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>
            
                        <div class="field">
                            <label class="label" for="contraseña_confirmation">Confirmar Contraseña:</label>
                            <div class="control">
                                <input class="input @error('contraseña_confirmation') is-danger @enderror" type="password"
                                    id="contraseña_confirmation" name="contraseña_confirmation" required>
                            </div>
                            <p id="confirmarContrasenaError" class="help is-danger"></p>
                            @error('contraseña_confirmation')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>
            
                        <div class="field">
                            <label class="label" for="telefono">Teléfono:</label>
                            <div class="control">
                                <input class="input @error('telefono') is-danger @enderror" type="tel" id="telefono"
                                    name="telefono" value="{{ old('telefono') }}" required>
                            </div>
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
                                        <option value="5" {{ old('rol') == 5 ? 'selected' : '' }}>Funcionario
                                        </option>
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
                                <input class="input @error('numero_ficha') is-danger @enderror" type="text"
                                    id="numero_ficha" name="numero_ficha" value="{{ old('numero_ficha') }}">
                            </div>
                            @error('numero_ficha')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field">
                            <label class="label" for="foto">Foto de Perfil:</label>
                            <div class="control">
                                <input class="input" type="file" id="foto" name="foto" accept="image/*">
                            </div>
                            @error('foto')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                            <!-- Elemento para la previsualización -->
                            <img id="previewUsuario" src="#" alt="Previsualización de Foto"
                                 style="display: none; margin-top: 10px; width: 150px; height: 150px; object-fit: cover;">
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
    <div class="modal-dialog modal-lg">
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
                        <!-- Campos de edición similares al modal anterior -->
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
                        <!-- Similar para los demás campos... -->
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Botón para eliminar elemento -->
                <form action="{{ route('admin.elementos.destroy', $elemento->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
                <!-- Botón para guardar cambios (oculto por defecto) -->
                <button type="button" class="btn btn-primary d-none" id="save-changes-btn-admin-{{ $elemento->id }}" onclick="saveChanges({{ $elemento->id }})">Guardar Cambios</button>
                <!-- Botón para editar elementos -->
                <button type="button" class="btn btn-warning" onclick="editElementAdmin({{ $elemento->id }})">Editar</button>
                <!-- Botón para cerrar el modal -->
                <button type="button" class="btn btn-secondary" onclick="closeModalAdmin({{ $elemento->id }})">Cerrar</button>
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


<!-- Código Javascripts -->
<script src="{{ asset('js/vista_admin.js') }}"></script> 
 
</body>
</html>
