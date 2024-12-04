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
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

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

            // Evento de clic para recargar la página
            row.addEventListener("click", () => {
                // Almacenar el ID del registro seleccionado en sessionStorage
                sessionStorage.setItem('selectedRegistroId', registro.id);
                // Recargar la página
                window.location.reload();
            });

            contenedorRegistros.appendChild(row);
        });
    }

    // Mostrar elementos al hacer clic en una fila (registro)
    /**
     * Función para mostrar los elementos asociados a un registro.
     * Realiza una solicitud al servidor y muestra los datos en el contenedor correspondiente.
     * @param {number} registroId - ID del registro a consultar.
     */
    function mostrarElementosPorRegistro(registroId) {
        // Obtener el contenedor de elementos
        const contenedorElementos = document.getElementById("contenedor-elementos");

        // Guardar el HTML del botón antes de limpiar
        const botonHTML = `
            <button type="button" class="btn-modal" id="abrir-modal-elementos" onclick="abrirModal('modal-elementos-usuario')">
            <img src="/imagenes/shopping.png" alt="Nuevo Registro" class="iconos">
            Ver Elementos
        </button>
        `;

        // Limpiar el contenedor y mantener el botón
        if (contenedorElementos) {
            contenedorElementos.innerHTML = botonHTML;
        }

        // Realiza la solicitud al servidor
        fetch(`/vigilante/elementos/${registroId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    crearYMostrarCards(data.elementos, "contenedor-elementos", false);
                } else {
                    alert(data.message || "No se encontraron elementos.");
                }
            })
            .catch(error => {
                console.error("Error al actualizar lista:", error);
                alert("Ocurrió un error al actualizar la lista de elementos.");
            });
    }

    //destacar fila seleccionada en la tabla de registros
    function destacarFilaSeleccionada(row) {
        // Remover la clase 'seleccionada' de todas las filas
        document.querySelectorAll('.registro-row').forEach(fila => {
            fila.classList.remove('seleccionada');
        });

        // Agregar la clase 'seleccionada' a la fila clickeada
        row.classList.add('seleccionada');
    }

    //para la escucha de la tabla
    const tablaBody = document.getElementById("tabla-reportes-body");

    if (tablaBody) {
        tablaBody.addEventListener("click", (e) => {
            const fila = e.target.closest(".registro-fila");
            if (!fila) return;

            const registroId = fila.getAttribute("data-registro-id");
            destacarFilaSeleccionada(fila);
            mostrarElementosPorRegistro(registroId);
        });
    } else {
        console.error("No se encontró el contenedor de la tabla.");
    }

    //le da diseño a los cards que salen cuando se selecciona un registro (osea una fila)
    function mostrarElementos(elementos) {
        const contenedorElementos = document.getElementById(
            "contenedor-elementos"
        ); // Contenedor de las tarjetas
        contenedorElementos.innerHTML = ""; // Limpiar el contenedor antes de agregar nuevos elementos

        elementos.forEach((elemento) => {
            // Aquí usamos la función crearCardElemento para cada elemento
            const cardElemento = crearCardElemento(elemento);
            contenedorElementos.innerHTML += cardElemento; // Agregar la card al contenedor
        });
    }

    // Función para mostrar detalles del elemento y abrir el modal
    window.mostrarDetallesElemento = function (elementoId) {
        try {

            // Verifica que 'elementos' esté disponible y tiene datos
            if (!Array.isArray(elementos) || elementos.length === 0) {
                console.error(
                    "El array 'elementos' no está disponible o está vacío."
                );
                alert("No se encontraron elementos para mostrar.");
                return;
            }

            // Buscar el elemento en el array por su ID
            const elemento = elementos.find(
                (el) => String(el.id) === String(elementoId)
            ); // Asegurarnos de que el tipo coincida

            // Verifica si el elemento fue encontrado
            if (!elemento) {
                console.error("Elemento no encontrado con ID: ", elementoId);
                alert("Elemento no encontrado.");
                return;
            }


            // Actualizar el contenido del modal con los datos del elemento
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

            // Llamar a la función de abrir el modal
            abrirModal("modalElemento");
        } catch (error) {
            console.error("Error al mostrar el detalle del elemento: ", error);
        }
    };

    // Función para cerrar el modal
    window.cerrarModal = function () {
        document.getElementById("modalElemento").style.display = "none";
    };

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
            // Registrar un nuevo elemento
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
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(
                            `Error ${response.status}: ${response.statusText}`
                        );
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success && data.elemento) {
                        alert("¡Elemento registrado exitosamente!");

                        // Agregar el nuevo elemento al contenedor
                        crearYMostrarCards(
                            [data.elemento],
                            "contenedor-elementos",
                            true
                        );

                        // Deshabilitar el botón del elemento registrado
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
            // Actualizar la lista de elementos
            fetch(`/vigilante/obtenerElementosPorRegistro/${registroId}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        crearYMostrarCards(
                            data.elementos,
                            "contenedor-elementos",
                            true
                        );
                        alert("Lista de elementos actualizada exitosamente.");
                    } else {
                        alert(data.message || "No se encontraron elementos.");
                    }
                })
                .catch((error) => {
                    console.error("Error al actualizar lista:", error);
                    alert(
                        "Ocurrió un error al actualizar la lista de elementos."
                    );
                });
        }
    };

    /**
     * Función para crear y mostrar cards en el contenedor especificado.
     * @param {Array} elementos - Lista de elementos para crear los cards.
     * @param {string} contenedorId - ID del contenedor donde se mostrarán los cards.
     * @param {boolean} permitirEliminar - Indica si los cards deben incluir el botón de eliminar.
     */
    async function crearYMostrarCards(
        elementosNuevos,
        contenedorId,
        permitirEliminar = false
    ) {
        const contenedor = document.getElementById(contenedorId);

        if (!contenedor) {
            console.error("No se encontró el contenedor con ID:", contenedorId);
            return;
        }

        // Mostrar el contenedor por si está oculto
        contenedor.style.display = "flex";

        // Actualizar el array global `elementos` para sincronizarlo con los nuevos datos
        elementos.push(...elementosNuevos); // Agregar nuevos elementos al array global

        // Verificar los IDs ya presentes en el contenedor para evitar duplicados
        const idsExistentes = new Set(
            Array.from(contenedor.querySelectorAll(".card")).map(
                (card) => card.dataset.elementId
            )
        );

        elementosNuevos.forEach((elemento) => {
            if (idsExistentes.has(String(elemento.id))) {
                return; // Evita duplicar elementos existentes
            }

            // Crear el contenedor principal del card
            const card = document.createElement("div");
            card.classList.add("card");
            card.dataset.elementId = elemento.id; // Asignar el ID del elemento para identificar duplicados

            // Crear y añadir el título
            const cabeza = document.createElement("h3");
            cabeza.classList.add("cabeza");
            cabeza.textContent = elemento.categoria.nombre;
            card.appendChild(cabeza);

            // Crear y añadir la imagen
            const img = document.createElement("img");
            img.classList.add("img-fluid", "mt-3", "elemento-foto");
            img.src = `/storage/${elemento.foto}`;
            img.alt = "Foto del elemento";
            card.appendChild(img);

            // Crear y añadir el párrafo para la serie
            const serie = document.createElement("p");
            serie.innerHTML = `<strong>Serie:</strong> ${elemento.serie}`;
            card.appendChild(serie);

            // Crear y añadir el párrafo para la marca
            const marca = document.createElement("p");
            marca.innerHTML = `<strong>Marca:</strong> ${elemento.marca}`;
            card.appendChild(marca);

            // Crear y añadir el enlace "Ver más"
            const linkVerMas = document.createElement("a");
            linkVerMas.href = "javascript:void(0)";
            linkVerMas.classList.add("link-ver-mas");
            linkVerMas.dataset.elementId = elemento.id;
            linkVerMas.textContent = "Ver más";
            linkVerMas.onclick = function () {
                mostrarDetallesElemento(elemento.id); // Mostrar detalles y abrir el modal
            };
            card.appendChild(linkVerMas);

            // Si permitirEliminar es verdadero, agregar el botón de eliminar
            if (permitirEliminar) {
                const btnEliminar = document.createElement("button");
                btnEliminar.classList.add("btn", "btn-danger", "btn-destroy");
                btnEliminar.dataset.elementId = elemento.id;
                btnEliminar.textContent = "Eliminar";

                // **Agregamos el evento para medir el tiempo y eliminar**
                btnEliminar.addEventListener("click", async function () {
                    const startTime = performance.now(); // Inicia el contador de tiempo
                    await eliminarElemento(elemento.id); // Llama a la función de eliminación
                    const endTime = performance.now(); // Finaliza el contador de tiempo
                });

                card.appendChild(btnEliminar);
            }

            // Añadir el card al contenedor
            contenedor.appendChild(card);
        });

    }

    async function eliminarElemento(id) {
        try {

            const response = await fetch(
                `/vigilante/sub_control_ingreso/${id}`,
                {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                }
            );


            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                document.querySelector(`[data-element-id='${id}']`)?.remove();
            } else {
                console.warn(
                    data.message || "No se pudo eliminar el elemento."
                );
            }
        } catch (error) {
            console.error("Error al eliminar el elemento:", error);
        }
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

    // Escucha de eventos global para botones
    document.addEventListener("DOMContentLoaded", function () {

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

        // Añadir eventos a botones de forma específica
        const botonesIngresar = document.querySelectorAll(".btn-ingresar");

        botonesIngresar.forEach((boton) => {
            boton.addEventListener("click", function () {
                const elementoId = this.getAttribute("data-id");
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
            });
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
                    cargarElementos(); // Sincronizar DOM tras la actualización
                } else {
                    alert(data.message || "Error desconocido.");
                }
            } catch (error) {
                console.error("Error en la solicitud:", error);
                alert("Ocurrió un error al intentar registrar el elemento.");
            }
        }

        async function cargarElementos() {
            try {
                const response = await fetch("/ruta-de-los-elementos");
                const data = await response.json();

                if (data.success) {
                    elementos = data.elementos; // Actualiza el array global
                    crearYMostrarCards(elementos, "contenedorElementos");
                } else {
                    console.error(
                        "Error al cargar los elementos:",
                        data.message
                    );
                }
            } catch (error) {
                console.error("Error en la solicitud de elementos:", error);
            }
        }
    });

    // Función para eliminar un registro de sub_control_ingreso
    document.querySelectorAll(".btn-destroy").forEach((button) => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const subControlId = this.getAttribute("data-subcontrol-id");

            if (subControlId) {
                eliminarSubControl(subControlId);
            } else {
                console.error("No se encontró un sub_control_id válido.");
            }
        });
    });

    function eliminarSubControl(subControlId) {
        if (!confirm("¿Estás seguro de que deseas eliminar este elemento?"))
            return;

        fetch(`/vigilante/sub_control_ingreso/${subControlId}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
        })
            .then((response) => {
                if (!response.ok)
                    throw new Error("Error en la solicitud de eliminación.");
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    document.getElementById(`card-${subControlId}`)?.remove();
                    alert("Elemento eliminado exitosamente.");
                    cargarElementos(); // Recargar los elementos
                } else {
                    alert(data.message || "No se pudo eliminar el elemento.");
                }
            })
            .catch((error) => {
                console.error("Error en la solicitud de eliminación:", error);
                alert("Ocurrió un error al eliminar el elemento.");
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

    // Agregar este código después de que se carga el DOM
    document.addEventListener("DOMContentLoaded", function () {
        // Verificar si hay un registro seleccionado previamente
        const selectedRegistroId = sessionStorage.getItem('selectedRegistroId');
        if (selectedRegistroId) {
            // Buscar la fila correspondiente
            const selectedRow = document.querySelector(`[data-registro-id="${selectedRegistroId}"]`);
            if (selectedRow) {
                // Destacar la fila seleccionada
                destacarFilaSeleccionada(selectedRow);
                // Mostrar los elementos del registro
                mostrarElementosPorRegistro(selectedRegistroId);
            }
            // Limpiar el ID almacenado
            sessionStorage.removeItem('selectedRegistroId');
        }
    });
});

// Función global para crear un nuevo registro control_ingresos
async function crearRegistro() {

    // Obtener los valores de los inputs ocultos
    const documentoVigilante = document.getElementById(
        "documento_vigilante"
    ).value;
    const usuarioId = document.getElementById("usuario-id-oculto").value;

    try {
        const response = await fetch("/nuevoRegistro", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                documento_vigilante: documentoVigilante,
                usuario_id: usuarioId,
            }),
        });

        const data = await response.json();

        if (data.success) {
            alert("Registro creado exitosamente.");

            // Aquí hacemos el reload de la página completa
            location.reload();
        } else {
            alert(data.message || "Error al crear el registro.");
        }
    } catch (error) {
        console.error("Error al crear el registro:", error);
    }
}

// Función para agregar una nueva fila a la tabla
function agregarFilaATabla(registro) {
    if (!registro || !registro.id) {
        console.error("El registro es inválido o no tiene ID:", registro);
        return;
    }

    const tablaBody = document.getElementById("tabla-reportes-body");
    if (!tablaBody) {
        console.error(
            "No se encontró el cuerpo de la tabla (tbody) con ID 'tabla-reportes-body'."
        );
        return;
    }

    // Eliminar la fila de mensaje "No se ha encontrado un registro"
    const filaNoRegistros = tablaBody.querySelector("tr");
    if (
        filaNoRegistros &&
        filaNoRegistros.children.length === 1 &&
        filaNoRegistros.textContent.trim() ===
        "No se ha encontrado un registro de control de ingreso."
    ) {
        filaNoRegistros.remove();

    }

    // Crear una nueva fila
    const fila = document.createElement("tr");
    fila.classList.add("registro-fila");
    fila.setAttribute("data-registro-id", registro.id);

    // Llenar la fila con los datos
    fila.innerHTML = `
        <td>${registro.id}</td>
        <td>${registro.centro?.nombre || "Centro no definido"}</td>
        <td>${registro.fecha_ingreso}</td>
        <td>${registro.fecha_salida || "N/A"}</td>
        <td>${registro.estado === 0 ? "Abierto" : "Cerrado"}</td>
    `;

    // Agregar la fila al inicio del tbody
    tablaBody.prepend(fila);
    // Verificar si no hay registros y mostrar el mensaje
    verificarYMostrarMensajeNoRegistros();
}

// Verificar si la tabla está vacía y mostrar el mensaje adecuado
function verificarYMostrarMensajeNoRegistros() {
    const tablaBody = document.getElementById("tabla-reportes-body");
    const filas = tablaBody.querySelectorAll("tr");

    // Si no hay filas (exceptuando el mensaje de "No hay registros disponibles")
    if (filas.length === 0) {
        const mensajeNoRegistros = document.createElement("tr");
        mensajeNoRegistros.innerHTML = `<td colspan="5">No se ha encontrado un registro de control de ingreso.</td>`;
        tablaBody.appendChild(mensajeNoRegistros);
    }
}

// Ejecutar todo cuando el DOM se haya cargado
document.addEventListener("DOMContentLoaded", function () {

    // Verificar si la tabla está vacía al cargar la página
    verificarYMostrarMensajeNoRegistros();

    // Asociar el evento al botón
    const botonAgregarRegistro = document.getElementById("agregar-registro");
    if (botonAgregarRegistro) {
        botonAgregarRegistro.addEventListener("click", crearRegistro);
    } else {
        console.error("No se encontró el botón con ID 'agregar-registro'.");
    }
});

//funcion para cerrar el registro
async function cerrarRegistro(id) {
    try {
        const confirmacion = confirm(
            "¿Estás seguro de que deseas cerrar este registro? No podrás agregar más elementos."
        );
        if (!confirmacion) return;

        const response = await fetch(
            `/vigilante/control_ingreso/${id}/cerrar`,
            {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            }
        );

        const data = await response.json();

        if (response.ok) {
            alert(data.message || "Registro cerrado exitosamente.");

            // Recargar toda la página después de cerrar el registro
            window.location.reload();
        } else {
            console.error("Error desde el servidor:", data);
            alert(data.message || "Hubo un problema al cerrar el registro.");
        }
    } catch (error) {
        console.error("Error al cerrar el registro:", error);
        alert("Ocurrió un error inesperado. Por favor, inténtalo de nuevo.");
    }
}

function actualizarTabla(id) {

    const tabla = document.getElementById("tabla-reportes"); // Cambié el id de la tabla a "tabla-reportes", ya que así lo tienes en el HTML

    if (!tabla) {
        console.error("No se encontró la tabla para actualizar.");
        return;
    }

    const fila = tabla.querySelector(`tr[data-registro-id="${id}"]`); // Usar el ID del registro para encontrar la fila
    if (fila) {
        // Actualizar el estado en la fila de la tabla
        const celdaEstado = fila.querySelector("td:last-child"); // Asegurémonos de que estamos seleccionando la celda correcta para el estado
        if (celdaEstado) {
            celdaEstado.textContent = "Cerrado"; // Cambiar a "Cerrado"
        }

        // Opcional: Desactivar acciones adicionales en esa fila
        const acciones = fila.querySelectorAll(".btn-accion"); // Botones u otras acciones
        acciones.forEach((btn) => {
            btn.disabled = true;
        });

    } else {
        console.warn("No se encontró la fila para actualizar en la tabla.");
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const btnCerrarRegistro = document.getElementById("guardar-registros");

    if (btnCerrarRegistro) {
        btnCerrarRegistro.addEventListener("click", function () {
            const id = btnCerrarRegistro.dataset.id; // Obtener el ID del botón
            if (id) {
                cerrarRegistro(id); // Llamar a la función de cerrar el registro
            } else {
                console.error("No se encontró el ID para cerrar el registro.");
            }
        });
    } else {
        console.error("El botón de cerrar registro no se encontró.");
    }
});
