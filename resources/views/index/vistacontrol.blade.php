<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista del Vigilante</title>
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            /* Fondo más claro */
        }

        .container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            /* Altura total de la ventana */
            box-sizing: border-box;
            /* Incluir bordes y relleno en el tamaño total */
            padding-bottom: 15px;
            /* Espacio al final de la página para el margen inferior */
        }

        .buscador {
            width: 1335px;
            /* Restar el margen de ambos lados */
            height: 50px;
            /* Reducir la altura del buscador */
            background-color: #ffffff;
            /* Fondo más claro */
            border-bottom: 1px solid #ddd;
            /* Borde más sutil */
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Box shadow más suave */
            margin: 15px;
            border-radius: 8px;
            /* Esquinas más redondeadas */
        }

        .buscador input {
            width: 60%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }

        .buscador input:focus {
            border-color: #00ff00;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.2);
        }

        .contenedor-intermedio {
            width: 1335px;
            height: 130px;
            /* Altura del nuevo contenedor */
            background-color: #ffffff;
            /* Fondo más claro */
            border: 1px solid #ddd;
            /* Borde más sutil */
            border-radius: 8px;
            /* Esquinas más redondeadas */
            margin: 0 15px 15px 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Box shadow más suave */
        }

        .contenido {
            flex: 1;
            /* Permite que el contenedor ocupe el espacio disponible */
            display: flex;
            margin: 0 15px;
            /* Espacio en los laterales */
            gap: 15px;
            box-sizing: border-box;
            /* Incluir bordes y relleno en el tamaño total */
        }

        .contenido .izquierdo {
            width: 400px;
            /* Ajuste del ancho en píxeles */
            background-color: #ffffff;
            /* Fondo más claro */
            border: 1px solid #ddd;
            /* Borde más sutil */
            border-radius: 8px;
            /* Esquinas más redondeadas */
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Box shadow más suave */
            box-sizing: border-box;   
            /* Incluir bordes y relleno en el tamaño total */
        }

        .contenido .derecho {
            width: 845px;
            background-color: #ffffff;
            /* Fondo más claro */
            border: 1px solid #ddd;
            /* Borde más sutil */
            border-radius: 8px;
            /* Esquinas más redondeadas */
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Box shadow más suave */
            box-sizing: border-box;
            /* Incluir bordes y relleno en el tamaño total */
        }

        .contenido .semaforo {
            width: 60px;
            background-color: #ffffff;
            /* Fondo más claro */
            border: 1px solid #ddd;
            /* Borde más sutil */
            border-radius: 8px;
            /* Esquinas más redondeadas */
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Box shadow más suave */
            box-sizing: border-box;
            /* Incluir bordes y relleno en el tamaño total */
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="buscador">
            <input type="text" placeholder="Buscar...">
        </div>

        <div class="contenedor-intermedio">
            <!-- Nuevo contenedor intermedio -->
        </div>

        <div class="contenido">
            <div class="izquierdo">
                <!-- Contenedor izquierdo (ancho fijo de 400px) -->
            </div>
            <div class="derecho">
                <!-- Contenedor derecho (ancho fijo de 820px) -->
            </div>
            <div class="semaforo">
                <!-- Contenedor semáforo (ancho fijo de 180px) -->
            </div>
        </div>
    </div>

</body>

</html>
