<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/styles.css">
    <title>Reporte General de Conductores y Vehículos</title>
</head>
<body>
    <h1>Reporte General de Conductores y Vehículos</h1>

    <?php
    //Realiza la conexión a la base de datos
    require_once "config/connection.php";

    //Consulta para obtener los datos de conductores y vehículos
    $consulta = "SELECT c.*, v.nro_placa, v.tipo_vehiculo, v.marca, v.modelo, f.fecha_registro
                 FROM conductor c
                 INNER JOIN vehiculo v ON c.id_conductor = v.id_conductor
                 INNER JOIN fecha_registro f ON c.id_conductor = f.id_conductor
                 ORDER BY c.id_conductor DESC";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->execute();
    $registros = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    //Mostrar los registros en una tabla
    if (!empty($registros)) {
        echo "<table>";
        echo "<tr>";
        echo "<th>DNI</th>";
        echo "<th>Conductor</th>";
        echo "<th>Celular</th>";
        echo "<th>Placa</th>";
        echo "<th>Tipo Vehículo</th>";
        echo "<th>Marca/Modelo</th>";
        echo "<th>Editar</th>"; //Nueva columna para actualizar registro
        echo "<th>Obtener Certificado</th>"; //Nueva columna redirecionar a certificado
        echo "</tr>";

        foreach ($registros as $registro) {
            echo "<tr>";
            echo "<td>{$registro['dni']}</td>";
            
            //Aquí se realiza la concatenación en orden (apellido_paterno apellido_materno,nombre) 
            echo "<td>{$registro['apellido_paterno']} {$registro['apellido_materno']}, {$registro['nombre']}</td>";
            echo "<td>{$registro['celular']}</td>";
            echo "<td>{$registro['nro_placa']}</td>";
            echo "<td>{$registro['tipo_vehiculo']}</td>";

            //Aquí se realiza la concatenación (marca / modelo)
            echo "<td>{$registro['marca']} / {$registro['modelo']}</td>";


            //Nuevas celdas con enlaces a las páginas (editar.php y certificado.php)
            echo "<td><a href=\"editar.php?id={$registro['id_conductor']}\">Editar</a></td>";
            echo "<td><a href=\"certificado.php?id={$registro['id_conductor']}\" target=\"_blank\">Certificar</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No hay registros para mostrar</p>";
    }
?>
</body>
</html>

