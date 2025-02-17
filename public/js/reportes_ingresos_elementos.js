document.addEventListener('DOMContentLoaded', function () {
    // Seleccionar elementos del DOM
    const formReporte = document.getElementById('formReporteElementos');
    const inputFechaInicio = document.getElementById('fechaInicio');
    const inputFechaFinal = document.getElementById('fechaFinal');
    const inputNumeroDocumento = document.getElementById('numeroDocumento');
    const resultadosContainer = document.getElementById('resultados');
    const mensajeError = document.getElementById('mensajeError');
    const spinner = document.getElementById('spinnerCarga');

    // Evento submit del formulario
    formReporte.addEventListener('submit', function (e) {
        e.preventDefault();

        // Validar fechas
        const fechaInicio = inputFechaInicio.value;
        const fechaFinal = inputFechaFinal.value;
        const numeroDocumento = inputNumeroDocumento.value;

        if (!fechaInicio || !fechaFinal) {
            mostrarMensajeError('Por favor, seleccione ambas fechas.');
            return;
        }

        if (new Date(fechaInicio) > new Date(fechaFinal)) {
            mostrarMensajeError('La fecha de inicio no puede ser mayor que la fecha final.');
            return;
        }

        // Limpiar mensajes previos y mostrar spinner
        mensajeError.classList.add('is-hidden');
        resultadosContainer.innerHTML = '';
        spinner.style.display = 'block';

        // Construir los parámetros de la consulta
        const params = new URLSearchParams({
            fecha_inicio: fechaInicio,
            fecha_final: fechaFinal
        });

        if (numeroDocumento) {
            params.append('numero_documento', numeroDocumento);
        }

        // Realizar la consulta al servidor
        fetch(`${formReporte.action}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.error || `Error ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            spinner.style.display = 'none';
            
            if (data.success) {
                mostrarResultados(data.elementos);
            } else {
                throw new Error(data.error || 'No se encontraron registros.');
            }
        })
        .catch(error => {
            spinner.style.display = 'none';
            mostrarMensajeError(error.message);
            console.error('Error:', error);
        });
    });

    // Función para mostrar los resultados
    function mostrarResultados(elementos) {
        resultadosContainer.innerHTML = '';

        if (!elementos || elementos.length === 0) {
            resultadosContainer.innerHTML = `
                <tr>
                    <td colspan="9" class="has-text-centered">No se encontraron registros</td>
                </tr>
            `;
            return;
        }

        elementos.forEach(elemento => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${elemento.ID}</td>
                <td>${elemento.NUMERO_DOCUMENTO}</td>
                <td>${elemento.CATEGORIA}</td>
                <td>${elemento.SERIE}</td>
                <td>${elemento.MARCA}</td>
                <td>${elemento.FECHA_INGRESO}</td>
                <td>${elemento.FECHA_EGRESO || 'N/A'}</td>
                <td>${elemento.ESTADO}</td>
                <td>
                    <div class="buttons are-small">
                        <button class="button is-info ver-usuario" 
                                data-documento="${elemento.NUMERO_DOCUMENTO}"
                                title="Ver detalles del usuario">
                            <span class="icon">
                                <i class="fas fa-user-circle"></i>
                            </span>
                        </button>
                        <button class="button is-success ver-elementos" 
                                data-elemento-id="${elemento.ID}"
                                title="Ver detalles del elemento">
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
                const elementoId = this.getAttribute('data-elemento-id');
                console.log('ID del elemento:', elementoId); // Para debugging

                try {
                    const response = await fetch(`/elementos/detalles/${elementoId}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    console.log('Respuesta del servidor:', data); // Para debugging
                    
                    if (data.success) {
                        const elemento = data.elemento;
                        // Crear modal para detalles del elemento
                        const modalHTML = `
                            <div class="modal fade show" id="modalDetallesElemento" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true" style="display: block;">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalDetallesLabel">Detalles del Elemento</h5>
                                            <button type="button" class="btn-close" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="details-view">
                                                ${elemento.foto ? 
                                                    `<img src="/storage/${elemento.foto}" 
                                                         alt="Foto del elemento" 
                                                         class="img-fluid mb-3"
                                                         onerror="this.src='/imagenes/sin_foto_elemento.webp'">`
                                                    : '<img src="/imagenes/sin_foto_elemento.webp" alt="Sin foto" class="img-fluid mb-3">'
                                                }
                                                <p><strong>Categoría:</strong> ${elemento.categoria.nombre}</p>
                                                <p><strong>Descripción:</strong> ${elemento.descripcion}</p>
                                                <p><strong>Marca:</strong> ${elemento.marca}</p>
                                                <p><strong>Modelo:</strong> ${elemento.modelo}</p>
                                                <p><strong>Serial:</strong> ${elemento.serie}</p>
                                                <p><strong>Especificaciones Técnicas:</strong> ${elemento.especificaciones_tecnicas}</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary cerrar-modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-backdrop fade show"></div>
                        `;
                        
                        document.body.insertAdjacentHTML('beforeend', modalHTML);
                        
                        // Agregar manejadores para cerrar
                        const modalElemento = document.getElementById('modalDetallesElemento');
                        const backdrop = document.querySelector('.modal-backdrop');
                        const cerrarModal = () => {
                            modalElemento.remove();
                            backdrop.remove();
                            document.body.classList.remove('modal-open');
                        };
                        
                        modalElemento.querySelector('.btn-close').addEventListener('click', cerrarModal);
                        modalElemento.querySelector('.cerrar-modal').addEventListener('click', cerrarModal);
                        document.body.classList.add('modal-open');
                    } else {
                        throw new Error(data.error || 'No se pudo obtener la información del elemento');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al obtener la información del elemento: ' + error.message);
                }
            });
        });
    }

    // Event listeners para cerrar modales
    document.querySelectorAll('.modal-background, .delete').forEach(element => {
        element.addEventListener('click', function() {
            document.getElementById('modalUsuario').classList.remove('is-active');
            document.getElementById('modalElementos').classList.remove('is-active');
        });
    });

    // Agregar manejador para el botón de limpiar consulta
    const btnLimpiarConsulta = document.createElement('button');
    btnLimpiarConsulta.id = 'limpiarConsulta';
    btnLimpiarConsulta.className = 'button is-warning ml-2';
    btnLimpiarConsulta.innerHTML = `
        <span class="icon">
            <i class="fas fa-broom"></i>
        </span>
        <span>Limpiar Consulta</span>
    `;
    
    document.querySelector('.field.is-grouped').appendChild(btnLimpiarConsulta);

    btnLimpiarConsulta.addEventListener('click', function() {
        // Limpiar la tabla
        resultadosContainer.innerHTML = '';
        
        // Limpiar el formulario
        formReporte.reset();
        
        // Ocultar mensajes de error si existen
        mensajeError.classList.add('is-hidden');
        
        // Agregar mensaje de tabla vacía
        resultadosContainer.innerHTML = `
            <tr>
                <td colspan="9" class="has-text-centered">Realice una nueva consulta</td>
            </tr>`;
    });

    // Agregar event listeners iniciales
    agregarEventListeners();

    // Función para mostrar mensajes de error
    function mostrarMensajeError(mensaje) {
        mensajeError.textContent = mensaje;
        mensajeError.classList.remove('is-hidden');
    }

    // Generar PDF - Simplificado como en reportes_ingresos.js
    const btnGenerarPDF = document.getElementById('generarPDF');
    if (btnGenerarPDF) {
        btnGenerarPDF.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Obtener los valores de los campos
            const fechaInicio = document.getElementById('fechaInicio').value;
            const fechaFinal = document.getElementById('fechaFinal').value;
            const numeroDocumento = document.getElementById('numeroDocumento').value;

            // Validar que haya fechas seleccionadas
            if (!fechaInicio || !fechaFinal) {
                mostrarMensajeError('Por favor, seleccione ambas fechas antes de generar el PDF.');
                return;
            }

            // Construir la URL con los parámetros
            const params = new URLSearchParams({
                fecha_inicio: fechaInicio,
                fecha_final: fechaFinal
            });

            if (numeroDocumento) {
                params.append('serie', numeroDocumento);
            }

            // Construir la URL completa
            const url = `${this.dataset.url}?${params.toString()}`;
            
            // Redirigir a la URL construida
            window.location.href = url;
        });
    }
});
