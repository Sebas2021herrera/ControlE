 document.addEventListener('DOMContentLoaded', function() {
            function previewImage(inputId, previewId) {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);
        
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    } else {
                        preview.src = '#';
                        preview.style.display = 'none';
                    }
                });
            }
        
            // Inicializar la previsualización para ambos formularios
            previewImage('foto', 'previewUsuario');
            previewImage('fotoElemento', 'previewElemento');
        });
        


        document.addEventListener('DOMContentLoaded', function() {
            var rolSelect = document.getElementById('rol');
            var numeroFichaField = document.getElementById('numero_ficha'); // Actualizado
            var numeroFichaDiv = numeroFichaField.closest('.field');

        function toggleNumeroFicha() {
            if (rolSelect.value == 3) { // Aprendiz
                numeroFichaDiv.style.display = 'block';
                numeroFichaField.setAttribute('required', 'required');
            } else {
                numeroFichaDiv.style.display = 'none';
                numeroFichaField.removeAttribute('required');
            }
        }

        rolSelect.addEventListener('change', toggleNumeroFicha);

        // Llamar a la función para inicializar el estado correcto en caso de que el rol esté preseleccionado
        toggleNumeroFicha();
    });

        document.addEventListener('DOMContentLoaded', function() {
            // Selección de elementos
            const registerUsersLink = document.getElementById('registerUsers');
            const registerElementsLink = document.getElementById('registerElements');
            const registerModal = document.getElementById('registerModal');
            const elementsModal = document.getElementById('elementsModal');
            const closeBtns = document.querySelectorAll('.close-btn');

            // Función para abrir modal
            function openModal(modal) {
                if (modal) {
                    modal.style.display = 'block';
                }
            }

            // Función para cerrar modal
            function closeModal(modal) {
                if (modal) {
                    modal.style.display = 'none';
                }
            }

            // Event listener para abrir modal de usuarios
            if (registerUsersLink) {
                registerUsersLink.addEventListener('click', (event) => {
                    event.preventDefault();
                    openModal(registerModal);
                });
            }

            // Event listener para abrir modal de elementos
            if (registerElementsLink) {
                registerElementsLink.addEventListener('click', (event) => {
                    event.preventDefault();
                    openModal(elementsModal);
                });
            }

            // Event listeners para cerrar modales con el botón X
            closeBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = btn.closest('.modal');
                    closeModal(modal);
                });
            });

            // Event listener para cerrar modales al hacer clic fuera
            window.addEventListener('click', (event) => {
                if (event.target.classList.contains('modal')) {
                    closeModal(event.target);
                }
            });

            // Mantener el resto de la funcionalidad de validación de contraseña
            document.getElementById('registroForm').addEventListener('submit', function (e) {
                var password = document.getElementById('contraseña').value;
                var confirmPassword = document.getElementById('contraseña_confirmation').value;
                var errorElement = document.getElementById('confirmarContrasenaError');

                if (password !== confirmPassword) {
                    e.preventDefault();
                    errorElement.textContent = 'Las contraseñas no coinciden';
                } else {
                    errorElement.textContent = '';
                }
            });
        });

    document.addEventListener('DOMContentLoaded', function() {
        // Selección de elementos para el modal de consulta
        const consultUsersLink = document.getElementById('consultUsers');
        const consultUsersModal = document.getElementById('consultUsersModal');
        const closeBtns = document.querySelectorAll('.close-btn');

        // Event listener para abrir modal de consulta de usuarios
        if (consultUsersLink) {
            consultUsersLink.addEventListener('click', function(event) {
                event.preventDefault();
                if (consultUsersModal) {
                    consultUsersModal.style.display = 'block';
                }
            });
        }

        // Event listeners para cerrar modales
        closeBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = btn.closest('.modal');
                if (modal) {
                    modal.style.display = 'none';
                }
            });
        });

        // Cerrar modal al hacer clic fuera
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.querySelector('form[action="{{ route("admin.usuarios.consultar") }}"]');
        const consultUsersModal = document.getElementById('consultUsersModal');
        const resultadoBusqueda = document.querySelector('.resultado-busqueda');
    
        if (searchForm) {
            searchForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                console.log('Formulario enviado');
    
                try {
                    const formData = new FormData(this);
                    const documento = formData.get('documento');
    
                    const response = await fetch(`/admin/usuarios/consultar?documento=${documento}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
    
                    if (response.ok) {
                        const data = await response.json();
                        console.log('Respuesta recibida:', data);
    
                        if (data.success) {
                            // Cerrar el modal si se usa Bulma
                            consultUsersModal.classList.add('is-hidden');
    
                            mostrarInformacionUsuario(data.usuario);
                            mostrarElementosUsuario(data.elementos || []);
                            resultadoBusqueda.style.display = 'block';
                        } else {
                            alert(data.mensaje || 'Usuario no encontrado');
                        }
                    } else {
                        throw new Error(`Error en la búsqueda: ${response.statusText}`);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al realizar la búsqueda');
                }
            });
        }
    });
    
    function mostrarInformacionUsuario(usuario) {
        console.log('Mostrando información del usuario:', usuario);
        document.querySelector('.usuario-nombre').textContent = usuario.nombres || 'N/A';
        document.querySelector('.usuario-apellidos').textContent = usuario.apellidos || 'N/A';
        document.querySelector('.usuario-documento').textContent = usuario.numero_documento || 'N/A';
        document.querySelector('.usuario-telefono').textContent = usuario.telefono || 'N/A';
        document.querySelector('.usuario-rh').textContent = usuario.rh || 'N/A';
        document.querySelector('.usuario-rol').textContent = usuario.role?.nombre || 'N/A';
        document.querySelector('.usuario-ficha').textContent = usuario.numero_ficha || 'N/A';
    
        const fotoPerfil = document.querySelector('.foto-perfil-usuario');
        if (fotoPerfil) {
            fotoPerfil.src = usuario.foto 
                ? `/storage/fotos_perfil/${usuario.foto}` 
                : '/imagenes/sin_foto_perfil.webp';
        }
    }
    
    function mostrarElementosUsuario(elementos) {
        console.log('Mostrando elementos del usuario:', elementos);
        const container = document.querySelector('.card-container');
        if (!container) return;
    
        container.innerHTML = '';
    
        if (elementos.length === 0) {
            container.innerHTML = '<p>No hay elementos asociados a este usuario.</p>';
            return;
        }
    
        elementos.forEach(elemento => {
            const card = `
                <div class="card">
                    <h5 class="cabeza">${elemento.categoria?.nombre || 'Sin categoría'}</h5>
                    <img src="/storage/${elemento.foto}" 
                         alt="Foto del elemento" 
                         class="elemento-foto"
                         onerror="this.src='/imagenes/sin_foto_elemento.webp'">
                    <p><strong>Serial:</strong> ${elemento.serie}</p>
                    <p><strong>Marca:</strong> ${elemento.marca}</p>
                    <a href="#" class="link-ver-mas" data-elemento-id="${elemento.id}">
                        Ver más
                    </a>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', card);
        });
    }
    

