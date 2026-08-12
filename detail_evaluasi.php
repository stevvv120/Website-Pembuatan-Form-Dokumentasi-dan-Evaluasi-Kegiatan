<?php
include 'db/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='data_evaluasi.php';</script>";
    exit();
}

$id = intval($_GET['id']);

$sql = "SELECT e.*, j.nama_jenis 
        FROM evaluasi e
        LEFT JOIN jenis_kegiatan_evaluasi j ON e.jenis_kegiatan_id = j.id
        WHERE e.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$evaluasi = $result->fetch_assoc();

if (!$evaluasi) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='data_evaluasi.php';</script>";
    exit();
}

$daftarPertanyaanEvaluasi = [
    "Kesesuaian jadwal pelaksanaan kegiatan dengan yang telah ditentukan",
    "Kejelasan informasi yang diberikan sebelum dan selama kegiatan berlangsung",
    "Koordinasi antara panitia/pelaksana dan peserta selama kegiatan",
    "Kompetensi dan pengalaman narasumber dalam menyampaikan materi",
    "Penyajian materi yang menarik dan mudah dipahami",
    "Kesempatan yang diberikan oleh narasumber untuk diskusi dan tanya jawab",
    "Kemampuan narasumber dalam menjawab pertanyaan dan memberikan jawaban yang jelas",
    "Kenyamanan dan kebersihan tempat/ruangan yang digunakan dalam kegiatan",
    "Kelengkapan dan sarana yang memadai untuk menunjang kegiatan (PC, LCD proyektor, dll)",
    "Tata letak ruangan (pengaturan tempat duduk, jarak pandang, ventilasi)",
    "Ketersediaan dan kualitas konsumsi (makanan/minuman) yang disediakan selama kegiatan",
    "Kejelasan informasi dan sosialisasi yang diberikan oleh panitia/pelaksana sebelum acara",
    "Keramahan dan responsivitas panitia dalam membantu peserta",
    "Kemampuan panitia/pelaksana dalam mengatasi kendala atau permasalahan teknis selama kegiatan",
    "Koordinasi dan manajemen acara yang dilakukan oleh panitia secara keseluruhan"
];

$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Evaluasi</title>
    <link rel="stylesheet" type="text/css" href="css/detail_evaluasi.css">
</head>
<body>

<div class="container">
    <h2>Detail Evaluasi</h2>

    <table>
        <tr>
            <th>ID</th>
            <td><?= $evaluasi['id'] ?></td>
        </tr>
        <tr>
            <th>Nama</th>
            <td><?= htmlspecialchars($evaluasi['nama']) ?></td>
        </tr>
        <tr>
            <th>NIP/NIM</th>
            <td><?= htmlspecialchars($evaluasi['NIP_NIM']) ?></td>
        </tr>
        <tr>
            <th>Jabatan/Angkatan</th>
            <td><?= htmlspecialchars($evaluasi['jabatan_angkatan']) ?></td>
        </tr>
        <tr>
            <th>Jenis Kegiatan</th>
            <td><?= htmlspecialchars($evaluasi['nama_jenis']) ?></td>
        </tr>
        <tr>
            <th>Nama Kegiatan</th>
            <td><?= htmlspecialchars($evaluasi['nama_kegiatan']) ?></td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td><?= htmlspecialchars($evaluasi['tanggal']) ?></td>
        </tr>
        <tr>
            <th>Waktu</th>
            <td><?= htmlspecialchars($evaluasi['waktu']) ?></td>
        </tr>
        <tr>
            <th>Tempat</th>
            <td><?= htmlspecialchars($evaluasi['tempat']) ?></td>
        </tr>

        <tr><th colspan="2">Jawaban Evaluasi</th></tr>
        <?php for ($i = 0; $i < count($daftarPertanyaanEvaluasi); $i++): ?>
            <tr>
                <th><?= htmlspecialchars($daftarPertanyaanEvaluasi[$i]) ?></th>
                <td><?= ucwords(str_replace('_', ' ', htmlspecialchars($evaluasi["pertanyaan$i"]))) ?></td>
            </tr>
        <?php endfor; ?>

        <tr>
            <th>Aspek Terbaik</th>
            <td><?= nl2br(htmlspecialchars($evaluasi['aspekTerbaik'])) ?></td>
        </tr>
        <tr>
            <th>Perbaikan</th>
            <td><?= nl2br(htmlspecialchars($evaluasi['perbaikan'])) ?></td>
        </tr>
        <tr>
            <th>Saran</th>
            <td><?= nl2br(htmlspecialchars($evaluasi['saran'])) ?></td>
        </tr>
    </table>



    <a href="excelevaluasi.php?id=<?= $id ?>" class="btn btn-download">Download Excel</a>

    <a href="data_evaluasi.php" class="btn btn-back">Kembali</a>
</div>

</body>
</html>

<?php $conn->close(); ?>
