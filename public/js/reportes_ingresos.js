document.addEventListener('DOMContentLoaded', function () {
    // Seleccionar elementos del DOM
    const formReporte = document.getElementById('formReporteIngresos');
    const inputFechaInicio = document.getElementById('fechaInicio');
    const inputFechaFinal = document.getElementById('fechaFinal');
    const inputDocumento = document.getElementById('numeroDocumento');
    const resultadosContainer = document.getElementById('resultados');
    const mensajeError = document.getElementById('mensajeError');
    const spinner = document.getElementById('spinnerCarga');

    // Función para agregar event listeners a los botones
    function agregarEventListeners() {
        // Event listeners para ver usuario
        document.querySelectorAll('.ver-usuario').forEach(button => {
            button.addEventListener('click', async function() {
                const documento = this.dataset.documento;
                try {
                    const response = await fetch(`/usuario/detalles/${documento}`);
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        const usuario = data.usuario;
                        document.getElementById('nombreUsuario').textContent = usuario.nombres;
                        document.getElementById('apellidosUsuario').textContent = usuario.apellidos;
                        document.getElementById('documentoUsuario').textContent = `${usuario.tipo_documento} ${usuario.numero_documento}`;
                        document.getElementById('telefonoUsuario').textContent = usuario.celular;
                        document.getElementById('rhUsuario').textContent = usuario.rh;
                        document.getElementById('rolUsuario').textContent = usuario.rol;
                        
                        const fichaContainer = document.querySelector('.ficha-container');
                        if (usuario.rol.toLowerCase() === 'aprendiz') {
                            fichaContainer.style.display = 'block';
                            document.getElementById('fichaUsuario').textContent = usuario.ficha || 'No especificada';
                        } else {
                            fichaContainer.style.display = 'none';
                        }

                        const fotoUrl = usuario.foto 
                            ? `/storage/fotos_perfil/${usuario.foto}` 
                            : '/imagenes/sin_foto_perfil.webp';
                        document.getElementById('fotoUsuario').src = fotoUrl;

                        document.getElementById('modalUsuario').classList.add('is-active');
                    } else {
                        alert('No se pudo encontrar la información del usuario');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al obtener la información del usuario');
                }
            });
        });

        // Event listeners para ver elementos
        document.querySelectorAll('.ver-elementos').forEach(button => {
            button.addEventListener('click', async function() {
                const documento = this.dataset.documento;
                try {
                    const response = await fetch(`/usuario/${documento}/elementos`);
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        const elementosBody = document.getElementById('elementosUsuario');
                        elementosBody.innerHTML = ''; // Limpiar tabla

                        if (data.elementos.length === 0) {
                            elementosBody.innerHTML = `
                                <tr>
                                    <td colspan="6" class="has-text-centered">Este usuario no tiene elementos registrados</td>
                                </tr>`;
                        } else {
                            data.elementos.forEach(elemento => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${elemento.codigo}</td>
                                    <td>${elemento.nombre}</td>
                                    <td>${elemento.marca || 'N/A'}</td>
                                    <td>${elemento.color || 'N/A'}</td>
                                    <td>${elemento.categoria}</td>
                                    <td>
                                        ${elemento.foto ? 
                                            `<a href="#" class="ver-foto-elemento" data-foto="/storage/${elemento.foto}">
                                                Ver foto
                                            </a>` : 
                                            'Sin foto'
                                        }
                                    </td>
                                `;
                                elementosBody.appendChild(row);
                            });

                            // Agregar event listeners a los enlaces de foto DESPUÉS de crear las filas
                            document.querySelectorAll('.ver-foto-elemento').forEach(link => {
                                link.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    const fotoUrl = this.dataset.foto;
                                    
                                    // Remover modal anterior si existe
                                    const modalAnterior = document.getElementById('modalFotoElemento');
                                    if (modalAnterior) {
                                        modalAnterior.remove();
                                    }
                                    
                                    // Crear modal para la foto
                                    const modalHTML = `
                                        <div class="modal is-active" id="modalFotoElemento">
                                            <div class="modal-background"></div>
                                            <div class="modal-content">
                                                <p class="image">
                                                    <img src="${fotoUrl}" alt="Foto del elemento" style="max-height: 80vh; width: auto;">
                                                </p>
                                            </div>
                                            <button class="modal-close is-large" aria-label="close"></button>
                                        </div>
                                    `;
                                    
                                    document.body.insertAdjacentHTML('beforeend', modalHTML);
                                    
                                    // Agregar manejador para cerrar
                                    const modalFoto = document.getElementById('modalFotoElemento');
                                    const cerrarModal = () => modalFoto.remove();
                                    
                                    modalFoto.querySelector('.modal-background').addEventListener('click', cerrarModal);
                                    modalFoto.querySelector('.modal-close').addEventListener('click', cerrarModal);
                                });
                            });
                        }

                        document.getElementById('modalElementos').classList.add('is-active');
                    } else {
                        alert('No se pudieron obtener los elementos del usuario');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al obtener los elementos del usuario');
                }
            });
        });
    }

    // Evento submit del formulario
    formReporte.addEventListener('submit', async function (e) {
        e.preventDefault();
        spinner.style.display = 'block';
        mensajeError.classList.add('is-hidden');

        try {
            const formData = new FormData(this);
            const queryParams = new URLSearchParams(formData);
            const response = await fetch(`${this.action}?${queryParams}`);
            const data = await response.json();

            if (data.success) {
                resultadosContainer.innerHTML = '';

                if (data.ingresos.length === 0) {
                    resultadosContainer.innerHTML = `
                        <tr>
                            <td colspan="7" class="has-text-centered">No se encontraron registros</td>
                        </tr>`;
                } else {
                    data.ingresos.forEach(ingreso => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${ingreso.ID}</td>
                            <td>${ingreso.NOMBRE_CENTRO}</td>
                            <td>${ingreso.NUMERO_DOCUMENTO}</td>
                            <td>${ingreso.FECHA_INGRESO}</td>
                            <td>${ingreso.FECHA_EGRESO || 'N/A'}</td>
                            <td>${ingreso.ESTADO}</td>
                            <td>
                                <div class="buttons are-small">
                                    <button class="button is-info ver-usuario" 
                                            data-documento="${ingreso.NUMERO_DOCUMENTO}"
                                            title="Ver detalles del usuario">
                                        <span class="icon">
                                            <i class="fas fa-user-circle"></i>
                                        </span>
                                    </button>
                                    <button class="button is-success ver-elementos" 
                                            data-documento="${ingreso.NUMERO_DOCUMENTO}"
                                            title="Ver elementos del usuario">
                                        <span class="icon">
                                            <i class="fas fa-box"></i>
                                        </span>
                                    </button>
                                </div>
                            </td>
                        `;
                        resultadosContainer.appendChild(row);
                    });

                    // Agregar event listeners a los nuevos botones
                    agregarEventListeners();
                }
            } else {
                mensajeError.textContent = data.error;
                mensajeError.classList.remove('is-hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            mensajeError.textContent = 'Error al procesar la consulta';
            mensajeError.classList.remove('is-hidden');
        } finally {
            spinner.style.display = 'none';
        }
    });

    // Event listeners para cerrar modales
    document.querySelectorAll('.modal-background, .delete').forEach(element => {
        element.addEventListener('click', function() {
            document.getElementById('modalUsuario').classList.remove('is-active');
            document.getElementById('modalElementos').classList.remove('is-active');
        });
    });

    // Agregar event listeners iniciales
    agregarEventListeners();

    // Generar PDF
    const btnGenerarPDF = document.getElementById('generarPDF');
    if (btnGenerarPDF) {
        btnGenerarPDF.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.dataset.url;
        });
    }

    // Agregar manejador para el botón de limpiar consulta
    const btnLimpiarConsulta = document.getElementById('limpiarConsulta');
    if (btnLimpiarConsulta) {
        btnLimpiarConsulta.addEventListener('click', function() {
            // Limpiar la tabla
            const resultadosContainer = document.getElementById('resultados');
            resultadosContainer.innerHTML = '';
            
            // Limpiar el formulario
            document.getElementById('formReporteIngresos').reset();
            
            // Ocultar mensajes de error si existen
            const mensajeError = document.getElementById('mensajeError');
            if (mensajeError) {
                mensajeError.classList.add('is-hidden');
            }
            
            // Opcional: Agregar mensaje de tabla vacía
            resultadosContainer.innerHTML = `
                <tr>
                    <td colspan="7" class="has-text-centered">Realice una nueva consulta</td>
                </tr>`;
        });
    }
});

// Función para mostrar mensajes de error
function mostrarMensajeError(mensaje) {
    mensajeError.textContent = mensaje;
    mensajeError.classList.remove('is-hidden');
}