<!DOCTYPE html>
<html lang="en">
    <?php
require "layout/head.php";
require "include/conn.php";
// Cek jumlah alternatif
$alt_count = 0;
$sql_alt = 'SELECT COUNT(*) as jml FROM saw_alternatives';
$res_alt = $db->query($sql_alt);
if ($res_alt) {
    $row_alt = $res_alt->fetch_assoc();
    $alt_count = $row_alt['jml'];
}
// Ambil urutan nama dari tabel alternatif (berdasarkan id_alternative ASC = urutan input)
$alt_names = array();
$sql_alt = "SELECT id_alternative, name FROM saw_alternatives ORDER BY id_alternative ASC";
$result_alt = $db->query($sql_alt);
while ($row_alt = $result_alt->fetch_object()) {
    $alt_names[$row_alt->id_alternative] = $row_alt->name;
}

// Ambil kriteria dinamis
$kriteria = array();
$sql_kriteria = "SELECT id_criteria FROM saw_criterias ORDER BY id_criteria";
$res_kriteria = $db->query($sql_kriteria);
while ($row = $res_kriteria->fetch_assoc()) {
    $kriteria[] = $row['id_criteria'];
}
$kriteria_count = count($kriteria);
?>

<body>
    <div id="app">
    <?php require "layout/navbar.php";?>
    <div id="main">
        <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
        </header>
        <div class="page-heading">
        <h3>Matrik</h3>
        </div>
        <div class="page-content">
        <section class="row">
            <div class="col-12">
            <div class="card">

                <div class="card-header">
                <h4 class="card-title">Matriks Keputusan (X) &amp; Ternormalisasi (R)</h4>
                </div>
                <div class="card-content">
                <div class="card-body">
                    <p class="card-text">Melakukan perhitungan normalisasi untuk mendapatkan matriks nilai ternormalisasi (R), dengan ketentuan :
Untuk normalisai nilai, jika faktor/attribute kriteria bertipe cost maka digunakan rumusan:
Rij = ( min{Xij} / Xij)
sedangkan jika faktor/attribute kriteria bertipe benefit maka digunakan rumusan:
Rij = ( Xij/max{Xij} )</p>
                </div>
                <button type="button" class="btn btn-outline-success btn-sm m-2" data-bs-toggle="modal" data-bs-target="#inlineForm" <?php if($alt_count==0) echo 'disabled'; ?>>
                    Isi Nilai Alternatif
                </button>
                <?php if($alt_count==0) echo '<div class="alert alert-warning mt-2">Silakan tambahkan alternatif terlebih dahulu!</div>'; ?>
                <div class="table-responsive">
                <table class="table table-striped mb-0">
<caption>Matrik Keputusan(X)</caption>
<tr>
    <th rowspan='2'>Alternatif</th>
    <th colspan='<?= $kriteria_count ?>'>Kriteria</th>
</tr>
<tr>
<?php foreach($kriteria as $kid): ?>
    <th>C<?= $kid ?></th>
<?php endforeach; ?>
</tr>
<?php
// Query dinamis matrik keputusan
$sql = "SELECT a.id_alternative, b.name";
foreach($kriteria as $kid) {
    $sql .= ", SUM(IF(a.id_criteria=$kid,a.value,0)) AS C$kid";
}
$sql .= " FROM saw_evaluations a JOIN saw_alternatives b USING(id_alternative) GROUP BY a.id_alternative ORDER BY b.name ASC";
$result = $db->query($sql);
$X = array();
$alt_map = array();
while ($row = $result->fetch_object()) {
    $alt_map[$row->id_alternative] = $row;
    foreach($kriteria as $kid) {
        if (!isset($X[$kid])) $X[$kid] = array();
        $X[$kid][] = round($row->{'C'.$kid}, 2);
    }
}
$alt_no = 1;
foreach ($alt_names as $id => $name) {
    if (isset($alt_map[$id])) {
        $row = $alt_map[$id];
        echo "<tr class='center'>\n<th>A{$alt_no} ({$row->name})</th>";
        foreach($kriteria as $kid) {
            echo "<td>" . round($row->{'C'.$kid}, 2) . "</td>";
        }
        echo "<td><a href='keputusan-hapus.php?id={$row->id_alternative}' class='btn btn-danger btn-sm'>Hapus</a></td></tr>\n";
        $alt_no++;
    }
}
$result->free();
foreach($kriteria as $kid) {
    if (empty($X[$kid])) $X[$kid] = [1];
}
?>
</table>

