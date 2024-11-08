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
                            <input type="hidden" id="documento_vigilante" name="documento_vigilante"
                                value="{{ $vigilante->numero_documento ?? '' }}">
                            <input type="hidden" id="usuario-id-oculto" name="usuario_id"
                                value="{{ $usuario->id ?? '' }}">
                        </form>
                        <button type="button" class="boton" id="agregar-registro">
                            <img src="{{ asset('imagenes/add.png') }}" alt="Nuevo Registro" class="iconos">
                            Nuevo Registro
                        </button>

                        <button type="button" class="boton" id="guardar-registros">
                            <img src="{{ asset('imagenes/close.png') }}" alt="Guardar"class="iconos">
                            Guardar Registros
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="contenido">
            <div class="elementos">
                <button type="button" class="btn-modal" id="abrir-modal-elementos">
                    <img src="{{ asset('imagenes/shopping.png') }}" alt="Nuevo Registro" class="iconos">
                    Ver Elementos
                </button>
                <!-- Modal para mostrar los elementos del usuario -->
                <div class="modal" id="modal-elementos-usuario" style="display: none;">
                    <div class="modal-content"> 
                        <div class="contenido-modal" style="padding: 20px;">
                            <div class="encabezado-modal">
                                <h5 class="titulo-modal">Elementos del Usuario</h5>
                            </div>
                            <div class="cuerpo-modal">
                                <div class="card-container tres-columnas">
                                    @if (isset($elementos) && $elementos->isNotEmpty())
                                        @foreach ($elementos as $elemento)
                                            <div class="card">
                                                <h3 class="cabeza">{{ $elemento->categoria->nombre }}</h3>
                                                <img src="{{ asset('storage/' . $elemento->foto) }}" alt="Foto del elemento" class="img-fluid mt-3 elemento-foto">
                                                <p><strong>Serie:</strong> {{ $elemento->serie }}</p>
                                                <p><strong>Marca:</strong> {{ $elemento->marca }}</p>
                                                <a href="#" class="link-ver-mas" onclick="mostrarModal({{ $elemento->id }})">Ver más</a>
                                                <button class="btn-ingresa" data-elemento-id="{{ $elemento->id }}"
                                                    data-categoria="{{ $elemento->categoria->nombre }}"
                                                    data-foto="{{ asset('storage/' . $elemento->foto) }}"
                                                    data-serie="{{ $elemento->serie }}"
                                                    data-marca="{{ $elemento->marca }}">
                                                    <img src="{{ asset('imagenes/check_box.png') }}" alt="Guardar" class="icono-ingresa">Ingresa
                                                </button>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>No hay elementos disponibles.</p>
                                    @endif
                                </div>
                            </div>
                            <div class="pie-modal">
                                <button type="button" class="btn-cerrar-modal" onclick="cerrarModal()">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (isset($controlIngresoId))
        <script>
            console.log("Control Ingreso ID: {{ $controlIngresoId }}");
        </script>
    @else
        <p>No se ha encontrado un registro de control de ingreso.</p>
    @endif

    <!-- Script para mostrar modal -->
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

        function cerrarModal() {
            document.getElementById('modal-elementos-usuario').style.display = 'none';
        }
    </script>

    <!-- Script para abrir modal de los elementos del usuario -->
    <script>
        document.getElementById('abrir-modal-elementos').addEventListener('click', function() {
            document.getElementById('modal-elementos-usuario').style.display = 'block';
        });
    </script>

    <!-- Script para registrar el control_ingreso -->
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
                        // Actualizar la tabla automáticamente sin alertas
                        limpiarTabla();
                        agregarRegistrosATabla(data.registros);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        function limpiarTabla() {
            const tablaBody = document.getElementById('tabla-reportes-body');
            tablaBody.innerHTML = ''; // Limpia todo el contenido de la tabla
        }

        function agregarRegistrosATabla(registros) {
            const tablaBody = document.getElementById('tabla-reportes-body');

            registros.forEach(registro => {
                const fila = document.createElement('tr');
                fila.classList.add('registro-fila');
                fila.setAttribute('data-registro-id', registro.id);
                fila.innerHTML = `
                    <td>${registro.id}</td>
                    <td>${registro.centro?.nombre ?? 'Centro no definido'}</td>
                    <td>${registro.fecha_ingreso}</td>
                    <td>${registro.fecha_salida ?? 'N/A'}</td>
                    <td>${registro.estado == 0 ? 'Abierto' : 'Cerrado'}</td>
                `;
                tablaBody.appendChild(fila);
            });

            aplicarEventListeners();
        }
    </script>

    <!-- Script para registrar y asociar los elementos al control de ingreso -->
    <script>
        document.querySelectorAll('.btn-ingresa').forEach(button => {
            button.addEventListener('click', function() {
                const elementoId = this.getAttribute('data-elemento-id');
                const controlIngresoId = "{{ $controlIngresoId ?? '' }}";

                if (!controlIngresoId) {
                    alert('No se ha encontrado un registro de control de ingreso.');
                    return;
                }

                fetch("{{ route('sub_control_ingreso.store') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        control_ingreso_id: controlIngresoId,
                        elemento_id: elementoId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarMensajeExito('Elemento registrado exitosamente.');
                        agregarElementoAlContenedor(data.elemento);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });

        function mostrarMensajeExito(mensaje) {
            const mensajeContainer = document.createElement('div');
            mensajeContainer.className = 'alert alert-success';
            mensajeContainer.textContent = mensaje;

            // Asegúrate de que el mensaje se inserte después del contenedor del buscador
            const buscador = document.querySelector('.buscador');
            buscador.insertAdjacentElement('afterend', mensajeContainer);

            setTimeout(() => {
                mensajeContainer.style.opacity = '0';
                setTimeout(() => mensajeContainer.remove(), 500);
            }, 5000);
        }

        function agregarElementoAlContenedor(elemento) {
            if (!elemento || !elemento.categoria) {
                console.error('Elemento o categoría no definido');
                return;
            }
            const contenedorElementos = document.querySelector('.elementos');
            if (!contenedorElementos) {
                console.error('Contenedor de elementos no encontrado');
                return;
            }
            const card = document.createElement('div');
            card.classList.add('card');
            card.innerHTML = `
                <h3 class="cabeza">${elemento.categoria.nombre}</h3>
                <img src="{{ asset('storage/') }}/${elemento.foto}" alt="Foto del elemento" class="img-fluid mt-3 elemento-foto">
                <p><strong>Serie:</strong> ${elemento.serie}</p>
                <p><strong>Marca:</strong> ${elemento.marca}</p>
                <a href="#" class="link-ver-mas" onclick="mostrarModal(${elemento.id})">Ver más</a>
            `;
            contenedorElementos.appendChild(card);
        }

        function mostrarModal(id) {
            const modal = document.getElementById('modal-' + id);
            if (modal) {
                modal.style.display = 'block';
            } else {
                console.error('Modal no encontrado para el ID:', id);
            }
        }

        function cerrarModal(id) {
            const modal = document.getElementById('modal-' + id);
            if (modal) {
                modal.style.display = 'none';
            } else {
                console.error('Modal no encontrado para el ID:', id);
            }
        }
    </script>

    <!-- Script para que al seleccionar los elementos se impriman los cards en el contenedor de los elementos del usuario -->
    <script>
        function agregarElementoAlContenedor(elemento) {
            if (!elemento || !elemento.categoria) {
                console.error('Elemento o categoría no definido');
                return;
            }
            console.log('Agregando elemento al contenedor:', elemento);
            const contenedorElementos = document.querySelector('.elementos');
            if (!contenedorElementos) {
                console.error('Contenedor de elementos no encontrado');
                return;
            }
            const card = document.createElement('div');
            card.classList.add('card');
            card.innerHTML = `
                <h3 style="margin-top:10px;" class="cabeza">${elemento.categoria.nombre}</h3>
                <img src="{{ asset('storage/') }}/${elemento.foto}" alt="Foto del elemento" class="img-fluid mt-3 elemento-foto">
                <p><strong>Serie:</strong> ${elemento.serie}</p>
                <p style="margin-top: -20px;"><strong>Marca:</strong> ${elemento.marca}</p>
                <a style="margin-bottom: 15px;" href="#" class="link-ver-mas" onclick="mostrarModal(${elemento.id})">Ver más</a>
            `;
            contenedorElementos.appendChild(card);
        }
    </script>

    <script>
        document.querySelector('table').addEventListener('click', function(event) {
            if (event.target && event.target.matches('tr.registro-fila')) {
                // Remover el resaltado de todas las filas
                document.querySelectorAll('.registro-fila').forEach(f => f.classList.remove('fila-seleccionada'));

                // Añadir la clase de resaltado a la fila seleccionada
                event.target.classList.add('fila-seleccionada');

                const registroId = event.target.getAttribute('data-registro-id');
                obtenerElementosAsociados(registroId);
            }
        });

        function obtenerElementosAsociados(registroId) {
            fetch(`/vigilante/elementos/${registroId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarElementos(data.elementos);
                    } else {
                        alert('No se encontraron elementos asociados.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function mostrarElementos(elementos) {
            const contenedorElementos = document.querySelector('.elementos');
            const botonVerElementos = document.getElementById('abrir-modal-elementos'); // Guardar referencia al botón
            contenedorElementos.innerHTML = ''; // Limpiar solo los elementos, no el botón

            elementos.forEach(elemento => {
                const card = document.createElement('div');
                card.classList.add('card');
                card.innerHTML = `
                    <h3 class="cabeza">${elemento.categoria.nombre}</h3>
                    <img src="{{ asset('storage/') }}/${elemento.foto}" alt="Foto del elemento" class="img-fluid mt-3 elemento-foto">
                    <p><strong>Serie:</strong> ${elemento.serie}</p>
                    <p><strong>Marca:</strong> ${elemento.marca}</p>
                    <a href="#" class="link-ver-mas" onclick="mostrarModal(${elemento.id})">Ver más</a>
                `;
                contenedorElementos.appendChild(card);
            });

            // Reinsertar el botón después de limpiar el contenedor
            contenedorElementos.appendChild(botonVerElementos);
        }

        function mostrarTodosLosElementos() {
            // Lógica para mostrar todos los elementos
        }
    </script>

    <script>
        function agregarFila(nuevaFilaHtml) {
            const tabla = document.querySelector('table');
            tabla.insertAdjacentHTML('beforeend', nuevaFilaHtml);
            aplicarEventListeners();
        }

        function aplicarEventListeners() {
            document.querySelectorAll('.registro-fila').forEach(fila => {
                fila.removeEventListener('click', handleFilaClick); // Eliminar el listener anterior si existe
                fila.addEventListener('click', handleFilaClick);
            });
        }

        function handleFilaClick() {
            // Remover el resaltado de todas las filas
            document.querySelectorAll('.registro-fila').forEach(f => f.classList.remove('fila-seleccionada'));

            // Añadir la clase de resaltado a la fila seleccionada
            this.classList.add('fila-seleccionada');

            const registroId = this.getAttribute('data-registro-id');
            obtenerElementosAsociados(registroId);
        }

        // Llama a aplicarEventListeners() después de cargar la página y cada vez que agregues una nueva fila
        aplicarEventListeners();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            aplicarEventListeners();
        });
    </script>

</body>

</html>

