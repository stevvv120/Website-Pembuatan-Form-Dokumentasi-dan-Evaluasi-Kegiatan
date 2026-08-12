<?php
include 'db/db.php';
include 'survey_mappings.php'; 

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = $conn->real_escape_string($_GET['id']);

$sql = "SELECT * FROM survey_responses WHERE id = '$id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Data tidak ditemukan.";
    exit;
}

$data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Data Survey</title>
    <link rel="stylesheet" type="text/css" href="css/detail.css">
</head>
<body>

<h2>Detail Responden ID: <?php echo $id; ?></h2>

<table>
    <tr><th>Pertanyaan</th><th>Jawaban</th></tr>
    <tr><td>Nama</td><td><?php echo $data['nama']; ?></td></tr>
    <tr><td>NIM</td><td><?php echo $data['nim']; ?></td></tr>
    <tr><td>Angkatan</td><td><?php echo $data['angkatan']; ?></td></tr>

    <?php
    foreach ($pertanyaan_setuju as $index => $pertanyaan) {
        $jawaban = isset($data['setuju_'.$index]) ? ucwords(str_replace("_", " ", $data['setuju_'.$index])) : "-";
        echo "<tr><td>$pertanyaan</td><td>$jawaban</td></tr>";
    }

    foreach ($pertanyaan_komentar as $index => $pertanyaan) {
        $komentar = $data['komentar_'.$index] ?? "-";
        echo "<tr><td>$pertanyaan</td><td>$komentar</td></tr>";
    }

    foreach ($tambahan as $field) {
        $label = $tambahan_deskripsi[$field] ?? ucwords(str_replace("_", " ", $field));
        $value = isset($data[$field]) ? ucwords(str_replace("_", " ", $data[$field])) : "-";
        echo "<tr><td>$label</td><td>$value</td></tr>";
    }
    ?>

</table>

<div class="center">
    <a href="export_excel.php?single_id=<?php echo $id; ?>" class="btn-export">Download Excel</a>
    <a href="data_survey.php" class="danger">Kembali</a>
</div>

</body>
</html>

<?php $conn->close(); ?>