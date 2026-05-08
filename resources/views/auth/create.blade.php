<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="has-footer-buttons">
    <div class="mode-toggle" id="modeToggle">
        <i class="fas fa-moon" id="modeIcon"></i>
        <span id="modeText">Modo Noche</span>
    </div>

    <div class="container d-flex flex-column align-items-center justify-content-center min-vh-100 py-5">
        <div class="logo-container mb-4 text-center">
            <img src="{{ asset('imagenes/logo-del-sena-01.png') }}" alt="SENA Logo" class="img-fluid logo-sena">
        </div>

        <div class="card custom-card shadow-sm" style="max-width: 480px; width: 100%;">
            <div class="card-header text-center font-weight-bold">
                REGISTRO DE USUARIOS
            </div>
            <div class="card-body card-body-scrollable">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="registroForm" method="POST" action="{{ route('createpost') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" class="form-control @error('nombres') is-invalid @enderror"
                               id="nombres" name="nombres" value="{{ old('nombres') }}"
                               required placeholder="Ingresar nombres">
                        @error('nombres') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control @error('apellidos') is-invalid @enderror"
                               id="apellidos" name="apellidos" value="{{ old('apellidos') }}"
                               required placeholder="Ingresar apellidos">
                        @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                        <select class="form-control @error('tipo_documento') is-invalid @enderror"
                                id="tipo_documento" name="tipo_documento" required>
                            <option value="" disabled selected>Seleccione un tipo</option>
                            <option value="CC" {{ old('tipo_documento') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                            <option value="TI" {{ old('tipo_documento') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                            <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                            <option value="PP" {{ old('tipo_documento') == 'PP' ? 'selected' : '' }}>Pasaporte</option>
                            <option value="RC" {{ old('tipo_documento') == 'RC' ? 'selected' : '' }}>Registro Civil</option>
                        </select>
                        @error('tipo_documento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="numero_documento" class="form-label">Número de Documento</label>
                        <input type="text" class="form-control @error('numero_documento') is-invalid @enderror"
                               id="numero_documento" name="numero_documento" required
                               maxlength="12"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 12)"
                               value="{{ old('numero_documento') }}"
                               placeholder="Ingresar número de documento">
                        <small class="form-text text-muted">Entre 6 y 12 dígitos</small>
                        @error('numero_documento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="rh" class="form-label">Tipo de Sangre (RH)</label>
                        <select class="form-control @error('rh') is-invalid @enderror"
                                id="rh" name="rh" required>
                            <option value="" disabled selected>Seleccione su tipo de sangre</option>
                            <option value="O+"  {{ old('rh') == 'O+'  ? 'selected' : '' }}>O+</option>
                            <option value="O-"  {{ old('rh') == 'O-'  ? 'selected' : '' }}>O-</option>
                            <option value="A+"  {{ old('rh') == 'A+'  ? 'selected' : '' }}>A+</option>
                            <option value="A-"  {{ old('rh') == 'A-'  ? 'selected' : '' }}>A-</option>
                            <option value="B+"  {{ old('rh') == 'B+'  ? 'selected' : '' }}>B+</option>
                            <option value="B-"  {{ old('rh') == 'B-'  ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('rh') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('rh') == 'AB-' ? 'selected' : '' }}>AB-</option>
                        </select>
                        @error('rh') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="correo_personal" class="form-label">Correo Personal</label>
                        <input type="email" class="form-control @error('correo_personal') is-invalid @enderror"
                               id="correo_personal" name="correo_personal"
                               value="{{ old('correo_personal') }}"
                               required placeholder="ejemplo@correo.com">
                        @error('correo_personal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="correo_institucional" class="form-label">Correo Institucional</label>
                        <input type="email" class="form-control @error('correo_institucional') is-invalid @enderror"
                               id="correo_institucional" name="correo_institucional"
                               value="{{ old('correo_institucional') }}"
                               required placeholder="ejemplo@sena.edu.co">
                        @error('correo_institucional') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="contraseña" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('contraseña') is-invalid @enderror"
                                   id="contraseña" name="contraseña" required
                                   placeholder="Mínimo 6 caracteres">
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="togglePassword('contraseña')" style="cursor:pointer;">
                                    <i class="fas fa-eye" id="contraseña-icon"></i>
                                </span>
                            </div>
                        </div>
                        <small id="contraseaError" class="form-text" style="color:#dc3545;"></small>
                        @error('contraseña') <div class="text-danger" style="font-size:.875rem;">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="contraseña_confirmation" class="form-label">Confirmar Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control"
                                   id="contraseña_confirmation" name="contraseña_confirmation" required
                                   placeholder="Repetir contraseña">
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="togglePassword('contraseña_confirmation')" style="cursor:pointer;">
                                    <i class="fas fa-eye" id="contraseña_confirmation-icon"></i>
                                </span>
                            </div>
                        </div>
                        <small id="confirmarContrasenaError" class="form-text" style="color:#dc3545;"></small>
                    </div>

                    <div class="form-group">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                               id="telefono" name="telefono" required
                               maxlength="10"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10)"
                               value="{{ old('telefono') }}"
                               placeholder="Ingresar número de teléfono">
                        <small class="form-text text-muted">Máximo 10 dígitos</small>
                        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="rol" class="form-label">Rol</label>
                        <select class="form-control @error('rol') is-invalid @enderror"
                                id="rol" name="rol" required>
                            <option value="3" {{ old('rol') == 3 ? 'selected' : '' }}>Aprendiz</option>
                            <option value="4" {{ old('rol') == 4 ? 'selected' : '' }}>Visitante</option>
                            <option value="5" {{ old('rol') == 5 ? 'selected' : '' }}>Funcionario</option>
                        </select>
                        @error('rol') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" id="numeroFichaField">
                        <label for="numero_ficha" class="form-label">Número de Ficha</label>
                        <input type="text" class="form-control @error('numero_ficha') is-invalid @enderror"
                               id="numero_ficha" name="numero_ficha"
                               value="{{ old('numero_ficha') }}"
                               placeholder="Ingresar número de ficha">
                        @error('numero_ficha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="foto" class="form-label">Foto de Perfil</label>
                        <input type="file" class="form-control-file @error('foto') is-invalid @enderror"
                               id="foto" name="foto"
                               accept="image/jpeg,image/png,image/gif">
                        <small class="form-text text-muted">JPG, PNG o GIF. Máximo 5MB</small>
                        @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="mt-2">
                            <img id="previewFoto" src="#" alt="Vista previa"
                                 style="display:none; max-width:150px; border-radius:4px; border:1px solid #dee2e6;">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" required
                                   name="accept_terms" id="accept_terms">
                            <label class="form-check-label" for="accept_terms">
                                He leído y acepto la
                                <a href="#" id="showPrivacyPolicy">Política de Privacidad de Datos</a>
                            </label>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <p class="text-center footer-text mt-4">
            <img src="{{ asset('imagenes/logo_copyrigth.png') }}" class="bombilla">
            Derechos reservados al SENA Regional Casanare, 2024.
        </p>
    </div>

    <!-- Botones fijos en la parte inferior -->
    <div class="buttons-footer">
        <button class="btn btn-dark" type="submit" form="registroForm">
            <i class="fas fa-user-plus mr-1"></i> Registrar
        </button>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Volver al inicio
        </a>
    </div>

    <!-- Modal Política de Privacidad -->
    <div class="modal fade" id="privacyPolicyModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Política de Privacidad de Datos</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Servicio Nacional de Aprendizaje (SENA) Regional Casanare</h6>

                    <h6 class="mt-3">1. Información que Recolectamos</h6>
                    <ul>
                        <li>Nombres y apellidos</li>
                        <li>Tipo y número de documento de identidad</li>
                        <li>Tipo de sangre (RH)</li>
                        <li>Correos electrónicos (personal e institucional)</li>
                        <li>Número telefónico</li>
                        <li>Número de ficha (para aprendices)</li>
                        <li>Fotografía de perfil</li>
                    </ul>

                    <h6 class="mt-3">2. Uso de la Información</h6>
                    <ul>
                        <li>Gestión de usuarios en la plataforma institucional</li>
                        <li>Comunicación institucional</li>
                        <li>Procesos académicos y administrativos</li>
                        <li>Atención de emergencias (información médica)</li>
                    </ul>

                    <h6 class="mt-3">3. Derechos del Usuario</h6>
                    <ul>
                        <li>Conocer, actualizar y rectificar sus datos personales</li>
                        <li>Solicitar la eliminación de sus datos</li>
                        <li>Revocar la autorización otorgada</li>
                        <li>Ser informado sobre el uso dado a sus datos</li>
                    </ul>

                    <h6 class="mt-3">4. Seguridad</h6>
                    <p>El SENA implementa medidas de seguridad técnicas y administrativas para proteger su información personal.</p>

                    <h6 class="mt-3">5. Contacto</h6>
                    <ul>
                        <li>Correo: protecciondatos@sena.edu.co</li>
                        <li>Dirección: Sede Regional Casanare</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Modo noche
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const modeToggle = document.getElementById('modeToggle');
            const modeIcon = document.getElementById('modeIcon');
            const modeText = document.getElementById('modeText');

            if (localStorage.getItem('dark-mode') === 'true') {
                body.classList.add('dark-mode');
                modeIcon.classList.replace('fa-moon', 'fa-sun');
                modeText.textContent = 'Modo Claro';
            }

            modeToggle.addEventListener('click', () => {
                body.classList.toggle('dark-mode');
                const isDarkMode = body.classList.contains('dark-mode');
                localStorage.setItem('dark-mode', isDarkMode);
                if (isDarkMode) {
                    modeIcon.classList.replace('fa-moon', 'fa-sun');
                    modeText.textContent = 'Modo Claro';
                } else {
                    modeIcon.classList.replace('fa-sun', 'fa-moon');
                    modeText.textContent = 'Modo Noche';
                }
            });
        });

        // Ver/ocultar contraseña
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {

            // Modal política de privacidad
            document.getElementById('showPrivacyPolicy').addEventListener('click', function (e) {
                e.preventDefault();
                $('#privacyPolicyModal').modal('show');
            });

            // Solo letras en nombres y apellidos
            document.querySelectorAll('#nombres, #apellidos').forEach(input => {
                input.addEventListener('input', function () {
                    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
                });
            });

            // Validación de contraseña en tiempo real
            const contraseña = document.getElementById('contraseña');
            const contraseñaError = document.getElementById('contraseaError');

            contraseña.addEventListener('input', function () {
                const v = this.value;
                let msgs = [];
                if (v.length < 6)        msgs.push('Mínimo 6 caracteres');
                if (!/[A-Z]/.test(v))    msgs.push('una mayúscula');
                if (!/[a-z]/.test(v))    msgs.push('una minúscula');
                if (!/[0-9]/.test(v))    msgs.push('un número');
                if (!/[!@#$%^&*]/.test(v)) msgs.push('un símbolo (!@#$%^&*)');
                contraseñaError.textContent = msgs.length ? 'Falta: ' + msgs.join(', ') : '';
                contraseñaError.style.color = msgs.length ? '#dc3545' : '#28a745';
            });

            // Validación correo institucional
            document.getElementById('correo_institucional').addEventListener('input', function () {
                const validDomains = ['@sena.edu.co', '@soy.sena.edu.co'];
                this.setCustomValidity(
                    validDomains.some(d => this.value.endsWith(d))
                        ? ''
                        : 'El correo debe terminar en @sena.edu.co o @soy.sena.edu.co'
                );
            });

            // Toggle número de ficha según rol
            const rolSelect  = document.getElementById('rol');
            const fichaField = document.getElementById('numero_ficha');
            const fichaDiv   = document.getElementById('numeroFichaField');

            function toggleFicha() {
                const esAprendiz = rolSelect.value == 3;
                fichaDiv.style.display = esAprendiz ? 'block' : 'none';
                esAprendiz
                    ? fichaField.setAttribute('required', 'required')
                    : fichaField.removeAttribute('required');
            }
            rolSelect.addEventListener('change', toggleFicha);
            toggleFicha();

            // Vista previa foto
            document.getElementById('foto').addEventListener('change', function (e) {
                const file    = e.target.files[0];
                const preview = document.getElementById('previewFoto');
                const maxSize = 5 * 1024 * 1024;

                if (!file) { preview.style.display = 'none'; return; }

                if (file.size > maxSize) {
                    Swal.fire({ icon: 'error', title: 'Archivo demasiado grande',
                        text: `El archivo pesa ${(file.size/(1024*1024)).toFixed(2)} MB. Máximo: 5 MB.`,
                        confirmButtonText: 'Entendido' });
                    this.value = '';
                    preview.style.display = 'none';
                    return;
                }
                if (!['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
                    Swal.fire({ icon: 'error', title: 'Formato no válido',
                        text: 'Seleccione un archivo JPG, PNG o GIF.',
                        confirmButtonText: 'Entendido' });
                    this.value = '';
                    preview.style.display = 'none';
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
                reader.readAsDataURL(file);
            });
        });

        // Validación al enviar
        document.getElementById('registroForm').addEventListener('submit', function (event) {
            const contrasena         = document.getElementById('contraseña').value;
            const confirmar          = document.getElementById('contraseña_confirmation').value;
            const errorPass          = document.getElementById('contraseaError');
            const errorConfirmar     = document.getElementById('confirmarContrasenaError');

            errorPass.textContent = '';
            errorConfirmar.textContent = '';

            const errores = [];
            if (contrasena.length < 6)          errores.push('Mínimo 6 caracteres');
            if (!/[A-Z]/.test(contrasena))       errores.push('Al menos una mayúscula');
            if (!/[a-z]/.test(contrasena))       errores.push('Al menos una minúscula');
            if (!/[0-9]/.test(contrasena))       errores.push('Al menos un número');
            if (!/[!@#$%^&*]/.test(contrasena))  errores.push('Al menos un símbolo (!@#$%^&*)');

            if (errores.length) { errorPass.textContent = errores.join(', '); event.preventDefault(); return; }
            if (contrasena !== confirmar) { errorConfirmar.textContent = 'Las contraseñas no coinciden.'; event.preventDefault(); }
        });
    </script>
</body>

</html>
