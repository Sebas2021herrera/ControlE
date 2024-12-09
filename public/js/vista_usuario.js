// Script para mostrar ficha solo si es rol aprendiz
document.addEventListener("DOMContentLoaded", function () {
    const numeroFichaField = document.getElementById("numeroFichaFieldModal");
    const numeroFichaInput = document.getElementById("numero_ficha_modal");

    if (numeroFichaField) {
        numeroFichaField.style.display = "block";
        numeroFichaInput.required = true;
    } else {
        numeroFichaField.style.display = "none";
        numeroFichaInput.required = false;
    }
});

// Funciones para editar y guardar cambios
function editElement(id) {
    document.getElementById("details-view-" + id).classList.add("d-none");
    document.getElementById("edit-view-" + id).classList.remove("d-none");
    document
        .getElementById("save-changes-btn-" + id)
        .classList.remove("d-none");
}

function saveChanges(id) {
    document
        .getElementById("edit-view-" + id)
        .querySelector("form")
        .submit();
}

function closeModal(id) {
    const editView = document.getElementById("edit-view-" + id);
    const detailsView = document.getElementById("details-view-" + id);
    const saveChangesBtn = document.getElementById("save-changes-btn-" + id);

    if (!editView.classList.contains("d-none")) {
        editView.classList.add("d-none");
        detailsView.classList.remove("d-none");
        saveChangesBtn.classList.add("d-none");
    } else {
        document.querySelector(`#modal-${id} .btn-close`).click();
    }
}

// Manejo de mensajes de éxito
$(document).ready(function () {
    const successMessage = $(".alert-success");
    if (successMessage.length) {
        setTimeout(() => {
            successMessage.fadeOut(500);
        }, 5000);
    }
});

let errorShown = false; // Asegúrate de declarar la variable solo una vez

$(document).ready(function () {
    $("#editarPerfilForm").on("submit", function (event) {
        event.preventDefault(); // Evitar el envío predeterminado del formulario

        const formData = new FormData(this);

        $.ajax({
            url: $(this).attr("action"), // Ruta del backend
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    alert(response.success); // Mostrar mensaje de éxito desde el backend
                    location.reload(); // Recargar la página completa
                } else {
                    alert(
                        "No se pudieron guardar los cambios. Intente nuevamente."
                    );
                }
            },
            error: function (xhr) {
                // Manejo de errores
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors; // Errores de validación del backend
                    let errorMessages = "";

                    for (const field in errors) {
                        errorMessages += errors[field].join(", ") + "\n"; // Concatenar mensajes de error
                    }

                    alert(errorMessages); // Mostrar los errores al usuario
                } else if (xhr.status === 500) {
                    alert("Ocurrió un error interno. Intente más tarde.");
                } else if (xhr.status === 403) {
                    alert(xhr.responseJSON.error); // Mensaje para errores de permisos
                } else {
                    alert(
                        "Ocurrió un error inesperado. Por favor, inténtelo nuevamente."
                    );
                }
            },
        });
    });
});

// Función para previsualizar imagen
function previewImage(event, previewId) {
    var file = event.target.files[0];
    var preview = document.getElementById(previewId);

    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = "/imagenes/sin_foto_perfil.webp";
        preview.style.display = "none";
    }
}
