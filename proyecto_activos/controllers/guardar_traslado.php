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
$fecha_aprobacion = $_POST['fecha_aprobacion'];
$fecha_traslado = $_POST['fecha_traslado'];
$canal = $_POST['canal_comunicacion'];
$motivo = $_POST['motivo'];
$tipo = $_POST['tipo_activo'];
$modelo = $_POST['modelo'];
$marca = $_POST['marca'];
$serie = $_POST['serie'];
$departamento = $_POST['departamento'];
$checklist = $_POST['checklist_completado'];
$ubicacion_ant = $_POST['ubicacion_anterior'];
$ubicacion_act = $_POST['ubicacion_actual'];

// Insertar en la base de datos
$sql = "INSERT INTO traslados (
    numero_activo, fecha_aprobacion, fecha_traslado, canal_comunicacion, motivo,
    tipo_activo, modelo, marca, serie, departamento, checklist_completado,
    ubicacion_anterior, ubicacion_actual
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssssssssss", $numero_activo, $fecha_aprobacion, $fecha_traslado, $canal, $motivo,
    $tipo, $modelo, $marca, $serie, $departamento, $checklist, $ubicacion_ant, $ubicacion_act);

if ($stmt->execute()) {

    // Crear PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,10,'Registro de Traslado de Activo',0,1,'C');
    $pdf->SetFont('Arial','',12);
    $pdf->Ln(10);

    // Datos a mostrar en el PDF
    $datos = [
        "Número Activo" => $numero_activo,
        "Fecha de Aprobación" => $fecha_aprobacion,
        "Fecha de Traslado" => $fecha_traslado,
        "Canal de Comunicación" => $canal,
        "Motivo" => $motivo,
        "Tipo de Activo" => $tipo,
        "Modelo" => $modelo,
        "Marca" => $marca,
        "Serie" => $serie,
        "Departamento" => $departamento,
        "Checklist Completado" => $checklist,
        "Ubicación Anterior" => $ubicacion_ant,
        "Ubicación Actual" => $ubicacion_act
    ];

    foreach ($datos as $campo => $valor) {
        $pdf->Cell(0,10,utf8_decode("$campo: $valor"), 0,1);
    }


    // Guardar PDF
    $nombre_pdf = "traslado_" . time() . ".pdf";
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
        $mail->Subject = 'Nuevo Traslado de Activo Registrado';
        $mail->Body = 'Se ha registrado un nuevo traslado de activo. Se adjunta el documento en PDF.';
        $mail->addAttachment($ruta_pdf);

        $mail->send();

        header("Location: ../views/registro_exitoso.php?tipo=traslado");
        exit();

    } catch (Exception $e) {
        header("Location: ../views/registro_exitoso.php?tipo=traslado&error=correo");
        exit();
    }

} else {
    header("Location: ../views/registro_exitoso.php?tipo=traslado&error=guardar");
    exit();
}
?>
