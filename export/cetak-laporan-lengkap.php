<?php
require_once __DIR__ . '/FPDF-master/fpdf.php';
include '../include/conn.php';

class PDF_Laporan extends FPDF {
    function Footer() {
        $this->SetY(-12);
        $this->SetFont('Arial','I',9);
        $this->Cell(0,8,'Dicetak pada: '.date('d-m-Y H:i').' oleh Sistem SPK SAW',0,0,'R');
    }
}

$pdf = new PDF_Laporan('L','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'LAPORAN LENGKAP SPK SAW','0','1','C');
$pdf->Ln(5);

// 1. Alternatif
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,'Daftar Alternatif','0','1');
$pdf->SetFont('Arial','',10);
$pdf->Cell(10,8,'No',1,0,'C');
$pdf->Cell(30,8,'Foto',1,0,'C');
$pdf->Cell(70,8,'Nama Alternatif',1,0,'C');
$pdf->Cell(40,8,'Biodata PDF',1,1,'C');
$no=1;
$q = mysqli_query($db, "SELECT id_alternative, name, profile_photo, biodata_pdf FROM saw_alternatives");
$alt = [];
while($d = mysqli_fetch_assoc($q)) {
    $pdf->Cell(10,20,$no,1,0,'C');
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
    $alt[$d['id_alternative']] = $d['name'];
    $no++;
}
$pdf->Ln(3);

// 2. Kriteria & Bobot
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,'Daftar Kriteria & Bobot','0','1');
$pdf->SetFont('Arial','',10);
$pdf->Cell(10,8,'ID',1,0,'C');
$pdf->Cell(70,8,'Kriteria',1,0,'C');
$pdf->Cell(20,8,'Bobot',1,0,'C');
$pdf->Cell(30,8,'Attribute',1,1,'C');
$q = mysqli_query($db, "SELECT id_criteria, criteria, weight, attribute FROM saw_criterias ORDER BY id_criteria");
$kriteria = [];
while($d = mysqli_fetch_assoc($q)) {
    $pdf->Cell(10,8,$d['id_criteria'],1,0,'C');
    $pdf->Cell(70,8,$d['criteria'],1,0);
    $pdf->Cell(20,8,$d['weight'],1,0,'C');
    $pdf->Cell(30,8,$d['attribute'],1,1,'C');
    $kriteria[$d['id_criteria']] = $d;
}
$pdf->Ln(3);

function potong_nama($nama, $max=35) {
    if (empty($nama)) return '-';
    return (mb_strlen($nama)>$max) ? mb_substr($nama,0,$max-3).'...' : $nama;
}

// 3. Matriks Penilaian (X)
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,'Matriks Penilaian (X)','0','1');
$pdf->SetFont('Arial','',10);
$pdf->Cell(60,8,'Alternatif',1,0,'C');
foreach($kriteria as $kid=>$kr) {
    $pdf->Cell(25,8,'C'.$kid,1,0,'C');
}
$pdf->Ln();
// Query dinamis untuk matriks penilaian
$sql_select = "SELECT a.id_alternative, b.name";
foreach(array_keys($kriteria) as $i) {
    $sql_select .= ", SUM(IF(a.id_criteria=$i,a.value,0)) AS C$i";
}
$sql_select .= " FROM saw_evaluations a JOIN saw_alternatives b USING(id_alternative) GROUP BY a.id_alternative ORDER BY a.id_alternative";
$q = mysqli_query($db, $sql_select);
$X = [];
while($d = mysqli_fetch_assoc($q)) {
    $nama_alt = isset($d['name']) ? $d['name'] : (isset($alt[$d['id_alternative']]) ? $alt[$d['id_alternative']] : '-');
    $pdf->Cell(60,8,potong_nama($nama_alt),1,0);
    $idx = 1;
    foreach(array_keys($kriteria) as $i) {
        $val = isset($d['C'.$i]) ? $d['C'.$i] : 0;
        $pdf->Cell(25,8,round($val,2),1,0,'C');
        if (!isset($X[$i])) $X[$i] = [];
        $X[$i][] = floatval($val);
        $idx++;
    }
    $pdf->Ln();
}
$pdf->Ln(3);

// 4. Matriks Normalisasi (R)
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,'Matriks Normalisasi (R)','0','1');
$pdf->SetFont('Arial','',10);
$pdf->Cell(60,8,'Alternatif',1,0,'C');
foreach($kriteria as $kid=>$kr) {
    $pdf->Cell(25,8,'C'.$kid,1,0,'C');
}
$pdf->Ln();
// Pastikan $X[i] tidak kosong
foreach(array_keys($kriteria) as $i) {
    if (!isset($X[$i]) || count($X[$i])==0) $X[$i] = [1];
}
// Query dinamis untuk normalisasi
$sql_norm = "SELECT a.id_alternative";
foreach(array_keys($kriteria) as $i) {
    $attr = $kriteria[$i]['attribute'];
    $max = max($X[$i]);
    $min = min($X[$i]);
    $sql_norm .= ", SUM(IF(a.id_criteria=$i,IF(b.attribute='benefit',a.value/$max,$min/a.value),0)) AS C$i";
}
$sql_norm .= " FROM saw_evaluations a JOIN saw_criterias b USING(id_criteria) GROUP BY a.id_alternative ORDER BY a.id_alternative";
$q = mysqli_query($db, $sql_norm);
$R = [];
while($d = mysqli_fetch_assoc($q)) {
    if (!isset($alt[$d['id_alternative']])) continue; // Skip jika alternatif tidak valid
    $nama_alt = $alt[$d['id_alternative']];
    $pdf->Cell(60,8,potong_nama($nama_alt),1,0);
    foreach(array_keys($kriteria) as $i) {
        $val = isset($d['C'.$i]) ? $d['C'.$i] : 0;
        $pdf->Cell(25,8,round($val,4),1,0,'C');
        $R[$d['id_alternative']][$i] = $val;
    }
    $pdf->Ln();
}
$pdf->Ln(3);

// 5. Hasil Akhir (Ranking)
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,'Hasil Akhir (Ranking)','0','1');
$pdf->SetFont('Arial','',10);
$pdf->Cell(10,8,'No',1,0,'C');
$pdf->Cell(60,8,'Alternatif',1,0,'C');
$pdf->Cell(30,8,'Nilai Akhir',1,0,'C');
$pdf->Cell(20,8,'Ranking',1,1,'C');
// Hitung nilai akhir
$W = [];
$q = mysqli_query($db, "SELECT weight FROM saw_criterias ORDER BY id_criteria");
while($d = mysqli_fetch_assoc($q)) $W[] = $d['weight'];
$hasil = [];
foreach($R as $id=>$nilai) {
    $total = 0;
    for($i=1;$i<=count($W);$i++) {
        $v = isset($nilai[$i]) ? $nilai[$i] : 0;
        $w = isset($W[$i-1]) ? $W[$i-1] : 0;
        $total += $v * $w;
    }
    $hasil[$id] = $total;
}
arsort($hasil);
$no=1;
foreach($hasil as $id=>$nilai) {
    if (!isset($alt[$id])) continue; // Hanya tampilkan alternatif yang valid
    $pdf->Cell(10,8,$no,1,0,'C');
    $pdf->Cell(60,8,$alt[$id],1,0);
    $pdf->Cell(30,8,round($nilai,4),1,0,'C');
    $pdf->Cell(20,8,$no,1,1,'C');
    $no++;
}
$pdf->Ln(5);

$pdf->Output('I', 'laporan-lengkap-spk-saw.pdf'); 