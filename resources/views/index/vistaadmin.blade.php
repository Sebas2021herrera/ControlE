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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Biblioteca jQuery -->
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="dropdown is-hoverable">
                <div class="dropdown-trigger">
                    <a href="#" id="registerLink" class="registrar">Registro</a>
                    <a href="#" id="reportLink" class="reporte">Reportes ingreso</a>
                </div>
                
                <!-- Menú para el enlace "Registro" -->
                <div class="dropdown-menu" id="dropdown-menu-register" role="menu">
                    <div class="dropdown-content">
                        <a href="#" class="dropdown-item" data-bs-toggle="modal" id="registerUsers">Registrar usuarios</a>
                        <a href="#" class="dropdown-item" id="registerElements">Registrar elementos</a>
                        <a href="#" class="dropdown-item" id="consultarUsuarios">Consultar usuarios</a>
                    </div>
                </div>
        
                <!-- Menú para el enlace "Reportes ingreso" -->
                <div class="dropdown-menu" id="dropdown-menu-report" role="menu">
                    <div class="dropdown-content">
                        <a href="#" class="dropdown-item" id="reporteDiario">Reporte diario</a>
                        <a href="#" class="dropdown-item" id="reporteMensual">Reporte mensual</a>
                        <a href="#" class="dropdown-item" id="consultarIngresos">Consultar ingresos</a>
                    </div>
                </div>
            </div>
        </div>

    </header>
    
    <!-- Modal de Registro de elementos -->
    <div id="registerModal" class="modal">
    <div class="container">
        <div class="ventana-formulario">
        <form action="{{ route('elementos.storeadmin') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="numeroDocumentoUsuario">Número de Documento:</label>
            <input type="text" name="numeroDocumentoUsuario" id="numeroDocumentoUsuario" required>
    
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

    <!-- Modal de Registro de Usuarios -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <div class="ventana-formulario">
                <h2>Registro de usuarios</h2>
               
                <form id="registroForm" method="POST" action="{{ route('createpostadmin') }}" enctype="multipart/form-data">
                    @csrf <!-- Para Laravel CSRF Token -->

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
                        <p id="contraseaError" class="help is-danger"></p>
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
                                    <option value="1" {{ old('rol') == 1 ? 'selected' : '' }}>Vigilante</option>
                                    <option value="2" {{ old('rol') == 2 ? 'selected' : '' }}>Administrador</option>
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
                            <input class="input" type="file" id="foto1" name="foto" accept="image/*"
                                onchange="previewImage(event)">
                        </div>
                        @error('foto')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                        <!-- Elemento para la previsualización -->
                        <img id="preview" src="#" alt="Previsualización de Foto"
                            style="display: none; margin-top: 10px; width: 150px; height: 150px; object-fit: cover;">
                    </div>

                    <div class="field">
                        <div class="control">
                            <button class="button is-success_admin" type="submit">Registrar</button>
                            
                    <!-- Mostrar mensajes de éxito y error -->
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- <!-- Modal de Registro de Elementos -->
    <div id="elementsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <div class="ventana-formulario">
                <h2>Registro de elementos</h2>
                <form>
                    <!-- Aquí puedes agregar los campos para registrar elementos -->
                    <!-- Campo de Número Documento Usuario -->
                <div class="mb-3">
                        <label for="numeroDocumentoUsuario" class="form-label">Número documento usuario</label>
                        <input type="text" class="form-control" id="numeroDocumentoUsuario" name="numeroDocumentoUsuario" placeholder="Ingrese el número de documento del usuario">
                </div>
                    <!-- Campo Categoría -->
                    <div class="mb-3">
                        <form action="{{ route('elementos.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <label for="categoria" class="form-label">Categoría</label>
                        <select id="categoria_id" name="categoria_id" class="form-select" required>
                        <option selected>Archiveros</option>
                        @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                            <!-- Agregar más opciones si es necesario -->
                        </select>
                    <!-- Campo de Descripción -->
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Ingrese la descripción">
            </div>
            <!-- Campo de Marca -->
            <div class="mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" class="form-control" id="marca" name="marca" placeholder="Ingrese la marca">
            </div>
             <!-- Campo de Modelo -->
             <div class="mb-3">
                <label for="modelo" class="form-label">Modelo</label>
                <input type="text" class="form-control" id="modelo" name="modelo" placeholder="Ingrese el modelo">
            </div>
            <!-- Campo de Número de Serie -->
            <div class="mb-3">
                <label for="numeroSerie" class="form-label">Número de Serie</label>
                <input type="text" class="form-control" id="numeroSerie" name="numeroSerie" placeholder="Ingrese el número de serie">
            </div>
            <!-- Campo de Especificaciones Técnicas -->
            <div class="mb-3">
                <label for="especificacionesTecnicas" class="form-label">Especificaciones Técnicas</label>
                <textarea class="form-control" id="especificacionesTecnicas" name="especificacionesTecnicas" rows="3" placeholder="Ingrese las especificaciones técnicas"></textarea>
            </div>
            <!-- Campo de Foto -->
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" class="form-control" id="foto" name="foto">
            </div>
                
                    </div>
                    <div class="field">
                        <div class="control">
                            <button class="button is-success" type="submit">Registrar Elemento</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}



