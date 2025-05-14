<?php
require '../fpdf/fpdf.php';
include("../config/db.php");

// Clase extendida de FPDF para agregar encabezado y pie de página
class PDF_Traslados extends FPDF {
    // Información para el encabezado y pie de página
    private $titulo = 'MUNICIPALIDAD DE OSA';
    private $subtitulo = 'Reporte de Activos Trasladados';
    
   
    function Header() {
        // Logo
        $this->Image('../assets/img/logo.png', 10, 6, 30);
        $this->Ln(20); // Espacio después del logo

        // Título principal con fondo verde
        $this->SetFillColor(12, 109, 69); // #0c6d45
        $this->SetTextColor(255);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 12, iconv('UTF-8', 'windows-1252', $this->titulo), 0, 1, 'C', true);

        // Subtítulo sin fondo
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
    
    // Función para crear una tabla de datos con estilo
    function DatosActivo($datos) {
        // Colores, fuente y grosor de línea
        $this->SetFillColor(12, 109, 69); // #0c6d45
        $this->SetTextColor(0);
        $this->SetFont('Arial', 'B', 11);
        $this->SetDrawColor(12, 109, 69);
        $this->SetLineWidth(0.3);
        
        // Título del activo con fondo azul
        $this->SetFillColor(12, 109, 69);
        $this->SetTextColor(255);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Activo N°: ' . $datos['numero_activo']), 1, 1, 'C', true);
        $this->SetTextColor(0);
        
        // Variables para controlar el ancho de columnas
        $col1Width = 60;
        $col2Width = 55;
        $col3Width = 35;
        $col4Width = 40;
        
        // Primera fila - Información principal
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(220, 220, 220);
        $this->Cell($col1Width, 8, iconv('UTF-8', 'windows-1252', 'Tipo de Activo:'), 1, 0, 'L', true);
        $this->SetFont('Arial', '', 10);
        $this->Cell($col2Width, 8, iconv('UTF-8', 'windows-1252', $datos['tipo_activo']), 1, 0, 'L');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col3Width, 8, iconv('UTF-8', 'windows-1252', 'Marca:'), 1, 0, 'L', true);
        $this->SetFont('Arial', '', 10);
        $this->Cell($col4Width, 8, iconv('UTF-8', 'windows-1252', $datos['marca']), 1, 1, 'L');
        
