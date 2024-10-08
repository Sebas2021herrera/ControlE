<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles_formulario_elementos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles_vistausuario.css') }}">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Control E</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ Auth::user()->nombres }}</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Opciones
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#registroModal">Registrar Elementos</a></li>

                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#editarPerfilModal">Editar Perfil</a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Cerrar Sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Bloque para mostrar mensajes de éxito -->
    @if (session('success'))
        <div id="success-message" class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <!-- Bloque para mostrar mensajes de error -->
    @if (session('error'))
        <div id="error-message" class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="container-fluid mt-4">
        <div class="left-panel">
            <div class="welcome-text" id="welcomeMessage">
                Bienvenido <br />{{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}
            </div>
            <!-- Mostrar la foto de perfil -->
            <div class="image-container" style="margin-top: 15px;">
                @if (isset($usuario) && $usuario->foto && file_exists(storage_path('app/public/fotos_perfil/' . $usuario->foto)))
                    <img id="left-panel-img" src="{{ asset('storage/fotos_perfil/' . $usuario->foto) }}"
                        alt="Foto de perfil" class="foto-perfil"
                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 20px;">
                @else
                    <img id="left-panel-img" src="{{ asset('imagenes/sin_foto_perfil.webp') }}"
                        alt="Foto de perfil predeterminada" class="foto-perfil"
                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 20px;">
                @endif
            </div>

            <div class="other-welcome">
                <br /><strong>Estos son tus elementos:</strong>
            </div>
        </div>
        <!--cards de los elementos-->
        <div class="right-panel">
            @foreach ($elementos as $elemento)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><strong>{{ $elemento->categoria->nombre }}</strong></h5>
                        @if ($elemento->foto)
                            <img src="{{ asset('storage/' . $elemento->foto) }}" alt="Foto del elemento"
                                class="img-fluid mt-3">
                        @endif
                        <p class="card-text"><strong>Serial:</strong> {{ $elemento->serie }}</p>
                        <p class="card-text"><strong>Marca:</strong> {{ $elemento->marca }}</p>
                        <a href="#" class="btn btn-link" data-bs-toggle="modal"
                            data-bs-target="#modal-{{ $elemento->id }}">
                            Ver más
                        </a>
                    </div>
                </div>

                <!-- Modal para ver más detalles y editar -->
                <div class="modal fade" id="modal-{{ $elemento->id }}" tabindex="-1"
                    aria-labelledby="modalLabel-{{ $elemento->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel-{{ $elemento->id }}">Detalles del Elemento</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Vista de detalles -->
                                <div id="details-view-{{ $elemento->id }}" class="details-view">
                                    @if ($elemento->foto)
                                        <img src="{{ asset('storage/' . $elemento->foto) }}" alt="Foto del elemento"
                                            class="img-fluid mb-3">
                                    @endif
                                    <p><strong>Categoría:</strong> {{ $elemento->categoria->nombre }}</p>
                                    <p><strong>Descripción:</strong> {{ $elemento->descripcion }}</p>
                                    <p><strong>Marca:</strong> {{ $elemento->marca }}</p>
                                    <p><strong>Modelo:</strong> {{ $elemento->modelo }}</p>
                                    <p><strong>Serial:</strong> {{ $elemento->serie }}</p>
                                    <p><strong>Especificaciones Técnicas:</strong>
                                        {{ $elemento->especificaciones_tecnicas }}</p>
                                </div>

                                <!-- Vista de edición (oculta por defecto) -->
                                <div id="edit-view-{{ $elemento->id }}" class="edit-view d-none">
                                    <form action="{{ route('elementos.update', $elemento->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="categoria_id-{{ $elemento->id }}"
                                                class="form-label">Categoría</label>
                                            <select id="categoria_id-{{ $elemento->id }}" name="categoria_id"
                                                class="form-select" required>
                                                @foreach ($categorias as $categoria)
                                                    <option value="{{ $categoria->id }}"
                                                        {{ $categoria->id == $elemento->categoria_id ? 'selected' : '' }}>
                                                        {{ $categoria->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="descripcion-{{ $elemento->id }}"
                                                class="form-label">Descripción</label>
                                            <input type="text" id="descripcion-{{ $elemento->id }}"
                                                name="descripcion" class="form-control"
                                                value="{{ $elemento->descripcion }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="marca-{{ $elemento->id }}" class="form-label">Marca</label>
                                            <input type="text" id="marca-{{ $elemento->id }}" name="marca"
                                                class="form-control" value="{{ $elemento->marca }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="modelo-{{ $elemento->id }}" class="form-label">Modelo</label>
                                            <input type="text" id="modelo-{{ $elemento->id }}" name="modelo"
                                                class="form-control" value="{{ $elemento->modelo }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="serie-{{ $elemento->id }}" class="form-label">Número de
                                                Serie</label>
                                            <input type="text" id="serie-{{ $elemento->id }}" name="serie"
                                                class="form-control" value="{{ $elemento->serie }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="especificaciones_tecnicas-{{ $elemento->id }}"
                                                class="form-label">Especificaciones Técnicas</label>
                                            <textarea id="especificaciones_tecnicas-{{ $elemento->id }}" name="especificaciones_tecnicas" class="form-control"
                                                rows="3" required>{{ $elemento->especificaciones_tecnicas }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="foto-{{ $elemento->id }}" class="form-label">Foto</label>
                                            <div class="mb-3">
                                                <label for="foto-{{ $elemento->id }}" class="form-label">Foto del
                                                    Elemento</label>
                                                <input type="file" id="foto-{{ $elemento->id }}" name="foto"
                                                    class="form-control" accept="image/*"
                                                    onchange="previewImage(event, 'previewImagen-{{ $elemento->id }}')">
                                                <img id="previewImagen-{{ $elemento->id }}"
                                                    src="{{ asset('storage/' . $elemento->foto) }}"
                                                    alt="Foto del elemento" class="img-fluid mt-3">

                                            </div>

                                        </div>

                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Botón para eliminar elemento -->
                                <form action="{{ route('elementos.destroy', $elemento->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                                <!-- Botón para guardar cambios (oculto por defecto) -->
                                <button type="button" class="btn btn-primary d-none"
                                    id="save-changes-btn-{{ $elemento->id }}"
                                    onclick="saveChanges({{ $elemento->id }})">Guardar Cambios</button>
                                <!-- Botón para editar elementos -->
                                <button type="button" class="btn btn-warning"
                                    onclick="editElement({{ $elemento->id }})">Editar</button>
                                <!-- Botón para cerrar el modal -->
                                <button type="button" class="btn btn-secondary"
                                    onclick="closeModal({{ $elemento->id }})">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para registrar elementos -->
    <div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registroModalLabel">Registrar Elementos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('elementos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
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
    </div>

    <!-- Modal para editar perfil -->
    <div class="modal fade" id="editarPerfilModal" tabindex="-1" aria-labelledby="editarPerfilModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarPerfilModalLabel">Editar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editarPerfilForm" method="POST" action="{{ route('updateProfile') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nombres" class="form-label">Nombres:</label>
                            <input type="text" id="nombres" name="nombres" class="form-control"
                                value="{{ Auth::user()->nombres }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellidos" class="form-label">Apellidos:</label>
                            <input type="text" id="apellidos" name="apellidos" class="form-control"
                                value="{{ Auth::user()->apellidos }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_documento" class="form-label">Tipo de Documento:</label>
                            <select id="tipo_documento" name="tipo_documento" class="form-select" required>
                                <option value="CC" {{ Auth::user()->tipo_documento == 'CC' ? 'selected' : '' }}>
                                    Cédula de Ciudadanía</option>
                                <option value="TI" {{ Auth::user()->tipo_documento == 'TI' ? 'selected' : '' }}>
                                    Tarjeta de Identidad</option>
                                <option value="CE" {{ Auth::user()->tipo_documento == 'CE' ? 'selected' : '' }}>
                                    Cédula de Extranjería</option>
                                <option value="PP" {{ Auth::user()->tipo_documento == 'PP' ? 'selected' : '' }}>
                                    Pasaporte</option>
                                <option value="RC" {{ Auth::user()->tipo_documento == 'RC' ? 'selected' : '' }}>
                                    Registro Civil</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="numero_documento" class="form-label">Número de Documento:</label>
                            <input type="text" id="numero_documento" name="numero_documento" class="form-control"
                                value="{{ Auth::user()->numero_documento }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="rh" class="form-label">Tipo de Sangre (RH):</label>
                            <input type="text" id="rh" name="rh" class="form-control"
                                value="{{ Auth::user()->rh }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="tel" id="telefono" name="telefono" class="form-control"
                                value="{{ Auth::user()->telefono }}" required>
                        </div>

                        @if (Auth::check() && Auth::user()->roles_id == 3)
                            <div class="mb-3" id="numeroFichaFieldModal">
                                <label for="numero_ficha" class="form-label">Número de Ficha:</label>
                                <input type="text" id="numero_ficha_modal" name="numero_ficha"
                                    class="form-control" value="{{ Auth::user()->numero_ficha }}">
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto de Perfil:</label>
                            <!-- Contenedor para la previsualización de la foto -->
                            <div class="mb-3">
                                @if (Auth::user()->foto && file_exists(storage_path('app/public/fotos_perfil/' . Auth::user()->foto)))
                                    <img id="previewPerfil"
                                        src="{{ asset('storage/fotos_perfil/' . Auth::user()->foto) }}"
                                        alt="Foto de perfil actual" style="max-width: 100px; height: auto;">
                                @else
                                    <img id="previewPerfil" src="{{ asset('imagenes/sin_foto_perfil.webp') }}"
                                        alt="Foto de perfil predeterminada" style="max-width: 100px; height: auto;">
                                @endif
                            </div>
                            <input type="file" id="foto" name="foto" class="form-control"
                                accept="image/*" onchange="previewImage(event, 'previewPerfil')">
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


    <!-- mostrar ficha solo si es roll aprendiz -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const numeroFichaField = document.getElementById('numeroFichaFieldModal');
            const numeroFichaInput = document.getElementById('numero_ficha_modal');

            if (numeroFichaField) {
                numeroFichaField.style.display = 'block';
                numeroFichaInput.required = true;
            } else {
                numeroFichaField.style.display = 'none';
                numeroFichaInput.required = false;
            }
        });
    </script>

    <script>
        function editElement(id) {
            document.getElementById('details-view-' + id).classList.add('d-none');
            document.getElementById('edit-view-' + id).classList.remove('d-none');
            document.getElementById('save-changes-btn-' + id).classList.remove('d-none');
        }

        function saveChanges(id) {
            document.getElementById('edit-view-' + id).querySelector('form').submit();
        }

        function closeModal(id) {
            const editView = document.getElementById('edit-view-' + id);
            const detailsView = document.getElementById('details-view-' + id);
            const saveChangesBtn = document.getElementById('save-changes-btn-' + id);

            if (!editView.classList.contains('d-none')) {
                // Cambia a la vista de detalles si se está en la vista de edición
                editView.classList.add('d-none');
                detailsView.classList.remove('d-none');
                saveChangesBtn.classList.add('d-none');
            } else {
                // Cierra el modal si ya está en la vista de detalles
                document.querySelector(`#modal-${id} .btn-close`).click();
            }
        }
    </script>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#editarPerfilForm').on('submit', function(event) {
                event.preventDefault(); // Evita el envío tradicional del formulario

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // Actualiza la vista con la nueva información del usuario
                        updateUserProfile(response.user);

                        // Cierra el modal
                        $('#editarPerfilModal').modal('hide');

                        // Muestra un mensaje de éxito si es necesario
                        $('#success-message').text(response.success).fadeIn().delay(5000)
                            .fadeOut();
                    },
                    error: function(response) {
                        // Muestra un mensaje de error si es necesario
                        $('#error-message').text('Ocurrió un error al actualizar el perfil.')
                            .fadeIn().delay(5000).fadeOut();
                    }
                });
            });

            function updateUserProfile(user) {
                $('#welcomeMessage').html(`Bienvenido <br />${user.nombres} ${user.apellidos}`);
                $('.navbar-nav .nav-link').text(user.nombres);
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#editarPerfilForm').on('submit', function(event) {
                event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

                $.ajax({
                    url: '{{ route('updateProfile') }}', // Cambia esta URL si es diferente
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.success); // Muestra mensaje de éxito
                        $('#editarPerfilModal').modal('hide'); // Cierra el modal
                        // Actualiza la vista si es necesario
                        updateUserProfile(response.user);
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.error); // Muestra mensaje de error
                    }
                });
            });

            function updateUserProfile(user) {
                // Actualiza los elementos en la vista con la información del usuario
                // Por ejemplo:
                $('#userName').text(user.nombres + ' ' + user.apellidos);
                $('#userEmail').text(user.correo_institucional);
                // Añade más actualizaciones según sea necesario
            }
        });
    </script>

    <script>
        document.getElementById('editarPerfilForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('{{ route('updateProfile') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Forzar recarga de la página
                        window.location.reload();
                    } else {
                        alert('Hubo un error al actualizar el perfil.');
                    }
                })
                .catch(error => {
                    console.error('Error al actualizar el perfil:', error);
                    alert('Hubo un error al actualizar el perfil.');
                });
        });
    </script>
    <!-- ver imagen en el editar perfil y editar elemento -->
    <script>
        function previewImage(event, previewId) {
            var file = event.target.files[0];
            var preview = document.getElementById(previewId);

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    console.log("Nueva foto de perfil seleccionada: ", e.target.result); // Log para la nueva imagen
                };
                reader.readAsDataURL(file);
            } else {
                // Reestablecer la imagen a la original si no se selecciona un archivo
                var originalPhotoUrl =
                    '{{ Auth::user()->foto ? asset('storage/fotos_perfil/' . Auth::user()->foto) : asset('imagenes/sin_foto_perfil.webp') }}';
                preview.src = originalPhotoUrl;
                console.log("Foto de perfil actual o predeterminada: ", originalPhotoUrl); // Log para la foto actual
            }
        }
    </script>

    <!-- previsualziar la foto en registrar un nuevo elemento -->
    <script>
        function previewImage(event, previewId) {
            var file = event.target.files[0];
            var preview = document.getElementById(previewId);

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // Asegúrate de que la imagen sea visible
                    console.log("Imagen seleccionada: ", e.target.result); // Log para la nueva imagen
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '#'; // Ocultar imagen si no se selecciona archivo
                preview.style.display = 'none';
            }
        }
    </script>


</body>

</html>
