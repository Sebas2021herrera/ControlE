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
            <a href="#" id="registerLink" class="registrar">Registrar usuarios</a>
            <nav class="nav">
                <ul class="nav-list">
                    <li><a href="#">Inicio</a></li>
                    <li><a href="#">Acerca de</a></li>
                    <li><a href="#">Servicios</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <!-- Modal -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <div class="ventana-formulario">
        <h2>Registro de usuarios</h2>
        <form>
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
                            <option value="3" {{ old('rol') == 1 ? 'selected' : '' }}>Administrador</option>
                            <option value="3" {{ old('rol') == 2 ? 'selected' : '' }}>Control</option>
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
    <script>
      // Selección de elementos
const registerLink = document.getElementById('registerLink');
const modal = document.getElementById('registerModal');
const closeBtn = document.querySelector('.close-btn');

// Mostrar el modal al hacer clic en "Registro"
registerLink.addEventListener('click', (event) => {
    event.preventDefault(); // Evita el comportamiento por defecto del enlace
    modal.style.display = 'block';
});

// Cerrar el modal al hacer clic en la "X"
closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
});

// Cerrar el modal al hacer clic fuera del contenido del modal
window.addEventListener('click', (event) => {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});
    </script>
</body>
</html>