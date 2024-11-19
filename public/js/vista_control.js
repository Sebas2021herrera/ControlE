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
    function actualizarElementosAsociados(registroId) {
        fetch(`/vigilante/elementos/${registroId}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    mostrarElementos(data.elementos);
                } else {
                    alert("No se encontraron elementos asociados.");
                }
            })
            .catch((error) => console.error("Error:", error));
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

            // Verificar que ambos valores estén presentes
            if (!controlIngresoId) {
                alert("No se ha encontrado un registro de control de ingreso.");
                return;
            }

            if (!elementoId) {
                alert("No se ha seleccionado un elemento para ingresar.");
                return;
            }

            // Hacer la solicitud al servidor
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
                        throw new Error(
                            `Error en la respuesta de la red: ${response.statusText}`
                        );
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        // Mostrar mensaje de éxito
                        mostrarMensajeExito(
                            "Elemento registrado exitosamente."
                        );

                        // Actualizar los elementos asociados al control de ingreso
                        actualizarElementosAsociados(controlIngresoId);

                        // Agregar el nuevo elemento al contenedor de elementos
                        agregarElementoAlContenedor(data.elemento);
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert(
                        "Se produjo un error al registrar el elemento: " +
                            error.message
                    );
                });
        });
    });

    // Función para mostrar un mensaje de éxito
    function mostrarMensajeExito(mensaje) {
        const mensajeElemento = document.createElement("div");
        mensajeElemento.classList.add("mensaje-exito");
        mensajeElemento.textContent = mensaje;
        document.body.appendChild(mensajeElemento);
        setTimeout(() => mensajeElemento.remove(), 3000); // Eliminar el mensaje después de 3 segundos
    }

    // Función para actualizar los elementos asociados
    function actualizarElementosAsociados(controlIngresoId) {
        fetch(`/vigilante/elementos_asociados/${controlIngresoId}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Actualiza la vista de los elementos asociados
                    mostrarElementosAsociados(data.elementos);
                } else {
                    console.log(
                        "No se pudieron obtener los elementos asociados."
                    );
                }
            })
            .catch((error) => {
                console.error(
                    "Error al obtener los elementos asociados:",
                    error
                );
            });
    }

    // Función para mostrar los elementos asociados a la vista
    function mostrarElementosAsociados(elementos) {
        const contenedor = document.getElementById("contenedor-elementos");
        contenedor.innerHTML = ""; // Limpiar el contenedor

        // Crear una lista o una tabla con los elementos asociados
        elementos.forEach((elemento) => {
            const elementoDiv = document.createElement("div");
            elementoDiv.classList.add("elemento");
            elementoDiv.textContent = `Elemento ID: ${elemento.id}, Nombre: ${elemento.nombre}`;
            contenedor.appendChild(elementoDiv);
        });
    }

    // Función para agregar un elemento al contenedor de elementos
    function agregarElementoAlContenedor(elemento) {
        const contenedor = document.getElementById("contenedor-elementos");
        const elementoDiv = document.createElement("div");
        elementoDiv.classList.add("elemento");
        elementoDiv.textContent = `Elemento ID: ${elemento.id}, Nombre: ${elemento.nombre}`;
        contenedor.appendChild(elementoDiv);
    }

    // Función para mostrar un mensaje de éxito
    function mostrarMensajeExito(mensaje) {
        const mensajeElemento = document.createElement("div");
        mensajeElemento.classList.add("mensaje-exito");
        mensajeElemento.textContent = mensaje;
        document.body.appendChild(mensajeElemento);
        setTimeout(() => mensajeElemento.remove(), 3000); // Eliminar el mensaje después de 3 segundos
    }

    // Función para actualizar los elementos asociados
    function actualizarElementosAsociados(controlIngresoId) {
        fetch(`/vigilante/elementos_asociados/${controlIngresoId}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Actualiza la vista de los elementos asociados
                    mostrarElementosAsociados(data.elementos);
                } else {
                    console.log(
                        "No se pudieron obtener los elementos asociados."
                    );
                }
            })
            .catch((error) => {
                console.error(
                    "Error al obtener los elementos asociados:",
                    error
                );
            });
    }

    // Función para mostrar los elementos asociados a la vista
    function mostrarElementosAsociados(elementos) {
        const contenedor = document.getElementById("contenedor-elementos");
        contenedor.innerHTML = ""; // Limpiar el contenedor

        // Crear una lista o una tabla con los elementos asociados
        elementos.forEach((elemento) => {
            const elementoDiv = document.createElement("div");
            elementoDiv.classList.add("elemento");
            elementoDiv.textContent = `Elemento ID: ${elemento.id}, Nombre: ${elemento.nombre}`;
            contenedor.appendChild(elementoDiv);
        });
    }

    // Función para agregar un elemento al contenedor de elementos
    function agregarElementoAlContenedor(elemento) {
        const contenedor = document.getElementById("contenedor-elementos");
        const elementoDiv = document.createElement("div");
        elementoDiv.classList.add("elemento");
        elementoDiv.textContent = `Elemento ID: ${elemento.id}, Nombre: ${elemento.nombre}`;
        contenedor.appendChild(elementoDiv);
    }

    // Función para limpiar los campos del formulario (si es necesario)
    function resetFormulario() {
        document.getElementById("control_ingreso_id").value = ""; // Limpiar el ID del control de ingreso
        // Limpiar otros campos si los hay, por ejemplo:
        // document.getElementById("otro-campo").value = '';
    }

    // Función para mostrar un mensaje de éxito
    function mostrarMensajeExito(mensaje) {
        const mensajeElemento = document.createElement("div");
        mensajeElemento.classList.add("mensaje-exito");
        mensajeElemento.textContent = mensaje;
        document.body.appendChild(mensajeElemento);
        setTimeout(() => mensajeElemento.remove(), 3000); // Eliminar el mensaje después de 3 segundos
    }

    // Función para actualizar los elementos asociados
    function actualizarElementosAsociados(controlIngresoId) {
        // Asegúrate de que controlIngresoId sea válido
        if (!controlIngresoId) {
            console.error("El ID de control de ingreso no es válido.");
            return;
        }

        fetch(`/vigilante/elementos/${controlIngresoId}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(
                        "No se pudieron obtener los elementos asociados."
                    );
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    mostrarElementosAsociados(data.elementos); // Llama a la función para mostrar los elementos
                } else {
                    console.log("No se encontraron elementos asociados.");
                }
            })
            .catch((error) => {
                console.error(
                    "Error al obtener los elementos asociados:",
                    error
                );
            });
    }

    // Función para mostrar los elementos asociados a la vista
    function mostrarElementosAsociados(elementos) {
        // Asegúrate de que el contenedor donde se mostrarán los elementos exista
        const contenedorElementos = document.getElementById(
            "contenedor-elementos"
        ); // O usa el selector correcto para tu contenedor

        // Verifica que el contenedor esté disponible
        if (contenedorElementos) {
            // Limpia el contenedor antes de agregar nuevos elementos
            contenedorElementos.innerHTML = "";

            // Si existen elementos, agregar al contenedor
            if (elementos.length > 0) {
                elementos.forEach(function (elemento) {
                    const divElemento = document.createElement("div");
                    divElemento.textContent = elemento.nombre; // Asegúrate de que el nombre del elemento sea correcto
                    contenedorElementos.appendChild(divElemento);
                });
            } else {
                contenedorElementos.innerHTML = "No se encontraron elementos.";
            }
        }
    }

    // Función para agregar un elemento al contenedor de elementos
    function agregarElementoAlContenedor(elemento) {
        const contenedor = document.getElementById("contenedor-elementos");
        const elementoDiv = document.createElement("div");
        elementoDiv.classList.add("elemento");
        elementoDiv.textContent = `Elemento ID: ${elemento.id}, Nombre: ${elemento.nombre}`;
        contenedor.appendChild(elementoDiv);
    }

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

