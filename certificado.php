<?php
// Verificar si se recibió el parámetro 'id' en la URL


 if (isset($_GET['id_conductor'])) {
    // Obtener el ID del conductor desde la URL
    $idConductor = $_GET['id_conductor'];

    // Realizar la conexión a la base de datos
    require_once "config/connection.php";

    // Consulta para obtener los datos del conductor y vehículo por ID
    $consulta = "SELECT c.*, v.nro_placa, v.tipo_vehiculo, v.marca, v.modelo
                 FROM conductor c
                 INNER JOIN vehiculo v ON c.id_conductor = v.id_conductor
                 WHERE c.id_conductor = :id";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bindParam(':id', $idConductor, PDO::PARAM_INT);
    $sentencia->execute();
    $conductor = $sentencia->fetch(PDO::FETCH_ASSOC);

    // Consulta para obtener la fecha_caducidad de la tabla fecha_registro por ID
    $consultaFechaRegistro = "SELECT fecha_caducidad FROM fecha_registro WHERE id_conductor = :id";
    $sentenciaFechaRegistro = $conexion->prepare($consultaFechaRegistro);
    $sentenciaFechaRegistro->bindParam(':id', $idConductor, PDO::PARAM_INT);
    $sentenciaFechaRegistro->execute();
    $fechaCaducidad = $sentenciaFechaRegistro->fetchColumn();

    // Verificar si se encontró el conductor
    if ($conductor) {
        // Incluir la librería TCPDF
        require('libreria_qr/TCPDF/tcpdf.php');

        // Crear una instancia del objeto TCPDF con orientación horizontal
        $pdf = new TCPDF('L');

        // Agregar una página al PDF
        $pdf->AddPage();

        // Calcular la posición X para centrar el logo en la página A4
        $posicionX = (297 - 45) / 2; //divide pagina para centrar

        // Agregar el logo centrado en el PDF con la clase "logo" 25=subir
        $pdf->Image('estilos/logo/logo_muni.png', $posicionX, 25, 45, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, 'C', false, false);

        // Calcular el ancho del texto "Subgerencia de Transporte"
        $anchoTexto = $pdf->GetStringWidth('Subgerencia de Transporte');

        // Calcular la posición X para centrar el texto en la página A4
        $posicionX = (297 - $anchoTexto) / 2;

        // Agregar la Subgerencia de Transporte centrada horizontalmente en el PDF con la clase "subgerencia"
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->SetXY($posicionX, 80); // Ajusta la posición Y (50 en este caso) según tus necesidades
        $pdf->Cell($anchoTexto, 10, 'SUB-GERENCIA DE TRANSPORTES', 0, 1, 'C');

        // Agregar espaciado
        $pdf->Ln(10);

        // Agregar la información del conductor con la clase "informacion"
        $pdf->SetFont('helvetica', '', 16);
        $contenidoCertificado = strtoupper($conductor['nombre'] . " " . $conductor['apellido_paterno'] . " " . $conductor['apellido_materno']) . "\n";

        // Agregar el espaciado deseado
        $contenidoCertificado .= "\n"; // Puedes ajustar la cantidad de saltos de línea según tu preferencia
        $contenidoCertificado .= "PLACA: " . $conductor['nro_placa'] . "\nMARCA: " .
            $conductor['marca'] . " \nMODELO: " .
            $conductor['modelo'];

        $contenidoCertificado .= "\n";
        $contenidoCertificado .= "\nCADUCIDAD: " . $fechaCaducidad;

        $pdf->MultiCell(0, 10, $contenidoCertificado, 0, 'C');

        // Construir el nombre del archivo QR con el formato "dni.png"
        $nombreArchivoQR = $conductor['dni'] . ".png";

        // Verificar si el archivo QR existe antes de mostrarlo
        $rutaArchivoQR = "qr_conductor/{$nombreArchivoQR}";
        if (file_exists($rutaArchivoQR)) {
            // Mostrar la imagen del código QR en el PDF
            $pdf->Image($rutaArchivoQR, 25, 138, 50, 50, 'PNG');
        } else {
            echo "Error: No se encontró el código QR para el DNI: {$conductor['dni']}";
        }

// Verificar si el archivo de la firma del gerente existe
$rutaArchivoFirmaGerente = "estilos/firma/firma_gerente.jpg";
if (file_exists($rutaArchivoFirmaGerente)) {
    // Mostrar la imagen de la firma del gerente en el PDF
    $pdf->Image($rutaArchivoFirmaGerente, 220, 158, 50, 0, 'JPG'); // Cambiar a 'JPEG' si el formato es JPG
} else {
    echo "Error: No se encontró la firma del gerente.";
}

        // Generar el PDF
        ob_clean(); // Limpiamos cualquier salida previa
        $pdf->Output();
        exit; // Detenemos la ejecución del resto del código
    } else {
        // Si no se encontró el conductor, puedes mostrar un mensaje de error o redireccionar a otra página.
        echo "No se encontró el conductor.";
    }
} else {
    // Si no se recibió el parámetro 'id' en la URL, puedes mostrar un mensaje de error o redireccionar a otra página.
    echo "Error: Falta el parámetro 'id' en la URL.";
}


?>