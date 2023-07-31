<?php
//Realiza la conexión a la base de datos
require_once "config/connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dni = $_POST["dni"];
    $nombre = $_POST["nombre"];
    $apellido_paterno = $_POST["apellido_paterno"];
    $apellido_materno = $_POST["apellido_materno"];
    $celular = $_POST["celular"];
    $nro_placa = $_POST["nro_placa"];
    $tipo_vehiculo = $_POST["tipo_vehiculo"];
    $marca = $_POST["marca"];
    $modelo = $_POST["modelo"];
    $fecha_registro = $_POST["fecha_registro"];

        //Insertar datos del conductor en la tabla "conductor"
        $consulta_conductor = "INSERT INTO conductor (dni, nombre, apellido_paterno, apellido_materno, celular)
                              VALUES (:dni, :nombre, :apellido_paterno, :apellido_materno, :celular)";
        $sentencia_conductor = $conexion->prepare($consulta_conductor);
        $sentencia_conductor->bindParam(':dni', $dni);
        $sentencia_conductor->bindParam(':nombre', $nombre);
        $sentencia_conductor->bindParam(':apellido_paterno', $apellido_paterno);
        $sentencia_conductor->bindParam(':apellido_materno', $apellido_materno);
        $sentencia_conductor->bindParam(':celular', $celular);
        $sentencia_conductor->execute();

        //Obtener el ID del conductor recién insertado
        $id_conductor = $conexion->lastInsertId();

        //Insertar datos del vehículo en la tabla "vehiculo"
        $consulta_vehiculo = "INSERT INTO vehiculo (id_conductor, nro_placa, tipo_vehiculo, marca, modelo)
                             VALUES (:id_conductor, :nro_placa, :tipo_vehiculo, :marca, :modelo)";
        $sentencia_vehiculo = $conexion->prepare($consulta_vehiculo);
        $sentencia_vehiculo->bindParam(':id_conductor', $id_conductor);
        $sentencia_vehiculo->bindParam(':nro_placa', $nro_placa);
        $sentencia_vehiculo->bindParam(':tipo_vehiculo', $tipo_vehiculo);
        $sentencia_vehiculo->bindParam(':marca', $marca);
        $sentencia_vehiculo->bindParam(':modelo', $modelo);
        $sentencia_vehiculo->execute();

        //Insertar fecha de registro en la tabla "fecha_registro"
        $consulta_fecha_registro = "INSERT INTO fecha_registro (id_conductor, fecha_registro)
                                    VALUES (:id_conductor, :fecha_registro)";
        $sentencia_fecha_registro = $conexion->prepare($consulta_fecha_registro);
        $sentencia_fecha_registro->bindParam(':id_conductor', $id_conductor);
        $sentencia_fecha_registro->bindParam(':fecha_registro', $fecha_registro);
        $sentencia_fecha_registro->execute();

        //Redirigi a la página reportegeneral.php para mostrar listado de registros
        header("Location: reportegeneral.php");
        exit();
       }
?>
