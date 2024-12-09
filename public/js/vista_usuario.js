// Script para mostrar ficha solo si es rol aprendiz
document.addEventListener('DOMContentLoaded', function() {
    const numeroFichaField = document.getElementById('numeroFichaFieldModal');
    const numeroFichaInput = document.getElementById('numero_ficha_modal');

    if (numeroFichaField) {
        numeroFichaField.style.display = 'block';
        numeroFichaInput.required = true;
    } else {
        numeroFichaField.style.display = 'none';
        numeroFichaInput.required = false;
    }
});

// Funciones para editar y guardar cambios
function editElement(id) {
    document.getElementById('details-view-' + id).classList.add('d-none');
    document.getElementById('edit-view-' + id).classList.remove('d-none');
    document.getElementById('save-changes-btn-' + id).classList.remove('d-none');
}

function saveChanges(id) {
    document.getElementById('edit-view-' + id).querySelector('form').submit();
}

function closeModal(id) {
    const editView = document.getElementById('edit-view-' + id);
    const detailsView = document.getElementById('details-view-' + id);
    const saveChangesBtn = document.getElementById('save-changes-btn-' + id);

    if (!editView.classList.contains('d-none')) {
        editView.classList.add('d-none');
        detailsView.classList.remove('d-none');
        saveChangesBtn.classList.add('d-none');
    } else {
        document.querySelector(`#modal-${id} .btn-close`).click();
    }
}

// Manejo de mensajes de éxito
$(document).ready(function() {
    const successMessage = $('.alert-success');
    if (successMessage.length) {
        setTimeout(() => {
            successMessage.fadeOut(500);
        }, 5000);
    }
});

// Manejo de la edición del perfil
$(document).ready(function() {
    $('#editarPerfilForm').on('submit', function(event) {
        event.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                updateUserProfile(response.user);
                $('#editarPerfilModal').modal('hide');
                $('#success-message').text(response.success).fadeIn().delay(5000).fadeOut();
                
                // Actualizar la imagen del panel izquierdo
                if (response.user.foto) {
                    $('#left-panel-img').attr('src', '/storage/fotos_perfil/' + response.user.foto);
                } else {
                    $('#left-panel-img').attr('src', '/imagenes/sin_foto_perfil.webp');
                }
                
                // También actualizar la imagen en el carnet digital si existe
                if ($('#carnetDigital .foto-perfil').length) {
                    if (response.user.foto) {
                        $('#carnetDigital .foto-perfil').attr('src', '/storage/fotos_perfil/' + response.user.foto);
                    } else {
                        $('#carnetDigital .foto-perfil').attr('src', '/imagenes/sin_foto_perfil.webp');
                    }
                }
            },
            error: function(response) {
                $('#error-message').text('Ocurrió un error al actualizar el perfil.').fadeIn().delay(5000).fadeOut();
            }
        });
    });

    function updateUserProfile(user) {
        // Actualizar nombre de usuario
        $('.nombre-usuario').text(user.nombres + ' ' + user.apellidos);
        
        // Actualizar mensaje de bienvenida
        $('#welcomeMessage').html('<strong>Bienvenido</strong><br/>');
        
        // Actualizar otros elementos que muestren información del usuario
        if (user.foto) {
            $('#left-panel-img, #carnetDigital .foto-perfil').attr('src', '/storage/fotos_perfil/' + user.foto);
        }
    }
});

// Función para previsualizar imagen
function previewImage(event, previewId) {
    var file = event.target.files[0];
    var preview = document.getElementById(previewId);

    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = '/imagenes/sin_foto_perfil.webp';
        preview.style.display = 'none';
    }
}
