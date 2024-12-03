document.addEventListener('DOMContentLoaded', function () {
    // Seleccionar elementos del DOM
    const formReporte = document.getElementById('formReporteIngresos');
    const inputFechaInicio = document.getElementById('fechaInicio');
    const inputFechaFinal = document.getElementById('fechaFinal');
    const inputDocumento = document.getElementById('numeroDocumento');
    const resultadosContainer = document.getElementById('resultados');
    const mensajeError = document.getElementById('mensajeError');
    const spinner = document.getElementById('spinnerCarga');

    // Evento submit del formulario
    formReporte.addEventListener('submit', function (e) {
        e.preventDefault();

        // Validar fechas
        const fechaInicio = inputFechaInicio.value;
        const fechaFinal = inputFechaFinal.value;
        const documento = inputDocumento.value;

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

        if (documento) {
            params.append('documento_usuario', documento);
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
                mostrarResultados(data.ingresos);
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
    function mostrarResultados(ingresos) {
        resultadosContainer.innerHTML = '';

        if (!ingresos || ingresos.length === 0) {
            resultadosContainer.innerHTML = `
                <tr>
                    <td colspan="5" class="has-text-centered">No se encontraron registros</td>
                </tr>
            `;
            return;
        }

        ingresos.forEach(ingreso => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${ingreso.ID}</td>
                <td>${ingreso.NOMBRE_CENTRO}</td>
                <td>${ingreso.NUMERO_DOCUMENTO}</td> <!-- Mostrar número de documento -->
                <td>${ingreso.FECHA_INGRESO}</td>
                <td>${ingreso.FECHA_EGRESO || 'N/A'}</td>
                <td>${ingreso.ESTADO}</td>
            `;
            resultadosContainer.appendChild(row);
        });
    }

    // Función para mostrar mensajes de error
    function mostrarMensajeError(mensaje) {
        mensajeError.textContent = mensaje;
        mensajeError.classList.remove('is-hidden');
    }

    // Generar PDF
    const btnGenerarPDF = document.getElementById('generarPDF');
    if (btnGenerarPDF) {
        btnGenerarPDF.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.dataset.url;
        });
    }
});