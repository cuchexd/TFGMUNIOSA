<?php
session_start();
include("../config/db.php");

// Librerías necesarias
require '../fpdf/fpdf.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

// Recolectar datos del formulario
$numero_activo = $_POST['numero_activo'];
$fecha_baja = $_POST['fecha_baja'];
$fecha_recepcion = $_POST['fecha_recepcion'];
$oficio = $_POST['oficio_baja'];
$tipo = $_POST['tipo_baja'];
$clase = $_POST['clase_activo'];
$motivo = $_POST['motivo'];
$modelo = $_POST['modelo'];
$marca = $_POST['marca'];
$serie = $_POST['serie'];
$departamento = $_POST['departamento'];
$checklist = $_POST['checklist_completado'];
$ubicacion = $_POST['ubicacion'];

// Insertar en la base de datos
$sql = "INSERT INTO bajas (
    numero_activo, fecha_baja, fecha_recepcion, oficio_baja, tipo_baja, clase_activo, motivo,
    modelo, marca, serie, departamento, checklist_completado, ubicacion
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssssssssss", $numero_activo, $fecha_baja, $fecha_recepcion, $oficio, $tipo,
    $clase, $motivo, $modelo, $marca, $serie, $departamento, $checklist, $ubicacion);

if ($stmt->execute()) {

    // Crear PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,10,'Registro de Baja de Activo',0,1,'C');
    $pdf->SetFont('Arial','',12);
    $pdf->Ln(10);

    // Datos a mostrar en el PDF
    $datos = [
        "Número Activo" => $numero_activo,
        "Fecha de Baja" => $fecha_baja,
        "Fecha de Recepción" => $fecha_recepcion,
        "Oficio de Baja" => $oficio,
        "Tipo de Baja" => $tipo,
        "Clase de Activo" => $clase,
        "Motivo" => $motivo,
        "Modelo" => $modelo,
        "Marca" => $marca,
        "Serie" => $serie,
        "Departamento" => $departamento,
        "Checklist Completado" => $checklist,
        "Ubicación" => $ubicacion
    ];

    foreach ($datos as $campo => $valor) {
        $pdf->Cell(0,10,utf8_decode("$campo: $valor"), 0,1);
    }

    // Guardar PDF
    $nombre_pdf = "baja_" . time() . ".pdf";
    $ruta_pdf = "../pdf/" . $nombre_pdf;
    $pdf->Output('F', $ruta_pdf);

    // Enviar correo
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'miguelurea.u@gmail.com'; // Tu correo
        $mail->Password = 'wcvnkcfzanfonzzm';       // Tu contraseña de app
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('miguelurea.u@gmail.com', 'Sistema Municipalidad Osa');

        // Agregar destinatarios
        $resultado = $conexion->query("SELECT correo FROM destinatarios");
        while ($fila = $resultado->fetch_assoc()) {
            $mail->addAddress($fila['correo']);
        }

        $mail->isHTML(true);
        $mail->Subject = 'Nueva Baja de Activo Registrada';
        $mail->Body = 'Se ha registrado una nueva baja de activo. Se adjunta el documento en PDF.';
        $mail->addAttachment($ruta_pdf);

        $mail->send();

        header("Location: ../views/registro_exitoso.php?tipo=baja");
        exit();

    } catch (Exception $e) {
        header("Location: ../views/registro_exitoso.php?tipo=baja&error=correo");
        exit();
    }

} else {
    header("Location: ../views/registro_exitoso.php?tipo=baja&error=guardar");
    exit();
}
?>
