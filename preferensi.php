<!DOCTYPE html>
<html lang="en">
  <?php
require "layout/head.php";
require "include/conn.php";
require "W.php";
require "R.php";
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
          <h3>Nilai Preferensi (P)</h3>
        </div>
        <div class="page-content">
          <section class="row">
            <div class="col-12">
              <div class="card">

                <div class="card-header">
                  <h4 class="card-title">Tabel Nilai Preferensi (P)</h4>
                </div>
                <div class="card-content">
                  <div class="card-body">
                    <p class="card-text">
                    Nilai preferensi (P) merupakan penjumlahan dari perkalian matriks ternormalisasi R dengan vektor bobot W.</p>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-striped mb-0">
                    <caption>
    Nilai Preferensi (P)
  </caption>
  <tr>
    <th>No</th>
    <th>Alternatif</th>
    <th>Hasil</th>
    <th>Ranking</th>
  </tr>
  <?php

if (empty($R)) {
    echo '<tr><td colspan="4" style="color:red;text-align:center;">Data penilaian kosong.</td></tr>';
    return;
}

$P = array();
$m = count($W);
$no = 0;
foreach ($R as $i => $r) {
    for ($j = 0; $j < $m; $j++) {
        $rj = isset($r[$j]) ? $r[$j] : 0; // Hindari undefined index
        $wj = isset($W[$j]) ? $W[$j] : 0;
        $P[$i] = (isset($P[$i]) ? $P[$i] : 0) + $rj * $wj;
    }
}
// Ambil urutan nama dari tabel alternatif (berdasarkan id_alternative ASC = urutan input)
$alt_names = array();
$sql_alt = "SELECT id_alternative, name FROM saw_alternatives ORDER BY id_alternative ASC";
$result_alt = $db->query($sql_alt);
while ($row_alt = $result_alt->fetch_object()) {
    $alt_names[$row_alt->id_alternative] = $row_alt->name;
}
// Urutkan $R dan $P sesuai urutan nama
$R_sorted = array();
$P_sorted = array();
foreach ($alt_names as $id => $name) {
    if (isset($R[$id])) {
        $R_sorted[$id] = $R[$id];
        $P_sorted[$id] = isset($P[$id]) ? $P[$id] : 0;
    }
}
// Buat mapping id_alternative ke label A1, A2, dst sesuai urutan input, TAPI hanya untuk alternatif yang tampil
$alt_label_map = array();
$label_no = 1;
$alt_tampil = array();
foreach ($alt_names as $id => $name) {
    // Cek apakah alternatif sudah diisi nilai untuk semua kriteria
    $is_complete = true;
    if (isset($R[$id])) {
        foreach ($R[$id] as $val) {
            if ($val == 0) { $is_complete = false; break; }
        }
    } else {
        $is_complete = false;
    }
    if ($is_complete) {
        $alt_label_map[$id] = 'A' . $label_no;
        $alt_tampil[] = $id;
        $label_no++;
    }
}
// Urutkan nilai preferensi dari terbesar ke terkecil
$ranking = $P_sorted;
arsort($ranking);
$rank_map = array();
$rank = 1;
foreach ($ranking as $key => $val) {
    $rank_map[$key] = $rank++;
}
// Tampilkan tabel urut ranking, label A1, A2 sesuai urutan input, nama sebagai keterangan tambahan
$no = 0;
foreach ($ranking as $i => $nilai) {
    if (!in_array($i, $alt_tampil)) continue;
    $nama = isset($alt_names[$i]) ? $alt_names[$i] : '';
    $alabel = isset($alt_label_map[$i]) ? $alt_label_map[$i] : '';
    $row_class = ($rank_map[$i] == 1) ? 'style="background:#eaf6ff; font-weight:600;"' : '';
    $badge = ($rank_map[$i] == 1) ? ' <span style="background:#6a82fb;color:#fff;border-radius:6px;padding:2px 10px;font-size:0.9em;margin-left:8px;">Terbaik</span>' : '';
    echo "<tr class='center' $row_class>
            <td>" . (++$no) . "</td>
            <td>{$alabel} ({$nama})</td>
            <td>{$P_sorted[$i]}</td>
            <td>{$rank_map[$i]}$badge</td>
          </tr>";
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
    <?php require "layout/js.php";?>
  </body>

</html>