        // Segunda fila
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(220, 220, 220);
        $this->Cell($col1Width, 8, iconv('UTF-8', 'windows-1252', 'Modelo:'), 1, 0, 'L', true);
        $this->SetFont('Arial', '', 10);
        $this->Cell($col2Width, 8, iconv('UTF-8', 'windows-1252', $datos['modelo']), 1, 0, 'L');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col3Width, 8, iconv('UTF-8', 'windows-1252', 'Serie:'), 1, 0, 'L', true);
        $this->SetFont('Arial', '', 10);
        $this->Cell($col4Width, 8, iconv('UTF-8', 'windows-1252', $datos['serie']), 1, 1, 'L');
        
        // Tercera fila - Información de traslado
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(220, 220, 220);
        $this->Cell($col1Width, 8, iconv('UTF-8', 'windows-1252', 'Fecha de Aprobación:'), 1, 0, 'L', true);
        $this->SetFont('Arial', '', 10);
        $this->Cell($col2Width, 8, iconv('UTF-8', 'windows-1252', $this->formatearFecha($datos['fecha_aprobacion'])), 1, 0, 'L');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col3Width, 8, iconv('UTF-8', 'windows-1252', 'Fecha de Traslado:'), 1, 0, 'L', true);
        $this->SetFont('Arial', '', 10);
        $this->Cell($col4Width, 8, iconv('UTF-8', 'windows-1252', $this->formatearFecha($datos['fecha_traslado'])), 1, 1, 'L');
        
        // Cuarta fila - Ubicaciones
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(220, 220, 220);
        $this->Cell($col1Width, 8, iconv('UTF-8', 'windows-1252', 'Ubicación Anterior:'), 1, 0, 'L', true);
        $this->SetFont('Arial', '', 10);
        $this->Cell($col2Width, 8, iconv('UTF-8', 'windows-1252', $datos['ubicacion_anterior']), 1, 0, 'L');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col3Width, 8, iconv('UTF-8', 'windows-1252', 'Ubicación Actual:'), 1, 0, 'L', true);
        $this->SetFont('Arial', '', 10);
        $this->Cell($col4Width, 8, iconv('UTF-8', 'windows-1252', $datos['ubicacion_actual']), 1, 1, 'L');
        
        // Quinta fila - Departamento
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(220, 220, 220);
        $this->Cell($col1Width, 8, iconv('UTF-8', 'windows-1252', 'Departamento:'), 1, 0, 'L', true);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 8, iconv('UTF-8', 'windows-1252', $datos['departamento']), 1, 1, 'L');
        
        // Sexta fila - Canal y Checklist
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(220, 220, 220);
        $this->Cell($col1Width, 8, iconv('UTF-8', 'windows-1252', 'Canal de Comunicación:'), 1, 0, 'L', true);
        $this->SetFont('Arial', '', 10);
        $this->Cell($col2Width, 8, iconv('UTF-8', 'windows-1252', $datos['canal_comunicacion']), 1, 0, 'L');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col3Width, 8, iconv('UTF-8', 'windows-1252', 'Checklist Completado:'), 1, 0, 'L', true);
        $this->SetFont('Arial', '', 10);
        $this->Cell($col4Width, 8, iconv('UTF-8', 'windows-1252', $datos['checklist_completado']), 1, 1, 'L');
        
        // Séptima fila - Motivo (puede ocupar más espacio)
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(220, 220, 220);
        $this->Cell(0, 8, iconv('UTF-8', 'windows-1252', 'Motivo del traslado:'), 1, 1, 'L', true);
        
        // Texto del motivo con posibilidad de varias líneas
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(0, 8, iconv('UTF-8', 'windows-1252', $datos['motivo']), 1, 'L');
        
        // Espacio entre registros
        $this->Ln(8);
    }
    
    // Función para formatear fechas
    function formatearFecha($fecha) {
        if (empty($fecha) || $fecha == '0000-00-00') {
            return 'No especificada';
        }
        
        // Convertir de formato MySQL (YYYY-MM-DD) a DD/MM/YYYY
        $fecha_dt = new DateTime($fecha);
        return $fecha_dt->format('d/m/Y');
    }
}

// Preparar la consulta SQL con parámetros seguros
$busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';

// Utilizar consultas preparadas para mayor seguridad
$stmt = $conexion->prepare("SELECT * FROM traslados WHERE numero_activo LIKE ? OR tipo_activo LIKE ? ORDER BY fecha_traslado DESC");
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}

// Preparar los parámetros para la búsqueda
$parametro = "%$busqueda%";
$stmt->bind_param("ss", $parametro, $parametro);
$stmt->execute();
$resultado = $stmt->get_result();

// Crear instancia de PDF
$pdf = new PDF_Traslados();
$pdf->AliasNbPages(); // Para mostrar el total de páginas en el pie de página
$pdf->SetAutoPageBreak(true, 15); // Margen inferior automático para el pie de página
$pdf->AddPage();

// Si no hay resultados, mostrar mensaje
if ($resultado->num_rows == 0) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'No se encontraron activos que coincidan con la búsqueda.'), 0, 1, 'C');
} else {
    // Generar el PDF con los datos obtenidos
    while ($row = $resultado->fetch_assoc()) {
        // Verificar si se necesita una nueva página
        if ($pdf->GetY() > 220) {
            $pdf->AddPage();
        }
        
        // Añadir los datos del activo actual
        $pdf->DatosActivo($row);
    }
}

// Cerrar la consulta
$stmt->close();

// Generar y descargar el PDF
$pdf->Output("D", "reporte_traslados_" . date('Y-m-d_H-i-s') . ".pdf");
exit;
?>



