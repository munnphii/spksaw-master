<?php
require "include/conn.php";

$id_alternative = $_POST['id_alternative'];
$id_criteria = $_POST['id_criteria'];
$value = $_POST['value'];

$sql_cek = "SELECT 1 FROM saw_evaluations WHERE id_alternative='$id_alternative' AND id_criteria='$id_criteria' LIMIT 1";
$cek = $db->query($sql_cek);
if ($cek && $cek->num_rows > 0) {
    echo "<script>alert('Data untuk alternatif dan kriteria ini sudah ada, tidak bisa disimpan ganda!');window.location='matrik.php';</script>";
    exit;
}
$sql = "INSERT INTO saw_evaluations values ('$id_alternative','$id_criteria','$value')";
$result = $db->query($sql);

if ($result === true) {
    header("location:./matrik.php");
} else {
    echo "<script>alert('Terjadi kesalahan saat menyimpan data!');window.location='matrik.php';</script>";
}
