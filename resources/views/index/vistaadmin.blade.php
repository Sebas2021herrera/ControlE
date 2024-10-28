<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administrador</title>
    <link rel="stylesheet" href="{{ asset('css/styles_vista_admin.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <!-- Enlaza el archivo CSS de Bulma -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- En el head, después de tus otros links -->
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
                    <a href="#" id="reportsLink" class="registrar">Reportes ingresos</a>
                </div>
                <!-- Aquí puedes agregar un dropdown para reportes si es necesario -->
                <div class="dropdown-menu" id="dropdown-menu" role="menu">
                    <div class="dropdown-content">
                        <a href="#" class="dropdown-item" id="reportUsers">Reportes ingresos</a>
                        <a href="#" class="dropdown-item" id="reportUsers">Reportes usuarios</a>
                        <a href="#" class="dropdown-item" id="reportElements">Reportes elementos</a>
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

            @if(isset($usuarioConsultado))
                <div class="usuario-info mt-4">
                    <h3>Información del Usuario</h3>
                    <div class="info-container">
                        <p><strong>Nombres:</strong> {{ $usuarioConsultado->nombres }}</p>
                        <p><strong>Apellidos:</strong> {{ $usuarioConsultado->apellidos }}</p>
                        <p><strong>Documento:</strong> {{ $usuarioConsultado->numero_documento }}</p>
                        <p><strong>Correo Personal:</strong> {{ $usuarioConsultado->correo_personal }}</p>
                        <p><strong>Correo Institucional:</strong> {{ $usuarioConsultado->correo_institucional }}</p>
                        <p><strong>Teléfono:</strong> {{ $usuarioConsultado->telefono }}</p>
                        <p><strong>Rol:</strong> {{ $usuarioConsultado->role->nombre }}</p>
                        @if($usuarioConsultado->numero_ficha)
                            <p><strong>Número de Ficha:</strong> {{ $usuarioConsultado->numero_ficha }}</p>
                        @endif
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="notification is-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Código Javascripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function previewImage(inputId, previewId) {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);
        
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    } else {
                        preview.src = '#';
                        preview.style.display = 'none';
                    }
                });
            }
        
            // Inicializar la previsualización para ambos formularios
            previewImage('foto', 'previewUsuario');
            previewImage('fotoElemento', 'previewElemento');
        });
        

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

</body>
</html>
