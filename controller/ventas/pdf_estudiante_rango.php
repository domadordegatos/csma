<?php
require_once "../../model/libraries/fpdf/fpdf.php"; 
require_once "../../model/movimientos.php";
require_once "../../model/conexion.php";

$obj = new movimientos();

// Recibimos parámetros por URL
$id_param = isset($_GET['id']) ? $_GET['id'] : '';
$f1 = isset($_GET['f1']) ? $_GET['f1'] : '';
$f2 = isset($_GET['f2']) ? $_GET['f2'] : '';

if($id_param == '' || $f1 == '' || $f2 == ''){
    die("Faltan parámetros para generar el reporte.");
}

// 1. Obtener datos del perfil del estudiante
$estudiante = $obj->obtener_datos_estudiante($id_param);

// 2. Obtener el desglose de consumos
$datos = $obj->consulta_pdf_detalle($id_param, $f1, $f2);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);

// --- CONFIGURACIÓN DEL LOGO ---
$ruta_logo = "../../view/media/recursos/logo.png"; 
$logo_x = 170; $logo_y = 10; $logo_ancho = 25; 

if(file_exists($ruta_logo)){
    $pdf->Image($ruta_logo, $logo_x, $logo_y, $logo_ancho);
}

// --- TÍTULO ---
$pdf->SetY(15); 
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('REPORTE DETALLADO DE MOVIMIENTOS'), 0, 1, 'C');
$pdf->Ln(15); 

// --- INFORMACIÓN DEL ESTUDIANTE ---
$pdf->SetFillColor(230, 230, 230);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 8, utf8_decode('INFORMACIÓN DEL ESTUDIANTE'), 1, 1, 'L', 1);

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(95, 7, utf8_decode('Nombre: ' . $estudiante['nombre'] . ' ' . $estudiante['apellido']), 1, 0);
$pdf->Cell(95, 7, utf8_decode('ID Sistema: ' . $estudiante['id_usuario']), 1, 1);
$pdf->Cell(95, 7, utf8_decode('No. Tarjeta: ' . $estudiante['id_tarjeta']), 1, 0);
$pdf->Cell(95, 7, utf8_decode('Grado: ' . $estudiante['grado']), 1, 1);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(95, 7, utf8_decode('Saldo Actual: $' . number_format($estudiante['saldo'])), 1, 0);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(95, 7, utf8_decode('Rango reporte: ' . $f1 . ' al ' . $f2), 1, 1);
$pdf->Ln(10);

// --- TABLA DE CONSUMOS ---
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(100, 100, 100);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(20, 7, 'Factura', 1, 0, 'C', 1);
$pdf->Cell(70, 7, 'Producto', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Valor', 1, 0, 'C', 1);
$pdf->Cell(35, 7, 'Fecha/Hora', 1, 0, 'C', 1);
$pdf->Cell(40, 7, 'Vendedor', 1, 1, 'C', 1);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 8);
$total_periodo = 0;

if(mysqli_num_rows($datos) > 0){
    while ($row = mysqli_fetch_assoc($datos)) {
        $pdf->Cell(20, 6, $row['id_factura'], 1, 0, 'C');
        $pdf->Cell(70, 6, utf8_decode($row['descripcion']), 1, 0, 'L');
        $pdf->Cell(25, 6, '$' . number_format($row['total']), 1, 0, 'R');
        $pdf->Cell(35, 6, $row['fecha'] . ' ' . $row['hora'], 1, 0, 'C');
        $pdf->Cell(40, 6, utf8_decode($row['vendedor']), 1, 1, 'L');
        $total_periodo += $row['total'];
    }
    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(90, 10, '', 0, 0);
    $pdf->Cell(50, 10, 'TOTAL GASTADO EN PERIODO:', 0, 0, 'R');
    $pdf->Cell(25, 10, '$' . number_format($total_periodo), 0, 1, 'R');
} else {
    $pdf->Cell(0, 10, utf8_decode('No hay movimientos registrados.'), 1, 1, 'C');
}

// =========================================================================
// CONFIGURACIÓN DEL NOMBRE DEL ARCHIVO DINÁMICO
// =========================================================================
$nombre_full = $estudiante['nombre'] . '_' . $estudiante['apellido'];
$grado_txt = $estudiante['grado'];
$tarjeta_txt = $estudiante['id_tarjeta'];

// Limpiar el nombre de espacios para que sea un nombre de archivo válido
$filename = "Reporte_" . $nombre_full . "_" . $grado_txt . "_Tarjeta_" . $tarjeta_txt . ".pdf";
$filename = str_replace(' ', '_', $filename); // Reemplaza espacios por guiones bajos

// 'I' envía el PDF al navegador, el segundo parámetro es el nombre que sugerirá al guardar
$pdf->Output('I', $filename);
?>