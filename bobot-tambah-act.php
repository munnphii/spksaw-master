<?php
require "include/conn.php";
$criteria = $_POST['criteria'];
$weight = $_POST['weight'];
$attribute = $_POST['attribute'];

// Cari id_criteria baru (otomatis increment)
$sql = "SELECT MAX(id_criteria) as maxid FROM saw_criterias";
$res = $db->query($sql);
$row = $res->fetch_assoc();
$id_criteria = $row['maxid'] + 1;

// Hitung total bobot saat ini
$res = $db->query("SELECT SUM(weight) as total FROM saw_criterias");
$row = $res->fetch_assoc();
$total_bobot = floatval($row['total']);
if ($total_bobot + floatval($weight) > 10) {
    echo "<script>alert('Total bobot kriteria tidak boleh lebih dari 10!');window.location='bobot.php';</script>";
    exit;
}

$sql = "INSERT INTO saw_criterias (id_criteria, criteria, weight, attribute) VALUES ('$id_criteria', '$criteria', '$weight', '$attribute')";
$result = $db->query($sql);

header("location:./bobot.php"); 