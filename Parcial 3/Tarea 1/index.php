<?php
require('fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 35);
$pdf->Cell(40, 10, 'Diaz Hernandez Axl Enrique');
$pdf->Output();
?>
