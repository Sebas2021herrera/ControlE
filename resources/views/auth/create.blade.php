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

                <form id="registroForm" method="POST" action="{{ route('createpost') }}">
                    @csrf <!-- Para Laravel CSRF Token -->
                    
                    <div class="field">
                        <label class="label" for="nombres">Nombres:</label>
                        <div class="control">
                            <input class="input @error('nombres') is-danger @enderror" type="text" id="nombres" name="nombres" value="{{ old('nombres') }}" required>
                        </div>
                        @error('nombres')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="label" for="apellidos">Apellidos:</label>
                        <div class="control">
                            <input class="input @error('apellidos') is-danger @enderror" type="text" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" required>
                        </div>
                        @error('apellidos')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="label" for="tipoDocumento">Tipo de Documento:</label>
                        <div class="control">
                            <div class="select @error('tipoDocumento') is-danger @enderror">
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
                        @error('tipoDocumento')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="label" for="numeroDocumento">Número de Documento:</label>
                        <div class="control">
                            <input class="input @error('numeroDocumento') is-danger @enderror" type="text" id="numeroDocumento" name="numeroDocumento" value="{{ old('numeroDocumento') }}" required>
                        </div>
                        @error('numeroDocumento')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="label" for="correo_personal">Correo Personal:</label>
                        <div class="control">
                            <input class="input @error('correo_personal') is-danger @enderror" type="email" id="correo_personal" name="correo_personal" value="{{ old('correo_personal') }}" required>
                        </div>
                        @error('correo_personal')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="label" for="correo_institucional">Correo Institucional:</label>
                        <div class="control">
                            <input class="input @error('correo_institucional') is-danger @enderror" type="email" id="correo_institucional" name="correo_institucional" value="{{ old('correo_institucional') }}" required>
                        </div>
                        @error('correo_institucional')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="label" for="contrasena">Contraseña:</label>
                        <div class="control">
                            <input class="input @error('contrasena') is-danger @enderror" type="password" id="contrasena" name="contrasena" required>
                        </div>
                        <p id="contrasenaError" class="help is-danger"></p>
                        @error('contrasena')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="label" for="contrasena_confirmation">Confirmar Contraseña:</label>
                        <div class="control">
                            <input class="input @error('contrasena_confirmation') is-danger @enderror" type="password" id="contrasena_confirmation" name="contrasena_confirmation" required>
                        </div>
                        <p id="confirmarContrasenaError" class="help is-danger"></p>
                        @error('contrasena_confirmation')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="label" for="telefono">Teléfono:</label>
                        <div class="control">
                            <input class="input @error('telefono') is-danger @enderror" type="tel" id="telefono" name="telefono" value="{{ old('telefono') }}" required>
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

                    <div class="field">
                        <label class="label" for="numeroFicha">Número de Ficha:</label>
                        <div class="control">
                            <input class="input @error('numeroFicha') is-danger @enderror" type="text" id="numeroFicha" name="numeroFicha" value="{{ old('numeroFicha') }}" required>
                        </div>
                        @error('numeroFicha')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
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
</body>
</html>
