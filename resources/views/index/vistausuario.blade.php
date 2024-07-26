<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Elementos</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles_formulario_elementos.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <h2 class="text-center mb-4">Registro de Elementos</h2>
            <form>
                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría:</label>
                    <select class="form-select" id="categoria" name="categoria" required>
                        <option value="Archiveros">Archiveros</option>
                        <option value="Automóviles">Automóviles</option>
                        <option value="Balanzas">Balanzas</option>
                        <option value="Baterías">Baterías</option>
                        <option value="Bicicletas">Bicicletas</option>
                        <option value="Camioneta">Camioneta</option>
                        <option value="Cámaras">Cámaras</option>
                        <option value="Cámaras de Seguridad">Cámaras de Seguridad</option>
                        <option value="Carros de Limpieza">Carros de Limpieza</option>
                        <option value="Carros de Servicio">Carros de Servicio</option>
                        <option value="Centrifugas">Centrifugas</option>
                        <option value="Computadoras">Computadoras</option>
                        <option value="Detectores de Humo">Detectores de Humo</option>
                        <option value="Dispositivos de Red(Routers, Switches)">Dispositivos de Red(Routers, Switches)</option>
                        <option value="Equipos de Atletismo">Equipos de Atletismo</option>
                        <option value="Equipos de Baloncesto">Equipos de Baloncesto</option>
                        <option value="Equipos de Difusión">Equipos de Difusión</option>
                        <option value="Equipos de Futbol">Equipos de Futbol</option>
                        <option value="Equipos de Gimnasio">Equipos de Gimnasio</option>
                        <option value="Equipos de Jardinería">Equipos de Jardinería</option>
                        <option value="Equipos de Limpieza">Equipos de Limpieza</option>
                        <option value="Equipos de Medición">Equipos de Medición</option>
                        <option value="Equipos de Protección Personal">Equipos de Protección Personal</option>
                        <option value="Equipos de Refrigeración">Equipos de Refrigeración</option>
                        <option value="Equipos de Telecomunicaciones">Equipos de Telecomunicaciones</option>
                        <option value="Equipos de Video Conferencia">Equipos de Video Conferencia</option>
                        <option value="Escritorios">Escritorios</option>
                        <option value="Escáneres">Escáneres</option>
                        <option value="Estanterías">Estanterías</option>
                        <option value="Estación de Carga">Estación de Carga</option>
                        <option value="Estufas y Hornos">Estufas y Hornos</option>
                        <option value="Extintores">Extintores</option>
                        <option value="Fax">Fax</option>
                        <option value="Fotocopiadoras">Fotocopiadoras</option>
                        <option value="Generadores">Generadores</option>
                        <option value="Herramientas Eléctricas">Herramientas Eléctricas</option>
                        <option value="Herramientas Manuales">Herramientas Manuales</option>
                        <option value="Impresoras">Impresoras</option>
                        <option value="Inversores">Inversores</option>
                        <option value="Lavavajillas">Lavavajillas</option>
                        <option value="Maquinas de Escribir">Maquinas de Escribir</option>
                        <option value="Mesas de Preparación">Mesas de Preparación</option>
                        <option value="Microscopios">Microscopios</option>
                        <option value="Montacargas">Montacargas</option>
                        <option value="Motocicletas">Motocicletas</option>
                        <option value="Paneles Solares">Paneles Solares</option>
                        <option value="Patinetas Eléctricas">Patinetas Eléctricas</option>
                        <option value="Proyectores">Proyectores</option>
                        <option value="Servidores">Servidores</option>
                        <option value="Shedderes(Destructoras de Papel)">Shedderes(Destructoras de Papel)</option>
                        <option value="Sillas">Sillas</option>
                        <option value="Sistemas de Alarma">Sistemas de Alarma</option>
                        <option value="Sistemas de Audio">Sistemas de Audio</option>
                        <option value="Sistemas de Radio">Sistemas de Radio</option>
                        <option value="Sistemas de Video Conferencia">Sistemas de Video Conferencia</option>
                        <option value="Telefonía">Telefonía</option>
                        <option value="Televisores">Televisores</option>
                        <option value="Utensilios de Cocina">Utensilios de Cocina</option>
                        <option value="Vehículos Especializados(Ambulancias, Camiones de Bomberos)">Vehículos Especializados(Ambulancias, Camiones de Bomberos)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="marca" class="form-label">Marca:</label>
                    <input type="text" class="form-control" id="marca" name="marca" required placeholder="Mac, Asus, Lenovo, Acer, Hewlett-Packard Etc.... ">
                </div>
                <div class="mb-3 d-flex align-items-center">
                    <label for="modelo" class="form-label me-2">Modelo:</label>
                    <a href="#" class="me-2" data-bs-toggle="modal" data-bs-target="#messageBox">Ayuda</a>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" id="modelo" name="modelo" required>
                </div>
                <button type="submit" class="btn btn-custom" style="background-color: #6c757d; color: white; border: none;" onmouseover="this.style.backgroundColor='#6c757d'" onmouseout="this.style.backgroundColor='#007bff'">Registrate</button>

            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="messageBox" tabindex="-1" aria-labelledby="messageBoxLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageBoxLabel">Ayuda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Aquí puedes incluir la información de ayuda para el modelo.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
