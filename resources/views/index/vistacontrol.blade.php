<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-storage-url" content="{{ asset('storage') }}">
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
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout">Cerrar Sesión</button>
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
                                @foreach ($registros->reverse() as $registro)
                                    <tr class="registro-fila" data-registro-id="{{ $registro->id }}">
                                        <td>{{ $registro->id }}</td>
                                        <td>{{ $registro->centro->nombre ?? 'Centro no definido' }}</td>
                                        <td>{{ $registro->fecha_ingreso }}</td>
                                        <td>{{ $registro->fecha_salida ?? 'N/A' }}</td>
                                        <td>{{ $registro->estado == 0 ? 'Abierto' : 'Cerrado' }}</td>
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
                            {{-- inputs ocultos que guardan unas variables --}}
                            <span id="estado-registro"></span>
                            <input type="hidden" id="documento_vigilante" name="documento_vigilante"
                                value="{{ $vigilante->numero_documento ?? '' }}">
                            <input type="hidden" id="usuario-id-oculto" name="usuario_id"
                                value="{{ $usuario->id ?? '' }}">
                            <input type="hidden" id="control_ingreso_id">

                        </form>
                        <button type="button" class="boton" id="agregar-registro">
                            <img src="{{ asset('imagenes/add.png') }}" alt="Nuevo Registro" class="iconos">
                            Ingresa
                        </button>

                        <button type="button" class="boton" id="guardar-registros"
                            data-id="{{ $controlIngresoId ?? '' }}">
                            <img src="{{ asset('imagenes/close.png') }}" alt="Guardar" class="iconos">
                            Egresa
                        </button>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para mostrar los elementos del usuario -->
        <div class="modal" id="modal-elementos-usuario" style="display: none;">
            <div class="modal-content">
                <div class="contenido-modal" style="padding: 20px;">
                    <div class="encabezado-modal">
                        <h5 class="titulo-modal" style="margin-top:0">Elementos del Usuario</h5>
                    </div>
                    <div class="cuerpo-modal">
                        <div class="card-container tres-columnas">
                            @if (isset($elementos) && $elementos->isNotEmpty())
                                @foreach ($elementos as $elemento)
                                    <div class="card">
                                        <h3 class="cabeza">{{ $elemento->categoria->nombre }}</h3>
                                        <img src="{{ asset('storage/' . $elemento->foto) }}" alt="Foto del elemento"
                                            class="img-fluid mt-3 elemento-foto">
                                        <p><strong>Serie:</strong> {{ $elemento->serie }}</p>
                                        <p><strong>Marca:</strong> {{ $elemento->marca }}</p>
                                        <a href="javascript:void(0)" class="link-ver-mas"
                                            data-element-id="{{ $elemento->id }}"
                                            onclick="mostrarDetallesElemento({{ $elemento->id }})">Ver más</a>
                                        <button class="btn-ingresa" data-id="{{ $elemento->id }}"
                                            onclick="actualizarElementos({{ $elemento->id }})">
                                            <img src="{{ asset('imagenes/check_box.png') }}" alt="Guardar"
                                                class="icono-ingresa"> Ingresa
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <p>No hay elementos disponibles.</p>
                            @endif
                        </div>
                    </div>
                    <div class="pie-modal">
                        <button style="margin-bottom: -10px; margin-right: -10px;" class="button btn-cerrar-modal"
                            onclick="cerrarModalElementos()">Cerrar</button>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para mostrar detalles del elemento -->
        <div id="modalElemento" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" id="closeModal" onclick="cerrarModal()"></span>
                <h2 class="modal-title">Detalles del Elemento</h2>
                <div id="contenidoElemento">
                    <img id="fotoElemento" src="" alt="Foto">
                    </p>
                    <p class="modal-text"><strong>Categoría:</strong> <span id="categoriaElemento"></span></p>
                    <p class="modal-text"><strong>Descripción:</strong> <span id="descripcionElemento"></span></p>
                    <p class="modal-text"><strong>Marca:</strong> <span id="marcaElemento"></span></p>
                    <p class="modal-text"><strong>Modelo:</strong> <span id="modeloElemento"></span></p>
                    <p class="modal-text"><strong>Serie:</strong> <span id="serieElemento"></span></p>
                    <p class="modal-text"><strong>Especificaciones Técnicas:</strong> <span
                            id="especificacionesElemento"></span></p>
                </div>
                <button type="button" class="btn-cerrar-modal" onclick="cerrarModal()">Cerrar</button>
            </div>
        </div>

        <div class="contenido">
            <div class="elementos" id="contenedor-elementos">
                <button type="button" class="btn-modal" id="abrir-modal-elementos"
                    onclick="abrirModal('modal-elementos-usuario')">
                    <img src="{{ asset('imagenes/shopping.png') }}" alt="Nuevo Registro" class="iconos">
                    Ver Elementos
                </button>
                @if (isset($controlIngresoId))
                @else
                    <p>No se ha encontrado un registro de control de ingreso.</p>
                @endif
            </div>
        </div>
</body>
<script>
    const baseStorageUrl = "{{ asset('storage') }}";
    const registroUrl = "{{ route('vigilante.registro') }}";
    const subControlIngresoUrl = "{{ route('sub_control_ingreso.store') }}";
    const elementos = @json($elementos);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    // Si necesitas establecer un valor inicial para control_ingreso_id
    const initialControlIngresoId = @json($controlIngresoId);
    if (initialControlIngresoId) {
        document.getElementById("control_ingreso_id").value = initialControlIngresoId;
    }
</script>
<script src="{{ asset('js/vista_control.js') }}" defer></script>

</html>
