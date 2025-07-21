<?php
require_once __DIR__ . '/FPDF-master/fpdf.php';
include '../include/conn.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Data Alternatif','0','1','C');
$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,8,'No',1,0,'C');
$pdf->Cell(30,8,'Foto',1,0,'C');
$pdf->Cell(70,8,'Nama Alternatif',1,0,'C');
$pdf->Cell(40,8,'Biodata PDF',1,1,'C');

$pdf->SetFont('Arial','',10);
$no = 1;
$q = mysqli_query($db, "SELECT name, profile_photo, biodata_pdf FROM saw_alternatives");
while($d = mysqli_fetch_assoc($q)) {
    $pdf->Cell(10,20,$no++,1,0,'C');
    // Foto
    if (!empty($d['profile_photo']) && file_exists('../uploads/'.$d['profile_photo'])) {
        $pdf->Cell(30,20,'',1,0,'C');
        $x = $pdf->GetX(); $y = $pdf->GetY();
        $pdf->Image('../uploads/'.$d['profile_photo'], $x-30+5, $y+2, 16, 16);
    } else {
        $pdf->Cell(30,20,'-',1,0,'C');
    }
    $pdf->Cell(70,20,$d['name'],1,0);
    if (!empty($d['biodata_pdf']) && file_exists('../uploads/'.$d['biodata_pdf'])) {
        $pdf->SetFont('Arial','U',10);
        $pdf->SetTextColor(0,0,255);
        $link = '../uploads/'.$d['biodata_pdf'];
        $pdf->Cell(40,20,'Ada',1,0,'C',false,$link);
        $pdf->SetFont('Arial','',10);
        $pdf->SetTextColor(0,0,0);
    } else {
        $pdf->Cell(40,20,'Tidak Ada',1,0,'C');
    }
    $pdf->Cell(0,20,'',0,1);
}

$pdf->Output('I', 'data-alternatif.pdf'); 