document.addEventListener('DOMContentLoaded', function () {
    // Seleccionar elementos del DOM
    const formReporte = document.getElementById('formReporteIngresos');
    const inputFechaInicio = document.getElementById('fecha_inicio');
    const inputFechaFinal = document.getElementById('fecha_final');
    const inputDocumento = document.getElementById('documento_usuario');
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

        // Construir la URL con parámetros de consulta a partir del atributo 'action'
        const url = new URL(formReporte.action);
        url.searchParams.append('fecha_inicio', fechaInicio);
        url.searchParams.append('fecha_final', fechaFinal);
        if (documento) {
            url.searchParams.append('documento_usuario', documento);
        }

        console.log('URL generada:', url.toString());

        // Realizar solicitud GET con parámetros en la URL
        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            spinner.style.display = 'none'; // Ocultar spinner

            if (data.success) {
                mostrarResultados(data.ingresos);
            } else {
                mostrarMensajeError(data.error || 'No se encontraron registros.');
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
        const tbody = resultadosContainer;
        tbody.innerHTML = ''; // Limpiar resultados anteriores

        ingresos.forEach(ingreso => {
            const row = `
                <tr>
                    <td>${ingreso.ID}</td>
                    <td>${ingreso.NOMBRE_CENTRO}</td>
                    <td>${ingreso.FECHA_INGRESO}</td>
                    <td>${ingreso.FECHA_EGRESO}</td>
                    <td>${ingreso.ESTADO}</td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
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
