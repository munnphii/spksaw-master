<?php
// Form upload file CSV untuk import data alternatif
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Import Data Alternatif</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
</head>
<body>
<div class="container mt-5">
    <h3>Import Data Alternatif (CSV)</h3>
    <form action="import-alternatif-proses.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="file_csv" class="form-label">Pilih File CSV</label>
            <input type="file" name="file_csv" id="file_csv" class="form-control" accept=".csv" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload & Import</button>
        <a href="../alternatif.php" class="btn btn-secondary">Kembali</a>
    </form>
    <div class="mt-3">
        <small>Format CSV: nama_alternatif,profile_photo,biodata_pdf<br>
        <b>Catatan:</b> profile_photo dan biodata_pdf adalah nama file yang harus diupload ke folder uploads/ secara manual setelah import, atau bisa dikosongkan jika tidak ada.</small>
    </div>
</div>
</body>
</html> 