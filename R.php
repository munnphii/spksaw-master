<?php
// Ambil semua kriteria
$criterias = array();
$sql = "SELECT id_criteria, attribute FROM saw_criterias ORDER BY id_criteria";
$result = $db->query($sql);
while ($row = $result->fetch_assoc()) {
    $criterias[$row['id_criteria']] = $row['attribute'];
}

// Ambil semua alternatif
$alternatives = array();
$sql = "SELECT id_alternative FROM saw_alternatives ORDER BY id_alternative";
$result_alt = $db->query($sql);
while ($row = $result_alt->fetch_assoc()) {
    $alternatives[] = $row['id_alternative'];
}

// Ambil nilai X (matriks keputusan)
$X = array();
foreach ($criterias as $cid => $attr) {
    $X[$cid] = array();
}
$sql = "SELECT id_alternative, id_criteria, value FROM saw_evaluations";
$result = $db->query($sql);
while ($row = $result->fetch_assoc()) {
    $X[$row['id_criteria']][] = $row['value'];
}
// Cegah error jika data kosong
foreach ($X as $cid => $arr) {
    if (empty($arr)) $X[$cid] = [1];
}

// Normalisasi matriks R
global $R;
$R = array();
foreach ($alternatives as $alt_id) {
    $R[$alt_id] = array();
    foreach ($criterias as $cid => $attr) {
        // Ambil value untuk alternatif dan kriteria ini
        $sql = "SELECT value FROM saw_evaluations WHERE id_alternative='$alt_id' AND id_criteria='$cid'";
        $res = $db->query($sql);
        $val = 0;
        if ($res && $d = $res->fetch_assoc()) {
            $val = $d['value'];
        }
        $max = max($X[$cid]);
        $min = min($X[$cid]);
        if ($attr == 'benefit') {
            $norm = ($max != 0) ? $val / $max : 0;
        } else {
            $norm = ($val != 0) ? $min / $val : 0;
        }
        $R[$alt_id][] = $norm;
    }
}
