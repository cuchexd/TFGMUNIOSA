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

    // Crear PDF con formato personalizado
    class PDF_Baja extends FPDF {
        function Header() {
            $this->Image('../assets/img/logo.png', 10, 6, 30);
            $this->Ln(20);
            $this->SetFillColor(12, 109, 69);
            $this->SetTextColor(255);
            $this->SetFont('Arial', 'B', 16);
            $this->Cell(0, 12, iconv('UTF-8', 'windows-1252', 'MUNICIPALIDAD DE OSA'), 0, 1, 'C', true);
            $this->SetFont('Arial', 'B', 14);
            $this->SetTextColor(0);
            $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Registro de Baja de Activo'), 0, 1, 'C');
            $this->SetFont('Arial', 'I', 9);
            $this->Cell(0, 8, iconv('UTF-8', 'windows-1252', 'Fecha de generación: ' . date('d/m/Y H:i:s')), 0, 1, 'R');
            $this->SetDrawColor(12, 109, 69);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(5);
        }
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
        }
        function ImprimirDatos($datos) {
            $this->SetFont('Arial', '', 11);
            foreach ($datos as $campo => $valor) {
                $this->SetFillColor(220, 245, 232); // verde claro
                $this->Cell(60, 8, iconv('UTF-8', 'windows-1252', "$campo:"), 0, 0, 'L', true);
                $this->Cell(0, 8, iconv('UTF-8', 'windows-1252', $valor), 0, 1);
            }
        }
    }

    $pdf = new PDF_Baja();
    $pdf->AddPage();

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

    $pdf->ImprimirDatos($datos);

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
        $mail->Username = 'miguelurea.u@gmail.com';
        $mail->Password = 'wcvnkcfzanfonzzm';
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
