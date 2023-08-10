

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/styles.css">
    <title>Editar Registro de Conductor</title>

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
    <h1>Editar Registro de Conductor</h1>

    <?php
    // Realiza la conexión a la base de datos
    require_once "config/connection.php";

    // Función para actualizar los datos del conductor en la base de datos
    function actualizarDatosConductor($conexion, $id_conductor, $dni, $nombre, $apellido_paterno, $apellido_materno, $celular)
    {
        // Consulta para actualizar los datos del conductor
        $consulta_conductor = "UPDATE conductor
                              SET dni = :dni,
                                  nombre = :nombre,
                                  apellido_paterno = :apellido_paterno,
                                  apellido_materno = :apellido_materno,
                                  celular = :celular
                              WHERE id_conductor = :id_conductor";

        $sentencia_conductor = $conexion->prepare($consulta_conductor);
        $sentencia_conductor->bindValue(':dni', $dni, PDO::PARAM_STR);
        $sentencia_conductor->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $sentencia_conductor->bindValue(':apellido_paterno', $apellido_paterno, PDO::PARAM_STR);
        $sentencia_conductor->bindValue(':apellido_materno', $apellido_materno, PDO::PARAM_STR);
        $sentencia_conductor->bindValue(':celular', $celular, PDO::PARAM_STR);
        $sentencia_conductor->bindValue(':id_conductor', $id_conductor, PDO::PARAM_INT);

        try {
            // Iniciar la transacción
            $conexion->beginTransaction();

            // Ejecutar la consulta de actualización
            $sentencia_conductor->execute();

            // Confirmar la transacción
            $conexion->commit();
        } catch (PDOException $e) {
            // Si ocurre algún error, deshacer la transacción
            $conexion->rollBack();
            echo "Error al actualizar los datos del conductor: " . $e->getMessage();
        }
    }

    // Función para actualizar los datos del vehículo en la base de datos
    function actualizarDatosVehiculo($conexion, $id_conductor, $nro_placa, $tipo_vehiculo, $marca, $modelo)
    {
        $consulta_vehiculo = "UPDATE vehiculo
                             SET nro_placa = :nro_placa,
                                 tipo_vehiculo = :tipo_vehiculo,
                                 marca = :marca,
                                 modelo = :modelo
                             WHERE id_conductor = :id_conductor";

        $sentencia_vehiculo = $conexion->prepare($consulta_vehiculo);
        $sentencia_vehiculo->bindValue(':nro_placa', $nro_placa, PDO::PARAM_STR);
        $sentencia_vehiculo->bindValue(':tipo_vehiculo', $tipo_vehiculo, PDO::PARAM_STR);
        $sentencia_vehiculo->bindValue(':marca', $marca, PDO::PARAM_STR);
        $sentencia_vehiculo->bindValue(':modelo', $modelo, PDO::PARAM_STR);
        $sentencia_vehiculo->bindValue(':id_conductor', $id_conductor, PDO::PARAM_INT);

        try {
            // Iniciar la transacción
            $conexion->beginTransaction();

            // Ejecutar la consulta de actualización
            $sentencia_vehiculo->execute();

            // Confirmar la transacción
            $conexion->commit();
        } catch (PDOException $e) {
            // Si ocurre algún error, deshacer la transacción
            $conexion->rollBack();
            echo "Error al actualizar los datos del vehículo: " . $e->getMessage();
        }
    }

    // Verificar si se ha proporcionado el parámetro "id" en la URL
    if (isset($_GET['id'])) {
        // Obtener el ID del conductor desde la URL
        $id_conductor = $_GET['id'];
        // Consulta para obtener los datos del conductor seleccionado por su ID
        $consulta = "SELECT c.*, v.nro_placa, v.tipo_vehiculo, v.marca, v.modelo, f.fecha_registro
                     FROM conductor c
                     INNER JOIN vehiculo v ON c.id_conductor = v.id_conductor
                     INNER JOIN fecha_registro f ON c.id_conductor = f.id_conductor
                     WHERE c.id_conductor = :id";

        $sentencia = $conexion->prepare($consulta);
        $sentencia->bindValue(':id', $id_conductor, PDO::PARAM_INT);
        $sentencia->execute();
        $registro_conductor = $sentencia->fetch(PDO::FETCH_ASSOC);
  
        // Verificar si se encontró el registro del conductor
        if ($registro_conductor) {
            // Si se envió el formulario para actualizar, guardar los datos en la base de datos
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Obtener los datos del formulario para actualizar el conductor
                $dni = $_POST['dni'];
                $nombre = $_POST['nombre'];
                $apellido_paterno = $_POST['apellido_paterno'];
                $apellido_materno = $_POST['apellido_materno'];
                $celular = $_POST['celular'];

                // Llamar a la función para actualizar los datos del conductor
                actualizarDatosConductor($conexion, $id_conductor, $dni, $nombre, $apellido_paterno, $apellido_materno, $celular);

                // Obtener los datos del formulario para actualizar el vehículo
                $nro_placa = $_POST['nro_placa'];
                $tipo_vehiculo = $_POST['tipo_vehiculo'];
                $marca = $_POST['marca'];
                $modelo = $_POST['modelo'];

                // Llamar a la función para actualizar los datos del vehículo
                actualizarDatosVehiculo($conexion, $id_conductor, $nro_placa, $tipo_vehiculo, $marca, $modelo);

                // Redireccionar al usuario a la página "reportegeneral.php" después de la actualización
                header("Location: reportegeneral.php");
                exit(); // Asegurar que el código posterior no se ejecute después de la redirección
            }
        }
       

        //Mostrar el formulario con los datos del conductor y vehículo para editar
        echo "<form action=\"editar.php?id={$id_conductor}\" method=\"POST\">";
        echo "<input type=\"hidden\" name=\"id\" value=\"{$registro_conductor['id_conductor']}\">";
        
        echo "<fieldset>";
        echo "<legend>DATOS DEL CONDUCTOR</legend>";
        echo "<div>";
        echo "<label for=\"dni\">DNI:</label>";
        echo "<input type=\"text\" id=\"dni\" name=\"dni\" value=\"{$registro_conductor['dni']}\" required oninput=\"filtrarNumero(this); limitarLongitud(this, 8);\">";
        echo "</div>";

        echo "<div>";
        echo "<label for=\"nombre\">Nombre:</label>";
        echo "<input type=\"text\" id=\"nombre\" name=\"nombre\" value=\"{$registro_conductor['nombre']}\" required>";
        echo "</div>";

        echo "<div>";
        echo "<label for=\"apellido_paterno\">Apellido Paterno:</label>";
        echo "<input type=\"text\" id=\"apellido_paterno\" name=\"apellido_paterno\" value=\"{$registro_conductor['apellido_paterno']}\" required>";
        echo "</div>";

        echo "<div>";
        echo "<label for=\"apellido_materno\">Apellido Materno:</label>";
        echo "<input type=\"text\" id=\"apellido_materno\" name=\"apellido_materno\" value=\"{$registro_conductor['apellido_materno']}\" required>";
        echo "</div>";

        echo "<div>";
        echo "<label for=\"celular\">Celular:</label>";
        echo "<input type=\"text\" id=\"celular\" name=\"celular\" value=\"{$registro_conductor['celular']}\" required oninput=\"filtrarNumero(this); limitarLongitud(this, 9); agregarSeparacion(this); validarInicio(this);\">";
        echo "</div>";
        echo "</fieldset>";

        echo "</fieldset>";

        echo "<fieldset>";
        echo "<legend>DATOS DEL VEHÍCULO</legend>";
        echo "<div>";
        echo "<label for=\"nro_placa\">Número de Placa:</label>";
        echo "<input type=\"text\" id=\"nro_placa\" name=\"nro_placa\" value=\"{$registro_conductor['nro_placa']}\" required>";
        echo "</div>";

        echo "<div>";
        echo "<label for=\"tipo_vehiculo\">Tipo de Vehículo:</label>";
        echo "<select id=\"tipo_vehiculo\" name=\"tipo_vehiculo\" required>";
        echo "<option value=\"Mototaxi\" " . ($registro_conductor['tipo_vehiculo'] === 'Mototaxi' ? 'selected' : '') . ">Mototaxi</option>";
        echo "<option value=\"Taxi\" " . ($registro_conductor['tipo_vehiculo'] === 'Taxi' ? 'selected' : '') . ">Taxi</option>";
        echo "<option value=\"Particular\" " . ($registro_conductor['tipo_vehiculo'] === 'Particular' ? 'selected' : '') . ">Particular</option>";
        echo "</select>";
        echo "</div>";

        echo "<div>";
        echo "<label for=\"marca\">Marca:</label>";
        echo "<input type=\"text\" id=\"marca\" name=\"marca\" value=\"{$registro_conductor['marca']}\" required>";
        echo "</div>";

        echo "<div>";
        echo "<label for=\"modelo\">Modelo:</label>";
        echo "<input type=\"text\" id=\"modelo\" name=\"modelo\" value=\"{$registro_conductor['modelo']}\" required>";
        echo "</div>";
        echo "</fieldset>";

        echo "<fieldset>";
        echo "<legend>FECHA DE REGISTRO</legend>";
        echo "<div>";
        echo "<label for=\"fecha_registro\">Fecha de Registro:</label>";
        echo "<input type=\"date\" id=\"fecha_registro\" name=\"fecha_registro\" value=\"{$registro_conductor['fecha_registro']}\" readonly onclick=\"mostrarAviso()\">";
        echo "<p id=\"aviso\" style=\"color: red; display: none;\">Este campo está bloqueado para edición.</p>";
        echo "</div>";
        echo "</fieldset>";

        echo "<script>
            function mostrarAviso() {
                var aviso = document.getElementById('aviso');
                aviso.style.display = 'block';
            }
        </script>";

        echo "<button type=\"submit\">Actualizar</button>";
        echo "</form>";

      }  

    ?>

</body>
</html>