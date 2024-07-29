<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css"> <!-- Enlaza el archivo CSS de Bulma -->
    <link rel="stylesheet" href="{{ asset('css/styles_create.css') }}"> <!-- Enlaza el archivo CSS personalizado -->
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <h2 class="title is-4">Registro Usuarios</h2>
                <form id="registroForm" method="POST" action="{{ route('createpost') }}">
                    @csrf <!-- Para Laravel CSRF Token -->
                    
                    <div class="field">
                        <label class="label" for="nombres">Nombres:</label>
                        <div class="control">
                            <input class="input" type="text" id="nombres" name="nombres" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label" for="apellidos">Apellidos:</label>
                        <div class="control">
                            <input class="input" type="text" id="apellidos" name="apellidos" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label" for="tipoDocumento">Tipo de Documento:</label>
                        <div class="control">
                            <div class="select">
                                <select id="tipoDocumento" name="tipoDocumento" required>
                                    <option value="" disabled selected>Seleccione un tipo de documento</option>
                                    <option value="CC">Cédula de Ciudadanía</option>
                                    <option value="TI">Tarjeta de Identidad</option>
                                    <option value="CE">Cédula de Extranjería</option>
                                    <option value="PP">Pasaporte</option>
                                    <option value="RC">Registro Civil</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label" for="numeroDocumento">Número de Documento:</label>
                        <div class="control">
                            <input class="input" type="text" id="numeroDocumento" name="numeroDocumento" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label" for="correo_personal">Correo Personal:</label>
                        <div class="control">
                            <input class="input" type="email" id="correo_personal" name="correo_personal" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label" for="correo_institucional">Correo Institucional:</label>
                        <div class="control">
                            <input class="input" type="email" id="correo_institucional" name="correo_institucional" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label" for="contrasena">Contraseña:</label>
                        <div class="control">
                            <input class="input" type="password" id="contrasena" name="contrasena" required>
                        </div>
                        <p id="contrasenaError" class="help is-danger"></p>
                    </div>

                    <div class="field">
                        <label class="label" for="contrasena_confirmation">Confirmar Contraseña:</label>
                        <div class="control">
                            <input class="input" type="password" id="contrasena_confirmation" name="contrasena_confirmation" required>
                        </div>
                        <p id="confirmarContrasenaError" class="help is-danger"></p>
                    </div>

                    <div class="field">
                        <label class="label" for="telefono">Teléfono:</label>
                        <div class="control">
                            <input class="input" type="tel" id="telefono" name="telefono" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label" for="rol">Rol:</label>
                        <div class="control">
                            <div class="select">
                                <select id="rol" name="rol" required>
                                    <option value="3">Aprendiz</option>
                                    <option value="4">Visitante</option>
                                    <option value="5">Funcionario</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label" for="numeroFicha">Número de Ficha:</label>
                        <div class="control">
                            <input class="input" type="text" id="numeroFicha" name="numeroFicha" required>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <button class="button is-primary" type="submit">Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
</body>
</html>
