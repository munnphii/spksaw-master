<!DOCTYPE html>
<html lang="en">
  <?php
require "layout/head.php";
require "include/conn.php";
?>

  <body>
    <div id="app">
      <?php require "layout/navbar.php";?>
      <div id="main">image.png
        <header class="mb-3">
          <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
          </a>
        </header>
        <div class="page-heading">
          <h3>Bobot Kriteria</h3>
        </div>
        <div class="page-content">
          <section class="row">
            <div class="col-12">
              <div class="card">

                <div class="card-header">
                  <h4 class="card-title">Tabel Bobot Kriteria</h4>
                </div>
                <div class="card-content">
                  <div class="card-body">
                    <form action="bobot-tambah-act.php" method="POST" class="row g-3 mb-4">
                      <div class="col-md-4">
                        <input type="text" name="criteria" class="form-control" placeholder="Nama Kriteria" required>
                      </div>
                      <div class="col-md-2">
                        <input type="number" step="0.1" name="weight" class="form-control" placeholder="Bobot" required>
                      </div>
                      <div class="col-md-3">
                        <select name="attribute" class="form-control" required>
                          <option value="benefit">Benefit</option>
                          <option value="cost">Cost</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Tambah Kriteria</button>
                      </div>
                    </form>
                    <p class="card-text">Pengambil keputusan memberi bobot preferensi dari setiap kriteria dengan
                      masing-masing jenisnya (keuntungan/benefit atau biaya/cost):</p>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-striped mb-0">
                    <caption>
    Tabel Kriteria C<sub>i</sub>
  </caption>
  <tr>
    <th>No</th>
    <th>Simbol</th>
    <th>Kriteria</th>
    <th>Bobot</th>
    <th colspan="1">Atribut</th>
    <th>Aksi</th>
  </tr>
  <?php
$sql = 'SELECT id_criteria,criteria,weight,attribute FROM saw_criterias';
$result = $db->query($sql);
$i = 0;
while ($row = $result->fetch_object()) {
    echo "<tr>
        <td class='right'>" . (++$i) . "</td>
        <td class='center'>C{$i}</td>
        <td>{$row->criteria}</td>
        <td>" . number_format($row->weight, 1) . "</td>
        <td>{$row->attribute}</td>
        <td>
            <a href='bobot-edit.php?id={$row->id_criteria}' class='btn btn-info btn-sm'>Edit</a>
            <a href='bobot-hapus.php?id={$row->id_criteria}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus kriteria ini?')\">Hapus</a>
            </td>
      </tr>";
}
$result->free();
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