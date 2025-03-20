<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consulta general de usuarios</title>
    <link rel="stylesheet" href="{{ asset('css/consulta_masiva.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <!-- Enlaza el archivo CSS de Bulma -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1 class="title">Consulta General de Usuarios</h1>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('admin.panel') }}" class="button is-link">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span>Volver a Admin</span>
            </a>

            <!-- Formulario de búsqueda -->
    <form method="GET" action="{{ route('admin.usuarios.consultaMasiva') }}" class="search-form">
        <div class="field has-addons">
            <div class="control">
                <input type="text" name="nombre" class="input" placeholder="Buscar por nombre" value="{{ request('nombre') }}">
            </div>
            <div class="control">
                <input type="text" name="apellido" class="input" placeholder="Buscar por apellido" value="{{ request('apellido') }}">
            </div>
            <div class="control">
                <button type="submit" class="button is-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

            <div class="table-container">
                <table class="table is-fullwidth is-striped is-hoverable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Documento</th>
                            <th>Rol</th>
                            <th>Correo Institucional</th>
                            <th>Fecha Registro</th>
                            <th>Elementos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->nombres }}</td>
                            <td>{{ $usuario->apellidos }}</td>
                            <td>{{ $usuario->tipo_documento }} {{ $usuario->numero_documento }}</td>
                            <td>{{ $usuario->role->nombre ?? 'Sin rol' }}</td>
                            <td>{{ $usuario->correo_institucional}}</td>
                            <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <button class="button is-info is-small ver-elementos" data-usuario-id="{{ $usuario->id }}">
                                    Ver ({{ $usuario->elementos->count() }})
                                </button>
                            </td>
                            <td>
                                <div class="buttons are-small">
                                    <button class="button is-warning editar-usuario" data-usuario-id="{{ $usuario->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="button is-danger eliminar-usuario" data-usuario-id="{{ $usuario->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Controles de paginación -->
<div class="pagination-links mt-4">
    <div class="pagination-summary">
        {{ __('pagination.showing', [
            'from' => $usuarios->firstItem(),
            'to' => $usuarios->lastItem(),
            'total' => $usuarios->total()
        ]) }}
    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            {{ $usuarios->links() }}
        </ul>
    </nav>
</div>
    
    <!-- Modal para mostrar elementos -->
    <div class="modal" id="elementosModal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Elementos del Usuario</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <div id="elementos-lista"></div>
            </section>
        </div>
    </div>

    <script src="{{ asset('js/consulta_masiva.js') }}"></script>

</body>
</html>
