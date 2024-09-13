<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <!-- Enlaza el archivo CSS de Bulma -->
    <link rel="stylesheet" href="{{ asset('css/styles_create.css') }}"> <!-- Enlaza el archivo CSS personalizado -->
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <h2 class="title is-4">Registro de Usuarios</h2>
 
                @if ($errors->any())
                    <div class="notification is-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="registroForm" method="POST" action="{{ route('createpost') }}" enctype="multipart/form-data">
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
        document.getElementById('registroForm').addEventListener('submit', function(event) {
            var contrasena = document.getElementById('contrasena').value;
            var confirmarContrasena = document.getElementById('contrasena_confirmation').value;
            var contrasenaError = document.getElementById('contrasenaError');
            var confirmarContrasenaError = document.getElementById('confirmarContrasenaError');

            // Limpiar mensajes de error
            contrasenaError.textContent = '';
            confirmarContrasenaError.textContent = '';

            if (contrasena.length < 6) {
                contrasenaError.textContent = 'La contraseña debe tener al menos 6 caracteres.';
                event.preventDefault(); // Evita el envío del formulario
                return;
            }

            if (contrasena !== confirmarContrasena) {
                confirmarContrasenaError.textContent = 'Las contraseñas no coinciden.';
                event.preventDefault(); // Evita el envío del formulario
                return;
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        $(document).ready(function() {
            const successMessage = $('.alert-success');
            if (successMessage.length) {
                setTimeout(() => {
                    successMessage.fadeOut(500); // Desvanecer el mensaje en 0.5 segundos
                }, 5000); // Mostrar el mensaje por 5 segundos antes de desvanecerlo
            }
        });
    </script>
    <script>
        function validateFileSize(event) {
            const file = event.target.files[0];
            const maxSize = 6 * 1024 * 1024; // 6 MB en bytes

            if (file && file.size > maxSize) {
                alert('El tamaño del archivo no debe superar los 6 MB.');
                event.target.value = ''; // Limpia el campo de archivo
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


</body>

</html>
