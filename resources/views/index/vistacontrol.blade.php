<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vista del Vigilante</title>
    <link rel="stylesheet" href="{{ asset('css/styles_vistacontrol.css') }}">
</head>

<body>
    <div class="container">
        <div class="buscador">
            <form action="{{ route('vigilante.buscar') }}" method="GET" class="form-busqueda">
                <input type="text" name="documento" class="input-busqueda" placeholder="Buscar por Documento...">
                <button type="submit" class="lupa-btn">
                    <img src="{{ asset('imagenes/lupa.png') }}" alt="Buscar" class="lupa-icon">
                </button>
            </form>
        </div>

        <div class="contenido-superior">
            <div class="contenedor-intermedio">
                <div class="usuario-info">
                    <div class="foto-logo">
                        <img src="{{ asset('imagenes/logo-del-sena-01.png') }}" alt="Logo del SENA" class="logo-sena">
                        <div class="barra-separadora"></div>
                    </div>

                    @if (isset($usuario))
                        <div class="info-text">
                            <p class="verde">{{ $usuario->nombres }}</p>
                            <p class="verde">{{ $usuario->apellidos }}</p>
                            <p><strong>Doc: {{ $usuario->numero_documento }}</strong></p>
                            <p><strong>Cel: {{ $usuario->telefono }}</strong></p>
                            <p><strong>RH: {{ $usuario->rh }}</strong></p>
                            <p><strong>{{ $usuario->role->nombre }}</strong></p>
                            <p><strong>Ficha: {{ $usuario->numero_ficha }}</strong></p>
                            <p class="verde" id="semifooter">Regional Casanare | Centro Agroindustrial y
                                Fortalecimiento Empresarial del Casanare</p>
                        </div>

                        <div class="foto-usuario">
                            @if ($usuario->foto && file_exists(storage_path('app/public/fotos_perfil/' . $usuario->foto)))
                                <img src="{{ asset('storage/fotos_perfil/' . $usuario->foto) }}" alt="Foto de perfil"
                                    class="foto-perfil-usuario">
                            @else
                                <img src="{{ asset('imagenes/sin_foto_perfil.webp') }}"
                                    alt="Foto de perfil predeterminada" class="foto-perfil-usuario">
                            @endif
                        </div>
                    @else
                        <div class="info-text">
                            <p>No se ha seleccionado ningún usuario o el documento ingresado no existe en la base de
                                datos.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="contenedor-reportes">
                <div class="tabla-container">
                    <table class="tabla-reportes" id="tabla-reportes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th class="centro">NOMBRE CENTRO</th>
                                <th>FECHA INGRESO</th>
                                <th>FECHA EGRESO</th>
                                <th>ESTADO</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-reportes-body">
                            @if (isset($registros) && $registros->isNotEmpty())
                                @foreach ($registros as $registro)
                                    <tr>
                                        <td>{{ $registro->id }}</td>
                                        <td>{{ $registro->centro->nombre ?? 'Centro no definido' }}</td>
                                        <td>{{ $registro->fecha_ingreso }}</td>
                                        <td>{{ $registro->fecha_salida ?? 'N/A' }}</td>
                                        <td>{{ $registro->estado == 0 ? 'Abierto' : 'Cerrado' }}</td>
                                        <!-- Mostrar texto en lugar del número -->
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No hay registros disponibles.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="contenedor-botones">
                        <form action="{{ route('vigilante.registro') }}" method="POST">
                            @csrf
                            <input type="hidden" id="documento_vigilante" name="documento_vigilante"
                                value="{{ $vigilante->numero_documento ?? '' }}">
                            <input type="hidden" id="usuario-id-oculto" name="usuario_id"
                                value="{{ $usuario->id ?? '' }}">
                            <button type="submit" class="boton" id="agregar-registro">Nuevo Registro</button>
                        </form>
                        <button class="boton">Guardar Registros</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="contenido">
            <div class="elementos">
                @if (isset($elementos) && $elementos->isNotEmpty())
                    <div class="card-container">
                        @foreach ($elementos as $elemento)
                            <div class="card">
                                <h3 class="cabeza">{{ $elemento->categoria->nombre }}</h3>
                                @if (file_exists(public_path('storage/' . $elemento->foto)))
                                    <img src="{{ asset('storage/' . $elemento->foto) }}" alt="Foto del elemento"
                                        class="img-fluid mt-3 elemento-foto">
                                @else
                                    <p>Imagen no encontrada: {{ asset('storage/' . $elemento->foto) }}</p>
                                @endif
                                <p><strong>Serie:</strong> {{ $elemento->serie }}</p>
                                <p><strong>Marca:</strong> {{ $elemento->marca }}</p>
                                <a href="#" class="link-ver-mas" data-bs-toggle="modal"
                                    data-bs-target="#modal-{{ $elemento->id }}">Ver más</a>

                                <div class="btn-container">
                                    <button class="btn-ingresa">Ingresa</button>
                                </div>
                            </div>

                            <div class="modal" id="modal-{{ $elemento->id }}">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ $elemento->categoria->nombre }}</h5>
                                        <button type="button" class="btn-close"
                                            onclick="document.getElementById('modal-{{ $elemento->id }}').style.display='none'">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="modal-body-content text-center">
                                            @if (file_exists(public_path('storage/' . $elemento->foto)))
                                                <img src="{{ asset('storage/' . $elemento->foto) }}"
                                                    alt="Foto del elemento" class="img-modal-ver-mas">
                                            @else
                                                <p>Imagen no encontrada: {{ asset('storage/' . $elemento->foto) }}</p>
                                            @endif
                                            <div class="info mt-3">
                                                <p><strong>Marca:</strong> {{ $elemento->marca }}</p>
                                                <p><strong>Modelo:</strong> {{ $elemento->modelo }}</p>
                                                <p><strong>Serie:</strong> {{ $elemento->serie }}</p>
                                                <p><strong>Especificaciones:</strong>
                                                    {{ $elemento->especificaciones_tecnicas }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn-close"
                                            onclick="document.getElementById('modal-{{ $elemento->id }}').style.display='none'">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No hay elementos disponibles.</p>
                @endif
            </div>
        </div>
    </div>
    <!--sccript para  mostrar modal-->
    <script>
        document.addEventListener('click', function(e) {
            if (e.target.matches('[data-bs-toggle="modal"]')) {
                const targetId = e.target.getAttribute('data-bs-target');
                document.querySelector(targetId).style.display = 'block';
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.matches('.modal')) {
                e.target.style.display = 'none';
            }
        });
    </script>
    <!--script  para mostrar modal-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    
    <script>
        document.getElementById('agregar-registro').addEventListener('click', function(event) {
            event.preventDefault(); // Evita recargar la página

            const documentoVigilante = "{{ $vigilante->numero_documento ?? '' }}";
            const usuarioId = "{{ $usuario->id ?? '' }}";

            if (!usuarioId) {
                alert('No se ha encontrado información del usuario.');
                return;
            }

            fetch("{{ route('vigilante.registro') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        documento_vigilante: documentoVigilante,
                        usuario_id: usuarioId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        agregarRegistroATabla(data.registro); // Agregar el registro a la tabla
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        function agregarRegistroATabla(registro) {
            const tablaBody = document.getElementById('tabla-reportes-body');

            // Crear una nueva fila para el registro
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${registro.id}</td>
                <td>${registro.centro?.nombre ?? 'Centro no definido'}</td>
                <td>${registro.fecha_ingreso}</td>
                <td>${registro.fecha_salida ?? 'N/A'}</td>
                <td>${registro.estado_texto}</td>
            `;

            tablaBody.appendChild(fila); // Agregar la fila a la tabla
        }
    </script>



</body>

</html>
