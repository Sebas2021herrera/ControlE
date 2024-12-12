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
                    <td colspan="8" class="has-text-centered">No se encontraron registros</td>
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
            `;
            resultadosContainer.appendChild(row);
        });
    }

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
