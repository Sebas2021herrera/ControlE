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

        <!-- Modal para mostrar los elementos del usuario -->
        <div class="modal" id="modal-elementos-usuario" style="display: none;">
            <div class="modal-content">
                <div class="contenido-modal" style="padding: 20px;">
                    <div class="encabezado-modal">
                        <h5 class="titulo-modal">Elementos del Usuario</h5>
                        <span class="close" id="closeModalElementos" onclick="cerrarModalElementos()">&times;</span>
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
                                        <button class="btn-ingresa" data-elemento-id="{{ $elemento->id }}"
                                            data-categoria="{{ $elemento->categoria->nombre }}"
                                            data-foto="{{ asset('storage/' . $elemento->foto) }}"
                                            data-serie="{{ $elemento->serie }}" data-marca="{{ $elemento->marca }}">
                                            <img src="{{ asset('imagenes/check_box.png') }}" alt="Guardar"
                                                class="icono-ingresa">Ingresa
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <p>No hay elementos disponibles.</p>
                            @endif
                        </div>
                    </div>
                    <div class="pie-modal">
                        <button type="button" class="btn-cerrar-modal"
                            onclick="cerrarModalElementos()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para mostrar detalles del elemento -->
        <div id="modalElemento" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" id="closeModal" onclick="cerrarModal()">&times;</span>
                <h2>Detalles del Elemento</h2>
                <div id="contenidoElemento">
                    <p><strong>Categoría:</strong> <span id="categoriaElemento"></span></p>
                    <p><strong>Descripción:</strong> <span id="descripcionElemento"></span></p>
                    <p><strong>Marca:</strong> <span id="marcaElemento"></span></p>
                    <p><strong>Modelo:</strong> <span id="modeloElemento"></span></p>
                    <p><strong>Serie:</strong> <span id="serieElemento"></span></p>
                    <p><strong>Especificaciones Técnicas:</strong> <span id="especificacionesElemento"></span></p>
                    <p><strong>Foto:</strong>
                        <img id="fotoElemento" src="" alt="Foto" style="max-width: 100%;">
                    </p>
                </div>
                <button type="button" class="btn-cerrar-modal" onclick="cerrarModal()">Cerrar</button>
            </div>
        </div>



        <div class="contenido">
            <div class="elementos">
                <button type="button" class="btn-modal" id="abrir-modal-elementos"
                    onclick="mostrarModalElementos()">
                    <img src="{{ asset('imagenes/shopping.png') }}" alt="Nuevo Registro" class="iconos">
                    Ver Elementos
                </button>
                @if (isset($controlIngresoId))
                    <script>
                        console.log("Control Ingreso ID: {{ $controlIngresoId }}");
                    </script>
                @else
                    <p>No se ha encontrado un registro de control de ingreso.</p>
                @endif


                <!-- Scripts al final del body para asegurar que el DOM esté cargado -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        // Definir las funciones en el ámbito global
                        window.mostrarModal = function(modalId = 'modal-elementos-usuario') {
                            console.log(`Intentando abrir el modal con ID: ${modalId}`);
                            const modal = document.getElementById(modalId);

                            if (modal) {
                                modal.style.display = 'block';
                                console.log(`Modal con ID ${modalId} abierto correctamente.`);
                            } else {
                                console.error(`Modal con ID ${modalId} no encontrado en el DOM.`);
                            }
                        };

                        window.cerrarModal = function(modalId = 'modal-elementos-usuario') {
                            const modal = document.getElementById(modalId);
                            if (modal) {
                                modal.style.display = 'none';
                                console.log(`Modal con ID ${modalId} cerrado.`);
                            } else {
                                console.error(`Modal con ID ${modalId} no encontrado en el DOM para cerrarlo.`);
                            }
                        };

                        // Asignar el evento al botón para abrir el modal
                        const botonAbrirModal = document.getElementById('abrir-modal-elementos');
                        if (botonAbrirModal) {
                            botonAbrirModal.addEventListener('click', function(event) {
                                event.stopPropagation(); // Evita que el clic se propague a otros elementos
                                mostrarModal(); // Abre el modal por defecto
                            });
                        }

                        // Asignar el evento al botón de cerrar dentro del modal
                        const botonCerrarModal = document.querySelector('.btn-cerrar-modal');
                        if (botonCerrarModal) {
                            botonCerrarModal.addEventListener('click', function() {
                                cerrarModal(); // Cierra el modal por defecto
                            });
                        }

                        // Función para manejar el clic en una fila de la tabla
                        function handleFilaClick() {
                            document.querySelectorAll('.registro-fila').forEach(f => f.classList.remove('fila-seleccionada'));
                            this.classList.add('fila-seleccionada');
                            const registroId = this.getAttribute('data-registro-id');
                            obtenerElementosAsociados(registroId);
                        }

                        // Asignar eventos a las filas de la tabla
                        function aplicarEventListeners() {
                            document.querySelectorAll('.registro-fila').forEach(fila => {
                                fila.removeEventListener('click', handleFilaClick);
                                fila.addEventListener('click', handleFilaClick);
                            });
                        }

                        // Ejecutar la asignación de eventos al cargar el DOM
                        aplicarEventListeners();

                        // Función para obtener elementos asociados
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

                        // Función para mostrar elementos en el modal
                        function mostrarElementos(elementos) {
                            const contenedorElementos = document.querySelector('.elementos');
                            const botonVerElementos = document.getElementById('abrir-modal-elementos');
                            contenedorElementos.innerHTML = ''; // Limpiar los elementos actuales

                            elementos.forEach(elemento => {
                                const card = document.createElement('div');
                                card.classList.add('card');
                                card.innerHTML = `
                    <h3 class="cabeza">${elemento.categoria.nombre}</h3>
                    <img src="{{ asset('storage/') }}/${elemento.foto}" alt="Foto del elemento" class="img-fluid mt-3 elemento-foto">
                    <p><strong>Serie:</strong> ${elemento.serie}</p>
                    <p><strong>Marca:</strong> ${elemento.marca}</p>
                    <a href="#" class="link-ver-mas" onclick="mostrarDetallesElemento('${elemento.id}')">Ver más</a>
                `;
                                contenedorElementos.appendChild(card);
                            });

                            contenedorElementos.appendChild(botonVerElementos); // Reinsertar el botón
                        }

                        // Función para mostrar detalles del elemento
                        window.mostrarDetallesElemento = function(elementoId) {
                            const modal = document.getElementById('modalElemento');
                            const descripcion = document.getElementById('descripcionElemento');
                            const marca = document.getElementById('marcaElemento');
                            const modelo = document.getElementById('modeloElemento');
                            const serie = document.getElementById('serieElemento');
                            const especificaciones = document.getElementById('especificacionesElemento');
                            const foto = document.getElementById('fotoElemento');
                            const categoria = document.getElementById('categoriaElemento');

                            // Mostrar el modal
                            modal.style.display = 'block';

                            // Hacer la petición para obtener los detalles del elemento
                            fetch(`/elementos/detalles/${elementoId}`)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Error en la respuesta de la red');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        // Actualizar los elementos del modal con los datos del elemento
                                        descripcion.textContent = data.elemento.descripcion || 'No disponible';
                                        marca.textContent = data.elemento.marca || 'No disponible';
                                        modelo.textContent = data.elemento.modelo || 'No disponible';
                                        serie.textContent = data.elemento.serie || 'No disponible';
                                        especificaciones.textContent = data.elemento.especificaciones ||
                                            'No disponible';
                                        categoria.textContent = data.elemento.categoria || 'No disponible';
                                        foto.src = data.elemento.foto ?
                                            `{{ asset('storage/') }}/${data.elemento.foto}` :
                                            '/path/to/default-image.jpg';
                                    } else {
                                        alert('No se encontraron detalles para este elemento.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error al obtener detalles:', error);
                                    alert('Ocurrió un error al cargar los detalles del elemento.');
                                });
                        };

                        // Función para cerrar el modal
                        document.getElementById('closeModal').addEventListener('click', function() {
                            const modal = document.getElementById('modalElemento');
                            modal.style.display = 'none';
                        });

                        // Cerrar el modal si el usuario hace clic fuera de la ventana
                        window.onclick = function(event) {
                            const modal = document.getElementById('modal-elementos-usuario');
                            if (modal && event.target === modal) {
                                cerrarModalElementos();
                            }
                        };



                        // Manejo de clics fuera del modal para cerrarlo
                        window.addEventListener('click', function(event) {
                            const modal = document.getElementById('modal-elementos-usuario');
                            if (modal && event.target === modal) {
                                cerrarModal();
                            }
                        });

                        // Función para registrar el control de ingreso
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
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content')
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

                        // Función para limpiar la tabla
                        function limpiarTabla() {
                            const tablaBody = document.getElementById('tabla-reportes-body');
                            tablaBody.innerHTML = ''; // Limpia todo el contenido de la tabla
                        }

                        // Función para agregar registros a la tabla
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

                        // Función para registrar y asociar los elementos al control de ingreso
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
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').getAttribute('content')
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

                        // Función para mostrar mensajes de éxito
                        function mostrarMensajeExito(mensaje) {
                            const mensajeContainer = document.createElement('div');
                            mensajeContainer.className = 'alert alert-success';
                            mensajeContainer.textContent = mensaje;

                            // Insertar el mensaje después del contenedor del buscador
                            const buscador = document.querySelector('.buscador');
                            buscador.insertAdjacentElement('afterend', mensajeContainer);

                            setTimeout(() => {
                                mensajeContainer.style.opacity = '0';
                                setTimeout(() => mensajeContainer.remove(), 500);
                            }, 5000);
                        }

                        // Función para agregar un elemento al contenedor
                        function agregarElementoAlContenedor(elemento) {
                            const contenedorElementos = document.querySelector('.elementos');
                            const card = document.createElement('div');
                            card.classList.add('card');
                            card.innerHTML = `
                <h3 class="cabeza">${elemento.categoria.nombre}</h3>
                <img src="{{ asset('storage/') }}/${elemento.foto}" alt="Foto del elemento" class="img-fluid mt-3 elemento-foto">
                <p><strong>Serie:</strong> ${elemento.serie}</p>
                <p><strong>Marca:</strong> ${elemento.marca}</p>
            `;
                            contenedorElementos.appendChild(card);
                        }

                        // Función para mostrar el modal de elementos del usuario
                        window.mostrarModalElementos = function() {
                            const modal = document.getElementById('modal-elementos-usuario');
                            if (modal) {
                                modal.style.display = 'block'; // Muestra el modal
                            } else {
                                console.error('Modal no encontrado');
                            }
                        };

                        // Función para cerrar el modal
                        window.cerrarModal = function() {
                            const modal = document.getElementById('modalElemento');
                            if (modal) {
                                modal.style.display = 'none'; // Oculta el modal
                            }
                        };

                        // Función para cerrar el modal de elementos
                        function cerrarModalElementos() {
                            const modal = document.getElementById('modal-elementos-usuario');
                            if (modal) {
                                modal.style.display = 'none'; // Oculta el modal
                            }
                        }
                    });
                </script>


                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        // Aquí van tus funciones JavaScript
                        window.cerrarModalElementos = function() {
                            const modal = document.getElementById('modal-elementos-usuario');
                            if (modal) {
                                modal.style.display = 'none'; // Oculta el modal
                            }
                        };
                    });
                </script>

</body>

</html>
