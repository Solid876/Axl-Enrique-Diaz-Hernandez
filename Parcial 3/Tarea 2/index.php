<?php
require('fpdf.php');

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Lista de Productos', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 10, 'id', 1, 0, 'C');
$pdf->Cell(60, 10, 'Nombre', 1, 0, 'C');
$pdf->Cell(80, 10, 'Descripcion', 1, 0, 'C');
$pdf->Cell(30, 10, 'Precio', 1, 1, 'C');

$pdf->SetFont('Arial', '', 10);

$productos = [
    ['id' => 1, 'nombre' => 'Cuaderno', 'descripcion' => 'Cuaderno de 100 hojas', 'precio' => 40.00],
    ['id' => 2, 'nombre' => 'Lapiz', 'descripcion' => 'Lapiz color naranja', 'precio' => 5.00],
    ['id' => 3, 'nombre' => 'Mochila', 'descripcion' => 'Mochila escolar color azul', 'precio' => 450.00],
    ['id' => 4, 'nombre' => 'Borrador', 'descripcion' => 'Borrador grueso', 'precio' => 10.00],
];

foreach ($productos as $producto) {
    $pdf->Cell(20, 10, $producto['id'], 1, 0, 'C');
    $pdf->Cell(60, 10, $producto['nombre'], 1, 0, 'L');
    $pdf->Cell(80, 10, $producto['descripcion'], 1, 0, 'L');
    $pdf->Cell(30, 10, '$' . number_format($producto['precio'], 2), 1, 1, 'C');
}

$pdf->Output();
?>
