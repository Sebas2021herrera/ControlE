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

    // Crear un nuevo registro
    if (btnNuevoRegistro) {
        btnNuevoRegistro.addEventListener("click", function () {
            const documentoVigilante = document.getElementById(
                "documento_vigilante"
            ).value;
            const usuarioId = inputUsuarioId.value;

            if (!documentoVigilante || !usuarioId) {
                alert("Faltan datos para realizar el registro.");
                return;
            }
            fetch("/vigilante/nuevoRegistro", {
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
                        alert("Registro creado correctamente.");
                        mostrarRegistros(data.registros);
                    } else {
                        alert(data.message);
                    }
                })
                .catch((error) => console.error("Error:", error));
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
        contenedorRegistros.innerHTML = "";

        registros.forEach((registro) => {
            const row = document.createElement("div");
            row.classList.add("registro-row");
            row.innerHTML = `
                <div class="registro-info" data-registro-id="${registro.id}">
                    <p><strong>ID Registro:</strong> ${registro.id}</p>
                    <p><strong>Fecha Ingreso:</strong> ${registro.fecha_ingreso}</p>
                </div>
            `;
            row.addEventListener("click", function () {
                mostrarElementosPorRegistro(registro.id);
            });
            contenedorRegistros.appendChild(row);
        });
    }

    // Mostrar elementos al hacer clic en una fila (registro)
    function mostrarElementosPorRegistro(registroId) {
        fetch(`/vigilante/obtenerElementosPorRegistro/${registroId}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    mostrarElementos(data.elementos);
                } else {
                    alert(data.message);
                }
            })
            .catch((error) => console.error("Error:", error));
    }

    // Mostrar los elementos registrados en el contenedor
    function mostrarElementos(elementos) {
        contenedorElementos.innerHTML = "";

        elementos.forEach((elemento) => {
            const card = document.createElement("div");
            card.classList.add("card");
            card.innerHTML = `
                <h3 class="cabeza">${elemento.categoria.nombre}</h3>
                <img src="${baseStorageUrl}/${elemento.foto}" alt="Foto del elemento" class="img-fluid mt-3">
                <p><strong>Serie:</strong> ${elemento.serie}</p>
                <p><strong>Marca:</strong> ${elemento.marca}</p>
                <button class="btn-destroy" onclick="verMasElemento(${elemento.id})">Ver más</button>
                <button class="btn-ingresar" onclick="registrarElementoEnSubControl(${elemento.id})">Ingresar</button>
            `;
            contenedorElementos.appendChild(card);
        });
    }

    // Función para mostrar los detalles de un elemento en el modal
    window.mostrarDetallesElemento = function (elementoId) {
        try {
            // Buscar el elemento en el arreglo local `elementos`
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

            // Mostrar el modal
            document.getElementById("modalElemento").style.display = "block";
        } catch (error) {
            console.error("Error:", error);
        }
    };

    // Función para cerrar el modal
    window.cerrarModal = function () {
        document.getElementById("modalElemento").style.display = "none";
    };

    // Registrar el elemento en el sub-control de ingreso
    window.registrarElementoEnSubControl = function (elementoId) {
        const registroId = document.getElementById("registro-id").value;

        if (!registroId) {
            alert("No se ha seleccionado un registro.");
            return;
        }

        fetch(`/vigilante/registrarElementoEnSubControl`, {
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
                if (data.success) {
                    alert("Elemento registrado correctamente.");
                    mostrarElementosPorRegistro(registroId);
                } else {
                    alert(data.message);
                }
            })
            .catch((error) => console.error("Error:", error));
    };
});
