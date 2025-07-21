<?php
require "include/conn.php";
$id = $_GET['id'];
mysqli_query($db, "DELETE FROM saw_evaluations WHERE id_alternative='$id'");
mysqli_query($db, "DELETE FROM saw_alternatives WHERE id_alternative='$id'");
header("location:./alternatif.php");