//esta es la funcion para eliminar elementos del contenedor de elementos
document.addEventListener("click", function (event) {
    if (event.target.classList.contains("btn-destroy")) {
        const elementRow = event.target.closest("tr"); // Encuentra la fila del elemento
        const elementId = elementRow.getAttribute("data-element-id"); // Obtén el ID del elemento si existe

        if (elementId) {
            // Realiza una solicitud AJAX para eliminar el elemento de la base de datos
            fetch(`/elementos/${elementId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => {
                    if (response.ok) {
                        elementRow.remove(); // Elimina la fila de la tabla en la vista
                        alert("Elemento eliminado correctamente.");
                    } else {
                        alert(
                            "No se pudo eliminar el elemento. Inténtalo nuevamente."
                        );
                    }
                })
                .catch((error) => console.error("Error:", error));
        } else {
            // Elimina la fila localmente si no tiene ID asociado en la base de datos
            elementRow.remove();
            alert("Elemento eliminado localmente.");
        }
    }
});

// Esta es la función para guardar los registros
document.addEventListener("DOMContentLoaded", function () {
    const guardarRegistrosButton = document.getElementById("guardar-registros");

    if (guardarRegistrosButton) {
        guardarRegistrosButton.addEventListener("click", function () {
            // Obtener el id del registro desde el atributo data-id del botón
            const activeRecordId =
                guardarRegistrosButton.getAttribute("data-id");

            console.log("ID del registro:", activeRecordId); // DEPURACIÓN

            if (activeRecordId) {
                // Fecha actual para pruebas (no es usada en el backend)
                const fechaEgreso = new Date().toISOString();

                console.log(
                    "Intentando cerrar el registro con fecha:",
                    fechaEgreso
                ); // DEPURACIÓN

                // Realizar la solicitud PUT
                fetch(`/vigilante/control_ingreso/${activeRecordId}/cerrar`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({ registro_id: activeRecordId }),
                })
                    .then((response) => {
                        console.log("Respuesta del servidor:", response); // DEPURACIÓN

                        if (response.ok) {
                            alert("Registro cerrado correctamente.");
                            location.reload();
                        } else {
                            return response.json().then((data) => {
                                alert(
                                    `No se pudo cerrar el registro: ${
                                        data.message || "Error desconocido"
                                    }`
                                );
                            });
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    ); // DEPURACIÓN
            } else {
                alert("No se encontró un ID de registro válido.");
            }
        });
    }
});

//Funciones para resetear el formulario
function resetFormulario() {
    // Limpiar los campos del formulario
    document.getElementById("documento_vigilante").value = "";
    document.getElementById("usuario-id-oculto").value = "";
    // Limpiar cualquier otro campo que sea necesario
}
function cerrarRegistro() {
    // Resetear el formulario
    resetFormulario();
    // Otros pasos para cerrar el registro
}
