<?php
require "include/conn.php";
$id = $_POST['id_alternative'];
$name = $_POST['name'];

// Ambil data lama
$old = $db->query("SELECT profile_photo, biodata_pdf FROM saw_alternatives WHERE id_alternative='$id'")->fetch_assoc();
$profile_photo = $old['profile_photo'];
$biodata_pdf = $old['biodata_pdf'];

// Handle upload foto profile
if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
    $ext = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
    $filename = 'foto_' . time() . '_' . rand(1000,9999) . '.' . $ext;
    move_uploaded_file($_FILES['profile_photo']['tmp_name'], 'uploads/' . $filename);
    $profile_photo = $filename;
}
// Handle upload PDF biodata
if (isset($_FILES['biodata_pdf']) && $_FILES['biodata_pdf']['error'] == 0) {
    $ext = pathinfo($_FILES['biodata_pdf']['name'], PATHINFO_EXTENSION);
    $filename = 'biodata_' . time() . '_' . rand(1000,9999) . '.' . $ext;
    move_uploaded_file($_FILES['biodata_pdf']['tmp_name'], 'uploads/' . $filename);
    $biodata_pdf = $filename;
}
$cek = $db->query("SELECT 1 FROM saw_alternatives WHERE name='$name' AND id_alternative!='$id' LIMIT 1");
if ($cek && $cek->num_rows > 0) {
    echo "<script>alert('Nama alternatif sudah ada, tidak boleh ganda!');window.location='alternatif.php';</script>";
    exit;
}
$sql = "UPDATE saw_alternatives SET name='$name', profile_photo=" . ($profile_photo ? "'$profile_photo'" : 'NULL') . ", biodata_pdf=" . ($biodata_pdf ? "'$biodata_pdf'" : 'NULL') . " WHERE id_alternative='$id'";
$result = $db->query($sql);
header("location:./alternatif.php");
