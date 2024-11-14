document.addEventListener("DOMContentLoaded", function () {
    // Definir las funciones en el ámbito global
    window.mostrarModal = function (modalId = "modal-elementos-usuario") {
        console.log(`Intentando abrir el modal con ID: ${modalId}`);
        const modal = document.getElementById(modalId);

        if (modal) {
            modal.style.display = "block";
            console.log(`Modal con ID ${modalId} abierto correctamente.`);
        } else {
            console.error(`Modal con ID ${modalId} no encontrado en el DOM.`);
        }
    };

    window.cerrarModal = function (modalId = "modal-elementos-usuario") {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "none";
            console.log(`Modal con ID ${modalId} cerrado.`);
        } else {
            console.error(
                `Modal con ID ${modalId} no encontrado en el DOM para cerrarlo.`
            );
        }
    };

    // Asignar el evento al botón para abrir el modal
    const botonAbrirModal = document.getElementById("abrir-modal-elementos");
    if (botonAbrirModal) {
        botonAbrirModal.addEventListener("click", function (event) {
            event.stopPropagation(); // Evita que el clic se propague a otros elementos
            mostrarModal(); // Abre el modal por defecto
        });
    }

    // Asignar el evento al botón de cerrar dentro del modal
    const botonCerrarModal = document.querySelector(".btn-cerrar-modal");
    if (botonCerrarModal) {
        botonCerrarModal.addEventListener("click", function () {
            cerrarModal(); // Cierra el modal por defecto
        });
    }

    // Función para manejar el clic en una fila de la tabla
    function handleFilaClick() {
        document
            .querySelectorAll(".registro-fila")
            .forEach((f) => f.classList.remove("fila-seleccionada"));
        this.classList.add("fila-seleccionada");
        const registroId = this.getAttribute("data-registro-id");
        obtenerElementosAsociados(registroId);
    }

    // Asignar eventos a las filas de la tabla
    function aplicarEventListeners() {
        document.querySelectorAll(".registro-fila").forEach((fila) => {
            fila.removeEventListener("click", handleFilaClick);
            fila.addEventListener("click", handleFilaClick);
        });
    }

    // Ejecutar la asignación de eventos al cargar el DOM
    aplicarEventListeners();

    // Función para obtener elementos asociados
    function obtenerElementosAsociados(registroId) {
        fetch(`/vigilante/elementos/${registroId}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    mostrarElementos(data.elementos);
                } else {
                    alert("No se encontraron elementos asociados.");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    }

    // Función para mostrar elementos en el modal
    function mostrarElementos(elementos) {
        const contenedorElementos = document.querySelector(".elementos");
        const botonVerElementos = document.getElementById(
            "abrir-modal-elementos"
        );
        contenedorElementos.innerHTML = ""; // Limpiar los elementos actuales

        elementos.forEach((elemento) => {
            const card = document.createElement("div");
            card.classList.add("card");
            card.innerHTML = `
<h3 class="cabeza">${elemento.categoria.nombre}</h3>
<img src="${baseStorageUrl}/${elemento.foto}" alt="Foto del elemento" class="img-fluid mt-3 elemento-foto">
<p><strong>Serie:</strong> ${elemento.serie}</p>
<p><strong>Marca:</strong> ${elemento.marca}</p>
<a href="#" class="link-ver-mas" onclick="mostrarDetallesElemento('${elemento.id}')">Ver más</a>
`;
            contenedorElementos.appendChild(card);
        });

        contenedorElementos.appendChild(botonVerElementos); // Reinsertar el botón
    }

    // Función para mostrar detalles del elemento
    window.mostrarDetallesElemento = function (elementoId) {
        const modal = document.getElementById("modalElemento");
        const descripcion = document.getElementById("descripcionElemento");
        const marca = document.getElementById("marcaElemento");
        const modelo = document.getElementById("modeloElemento");
        const serie = document.getElementById("serieElemento");
        const especificaciones = document.getElementById(
            "especificacionesElemento"
        );
        const foto = document.getElementById("fotoElemento");
        const categoria = document.getElementById("categoriaElemento");

        // Mostrar el modal
        modal.style.display = "block";

        // Hacer la petición para obtener los detalles del elemento
        fetch(`/elementos/detalles/${elementoId}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Error en la respuesta de la red");
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    // Actualizar los elementos del modal con los datos del elemento
                    descripcion.textContent =
                        data.elemento.descripcion || "No disponible";
                    marca.textContent = data.elemento.marca || "No disponible";
                    modelo.textContent =
                        data.elemento.modelo || "No disponible";
                    serie.textContent = data.elemento.serie || "No disponible";
                    especificaciones.textContent =
                        data.elemento.especificaciones || "No disponible";
                    categoria.textContent =
                        data.elemento.categoria || "No disponible";

                    // Generar la ruta de la imagen
                    foto.src = data.elemento.foto
                        ? `${baseStorageUrl}/${data.elemento.foto}`
                        : "/path/to/default-image.jpg";
                } else {
                    alert("No se encontraron detalles para este elemento.");
                }
            })
            .catch((error) => {
                console.error("Error al obtener detalles:", error);
                alert("Ocurrió un error al cargar los detalles del elemento.");
            });
    };

    // Función para cerrar el modal
    function cerrarModal() {
        const modal = document.getElementById("modalElemento");
        if (modal) {
            modal.style.display = "none"; // Oculta el modal
        }
    }

    // Asegúrate de que el evento de clic esté correctamente asignado
    document
        .getElementById("closeModal")
        .addEventListener("click", cerrarModal);

    // Cerrar el modal si el usuario hace clic fuera de la ventana
    window.onclick = function (event) {
        const modal = document.getElementById("modal-elementos-usuario");
        if (modal && event.target === modal) {
            cerrarModalElementos();
        }
    };

    // Manejo de clics fuera del modal para cerrarlo
    window.addEventListener("click", function (event) {
        const modal = document.getElementById("modal-elementos-usuario");
        if (modal && event.target === modal) {
            cerrarModal();
        }
    });

    // Función para registrar el control de ingreso
    document
        .getElementById("agregar-registro")
        .addEventListener("click", function (event) {
            event.preventDefault(); // Evita recargar la página

            const documentoVigilante = document.getElementById(
                "documento_vigilante"
            ).value; // Asegúrate de obtener el valor correcto
            const usuarioId =
                document.getElementById("usuario-id-oculto").value; // Asegúrate de obtener el valor correcto

            if (!usuarioId) {
                alert("No se ha encontrado información del usuario.");
                return;
            }

            fetch(registroUrl, {
                // Asegúrate de que registroUrl esté definido correctamente
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
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Error en la respuesta de la red");
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        // Actualizar la tabla automáticamente sin alertas
                        limpiarTabla();
                        agregarRegistrosATabla(data.registros);
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        });

    // Función para limpiar la tabla
    function limpiarTabla() {
        const tablaBody = document.getElementById("tabla-reportes-body");
        tablaBody.innerHTML = ""; // Limpia todo el contenido de la tabla
    }

    // Función para agregar registros a la tabla
    function agregarRegistrosATabla(registros) {
        const tablaBody = document.getElementById("tabla-reportes-body");

        registros.forEach((registro) => {
            const fila = document.createElement("tr");
            fila.classList.add("registro-fila");
            fila.setAttribute("data-registro-id", registro.id);
            fila.innerHTML = `
            <td>${registro.id}</td>
            <td>${registro.centro?.nombre ?? "Centro no definido"}</td>
            <td>${registro.fecha_ingreso}</td>
            <td>${registro.fecha_salida ?? "N/A"}</td>
            <td>${registro.estado == 0 ? "Abierto" : "Cerrado"}</td>
        `;

            // Insertar la nueva fila al inicio de la tabla
            tablaBody.insertBefore(fila, tablaBody.firstChild);
        });

        aplicarEventListeners(); // Asegúrate de que las nuevas filas tengan los eventos de clic
    }

    // Función sub_control_ingresos para registrar y asociar los elementos al control de ingreso
    document.querySelectorAll(".btn-ingresa").forEach((button) => {
        button.addEventListener("click", function () {
            const elementoId = this.getAttribute("data-elemento-id");
            const controlIngresoId =
                document.getElementById("control_ingreso_id").value; // Captura el ID

            if (!controlIngresoId) {
                alert("No se ha encontrado un registro de control de ingreso.");
                return;
            }

            fetch(subControlIngresoUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    control_ingreso_id: controlIngresoId,
                    elemento_id: elementoId,
                }),
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Error en la respuesta de la red");
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        mostrarMensajeExito(
                            "Elemento registrado exitosamente."
                        );
                        agregarElementoAlContenedor(data.elemento);
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        });
    });

    // Función para mostrar mensajes de éxito
    function mostrarMensajeExito(mensaje) {
        const mensajeContainer = document.createElement("div");
        mensajeContainer.className = "alert alert-success";
        mensajeContainer.textContent = mensaje;

        // Insertar el mensaje después del contenedor del buscador
        const buscador = document.querySelector(".buscador");
        buscador.insertAdjacentElement("afterend", mensajeContainer);

        setTimeout(() => {
            mensajeContainer.style.opacity = "0";
            setTimeout(() => mensajeContainer.remove(), 500);
        }, 5000);
    }

    // Función para agregar un elemento al contenedor
    function agregarElementoAlContenedor(elemento) {
        const contenedorElementos = document.querySelector(".elementos");
        const card = document.createElement("div");
        card.classList.add("card");
        card.innerHTML = `
<h3 class="cabeza">${elemento.categoria.nombre}</h3>
<img src="${baseStorageUrl}/${elemento.foto}" alt="Foto del elemento" class="img-fluid mt-3 elemento-foto">
<p><strong>Serie:</strong> ${elemento.serie}</p>
<p><strong>Marca:</strong> ${elemento.marca}</p>
<a href="#" class="link-ver-mas" style="margin-top:-10px" onclick="mostrarDetallesElemento('${elemento.id}')">Ver más</a>
<button type="button" class="btn-destroy" onclick="eliminarElemento('${elemento.id}')">Eliminar</button>
`;
        contenedorElementos.appendChild(card);
    }

    // Función para mostrar el modal de elementos del usuario
    window.mostrarModalElementos = function () {
        const modal = document.getElementById("modal-elementos-usuario");
        if (modal) {
            modal.style.display = "block"; // Muestra el modal
        } else {
            console.error("Modal no encontrado");
        }
    };

    // Función para cerrar el modal
    window.cerrarModal = function () {
        const modal = document.getElementById("modalElemento");
        if (modal) {
            modal.style.display = "none"; // Oculta el modal
        }
    };

    // Función para cerrar el modal de elementos
    function cerrarModalElementos() {
        const modal = document.getElementById("modal-elementos-usuario");
        if (modal) {
            modal.style.display = "none"; // Oculta el modal
        }
    }
});

document.addEventListener("DOMContentLoaded", function () {
    // Aquí van tus funciones JavaScript
    window.cerrarModalElementos = function () {
        const modal = document.getElementById("modal-elementos-usuario");
        if (modal) {
            modal.style.display = "none"; // Oculta el modal
        }
    };
});
