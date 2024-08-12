<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles_formulario_elementos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles_vistausuario.css') }}">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .left-panel {
            flex: 0 0 350px;
            border-right: 1px solid #ccc;
            padding: 20px;
            box-sizing: border-box;
        }

        .right-panel {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            padding: 20px;
            box-sizing: border-box;
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        .right-panel::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        .card {
            display: flex;
            flex-direction: column;
            height: 350px;
            width: 270px;
            left: 22px;
            top:-10px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: box-shadow 0.3s;
            overflow: hidden;
            background: #fff;
            border: 1px solid #ddd;
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 10px 20px;
            overflow-y: scroll;
            scrollbar-width: none;
            -ms-overflow-style: none;
            flex: 1;
        }

        .card-body::-webkit-scrollbar {
            width: 0;
            height: 0;
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: contain;
            margin-bottom: 15px;
        }


        .card-title {
            margin-top: 0;
            font-size: 24px;
            text-align: center;
        }

        .card-text {
            font-size: 14px;
            margin-bottom: 0;
            text-align: center;
        }

        .right-panel {
            padding-bottom: 60px;
        }

        .welcome-text {
            font-size: 30px;
        }

        .other-welcome {
            font-size: 20px;
        }

        @media (max-width: 768px) {
            .right-panel {
                grid-template-columns: 1fr;
            }
        }

        .modal-body img {
            width: 100%;
            max-width: 300px;
            height: auto;
            display: block;
            margin: 0 auto 20px auto;
        }

        .modal-dialog {
            max-width: 500px;
            margin: 30px auto;
        }

        .btn-link:focus,
        .btn-link:active {
            outline: none;
            box-shadow: none;
            border: none;
            color: inherit;
        }


    </style>
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
<div class="alert alert-success text-center">
    {{ session('success') }}
</div>
@endif

    <div class="container-fluid mt-4">
        <div class="left-panel">
            <div class="welcome-text">Bienvenido <br />{{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}</div>
            <div class="other-welcome"><br /><strong> Estos son tus elementos:</strong> </div>
        </div>
        <div class="right-panel">
            @foreach ($elementos as $elemento)
                <div class="card">
                    <div class="card-body">
                        <!-- Mostrar el nombre de la categoría -->
                        <h5 class="card-title"><strong>{{ $elemento->categoria->nombre }}</strong></h5>

                        <!-- Mostrar la foto si existe -->
                        @if ($elemento->foto)
                            <img src="{{ asset('storage/' . $elemento->foto) }}" alt="Foto del elemento"
                                class="img-fluid mt-3">
                        @endif

                        <!-- Mostrar el número de serie -->
                        <p class="card-text"><strong>Serial:</strong> {{ $elemento->serie }}</p>

                        <!-- Mostrar la marca -->
                        <p class="card-text"><strong>Marca:</strong> {{ $elemento->marca }}</p>

                        <!-- Enlace para ver más detalles -->
                        <a href="#" class="btn btn-link" data-bs-toggle="modal"
                            data-bs-target="#modal-{{ $elemento->id }}">
                            Ver más
                        </a>
                    </div>
                </div>

                <!-- Modal para ver más detalles -->
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
                                <!-- Mostrar la foto si existe -->
                                @if ($elemento->foto)
                                    <img src="{{ asset('storage/' . $elemento->foto) }}" alt="Foto del elemento"
                                        class="img-fluid mb-3">
                                @endif

                                <!-- Mostrar la categoría -->
                                <p><strong>Categoría:</strong> {{ $elemento->categoria->nombre }}</p>
                                <!-- Mostrar la descripción -->
                                <p><strong>Descripción:</strong> {{ $elemento->descripcion }}</p>

                                <!-- Mostrar la marca -->
                                <p><strong>Marca:</strong> {{ $elemento->marca }}</p>

                                <!-- Mostrar el modelo -->
                                <p><strong>Modelo:</strong> {{ $elemento->modelo }}</p>

                                <!-- Mostrar el número de serie -->
                                <p><strong>Serial:</strong> {{ $elemento->serie }}</p>

                                <!-- Mostrar especificaciones técnicas -->
                                <p><strong>Especificaciones Técnicas:</strong>
                                    {{ $elemento->especificaciones_tecnicas }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <div class="modal-footer">
                                    <!-- Botón de Eliminar -->
                                    <form action="{{ route('elementos.destroy', $elemento->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este elemento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                
                                    <!-- Botón de Editar -->
                                    <a href="{{ route('elementos.edit', $elemento->id) }}" class="btn btn-primary">Editar</a>
                                
                                    <!-- Botón de Cerrar -->
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para el registro de elementos -->
    <div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registroModalLabel">Registrar Elementos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        

                        <form id="elementos-form" action="{{ route('elementos.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div id="form-container">
                                <!-- Formulario de Elemento -->
                                <div class="elemento-form mb-3">
                                    <div class="mb-3">
                                        <label for="categoria_id" class="form-label">Categoría</label>
                                        <select id="categoria_id" name="categoria_id" class="form-select" required>
                                            <option value="" disabled selected>Selecciona una categoría</option>
                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <input type="text" id="descripcion" name="descripcion"
                                            class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="marca" class="form-label">Marca</label>
                                        <input type="text" id="marca" name="marca" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="modelo" class="form-label">Modelo</label>
                                        <input type="text" id="modelo" name="modelo" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="serie" class="form-label">Número de Serie</label>
                                        <input type="text" id="serie" name="serie" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="especificaciones_tecnicas" class="form-label">Especificaciones
                                            Técnicas</label>
                                        <textarea id="especificaciones_tecnicas" name="especificaciones_tecnicas" class="form-control" rows="3"
                                            required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="foto" class="form-label">Foto</label>
                                        <input type="file" id="foto" name="foto" class="form-control"
                                            accept="image/*">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Registrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>

</html>
