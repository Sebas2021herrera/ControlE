<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        <!-- Mostrar la información del usuario si existe -->
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

                        <!-- Mostrar la foto del usuario si existe -->
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
                        <!-- Mostrar el mensaje si no se ha encontrado ningún usuario -->
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

                        </tbody>
                    </table>
                    <div class="contenedor-botones">
                        <button class="boton" id="agregar-registro">Nuevo Registro</button>
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
                                    data-bs-target="#modal-{{ $elemento->id }}">
                                    Ver más
                                </a>

                                <div class="btn-container">
                                    <button class="btn-ingresa">Ingresa</button>
                                </div>
                            </div>

                            <!-- Modal ver más adaptado -->
                            <div class="modal" id="modal-{{ $elemento->id }}">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ $elemento->categoria->nombre }}</h5>
                                        <button type="button" class="btn-close"
                                            onclick="document.getElementById('modal-{{ $elemento->id }}').style.display='none'">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="modal-body-content text-center">
                                            <!-- Clase para centrar el contenido -->
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
        document.addEventListener('DOMContentLoaded', function() {
            const tablaReportesBody = document.getElementById('tabla-reportes-body');
            const agregarRegistroBtn = document.getElementById('agregar-registro');

            // Función para crear una nueva fila
            function crearNuevaFila() {
                const tr = document.createElement('tr');

                // Crear celdas para la fila
                for (let i = 0; i < 5; i++) { // Cambiamos a 5 para agregar la columna de 'Estado'
                    const td = document.createElement('td');

                    // Añadir contenido específico por columna
                    switch (i) {
                        case 0:
                            td.textContent = 'ID'; // Puedes reemplazar con el ID correspondiente
                            break;
                        case 1:
                            td.textContent = 'Yopal'; // Columna de NOMBRE CENTRO
                            td.classList.add('centro'); // Añadimos la clase 'centro' si es necesario
                            break;
                        case 2:
                            td.textContent = 'Fecha Ingreso'; // Puedes reemplazar con la fecha
                            break;
                        case 3:
                            td.textContent = 'Fecha Egreso'; // Puedes reemplazar con la fecha
                            break;
                        case 4:
                            td.textContent = 'Activo'; // Columna de ESTADO, puedes cambiarlo
                            break;
                    }

                    // Aplicamos el estilo de borde a cada celda
                    td.style.border = '1px solid white';

                    tr.appendChild(td);
                }

                tablaReportesBody.appendChild(tr);
            }

            // Evento para el botón de agregar registro
            agregarRegistroBtn.addEventListener('click', function() {
                crearNuevaFila();
            });
        });
    </script>



</body>

</html>
