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
        e.preventDefault(); // Evitar recarga de la página

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
        mensajeError.textContent = '';
        resultadosContainer.innerHTML = '';
        spinner.style.display = 'block';

        // Preparar datos para la solicitud AJAX
        const datos = {
            fecha_inicio: fechaInicio,
            fecha_final: fechaFinal,
            documento_usuario: documento
        };

        // Realizar solicitud AJAX
        fetch(formReporte.dataset.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': formReporte.dataset.token
            },
            body: JSON.stringify(datos)
        })
        .then(response => response.json())
        .then(data => {
            spinner.style.display = 'none'; // Ocultar spinner

            if (data.success) {
                mostrarResultados(data.ingresos);
            } else {
                mostrarMensajeError(data.message || 'No se encontraron registros.');
            }
        })
        .catch(error => {
            spinner.style.display = 'none';
            mostrarMensajeError('Error al realizar la consulta. Inténtalo nuevamente.');
            console.error('Error:', error);
        });
    });

    // Función para mostrar los resultados
    function mostrarResultados(ingresos) {
        if (ingresos.length === 0) {
            resultadosContainer.innerHTML = '<p>No se encontraron registros.</p>';
            return;
        }

        // Actualización de los encabezados de las columnas
        let html = '<table class="table is-bordered is-striped is-hoverable"><thead><tr>';
        html += '<th>ID</th><th class="centro">NOMBRE CENTRO</th><th>FECHA INGRESO</th><th>FECHA EGRESO</th><th>ESTADO</th>';
        html += '</tr></thead><tbody>';

        ingresos.forEach(ingreso => {
            // Ajuste de las columnas de acuerdo con los nuevos datos
            html += `<tr>
                        <td>${ingreso.id}</td>  <!-- ID -->
                        <td>${ingreso.centro.nombre}</td>  <!-- NOMBRE CENTRO -->
                        <td>${ingreso.fecha_ingreso}</td>  <!-- FECHA INGRESO -->
                        <td>${ingreso.fecha_egreso}</td>   <!-- FECHA EGRESO -->
                        <td>${ingreso.estado}</td>  <!-- ESTADO -->
                    </tr>`;
        });

        html += '</tbody></table>';
        resultadosContainer.innerHTML = html;
    }

    // Función para mostrar mensajes de error
    function mostrarMensajeError(mensaje) {
        mensajeError.textContent = mensaje;
        mensajeError.style.display = 'block';
    }

    // Generar PDF
    document.getElementById('generarPDF').addEventListener('click', function() {
        window.location.href = this.dataset.url;
    });    
});
