document.addEventListener("DOMContentLoaded", function () {
    // Verificar si las meta etiquetas existen antes de acceder a ellas
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const baseStorageUrlMeta = document.querySelector(
        'meta[name="base-storage-url"]'
    );

    if (!csrfTokenMeta || !baseStorageUrlMeta) {
        console.error(
            "Las meta etiquetas 'csrf-token' o 'base-storage-url' no se encontraron."
        );
        return;
    }

    const csrfToken = csrfTokenMeta.getAttribute("content");
    const baseStorageUrl = baseStorageUrlMeta.getAttribute("content");
    const contenedorElementos = document.getElementById("contenedorElementos");
    const modalVerMas = document.getElementById("modalVerMas");
    const modalElementosUsuario = document.getElementById(
        "modal-elementos-usuario"
    );
    const btnNuevoRegistro = document.getElementById("btnNuevoRegistro");
    const btnCerrarRegistro = document.getElementById("btnCerrarRegistro");
    const modalBody = document.getElementById("modalBody");
    const inputUsuarioId = document.getElementById("usuario-id-oculto");
    const contenedorRegistros = document.getElementById("contenedorRegistros");

    // Abrir el modal de elementos
    window.abrirModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "block"; // Mostrar el modal
        }
    };

    // Cerrar el modal de elementos
    window.cerrarModalElementos = function () {
        if (modalElementosUsuario) {
            modalElementosUsuario.style.display = "none"; // Ocultar el modal
        }
    };

    // Función para crear un nuevo registro
    function crearRegistro() {
        // Recibir datos del formulario
        const documentoVigilante = document.getElementById(
            "documento_vigilante"
        ).value;
        const usuarioId = document.getElementById("usuario_id").value;

        // Enviar la solicitud AJAX
        fetch("/ruta/del/nuevo/registro", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                documento_vigilante: documentoVigilante,
                usuario_id: usuarioId,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Actualizar el campo active-record-id con el nuevo ID
                    document.getElementById("active-record-id").value =
                        data.nuevoRegistroId;
                } else {
                    // Mostrar mensaje de error
                    console.error("Error:", data.message);
                }
            })
            .catch((error) => {
                console.error("Error en la solicitud:", error);
            });
    }

    // Cerrar un registro
    if (btnCerrarRegistro) {
        btnCerrarRegistro.addEventListener("click", function () {
            const registroId = document.getElementById("registro-id").value;
            if (!registroId) {
                alert("No se ha seleccionado un registro.");
                return;
            }
            fetch(`/vigilante/cerrarRegistro/${registroId}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert("Registro cerrado.");
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch((error) => console.error("Error:", error));
        });
    }

    // Mostrar los registros existentes
    function mostrarRegistros(registros) {
        contenedorRegistros.innerHTML = ""; // Limpiar contenedor de registros

        registros.forEach((registro) => {
            // Crear una nueva fila para cada registro
            const row = document.createElement("div");
            row.classList.add("registro-row"); // Clase para la fila
            row.innerHTML = `
                <div class="registro-info" data-registro-id="${registro.id}">
                    <p><strong>ID Registro:</strong> ${registro.id}</p>
                    <p><strong>Fecha Ingreso:</strong> ${registro.fecha_ingreso}</p>
                </div>
            `;

            // Evento de clic para destacar fila y mostrar elementos
            row.addEventListener("click", () => {
                console.log(`Fila clicada: ID Registro = ${registro.id}`);
                destacarFilaSeleccionada(row); // Pasar `row` correctamente a la función
                mostrarElementosPorRegistro(registro.id); // Mostrar los elementos relacionados
            });

            contenedorRegistros.appendChild(row); // Agregar la fila al contenedor
        });
    }

    // Mostrar elementos al hacer clic en una fila (registro)
    function mostrarElementosPorRegistro(registroId) {
        console.log(`Buscando elementos para el registro ID: ${registroId}`);
        fetch(`/vigilante/obtenerElementosPorRegistro/${registroId}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(
                        `Error en la solicitud: ${response.status}`
                    );
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    console.log("Elementos obtenidos:", data.elementos);
                    mostrarElementosYCrearCards(data.elementos); // Mostrar los elementos en el contenedor
                } else {
                    alert(data.message || "No se encontraron elementos.");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    }

    //destacar fila seleccionada en la tabla de registros
    function destacarFilaSeleccionada(filaSeleccionada) {
        // Eliminar la clase "seleccionado" de todas las filas
        const filas = document.querySelectorAll(".registro-row");
        filas.forEach((fila) => fila.classList.remove("seleccionado"));

        // Agregar la clase "seleccionado" a la fila clicada
        filaSeleccionada.classList.add("seleccionado");
        console.log("Fila destacada:", filaSeleccionada);
    }

    // Función para mostrar los detalles de un elemento en el modal
    window.mostrarDetallesElemento = function (elementoId) {
        try {
            const elemento = elementos.find((el) => el.id === elementoId);

            if (!elemento) {
                alert("Elemento no encontrado.");
                return;
            }

            // Actualizar el contenido del modal con los datos del elemento(ver mas en los cards del modal de ver elementos)
            document.getElementById(
                "fotoElemento"
            ).src = `${baseStorageUrl}/${elemento.foto}`;
            document.getElementById("categoriaElemento").textContent =
                elemento.categoria.nombre || "Sin categoría";
            document.getElementById("descripcionElemento").textContent =
                elemento.descripcion || "Sin descripción";
            document.getElementById("marcaElemento").textContent =
                elemento.marca || "Sin marca";
            document.getElementById("modeloElemento").textContent =
                elemento.modelo || "Sin modelo";
            document.getElementById("serieElemento").textContent =
                elemento.serie || "Sin serie";
            document.getElementById("especificacionesElemento").textContent =
                elemento.especificaciones || "Sin especificaciones";

            document.getElementById("modalElemento").style.display = "block";
        } catch (error) {
            console.error("Error:", error);
        }
    };

    // Función para cerrar el modal
    window.cerrarModal = function () {
        document.getElementById("modalElemento").style.display = "none";
    };

    //
    //
    //
    //de aqui hasta la linea 375 es para el registro de sub_control osea el de los elementos

    window.actualizarElementos = function (
        elementoId = null,
        registroId = null
    ) {
        if (!registroId) {
            registroId = document.getElementById("control_ingreso_id")?.value;
            if (!registroId) {
                alert("No se ha seleccionado un registro.");
                return;
            }
        }

        if (elementoId) {
            // Registrar un elemento específico en el sub-control
            fetch("/sub_control_ingreso", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    control_ingreso_id: registroId,
                    elemento_id: elementoId,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success && data.elemento) {
                        alert("Elemento registrado exitosamente.");
                        const contenedor = document.getElementById(
                            "contenedor-elementos"
                        );
                        if (!contenedor) return;

                        contenedor.insertAdjacentHTML(
                            "beforeend",
                            crearCardElemento(data.elemento) // Usa el elemento devuelto por el servidor
                        );

                        const boton = document.querySelector(
                            `.btn-ingresar[data-id="${elementoId}"]`
                        );
                        if (boton) boton.setAttribute("disabled", true);
                    } else {
                        alert(
                            data.message || "No se pudo registrar el elemento."
                        );
                    }
                })
                .catch((error) => {
                    console.error("Error en la solicitud:", error);
                    alert("Ocurrió un error al registrar el elemento.");
                });
        } else {
            fetch(`/vigilante/obtenerElementosPorRegistro/${registroId}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        mostrarElementosYCrearCards(data.elementos);
                    } else {
                        alert(data.message || "No se encontraron elementos.");
                    }
                })
                .catch((error) =>
                    console.error("Error al actualizar lista:", error)
                );
        }
    };

    function crearCardElemento({ categoria = {}, foto, serie, marca, id }) {
        const nombreCategoria = categoria.nombre || "Sin categoría";
        return `
            <div class="card">
                <h3 class="cabeza">${nombreCategoria}</h3>
                <img src="${baseStorageUrl}/${foto}" alt="Foto del elemento" class="img-fluid mt-3 elemento-foto">
                <p><strong>Serie:</strong> ${serie || "Sin serie"}</p>
                <p><strong>Marca:</strong> ${marca || "Sin marca"}</p>
                <a href="javascript:void(0)" class="link-ver-mas" data-element-id="${id}"
                    onclick="mostrarDetallesElemento(${id})">Ver más</a>
                <button class="btn-destroy" data-id="${id}" onclick="eliminarElemento(${id})">
                    <img style="width:30px;" src="/imagenes/close.png" alt="Eliminar" class="icono-ingresa"> Eliminar
                </button>
            </div>
        `;
    }

    // Función global para manejar "ver más" (definir si no existe)
    window.verMasElemento = function (id) {
        alert(`Función Ver Más no implementada aún para el ID: ${id}`);
    };

    // Corrección de eventos globales y eliminación de consola innecesaria
    document.addEventListener("DOMContentLoaded", function () {
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const baseStorageUrlMeta = document.querySelector(
            'meta[name="base-storage-url"]'
        );

        if (!csrfTokenMeta || !baseStorageUrlMeta) {
            console.error(
                "Faltan las meta etiquetas 'csrf-token' o 'base-storage-url'."
            );
            return;
        }

        csrfToken = csrfTokenMeta.getAttribute("content");
        baseStorageUrl = baseStorageUrlMeta.getAttribute("content");

        // Eliminar eventos y mensajes innecesarios
    });

    // Mostrar los elementos registrados en el sub-control
    function mostrarElementosYCrearCards(elementos) {
        const contenedorElementos = document.getElementById(
            "contenedor-elementos"
        );
        if (!contenedorElementos) {
            console.error("El contenedor de elementos no existe.");
            return;
        }

        contenedorElementos.innerHTML = ""; // Limpiar contenedor antes de actualizar

        elementos.forEach(({ categoria, foto, serie, marca, id }) => {
            const cardHTML = crearCardElemento({
                categoria,
                foto,
                serie,
                marca,
                id,
            });
            contenedorElementos.insertAdjacentHTML("beforeend", cardHTML);
        });
        console.log("Elementos mostrados en el contenedor.");
    }

    // Escucha de eventos global para botones
    document.addEventListener("DOMContentLoaded", function () {
        console.log(
            "Documento cargado. Iniciando la configuración de eventos."
        );

        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const baseStorageUrlMeta = document.querySelector(
            'meta[name="base-storage-url"]'
        );

        if (!csrfTokenMeta || !baseStorageUrlMeta) {
            console.error(
                "Las meta etiquetas 'csrf-token' o 'base-storage-url' no se encontraron."
            );
            return;
        }

        const csrfToken = csrfTokenMeta.getAttribute("content");
        const baseStorageUrl = baseStorageUrlMeta.getAttribute("content");

        // Detectar el clic en los botones "Ingresa"
        document.addEventListener("click", function (event) {
            const boton = event.target.closest(".btn-ingresar");

            if (boton) {
                const elementoId = boton.getAttribute("data-id");
                if (!elementoId) return;

                const registroId =
                    document.getElementById("control_ingreso_id")?.value;
                if (!registroId) {
                    alert(
                        "Por favor, selecciona un registro válido antes de continuar."
                    );
                    return;
                }

                actualizarElementoPorAPI(elementoId, registroId);
            }
        });

        async function actualizarElementoPorAPI(elementoId, registroId) {
            try {
                const response = await fetch("/sub_control_ingreso", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        elemento_id: elementoId,
                        control_ingreso_id: registroId,
                    }),
                });

                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    alert("Elemento registrado exitosamente.");
                } else {
                    alert(data.message || "Error desconocido.");
                }
            } catch (error) {
                console.error("Error en la solicitud:", error);
                alert("Ocurrió un error al intentar registrar el elemento.");
            }
        }
    });
});
