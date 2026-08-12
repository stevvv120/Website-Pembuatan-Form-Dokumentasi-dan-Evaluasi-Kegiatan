<?php
session_start();
include 'db/db.php';

$nama = isset($_POST['komentar_19']) ? $conn->real_escape_string($_POST['komentar_19']) : NULL;
$nim = isset($_POST['komentar_20']) ? $conn->real_escape_string($_POST['komentar_20']) : NULL;
$angkatan = isset($_POST['angkatan']) ? $conn->real_escape_string($_POST['angkatan']) : NULL;

$fields = [];

for ($i = 0; $i <= 71; $i++) {
    $key = "setuju_$i";
    $fields[$key] = isset($_POST[$key]) ? $conn->real_escape_string($_POST[$key]) : NULL;
}

for ($i = 1; $i <= 18; $i++) {
    $key = "komentar_$i";
    $fields[$key] = isset($_POST[$key]) ? $conn->real_escape_string($_POST[$key]) : NULL;
}

// Data tambahan lainnya
$fields['usaha_puas_1'] = isset($_POST['usaha_puas_1']) ? $conn->real_escape_string($_POST['usaha_puas_1']) : NULL;
$fields['hasil_puas_1'] = isset($_POST['hasil_puas_1']) ? $conn->real_escape_string($_POST['hasil_puas_1']) : NULL;
$fields['efektivitas_1'] = isset($_POST['efektivitas_1']) ? $conn->real_escape_string($_POST['efektivitas_1']) : NULL;
$fields['efektivitas_2'] = isset($_POST['efektivitas_2']) ? $conn->real_escape_string($_POST['efektivitas_2']) : NULL;
$fields['efektivitas_3'] = isset($_POST['efektivitas_3']) ? $conn->real_escape_string($_POST['efektivitas_3']) : NULL;
$fields['mentor'] = isset($_POST['mentor']) ? $conn->real_escape_string($_POST['mentor']) : NULL;
$fields['MBKM_1'] = isset($_POST['MBKM_1']) ? $conn->real_escape_string($_POST['MBKM_1']) : NULL;
$fields['MBKM_2'] = isset($_POST['MBKM_2']) ? $conn->real_escape_string($_POST['MBKM_2']) : NULL;
$fields['PPP'] = isset($_POST['PPP']) ? $conn->real_escape_string($_POST['PPP']) : NULL;
$fields['TA'] = isset($_POST['TA']) ? $conn->real_escape_string($_POST['TA']) : NULL;

$fields['angkatan'] = $angkatan;
$fields['nama'] = $nama;
$fields['nim'] = $nim;

$columns = implode(", ", array_keys($fields));
$values = implode(", ", array_map(function ($value) {
    return isset($value) ? "'$value'" : "NULL";
}, array_values($fields)));

$sql = "INSERT INTO survey_responses ($columns) VALUES ($values)";

if ($conn->query($sql) === TRUE) {
    echo "<script> window.location.href='index.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
