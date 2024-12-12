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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <!-- Agregar el logo del SENA -->
                <figure class="image is-flex is-justify-content-center mb-4">
                    <img src="{{ asset('imagenes/logo-del-sena-01.png') }}" alt="Logo SENA" style="max-width: 200px;">
                </figure>
                
                <h2 class="title is-4">Registro de Usuarios</h2>

                <!-- Mostrar errores de validación -->
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
                    @csrf <!-- CSRF Token necesario en Laravel -->

                    <!-- Nombres -->
                    <div class="field">
                        <label class="label" for="nombres">Nombres:</label>
                        <div class="control">
                            <input class="input @error('nombres') is-danger @enderror" type="text" id="nombres"
                                name="nombres" value="{{ old('nombres') }}" required
                                placeholder="Ingresar nombres">
                        </div>
                        @error('nombres')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Apellidos -->
                    <div class="field">
                        <label class="label" for="apellidos">Apellidos:</label>
                        <div class="control">
                            <input class="input @error('apellidos') is-danger @enderror" type="text" id="apellidos"
                                name="apellidos" value="{{ old('apellidos') }}" required
                                placeholder="Ingresar apellidos">
                        </div>
                        @error('apellidos')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Documento -->
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

                    <!-- Número de Documento -->
                    <div class="field">
                        <label class="label" for="numero_documento">Número de Documento:</label>
                        <div class="control">
                            <input class="input @error('numero_documento') is-danger @enderror" 
                                   type="text" 
                                   id="numero_documento" 
                                   name="numero_documento" 
                                   required 
                                   maxlength="11" 
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11)"
                                   value="{{ old('numero_documento') }}"
                                   required
                                   placeholder="Ingresar número de documento">
                        </div>
                        <p class="help">Máximo 11 dígitos</p>
                        @error('numero_documento')
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

                    <!-- Correo Personal -->
                    <div class="field">
                        <label class="label" for="correo_personal">Correo Personal:</label>
                        <div class="control">
                            <input class="input @error('correo_personal') is-danger @enderror" type="email"
                                id="correo_personal" name="correo_personal" value="{{ old('correo_personal') }}"
                                required placeholder="ejemplo@correo.com">
                        </div>
                        @error('correo_personal')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Correo Institucional -->
                    <div class="field">
                        <label class="label" for="correo_institucional">Correo Institucional:</label>
                        <div class="control">
                            <input class="input @error('correo_institucional') is-danger @enderror" type="email"
                                id="correo_institucional" name="correo_institucional"
                                value="{{ old('correo_institucional') }}" required
                                placeholder="ejemplo@sena.edu.co">
                        </div>
                        @error('correo_institucional')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div class="field">
                        <label class="label" for="contraseña">Contraseña nueva:</label>
                        <div class="control has-icons-right">
                            <input class="input @error('contraseña') is-danger @enderror" type="password"
                                id="contraseña" name="contraseña" required
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

                    <!-- Confirmación de Contraseña -->
                    <div class="field">
                        <label class="label" for="contraseña_confirmation">Confirmar contraseña nueva:</label>
                        <div class="control has-icons-right">
                            <input class="input @error('contraseña_confirmation') is-danger @enderror" type="password"
                                id="contraseña_confirmation" name="contraseña_confirmation" required
                                placeholder="Confirmar contraseña">
                            <span class="icon is-small is-right" style="pointer-events: all; cursor: pointer;"
                                onclick="togglePassword('contraseña_confirmation')">
                                <i class="fas fa-eye" id="contraseña_confirmation-icon"></i>
                            </span>
                        </div>
                        <p id="confirmarContrasenaError" class="help is-danger"></p>
                        @error('contraseña_confirmation')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div class="field">
                        <label class="label" for="telefono">Teléfono:</label>
                        <div class="control">
                            <input class="input @error('telefono') is-danger @enderror" 
                                   type="text" 
                                   id="telefono" 
                                   name="telefono" 
                                   required 
                                   maxlength="10" 
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10)"
                                   value="{{ old('telefono') }}"
                                   required
                                   placeholder="Ingresar número de teléfono">
                        </div>
                        <p class="help">Máximo 10 dígitos</p>
                        @error('telefono')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rol -->
                    <div class="field">
                        <label class="label" for="rol">Rol:</label>
                        <div class="control">
                            <div class="select @error('rol') is-danger @enderror">
                                <select id="rol" name="rol" required>
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

                    <!-- Número de Ficha -->
                    <div class="field" id="numeroFichaField">
                        <label class="label" for="numero_ficha">Número de Ficha:</label>
                        <div class="control">
                            <input class="input @error('numero_ficha') is-danger @enderror" type="text"
                                id="numero_ficha" name="numero_ficha" value="{{ old('numero_ficha') }}"
                                placeholder="Ingresar número de ficha">
                        </div>
                        @error('numero_ficha')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto de Perfil -->
                    <div class="field">
                        <label class="label" for="foto">Foto:</label>
                        <div class="control">
                            <input class="input @error('foto') is-danger @enderror" 
                                   type="file" 
                                   id="foto" 
                                   name="foto" 
                                   accept="image/jpeg,image/png,image/gif">
                        </div>
                        <p class="help">Formatos permitidos: JPG, JPEG, PNG, GIF. Tamaño máximo: 5MB</p>
                        @error('foto')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                        <div class="mt-2">
                            <img id="previewFoto" src="#" alt="Vista previa de la foto" 
                                 style="display: none; max-width: 200px; height: auto;">
                        </div>
                    </div>

                    <!-- Agregar antes del cierre del formulario -->
                    <div class="field">
                        <div class="control">
                            <label class="checkbox is-flex is-align-items-center">
                                <input type="checkbox" required name="accept_terms">
                                <span style="white-space: nowrap; margin-left: 0.5rem">
                                    He leído y acepto la: <a href="#" id="showPrivacyPolicy">Política de Privacidad de Datos</a>
                                </span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="buttons-container">
            <button class="button is-success" type="submit" form="registroForm">Registrar</button>
            <a href="{{ route('welcome') }}" class="button is-success">Volver a inicio</a>
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

    <!-- Agregar el modal de la política de privacidad -->
    <div class="modal" id="privacyPolicyModal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Política de Privacidad de Datos</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <div class="content">
                    <h4>Servicio Nacional de Aprendizaje (SENA) Regional Casanare</h4>
                    
                    <h5>1. Información que Recolectamos</h5>
                    <p>Recolectamos la siguiente información personal:</p>
                    <ul>
                        <li>Nombres y apellidos</li>
                        <li>Tipo y número de documento de identidad</li>
                        <li>Tipo de sangre (RH)</li>
                        <li>Correos electrónicos (personal e institucional)</li>
                        <li>Número telefónico</li>
                        <li>Número de ficha (para aprendices)</li>
                        <li>Fotografía de perfil</li>
                    </ul>

                    <h5>2. Uso de la Información</h5>
                    <p>La información recolectada será utilizada para:</p>
                    <ul>
                        <li>Gestión de usuarios en la plataforma institucional</li>
                        <li>Comunicación institucional</li>
                        <li>Procesos académicos y administrativos</li>
                        <li>Atención de emergencias (información médica)</li>
                    </ul>

                    <h5>3. Derechos del Usuario</h5>
                    <p>Como titular de los datos personales, usted tiene derecho a:</p>
                    <ul>
                        <li>Conocer, actualizar y rectificar sus datos personales</li>
                        <li>Solicitar la eliminación de sus datos</li>
                        <li>Revocar la autorización otorgada para el tratamiento de datos</li>
                        <li>Ser informado sobre el uso dado a sus datos</li>
                    </ul>

                    <h5>4. Seguridad</h5>
                    <p>El SENA implementa medidas de seguridad técnicas y administrativas para proteger su información personal.</p>

                    <h5>5. Contacto</h5>
                    <p>Para ejercer sus derechos o realizar consultas sobre el tratamiento de sus datos, puede contactarnos a través de:</p>
                    <ul>
                        <li>Correo electrónico: protecciondatos@sena.edu.co</li>
                        <li>Dirección: [Dirección de la sede Regional Casanare]</li>
                        <li>Teléfono: [Número de contacto]</li>
                    </ul>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success" id="closeModal">Entendido</button>
            </footer>
        </div>
    </div>

    <!-- Agregar el script para el modal -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('privacyPolicyModal');
        const showModal = document.getElementById('showPrivacyPolicy');
        const closeModal = document.getElementById('closeModal');
        const modalBackground = modal.querySelector('.modal-background');
        const modalDelete = modal.querySelector('.delete');

        function toggleModal() {
            modal.classList.toggle('is-active');
        }

        showModal.addEventListener('click', function(e) {
            e.preventDefault();
            toggleModal();
        });

        [closeModal, modalBackground, modalDelete].forEach(element => {
            element.addEventListener('click', toggleModal);
        });
    });
    </script>

    <!-- Agregar este script antes del cierre del body -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validación para nombres y apellidos (solo letras y espacios)
        const soloLetras = document.querySelectorAll('#nombres, #apellidos');
        soloLetras.forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
            });
        });

        // Validación para número de documento y teléfono (solo números)
        const soloNumeros = document.querySelectorAll('#numero_documento, #telefono');
        soloNumeros.forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });

        // Validación de contraseña
        const contraseña = document.getElementById('contraseña');
        const contraseñaConfirm = document.getElementById('contraseña_confirmation');
        const contraseñaError = document.getElementById('contraseaError');

        contraseña.addEventListener('input', function() {
            const value = this.value;
            let mensaje = [];

            if (value.length < 6) mensaje.push('Mínimo 6 caracteres');
            if (!/[A-Z]/.test(value)) mensaje.push('Al menos una mayúscula');
            if (!/[a-z]/.test(value)) mensaje.push('Al menos una minúscula');
            if (!/[0-9]/.test(value)) mensaje.push('Al menos un número');
            if (!/[!@#$%^&*]/.test(value)) mensaje.push('Al menos un símbolo (!@#$%^&*)');

            contraseñaError.textContent = mensaje.join(', ');
            contraseñaError.style.color = mensaje.length > 0 ? '#ff3860' : '#48c774';
        });

        // Validación de correos
        const correoPersonal = document.getElementById('correo_personal');
        const correoInstitucional = document.getElementById('correo_institucional');

        correoInstitucional.addEventListener('input', function() {
            if (!this.value.endsWith('@sena.edu.co')) {
                this.setCustomValidity('El correo debe terminar en @sena.edu.co');
            } else {
                this.setCustomValidity('');
            }
        });
    });
    </script>

    <script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '-icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    </script>

    <!-- Agregar este script para la vista previa de la imagen -->
    <script>
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('previewFoto');
        const maxSize = 5 * 1024 * 1024; // 5MB en bytes
        
        if (file) {
            // Verificar el tamaño del archivo
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'Archivo demasiado grande',
                    text: 'El archivo seleccionado pesa ' + (file.size / (1024 * 1024)).toFixed(2) + 'MB. El tamaño máximo permitido es 5MB.',
                    confirmButtonText: 'Entendido'
                });
                this.value = ''; // Limpiar el input
                preview.style.display = 'none';
                return;
            }

            // Verificar el tipo de archivo
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Formato no válido',
                    text: 'Por favor, seleccione un archivo en formato JPG, JPEG, PNG o GIF.',
                    confirmButtonText: 'Entendido'
                });
                this.value = '';
                preview.style.display = 'none';
                return;
            }

            // Mostrar vista previa
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
    </script>

    <!-- Validación adicional para nombres y apellidos -->
    <script>
    document.querySelectorAll('#nombres, #apellidos').forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
        });
    });
    </script>

</body>

</html>