<script>
    // Agrega un evento al enlace de registro para mostrar el menú correspondiente
    document.getElementById('registerLink').addEventListener('click', function(e) {
        e.preventDefault(); // Previene el comportamiento predeterminado del enlace
        // Muestra el menú de registro y oculta el menú de reportes
        document.getElementById('dropdown-menu-register').style.display = 'block';
        document.getElementById('dropdown-menu-report').style.display = 'none';
    });

    // Agrega un evento al enlace de reportes para mostrar el menú correspondiente
    document.getElementById('reportLink').addEventListener('click', function(e) {
        e.preventDefault(); // Previene el comportamiento predeterminado del enlace
        // Muestra el menú de reportes y oculta el menú de registro
        document.getElementById('dropdown-menu-register').style.display = 'none';
        document.getElementById('dropdown-menu-report').style.display = 'block';
    });

    // Agrega un evento para cerrar los menús al hacer clic fuera de ellos
    document.addEventListener('click', function(e) {
        // Verifica si el clic no fue en un elemento con la clase 'dropdown-trigger'
        if (!e.target.closest('.dropdown-trigger')) {
            // Oculta ambos menús
            document.getElementById('dropdown-menu-register').style.display = 'none';
            document.getElementById('dropdown-menu-report').style.display = 'none';
        }
    });
</script> 

{{-- <script>
    // Agrega un evento para manejar el envío del formulario con id 'registroForm'
    document.getElementById('registroForm').addEventListener('submit', function(event) {
        // Obtiene el valor de la contraseña y la confirmación de contraseña
        var contrasena = document.getElementById('contrasena').value;
        var confirmarContrasena = document.getElementById('contrasena_confirmation').value;
        // Obtiene los elementos para mostrar mensajes de error
        var contrasenaError = document.getElementById('contrasenaError');
        var confirmarContrasenaError = document.getElementById('confirmarContrasenaError');

        // Limpia los mensajes de error previos
        contrasenaError.textContent = '';
        confirmarContrasenaError.textContent = '';

        // Verifica si la contraseña tiene al menos 6 caracteres
        if (contrasena.length < 6) {
            contrasenaError.textContent = 'La contraseña debe tener al menos 6 caracteres.';
            event.preventDefault(); // Evita el envío del formulario
            return; // Sale de la función
        }

        // Verifica si las contraseñas coinciden
        if (contrasena !== confirmarContrasena) {
            confirmarContrasenaError.textContent = 'Las contraseñas no coinciden.';
            event.preventDefault(); // Evita el envío del formulario
            return; // Sale de la función
        }
    });
</script>

<script>
    // Función para previsualizar una imagen seleccionada
    function previewImage(event) {
        // Obtiene el input que disparó el evento
        var input = event.target;
        // Obtiene el elemento de imagen donde se mostrará la previsualización
        var preview = document.getElementById('preview');

        // Verifica si hay archivos seleccionados
        if (input.files && input.files[0]) {
            // Crea un nuevo objeto FileReader para leer el archivo
            var reader = new FileReader();

            // Cuando el archivo se ha cargado
            reader.onload = function(e) {
                // Establece la fuente de la imagen de previsualización al contenido del archivo
                preview.src = e.target.result;
                // Muestra el elemento de previsualización
                preview.style.display = 'block';
            }

            // Lee el primer archivo como una URL de datos
            reader.readAsDataURL(input.files[0]);
        } else {
            // Si no hay archivos, restablece la imagen de previsualización
            preview.src = '#';
            // Oculta el elemento de previsualización
            preview.style.display = 'none';
        }
    }
</script>

<script>
    // Espera a que el contenido del documento esté completamente cargado
    document.addEventListener('DOMContentLoaded', function() {
        // Obtiene el elemento select con el id 'rol'
        var rolSelect = document.getElementById('rol');
        // Obtiene el campo de número de ficha y su contenedor más cercano con la clase 'field'
        var numeroFichaField = document.getElementById('numero_ficha'); // Actualizado
        var numeroFichaDiv = numeroFichaField.closest('.field');

        // Función para mostrar u ocultar el campo de número de ficha
        function toggleNumeroFicha() {
            // Si el rol seleccionado es '3' (Aprendiz)
            if (rolSelect.value == 3) { // Aprendiz
                // Muestra el campo de número de ficha
                numeroFichaDiv.style.display = 'block';
                // Marca el campo como requerido
                numeroFichaField.setAttribute('required', 'required');
            } else {
                // Oculta el campo de número de ficha
                numeroFichaDiv.style.display = 'none';
                 // Elimina la marca de requerido
                numeroFichaField.removeAttribute('required');
            }
        }

        // Añade un evento para cambiar la visibilidad del campo al cambiar el rol
        rolSelect.addEventListener('change', toggleNumeroFicha);

        // Llamar a la función para inicializar el estado correcto en caso de que el rol esté preseleccionado
        toggleNumeroFicha();
    });
</script>
<script> --}}
    <script>
        $(document).ready(function() {
            const successMessage = $('.alert-success_admin');
            if (successMessage.length) {
                setTimeout(() => {
                    successMessage.fadeOut(500); // Desvanecer el mensaje en 0.5 segundos
                }, 5000); // Mostrar el mensaje por 5 segundos antes de desvanecerlo
            }
        });
        </script>

    <script>
        // Selección de elementos
        const registerUsersLink = document.getElementById('registerUsers');
        const registerElementsLink = document.getElementById('registerElements');
        const registerModal = document.getElementById('registerModal');
        const elementsModal = document.getElementById('elementsModal');
        const closeBtns = document.querySelectorAll('.close-btn');

        // Mostrar el modal de registro de usuarios
        registerUsersLink.addEventListener('click', (event) => {
            event.preventDefault();
            registerModal.style.display = 'block';
        });

        // Mostrar el modal de registro de elementos
        registerElementsLink.addEventListener('click', (event) => {
            event.preventDefault();
            elementsModal.style.display = 'block';
        });

        // Cerrar los modales al hacer clic en la "X"
        closeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('.modal').style.display = 'none';
            });
        });

        // Cerrar los modales al hacer clic fuera del contenido del modal
        window.addEventListener('click', (event) => {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        });
    </script>
</body>
</html>