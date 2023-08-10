<?php

// Realiza la conexión a la base de datos
require_once "config/connection.php";


// Incluir la librería PHPQRCode
require_once "libreria_qr/PHPQRCode/qrlib.php";

// Función para generar el código QR y guardar la imagen en la carpeta "qr_conductor"
function generarQR($conexion, $datos, $filename)
{
    // Construir el contenido del código QR con los datos del conductor
    $contenidoQR = "DNI: {$datos['dni']}\n";
    $contenidoQR .= "Nombre: {$datos['apellido_paterno']} {$datos['apellido_materno']}, {$datos['nombre']}\n";
    $contenidoQR .= "Celular: {$datos['celular']}\n";
    $contenidoQR .= "Placa: {$datos['nro_placa']}\n";
    $contenidoQR .= "Marca/Modelo: {$datos['marca']} / {$datos['modelo']}\n";

    // Calcular la fecha de caducidad (2 años después de la fecha de registro)
    $fechaCaducidad = date('Y-m-d', strtotime('+2 years', strtotime($datos['fecha_registro'])));
    $contenidoQR .= "Caducidad: {$fechaCaducidad}\n";

    // Generar el código QR con la cadena de datos
    QRcode::png($contenidoQR, "qr_conductor/{$filename}", 'L', 10, 2);

    // Actualizar la fecha de caducidad en la base de datos
    $idConductor = $datos['id_conductor'];
    $fechaGeneracionQR = date('Y-m-d H:i:s'); // Fecha y hora actual

    try {
        // Iniciar la transacción
        $conexion->beginTransaction();

        // Consulta para actualizar la fecha de caducidad y la fecha de generación del QR en la tabla "fecha_registro"
        $actualizarFechaQR = "UPDATE fecha_registro SET fecha_caducidad = :fechaCaducidad, fecha_certificado_qr = NOW() WHERE id_conductor = :idConductor";
        $sentenciaActualizarQR = $conexion->prepare($actualizarFechaQR);
        $sentenciaActualizarQR->execute(['fechaCaducidad' => $fechaCaducidad, 'idConductor' => $idConductor]);

        // Confirmar la transacción
        $conexion->commit();
    } catch (PDOException $e) {
        // Si ocurre algún error, deshacer la transacción
        $conexion->rollBack();
        echo "Error al actualizar la fecha de caducidad en la base de datos: " . $e->getMessage();
    }
}

if (isset($_GET['id_conductor'])) {
    // Obtener el ID del conductor seleccionado desde el parámetro GET
    $idConductorSeleccionado = $_GET['id_conductor'];

    // Consulta para obtener los datos del conductor seleccionado y su vehículo
    $consulta = "SELECT c.*, v.nro_placa, v.marca, v.modelo, f.fecha_registro
                 FROM conductor c
                 INNER JOIN vehiculo v ON c.id_conductor = v.id_conductor
                 INNER JOIN fecha_registro f ON c.id_conductor = f.id_conductor
                 WHERE c.id_conductor = :idConductor
                 ORDER BY c.id_conductor DESC";

    $sentencia = $conexion->prepare($consulta);
    $sentencia->execute(['idConductor' => $idConductorSeleccionado]);
    $registro = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($registro) {
        // Generar el nombre del archivo QR con el formato "dni.png"
        $nombreArchivoQR = $registro['dni'] . ".png";

        // Generar y guardar el código QR con los datos del conductor seleccionado
        generarQR($conexion, $registro, $nombreArchivoQR);

        // Calcular la fecha de caducidad para mostrarla
        $fechaCaducidad = date('Y-m-d', strtotime('+2 years', strtotime($registro['fecha_registro'])));

        // Mostrar el contenido del código QR para verificar su contenido (opcional)
        echo "Código QR para DNI: {$registro['dni']} <br>";
        echo nl2br($registro['dni'] . "\n" . $registro['nombre'] . "\n" . $registro['celular'] . "\n" . $registro['nro_placa'] . "\n" . $registro['marca'] . "\n" . $registro['modelo'] . "\n" . "Caducidad: {$fechaCaducidad}" . "\n\n");
    } else {
        echo "No se encontró ningún conductor con el ID proporcionado.";
    }
} else {
    echo "No se ha seleccionado ningún conductor.";
}
?>