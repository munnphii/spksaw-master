<?php
include '../include/conn.php';

if (isset($_FILES['file_csv']['tmp_name'])) {
    $file = $_FILES['file_csv']['tmp_name'];
    $handle = fopen($file, 'r');
    $row = 0;
    $imported = 0;
    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $row++;
        if ($row == 1) continue; // Lewati header jika ada
        $nama = isset($data[0]) ? addslashes($data[0]) : '';
        $profile_photo = isset($data[1]) ? addslashes($data[1]) : null;
        $biodata_pdf = isset($data[2]) ? addslashes($data[2]) : null;
        if ($nama != '') {
            // Cek nama sudah ada atau belum
            $cek = mysqli_query($db, "SELECT 1 FROM saw_alternatives WHERE name='$nama' LIMIT 1");
            if ($cek && mysqli_num_rows($cek) > 0) {
                // Nama sudah ada, skip insert
                continue;
            }
            $sql = "INSERT INTO saw_alternatives (name, profile_photo, biodata_pdf) VALUES ('$nama', " . ($profile_photo ? "'$profile_photo'" : 'NULL') . ", " . ($biodata_pdf ? "'$biodata_pdf'" : 'NULL') . ")";
            mysqli_query($db, $sql);
            $imported++;
        }
    }
    fclose($handle);
    echo "<script>alert('Import selesai. $imported data berhasil diimport.');window.location='../alternatif.php';</script>";
} else {
    echo "<script>alert('File tidak ditemukan!');window.location='import-alternatif-form.php';</script>";
} 