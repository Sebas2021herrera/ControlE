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
</head>
<body>
    <header class="main-header">
        <div class="container">
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
            <nav class="nav">
                <ul class="nav-list">
                    <li><a href="#">Inicio</a></li>
                    <li><a href="#">Acerca de</a></li>
                    <li><a href="#">Servicios</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Modal de Registro de Usuarios -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <div class="ventana-formulario">
                <h2>Registro de usuarios</h2>
                    <form id="registroForm" method="POST" action="{{ route('createpost') }}" enctype="multipart/form-data">
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
                                <input class="input" type="file" id="foto" name="foto" accept="image/*"
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
                            <label for="documento_id" class="form-label">Número documento</label>
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


    <script>
        function previewImage(event) {
            var input = event.target;
            var preview = document.getElementById('preview');

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
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


        // Mostrar el campo "Número de ficha" cuando seleccionamos el rol Aprendiz
        document.getElementById('rol').addEventListener('change', function() {
        const fichaGroup = document.getElementById('fichaGroup');
        if (this.value == '3') { // 3 es el valor para el rol de Aprendiz
            fichaGroup.style.display = 'block';
        } else {
            fichaGroup.style.display = 'none';
        }
        });

        // Validación de contraseña en Javascript 

        document.getElementById('registroForm').addEventListener('submit', function (e) {
            var password = document.getElementById('contraseña').value;
            var confirmPassword = document.getElementById('contraseña_confirmation').value;
            var errorElement = document.getElementById('confirmarContrasenaError');

            if (password !== confirmPassword) {
                e.preventDefault(); // Evita que el formulario se envíe
                errorElement.textContent = 'Las contraseñas no coinciden';
            } else {
                errorElement.textContent = ''; // Borra el mensaje de error si coinciden
            }
        });


        // Función previsualización de la imagen del foto de perfil

        function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function () {
            var preview = document.getElementById('preview');
            preview.src = reader.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }


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
