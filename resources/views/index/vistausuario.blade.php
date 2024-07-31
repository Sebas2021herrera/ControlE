<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Elementos</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles_formulario_elementos.css') }}">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow">
            <h2 class="text-center mb-4">Registro de Elementos</h2>
            <form action="{{ route('elementos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Campo Categoría -->
                <div class="mb-3">
                    <label for="categoria" class="form-label"><strong>Categoría</strong></label>
                    <select name="categoria" id="categoria" class="form-select" aria-label="Seleccionar categoría" required>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Campo Descripción -->
                <div class="mb-3">
                    <label for="descripcion" class="form-label"><strong>Descripción</strong></label>
                    <input type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Ingrese una descripción" required>
                </div>

                <!-- Campo Marca -->
                <div class="mb-3">
                    <label for="marca" class="form-label"><strong>Marca</strong></label>
                    <input type="text" name="marca" id="marca" class="form-control" placeholder="Ingrese la marca" required>
                </div>

                <!-- Campo Modelo -->
                <div class="mb-3 position-relative">
                    <div class="d-flex align-items-center">
                        <label for="modelo" class="form-label mb-0"><strong>Modelo</strong></label>
                        <!-- Enlace de ayuda al lado del campo Modelo -->
                        <a href="#" class="ms-2" data-bs-toggle="modal" data-bs-target="#modeloHelpModal">
                            Ayuda
                        </a>
                    </div>
                    <input type="text" name="modelo" id="modelo" class="form-control mt-2" placeholder="Ingrese el modelo" required>
                    <!-- Modal -->
                    <div class="modal fade" id="modeloHelpModal" tabindex="-1" aria-labelledby="modeloHelpModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modeloHelpModalLabel">Información del Campo Modelo</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Ingresa el modelo del elemento. Este campo es obligatorio.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campo Serie -->
                <div class="mb-3">
                    <label for="serie" class="form-label"><strong>Serie</strong></label>
                    <input type="text" name="serie" id="serie" class="form-control" placeholder="Ingrese el número de serie (opcional)">
                </div>

                <!-- Campo Especificaciones Técnicas -->
                <div class="mb-3">
                    <label for="especificaciones_tecnicas" class="form-label"><strong>Especificaciones Técnicas</strong></label>
                    <textarea name="especificaciones_tecnicas" id="especificaciones_tecnicas" class="form-control" placeholder="Ingrese especificaciones técnicas (opcional)"></textarea>
                </div>

                <!-- Campo Foto -->
                <div class="mb-3">
                    <label for="foto" class="form-label"><strong>Foto</strong></label>
                    <input type="file" name="foto" id="foto" class="form-control" accept="image/*" onchange="previewImage(event)">
                    <small id="fotoHelp" class="form-text text-muted">Cargar una imagen del elemento (opcional, máximo 5MB).</small>
                    <div class="image-preview mt-3">
                        <img id="imagePreview" src="#" alt="Vista previa de la imagen" style="display: none;">
                    </div>
                </div>

                <!-- Botón de Enviar -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para previsualizar la imagen cargada
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                // Validar el tamaño del archivo
                if (file.size > 5242880) { // 5MB en bytes
                    alert('El tamaño del archivo debe ser menor o igual a 5MB.');
                    event.target.value = ''; // Limpiar el archivo seleccionado
                    preview.style.display = 'none'; // Ocultar la vista previa
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // Mostrar la vista previa
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none'; // Ocultar la vista previa si no hay archivo
            }
        }
    </script>
</body>
</html>
