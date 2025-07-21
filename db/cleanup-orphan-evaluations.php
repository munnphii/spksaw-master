<?php
require '../include/conn.php';
$sql = "DELETE FROM saw_evaluations WHERE id_alternative NOT IN (SELECT id_alternative FROM saw_alternatives)";
if ($db->query($sql) === TRUE) {
    echo "Pembersihan data orphan berhasil!";
} else {
    echo "Gagal membersihkan data orphan: " . $db->error;
} 