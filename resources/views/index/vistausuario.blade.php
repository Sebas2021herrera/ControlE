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

                <!-- Agrupación de campos -->
                <div class="mb-4">
                    <!-- Campo Categoría -->
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select name="categoria" id="categoria" class="form-select" aria-label="Seleccionar categoría" required>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}"><strong>{{ $categoria->nombre }}</strong></option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <input type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Ingrese una descripción" required aria-describedby="descripcionHelp">
                        <small id="descripcionHelp" class="form-text text-muted">Descripción del elemento (requerido).</small>
                    </div>

                    <!-- Campo Marca -->
                    <div class="mb-3">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" name="marca" id="marca" class="form-control" placeholder="Ingrese la marca" required aria-describedby="marcaHelp">
                        <small id="marcaHelp" class="form-text text-muted">Marca del elemento (requerido).</small>
                    </div>

                    <!-- Campo Modelo con botón de ayuda -->
                    <div class="mb-3 d-flex align-items-center position-relative">
                        <div class="me-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" name="modelo" id="modelo" class="form-control" placeholder="Ingrese el modelo" required aria-describedby="modeloHelp">
                            <small id="modeloHelp" class="form-text text-muted">Modelo del elemento (requerido).</small>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modeloHelpModal">
                            <i class="bi bi-info-circle"></i> <!-- Icono de ayuda -->
                        </button>
                    </div>

                    <!-- Campo Serie -->
                    <div class="mb-3">
                        <label for="serie" class="form-label">Serie</label>
                        <input type="text" name="serie" id="serie" class="form-control" placeholder="Ingrese el número de serie (opcional)" aria-describedby="serieHelp">
                        <small id="serieHelp" class="form-text text-muted">Número de serie del elemento (opcional).</small>
                    </div>
                </div>
                
                <div class="mb-4">
                    <!-- Campo Especificaciones Técnicas -->
                    <div class="mb-3">
                        <label for="especificaciones_tecnicas" class="form-label">Especificaciones Técnicas</label>
                        <textarea name="especificaciones_tecnicas" id="especificaciones_tecnicas" class="form-control" placeholder="Ingrese especificaciones técnicas (opcional)" aria-describedby="especificacionesHelp"></textarea>
                        <small id="especificacionesHelp" class="form-text text-muted">Detalles técnicos del elemento (opcional).</small>
                    </div>

                    <!-- Campo Foto -->
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*" aria-describedby="fotoHelp" onchange="previewImage(event)">
                        <small id="fotoHelp" class="form-text text-muted">Cargar una imagen del elemento (opcional, máximo 5MB).</small>
                        <!-- Contenedor para la vista previa de la imagen -->
                        <div class="image-preview mt-3">
                            <img id="imagePreview" src="#" alt="Vista previa de la imagen" style="display: none;">
                        </div>
                    </div>
                </div>

                <!-- Botón de Enviar -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de ayuda del campo Modelo -->
    <div class="modal fade" id="modeloHelpModal" tabindex="-1" aria-labelledby="modeloHelpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modeloHelpModalLabel">Ayuda - Campo Modelo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    Aquí puedes ingresar el modelo del elemento. Este campo es obligatorio y debe contener información específica sobre el modelo del producto.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
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
