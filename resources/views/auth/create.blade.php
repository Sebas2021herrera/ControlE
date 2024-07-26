
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles_create.css') }}"> <!-- Enlaza el archivo CSS -->
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Registro Usuarios</h2>
            <form id="registroForm" method="POST" action="{{ route('createpost') }}">
                @csrf <!-- Para Laravel CSRF Token -->
                <label for="nombres">Nombres:</label>
                <input type="text" id="nombres" name="nombres" required>

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" required>

                <label for="tipoDocumento">Tipo de Documento:</label>
                <input type="text" id="tipoDocumento" name="tipoDocumento" required>

                <label for="numeroDocumento">Número de Documento:</label>
                <input type="text" id="numeroDocumento" name="numeroDocumento" required>

                <label for="correoPersonal">Correo Personal:</label>
                <input type="email" id="correo_personal" name="correo_personal" required>

                <label for="correoInstitucional">Correo Institucional:</label>
                <input type="email" id="correo_institucional" name="correo_institucional" required>

                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
                <div id="contrasenaError" class="error"></div>

                <label for="contrasena_confirmation">Confirmar Contraseña:</label>
                <input type="password" id="contrasena_confirmation" name="contrasena_confirmation" required>
                <div id="confirmarContrasenaError" class="error"></div>

                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required>

                <label for="rol">Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="3">Aprendiz</option>
                    <option value="4">Visitante</option>
                    <option value="5">Funcionario</option>
                </select>

                <label for="numeroFicha">Número de Ficha:</label>
                <input type="text" id="numeroFicha" name="numeroFicha" required>

                <button type="submit">Registrar</button>
            </form>
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