<table class="table table-striped mb-0">
<caption>Matrik Ternormalisasi (R)</caption>
<tr>
    <th rowspan='2'>Alternatif</th>
    <th colspan='<?= $kriteria_count ?>'>Kriteria</th>
</tr>
<tr>
<?php foreach($kriteria as $kid): ?>
    <th>C<?= $kid ?></th>
<?php endforeach; ?>
</tr>
<?php
// Query dinamis matrik ternormalisasi
$sql = "SELECT a.id_alternative";
foreach($kriteria as $kid) {
    $max = max($X[$kid]);
    $min = min($X[$kid]);
    $attr = '';
    $sql_attr = $db->query("SELECT attribute FROM saw_criterias WHERE id_criteria='$kid'");
    if ($sql_attr && $row_attr = $sql_attr->fetch_assoc()) $attr = $row_attr['attribute'];
    $sql .= ", SUM(IF(a.id_criteria=$kid,IF('$attr'='benefit',a.value/$max,$min/a.value),0)) AS C$kid";
}
$sql .= " FROM saw_evaluations a JOIN saw_criterias b USING(id_criteria) GROUP BY a.id_alternative ORDER BY (SELECT name FROM saw_alternatives WHERE id_alternative=a.id_alternative) ASC";
$result = $db->query($sql);
$R = array();
$R_map = array();
while ($row = $result->fetch_object()) {
    $R[$row->id_alternative] = array();
    foreach($kriteria as $kid) {
        $R[$row->id_alternative][$kid] = $row->{'C'.$kid};
    }
    $R_map[$row->id_alternative] = $row;
}
$alt_no = 1;
foreach ($alt_names as $id => $name) {
    if (isset($R_map[$id])) {
        $row = $R_map[$id];
        echo "<tr class='center'>\n<th>A{$alt_no} ({$name})</th>";
        foreach($kriteria as $kid) {
            echo "<td>" . round($row->{'C'.$kid}, 2) . "</td>";
        }
        echo "</tr>\n";
        $alt_no++;
    }
}
?>
</table>
                </div>
                </div>
            </div>
            </div>
        </section>
        </div>
        <?php require "layout/footer.php";?>
    </div>
    </div>

    <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Isi Nilai Kandidat </h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    <form action="matrik-simpan.php" method="POST">
                        <div class="modal-body">
                            <label>Name: </label>
                            <div class="form-group">
                            <select class="form-control form-select" name="id_alternative">
                            <?php
$sql = 'SELECT id_alternative,name FROM saw_alternatives';
$result = $db->query($sql);
$i = 0;
while ($row = $result->fetch_object()) {
    echo '<option value="' . $row->id_alternative . '">' . $row->name . '</option>';
}
$result->free();
?>
                                        </select>
                            </div>
                        </div>
                        <div class="modal-body">
                            <label>Criteria: </label>
                            <div class="form-group">
                            <select class="form-control form-select" name="id_criteria">
                            <?php
$sql = 'SELECT * FROM saw_criterias';
$result = $db->query($sql);
$i = 0;
while ($row = $result->fetch_object()) {
    echo '<option value="' . $row->id_criteria . '">' . $row->criteria . '</option>';
}
$result->free();
?>
                                        </select>
                            </div>
                        </div>
                        <div class="modal-body">
                            <label>Value: </label>
                            <div class="form-group">
                                <input type="text" name="value" placeholder="value..." class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" name="submit" class="btn btn-primary ml-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Simpan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php require "layout/js.php";?>
  </body>

</html>