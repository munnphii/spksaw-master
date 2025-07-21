<?php
require "include/conn.php";
$name = $_POST['name'];
$profile_photo = null;
$biodata_pdf = null;

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
$cek = $db->query("SELECT 1 FROM saw_alternatives WHERE name='$name' LIMIT 1");
if ($cek && $cek->num_rows > 0) {
    echo "<script>alert('Nama alternatif sudah ada, tidak boleh ganda!');window.location='alternatif.php';</script>";
    exit;
}
$sql = "INSERT INTO saw_alternatives (name, profile_photo, biodata_pdf) VALUES ('$name', " . ($profile_photo ? "'$profile_photo'" : 'NULL') . ", " . ($biodata_pdf ? "'$biodata_pdf'" : 'NULL') . ")";

if ($db->query($sql) === true) {
    header("location:./alternatif.php");
} else {
    echo "Error: " . $sql . "<br>" . $db->error;
}

