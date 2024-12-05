<?php
require('fpdf186/fpdf.php');

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "papeleria_cetis84");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta para obtener los datos de la tabla productos
$query = "SELECT id, nombre, descripcion, precio  FROM productos";
$resultado = $conexion->query($query);

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Establece la fuente para el encabezado
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Lista de Productos', 0, 1, 'C');
$pdf->Ln(5);

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 10, 'id', 1, 0, 'C');
$pdf->Cell(60, 10, 'Nombre', 1, 0, 'C');
$pdf->Cell(80, 10, 'Descripcion', 1, 0, 'C');
$pdf->Cell(30, 10, 'Precio', 1, 1, 'C');


// Cambia la fuente para los datos
$pdf->SetFont('Arial', '', 10);

// Llenado de la tabla con los datos de la base de datos
while ($row = $resultado->fetch_assoc()) {
    $pdf->Cell(20, 10, $row['id'], 1, 0, 'C');
    $pdf->Cell(60, 10, $row['nombre'], 1, 0, 'L');
    $pdf->Cell(80, 10, $row['descripcion'], 1, 0, 'L');
    $pdf->Cell(30, 10, '$' . number_format($row['precio'], 2), 1, 1, 'C');

}

// Cierra la conexión a la base de datos
$conexion->close();

// Salida del PDF
$pdf->Output();
?>