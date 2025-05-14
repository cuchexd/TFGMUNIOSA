<?php
require '../fpdf/fpdf.php';
include("../config/db.php");

class PDF_Bajas extends FPDF {
    private $titulo = 'MUNICIPALIDAD DE OSA';
    private $subtitulo = 'Reporte de Activos Dados de Baja';

    function Header() {
        // Logo
        $this->Image('../assets/img/logo.png', 10, 6, 30);
        $this->Ln(20); // Espacio después del logo

        // Título principal
        $this->SetFillColor(12, 109, 69); // #0c6d45
        $this->SetTextColor(255);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 12, iconv('UTF-8', 'windows-1252', $this->titulo), 0, 1, 'C', true);

        // Subtítulo
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(0);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', $this->subtitulo), 0, 1, 'C');

        // Fecha de generación
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(0, 8, iconv('UTF-8', 'windows-1252', 'Fecha de generación: ' . date('d/m/Y H:i:s')), 0, 1, 'R');

        // Línea separadora
        $this->SetDrawColor(12, 109, 69);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Página ' . $this->PageNo() . ' de {nb}'), 0, 0, 'C');
    }

    function ImprimirDatos($datos) {
        $this->SetFont('Arial', 'B', 11);
        $this->SetFillColor(12, 109, 69); // Verde encabezado
        $this->SetTextColor(255);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Activo N°: ' . $datos['numero_activo']), 1, 1, 'C', true);

        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(0);

        foreach ($datos as $campo => $valor) {
            $this->SetFillColor(220, 245, 232); // Verde claro en etiquetas
            $this->Cell(60, 8, iconv('UTF-8', 'windows-1252', "$campo:"), 0, 0, 'L', true);
            $this->Cell(0, 8, iconv('UTF-8', 'windows-1252', $valor), 0, 1);
        }

        $this->Ln(5);
    }
}

// Lógica de búsqueda
$busqueda = isset($_POST['busqueda']) ? $conexion->real_escape_string($_POST['busqueda']) : '';
$filtro = "WHERE numero_activo LIKE '%$busqueda%' OR tipo_baja LIKE '%$busqueda%'";

$query = "SELECT * FROM bajas $filtro ORDER BY fecha_baja DESC";
$resultado = $conexion->query($query);

// Crear PDF
$pdf = new PDF_Bajas();
$pdf->AliasNbPages();
$pdf->AddPage();

// Mostrar datos
while ($row = $resultado->fetch_assoc()) {
    $campos = [
        "Fecha de Baja" => $row['fecha_baja'],
        "Fecha de Recepción" => $row['fecha_recepcion'],
        "Oficio de Baja" => $row['oficio_baja'],
        "Tipo de Baja" => $row['tipo_baja'],
        "Clase de Activo" => $row['clase_activo'],
        "Motivo" => $row['motivo'],
        "Modelo" => $row['modelo'],
        "Marca" => $row['marca'],
        "Serie" => $row['serie'],
        "Departamento" => $row['departamento'],
        "Checklist Completado" => $row['checklist_completado'],
        "Ubicación" => $row['ubicacion']
    ];

    // Agregar el número de activo como parte del encabezado
    $campos_con_numero = ["numero_activo" => $row['numero_activo']] + $campos;

    $pdf->ImprimirDatos($campos_con_numero);
}

$pdf->Output("D", "reporte_bajas_" . time() . ".pdf");
exit;
