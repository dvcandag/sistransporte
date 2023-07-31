<?php
// Realiza la conexión a la base de datos
require_once "config/connection.php";

// Función para generar el código QR y guardar la imagen en la carpeta "qr_conductor"
function generarQR($datos, $filename)
{
    // Tamaño del código QR y margen
    $longitud = 5;
    $margen = 1;

    // Creamos una imagen en blanco del tamaño del código QR
    $imagen = imagecreate($longitud * 25, $longitud * 25);

    // Definimos los colores para el código QR
    $colorBlanco = imagecolorallocate($imagen, 255, 165, 0);
    $colorNegro = imagecolorallocate($imagen, 0, 0, 0);

    // Rellenamos la imagen con el color blanco
    imagefill($imagen, 0, 0, $colorBlanco);

    // Construir el contenido del código QR con los datos del conductor
    $contenidoQR = "DNI: {$datos['dni']}\n";
    $contenidoQR .= "Nombre: {$datos['apellido_paterno']} {$datos['apellido_materno']}, {$datos['nombre']}\n";
    $contenidoQR .= "Celular: {$datos['celular']}\n";
    $contenidoQR .= "Placa: {$datos['nro_placa']}\n";
    $contenidoQR .= "Marca/Modelo: {$datos['marca']} / {$datos['modelo']}\n";

    // Calcular la fecha de caducidad (2 años después de la fecha de registro)
    $fechaCaducidad = date('Y-m-d', strtotime('+2 years', strtotime($datos['fecha_registro'])));
    $contenidoQR .= "Caducidad: {$fechaCaducidad}\n";

    // Convertimos el contenido en una matriz de puntos (0 para blanco, 1 para negro)
    $puntos = str_split($contenidoQR);
    $x = $margen;

    // Dibujamos el código QR punto por punto
    foreach ($puntos as $punto) {
        $y = $margen;
        if ($punto === "2") {
            imagesetpixel($imagen, $x, $y, $colorNegro);
        }
        $x += $longitud;
    }

    // Guardamos la imagen como archivo PNG en la carpeta "qr_conductor"
    $rutaImagenQR = "qr_conductor/" . $filename;
    imagepng($imagen, $rutaImagenQR);

    // Liberamos memoria
    imagedestroy($imagen);

    // Actualizamos la fecha de caducidad en la base de datos
    $idConductor = $datos['id_conductor'];
    $fechaCaducidad = date('Y-m-d', strtotime('+2 years', strtotime($datos['fecha_registro'])));

    // Establecer la conexión a la base de datos (ya que estamos dentro de una función)
    global $conexion;

    // Consulta para actualizar la fecha de caducidad en la tabla "fecha_registro"
    $actualizarFechaCaducidad = "UPDATE fecha_registro SET fecha_caducidad = :fechaCaducidad WHERE id_conductor = :idConductor";
    $sentenciaActualizar = $conexion->prepare($actualizarFechaCaducidad);
    $sentenciaActualizar->execute(['fechaCaducidad' => $fechaCaducidad, 'idConductor' => $idConductor]);
}

// Consulta para obtener los datos de conductores y vehículos
$consulta = "SELECT c.*, v.nro_placa, v.marca, v.modelo, f.fecha_registro
             FROM conductor c
             INNER JOIN vehiculo v ON c.id_conductor = v.id_conductor
             INNER JOIN fecha_registro f ON c.id_conductor = f.id_conductor
             ORDER BY c.id_conductor DESC";
$sentencia = $conexion->prepare($consulta);
$sentencia->execute();
$registros = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Generar y guardar el código QR para cada conductor
if (!empty($registros)) {
    foreach ($registros as $registro) {
        // Generar el nombre del archivo QR con el formato "dni.png"
        $nombreArchivoQR = $registro['dni'] . ".png";

        // Generar y guardar el código QR con los datos del conductor
        generarQR($registro, $nombreArchivoQR);

        // Mostrar el contenido del código QR para verificar su contenido (opcional)
        echo "Contenido del código QR para DNI: {$registro['dni']} <br>";
       
    }

    echo "Se han generado y guardado los códigos QR exitosamente.";
} else {
    echo "No hay registros para generar códigos QR.";
}
