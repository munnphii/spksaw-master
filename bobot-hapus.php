<?php
require "include/conn.php";
$id = $_GET['id'];

// Hapus kriteria berdasarkan id
$sql = "DELETE FROM saw_criterias WHERE id_criteria='$id'";
$db->query($sql);

header("location:./bobot.php"); 