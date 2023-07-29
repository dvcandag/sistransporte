<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/styles.css">
    <title>Registro de Conductores y Vehículos</title>
</head>
<body>
    <h1>Registro de Conductores y Vehículos</h1>
    <form action="i-registro.php" method="POST">
        <fieldset>
            <legend>DATOS DEL CONDUCTOR</legend>
            <div>
                <label for="dni">DNI:</label>
                <input type="text" id="dni" name="dni" required>
            </div>

            <div>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div>
                <label for="apellido_paterno">Apellido Paterno:</label>
                <input type="text" id="apellido_paterno" name="apellido_paterno" required>
            </div>

            <div>
                <label for="apellido_materno">Apellido Materno:</label>
                <input type="text" id="apellido_materno" name="apellido_materno" required>
            </div>

            <div>
                <label for="celular">Celular:</label>
                <input type="text" id="celular" name="celular" required>
            </div>
        </fieldset>

        <fieldset>
            <legend>DATOS DEL VEHÍCULO</legend>
            <div>
                <label for="nro_placa">Número de Placa:</label>
                <input type="text" id="nro_placa" name="nro_placa" required>
            </div>

            <div>
                <label for="tipo_vehiculo">Tipo de Vehículo:</label>
                <select id="tipo_vehiculo" name="tipo_vehiculo" required>
                    <option value="">Seleccionar</option>
                    <option value="Mototaxi">Mototaxi</option>
                    <option value="Taxi">Taxi</option>
                    <option value="Particular">Particular</option>
                </select>
            </div>

            <div>
                <label for="marca">Marca:</label>
                <input type="text" id="marca" name="marca" required>
            </div>

            <div>
                <label for="modelo">Modelo:</label>
                <input type="text" id="modelo" name="modelo" required>
            </div>
        </fieldset>

        <fieldset>
            <legend>FECHA DE SOLICITUD</legend>
            <div>
                <label for="fecha_solicitud">Fecha de Registro:</label>
                <input type="date" id="fecha_solicitud" name="fecha_solicitud" required>
            </div>
        </fieldset>

        <button type="submit">Registrar</button>
    </form>
</body>
</html>
