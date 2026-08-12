<?php
include 'db/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='data_dokumentasi.php';</script>";
    exit();
}

$id = intval($_GET['id']);

$sql = "SELECT d.*, 
               u.nama_unit, 
               j.nama_jenis, 
               p.nama_pelaksana, 
               pk.nama_peran, 
               t.nama_tempat 
        FROM dokumentasi d
        LEFT JOIN unit u ON d.unit_id = u.id
        LEFT JOIN jenis_kegiatan j ON d.jenis_kegiatan_id = j.id
        LEFT JOIN pelaksana_kegiatan p ON d.pelaksana_kegiatan_id = p.id
        LEFT JOIN peran_kegiatan pk ON d.peran_kegiatan_id = pk.id
        LEFT JOIN tempat_pelaksanaan t ON d.tempat_id = t.id
        WHERE d.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$dokumentasi = $result->fetch_assoc();

if (!$dokumentasi) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='data_dokumentasi.php';</script>";
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Dokumentasi</title>
    <link rel="stylesheet" type="text/css" href="css/detail_dokumentasi.css">
</head>
<body>

<div class="container">
    <h2>Detail Dokumentasi</h2>

    <table>
        <tr><th>ID</th><td><?= $dokumentasi['id'] ?></td></tr>
        <tr><th>Nama</th><td><?= htmlspecialchars($dokumentasi['nama']) ?></td></tr>
        <tr><th>NIP/NIM</th><td><?= htmlspecialchars($dokumentasi['nip_nim']) ?></td></tr>
        <tr><th>Unit</th><td><?= htmlspecialchars($dokumentasi['nama_unit']) ?></td></tr>
        <tr><th>Jabatan/Angkatan</th><td><?= htmlspecialchars($dokumentasi['jabatan_angkatan']) ?></td></tr>
        <tr><th>Jenis Kegiatan</th><td><?= htmlspecialchars($dokumentasi['nama_jenis']) ?></td></tr>
        <tr><th>Pelaksana Kegiatan</th><td><?= htmlspecialchars($dokumentasi['nama_pelaksana']) ?></td></tr>
        <tr><th>Peran Kegiatan</th><td><?= htmlspecialchars($dokumentasi['nama_peran']) ?></td></tr>
        <tr><th>Topik Kegiatan</th><td><?= htmlspecialchars($dokumentasi['topik']) ?></td></tr>
        <tr><th>Tanggal</th><td><?= htmlspecialchars($dokumentasi['tanggal']) ?></td></tr>
        <tr><th>Waktu</th><td><?= htmlspecialchars($dokumentasi['waktu']) ?></td></tr>
        <tr><th>Tempat</th><td><?= htmlspecialchars($dokumentasi['nama_tempat']) ?></td></tr>
        <tr><th>Agenda</th><td><?= nl2br(htmlspecialchars($dokumentasi['agenda'])) ?></td></tr>
        <tr><th>Kesimpulan</th><td><?= nl2br(htmlspecialchars($dokumentasi['kesimpulan'])) ?></td></tr>
        <tr><th>Rencana Tindak Lanjut</th><td><?= nl2br(htmlspecialchars($dokumentasi['rencana_tindak_lanjut'])) ?></td></tr>

        <tr>
            <th>Dokumentasi Kegiatan</th>
            <td>
                <?php if (!empty($dokumentasi['dokumentasi_path']) && file_exists($dokumentasi['dokumentasi_path'])): ?>
                    <img src="<?= $dokumentasi['dokumentasi_path'] ?>" alt="Dokumentasi" class="dokumentasi-img">
                <?php else: ?>
                    <p class="no-image">Tidak ada dokumentasi</p>
                <?php endif; ?>
            </td>
        </tr>

        <tr>
            <th>Notulensi Kegiatan</th>
            <td>
                <?php if (!empty($dokumentasi['notulensi_path']) && file_exists($dokumentasi['notulensi_path'])): ?>
                    <a href="<?= $dokumentasi['notulensi_path'] ?>" target="_blank" class="btn-download">Unduh Notulensi</a>
                <?php else: ?>
                    <p class="no-file">Tidak ada notulensi</p>
                <?php endif; ?>
            </td>
        </tr>

        <tr>
            <th>Kesesuaian agenda kegiatan dengan pembahasan kegiatan</th>
            <td><?= htmlspecialchars($dokumentasi['pertanyaan0']) ?></td>
        </tr>
        <tr>
            <th>Kejelasan informasi dalam kegiatan</th>
            <td><?= htmlspecialchars($dokumentasi['pertanyaan1']) ?></td>
        </tr>
        <tr><th>Saran</th><td><?= nl2br(htmlspecialchars($dokumentasi['saran'])) ?></td></tr>
        <tr><th>Created At</th><td><?= htmlspecialchars($dokumentasi['created_at']) ?></td></tr>
    </table>

    <a href="exceldokumentasi.php?id=<?= $id ?>" class="btn btn-download">Download Excel</a>
    <a href="data_dokumentasi.php" class="btn btn-back">Kembali</a>
</div>

</body>
</html>

<?php $conn->close(); ?>