// Código JavaScript para el modal de "Detalles del Elemento".
// Función para alternar entre modos de vista y edición
function toggleEditView(elementId) {
    const viewMode = document.getElementById(`view-${elementId}`);
    const editMode = document.getElementById(`edit-${elementId}`);
    const editButton = document.getElementById(`edit-button-${elementId}`);
    const cancelButton = document.getElementById(`cancel-button-${elementId}`);

    // Alternar las clases para ocultar o mostrar las secciones de vista y edición
    viewMode.classList.toggle('hidden');
    editMode.classList.toggle('hidden');

    // Alternar la visibilidad de los botones de edición y cancelación
    if (!viewMode.classList.contains('hidden')) {
        editButton.style.display = 'inline';
        cancelButton.style.display = 'none';
    } else {
        editButton.style.display = 'none';
        cancelButton.style.display = 'inline';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const detailLinks = document.querySelectorAll('.link-ver-mas');
    detailLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const elementoId = link.getAttribute('data-elemento-id');
            const modalId = `modal-detalles-${elementoId}`; // Asegúrate de que el ID coincida
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block'; // Abre el modal
                modal.classList.add('show'); // Asegúrate de que se muestre correctamente
            }
        });
    });
});


    document.addEventListener('DOMContentLoaded', function() {
        // ... código existente ...

        // Evento para limpiar la consulta
        document.getElementById('clearSearch').addEventListener('click', function() {
            // Ocultar el contenedor de resultados
            document.querySelector('.resultado-busqueda').style.display = 'none';
            // Limpiar la información del usuario
            document.querySelector('.usuario-nombre').textContent = '';
            document.querySelector('.usuario-apellidos').textContent = '';
            document.querySelector('.usuario-documento').textContent = '';
            document.querySelector('.usuario-telefono').textContent = '';
            document.querySelector('.usuario-rh').textContent = '';
            document.querySelector('.usuario-rol').textContent = '';
            document.querySelector('.usuario-ficha').textContent = '';
            // Limpiar los elementos
            document.querySelector('.card-container').innerHTML = '';
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Selecciona el modal y los botones
        const reportIngresosModal = document.getElementById("reportIngresosModal");
        const openModalBtn = document.querySelector(".open-report-ingresos-modal"); // Asegúrate que el botón tenga esta clase
        const closeModalBtn = reportIngresosModal.querySelector(".close-btn");
 
        // Función para abrir el modal
        openModalBtn.addEventListener("click", function(event) {
            event.preventDefault(); // Evita redireccionamientos
            reportIngresosModal.style.display = "block";
        });
 
        // Función para cerrar el modal al hacer clic en el botón de cerrar
        closeModalBtn.addEventListener("click", function() {
            reportIngresosModal.style.display = "none";
        });
 
        // Cierra el modal si el usuario hace clic fuera del contenido del modal
        window.addEventListener("click", function(event) {
            if (event.target === reportIngresosModal) {
                reportIngresosModal.style.display = "none";
            }
        });
    });
