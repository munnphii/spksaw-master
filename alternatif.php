<!DOCTYPE html>
<html lang="en">
    <?php
require "layout/head.php";
require "include/conn.php";
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
                    <h3>Alternatif</h3>
                </div>
                <div class="page-content">
                    <section class="row">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-header">
                                    <h4 class="card-title">Tabel Alternatif</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <p class="card-text">
                                            Data-data mengenai kandidat yang akan dievaluasi di representasikan dalam
                                            tabel berikut:
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <a href="import/import-alternatif-form.php" class="btn btn-success">Import Alternatif (CSV)</a>
                                    </div>
                                    <button type="button" class="btn btn-outline-success btn-sm m-2" data-bs-toggle="modal"
                                        data-bs-target="#inlineForm">
                                        Tambah Alternatif
                                    </button>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <caption>
                                                Tabel Alternatif A<sub>i</sub>
                                            </caption>
                                            <tr>
                                                <th>No</th>
                                                <th>Foto</th>
                                                <th>Nama</th>
                                                <th>Biodata PDF</th>
                                                <th>Aksi</th>
                                            </tr>
                                            <?php
                                            $sql = 'SELECT id_alternative, name, profile_photo, biodata_pdf FROM saw_alternatives';
                                            $result = $db->query($sql);
                                            $i = 0;
                                            while ($row = $result->fetch_object()) {
                                                echo "<tr>
                                                    <td class='right'>" . (++$i) . "</td>";
                                                echo "<td class='center'>";
                                                if (!empty($row->profile_photo)) {
                                                    echo "<img src='uploads/" . htmlspecialchars($row->profile_photo) . "' alt='Foto' style='max-width:60px;max-height:60px;cursor:pointer;' class='img-thumbnail profile-photo-thumb' data-img='uploads/" . htmlspecialchars($row->profile_photo) . "'>";
                                                } else {
                                                    echo "-";
                                                }
                                                echo "</td>";
                                                echo "<td class='center'>{$row->name}</td>";
                                                echo "<td class='center'>";
                                                if (!empty($row->biodata_pdf)) {
                                                    echo "<a href='uploads/" . htmlspecialchars($row->biodata_pdf) . "' target='_blank'>Lihat PDF</a>";
                                                } else {
                                                    echo "-";
                                                }
                                                echo "</td>";
                                                echo "<td>\n<div class='btn-group mb-1'>\n<div class='dropdown'>\n<button class='btn btn-primary dropdown-toggle me-1 btn-sm' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Aksi</button>\n<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>\n<a class='dropdown-item' href='alternatif-edit.php?id={$row->id_alternative}'>Edit</a>\n<a class='dropdown-item' href='alternatif-hapus.php?id={$row->id_alternative}'>Hapus</a>\n</div>\n</div>\n</div>\n</td>\n</tr>\n";
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
        <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Login Form </h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    <form action="alternatif-simpan.php" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <label>Name: </label>
                            <div class="form-group">
                                <input type="text" name="name" placeholder="Nama Kandidat..." class="form-control" required>
                            </div>
                            <label>Foto Profile: </label>
                            <div class="form-group">
                                <input type="file" name="profile_photo" accept="image/*" class="form-control">
                            </div>
                            <label>PDF Biodata: </label>
                            <div class="form-group">
                                <input type="file" name="biodata_pdf" accept="application/pdf" class="form-control">
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
        <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="photoModalLabel">Foto Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body text-center">
        <img id="modalPhoto" src="" alt="Foto Profile" style="max-width:100%;max-height:70vh;">
    </div>
    </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var photoThumbs = document.querySelectorAll('.profile-photo-thumb');
    var modalPhoto = document.getElementById('modalPhoto');
    var photoModal = new bootstrap.Modal(document.getElementById('photoModal'));
    photoThumbs.forEach(function(img) {
    img.addEventListener('click', function() {
    modalPhoto.src = img.getAttribute('data-img');
    photoModal.show();
    });
    });
});
</script>
        <?php require "layout/js.php";?>
    </body>

</html>