<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/styles.css">
    <title>Conductores-Vehículos</title>


<script>
    //Se valida para que solo se ingrese números
    function filtrarNumero(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
    }
    function limitarLongitud(input, maxLength) {
        if (input.value.length > maxLength) {
            input.value = input.value.slice(0, maxLength);
        }
    }
// Se valida para que solo se ingrese números con 1er caracter 9
    function validarInicio(input) {
        if (input.value.length === 1 && input.value !== '9') {
            input.value = '';
        }
    }
    
    function agregarSeparacion(input) {
        var formattedValue = input.value.replace(/\D/g, ''); // Eliminar no-numéricos
        var chunks = [];
        for (var i = 0; i < formattedValue.length; i += 3) {
            chunks.push(formattedValue.slice(i, i + 3));
        }
        input.value = chunks.join(' ');
    }
</script>


</head>
<body>
    <h1>Registro de Conductores y Vehículos</h1>
    <form action="i-registro.php" method="POST" onsubmit="return validarFormulario()">
        <fieldset>
            <legend>DATOS DEL CONDUCTOR</legend>
            
         
            <div>
                <label for="dni">DNI:</label>
                <input type="text" id="dni" name="dni" required oninput="filtrarNumero(this); limitarLongitud(this, 8);">
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
            <input type="text" id="celular" name="celular" required oninput="filtrarNumero(this); limitarLongitud(this, 9); agregarSeparacion(this); validarInicio(this);">
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
                <label for="fecha_registro">Fecha de Registro:</label>
                <input type="date" id="fecha_registro" name="fecha_registro" required value="<?php echo date('Y-m-d'); ?>">
            </div>




        </fieldset>

        <button type="submit">Registrar</button>
    </form>

    
</body>
</html>
