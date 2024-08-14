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
            
            <!-- Bloque para mostrar mensajes de éxito -->
            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif
            
            <form id="elementos-form" action="{{ route('elementos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="form-container">
                    <!-- Formulario de Elemento -->
                    <div class="elemento-form mb-3">
                        <div class="mb-3">
                            <label for="categoria" class="form-label"><strong>Categoría</strong></label>
                            <select name="categoria[]" class="form-select" aria-label="Seleccionar categoría" required>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label"><strong>Descripción</strong></label>
                            <input type="text" name="descripcion[]" class="form-control" placeholder="Ingrese una descripción" required>
                        </div>

                        <div class="mb-3">
                            <label for="marca" class="form-label"><strong>Marca</strong></label>
                            <input type="text" name="marca[]" class="form-control" placeholder="Ingrese la marca" required>
                        </div>

                        <div class="mb-3 position-relative">
                            <div class="d-flex align-items-center">
                                <label for="modelo" class="form-label mb-0"><strong>Modelo</strong></label>
                                <a href="#" class="ms-2" data-bs-toggle="modal" data-bs-target="#modeloHelpModal">
                                    Ayuda
                                </a>
                            </div>
                            <input type="text" name="modelo[]" class="form-control mt-2" placeholder="Ingrese el modelo" required>
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

                        <div class="mb-3">
                            <label for="serie" class="form-label"><strong>Serie</strong></label>
                            <input type="text" name="serie[]" class="form-control" placeholder="Ingrese el número de serie (opcional)">
                        </div>

                        <div class="mb-3">
                            <label for="especificaciones_tecnicas" class="form-label"><strong>Especificaciones Técnicas</strong></label>
                            <textarea name="especificaciones_tecnicas[]" class="form-control" placeholder="Ingrese especificaciones técnicas (opcional)"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label"><strong>Foto</strong></label>
                            <input type="file" name="foto[]" class="form-control" accept="image/*" onchange="previewImage(event)">
                            <small class="form-text text-muted">Cargar una imagen del elemento (opcional, máximo 5MB).</small>
                            <div class="image-preview mt-3">
                                <img class="image-preview-img" src="#" alt="Vista previa de la imagen" style="display: none;">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-form" class="btn btn-custom mb-3">Agregar otro elemento</button>
                <div class="btn-custom-container">
                    <button type="submit" class="btn btn-custom">Registrar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#add-form').click(function() {
                let newForm = $('.elemento-form').first().clone();
                newForm.find('input, select, textarea').val('');
                newForm.find('.image-preview-img').attr('src', '#').hide();
                $('#form-container').append(newForm);
            });

            $('body').on('change', 'input[type="file"]', function(event) {
                const file = event.target.files[0];
                const preview = $(this).closest('.elemento-form').find('.image-preview-img');
                
                if (file) {
                    if (file.size > 5242880) { // 5MB en bytes
                        alert('El tamaño del archivo debe ser menor o igual a 5MB.');
                        $(this).val('');
                        preview.hide();
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result);
                        preview.show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.hide();
                }
            });
        });
    </script>
</body>
</html>
