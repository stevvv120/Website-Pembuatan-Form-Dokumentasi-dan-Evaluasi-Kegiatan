<?php
include 'db/db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_column = isset($_GET['filter_column']) ? $_GET['filter_column'] : 'nama';

$filter_options = [
    "nama" => "Nama",
    "nip_nim" => "NIP/NIM",
    "unit" => "Unit/Fakultas",
    "jenis_kegiatan" => "Jenis Kegiatan",
    "tanggal" => "Tanggal",
    "topik" => "Topik"
];

$sql = "SELECT d.id, d.nama, d.nip_nim, u.nama_unit, d.jabatan_angkatan, 
               j.nama_jenis, p.nama_pelaksana, pk.nama_peran, d.topik, d.tanggal, 
               t.nama_tempat
        FROM dokumentasi d
        LEFT JOIN unit u ON d.unit_id = u.id
        LEFT JOIN jenis_kegiatan j ON d.jenis_kegiatan_id = j.id
        LEFT JOIN pelaksana_kegiatan p ON d.pelaksana_kegiatan_id = p.id
        LEFT JOIN peran_kegiatan pk ON d.peran_kegiatan_id = pk.id
        LEFT JOIN tempat_pelaksanaan t ON d.tempat_id = t.id";

if (!empty($search)) {
    $sql .= " WHERE $filter_column LIKE '%$search%'";
}

$sql .= " ORDER BY d.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Data Dokumentasi</title>
    <link rel="stylesheet" type="text/css" href="css/data_dokumentasi.css">
</head>
<body>
<h2>Admin Panel - Data Dokumentasi</h2>

<form method="GET" action="">
    <label for="filter_column">Cari berdasarkan:</label>
    <select name="filter_column" id="filter_column">
        <?php foreach ($filter_options as $key => $label): ?>
            <option value="<?= $key ?>" <?= $filter_column == $key ? 'selected' : '' ?>><?= $label ?></option>
        <?php endforeach; ?>
    </select>

    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Masukkan kata kunci">
    <button type="submit">Cari</button>
    <a href="data_dokumentasi.php" class="btn-clear">Clear</a>
</form>

<a href="dokumentasi.php" class="btn btn-add">+ Tambah Dokumentasi</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>NIP/NIM</th>
            <th>Unit/Fakultas</th>
            <th>Jabatan/Angkatan</th>
            <th>Jenis Kegiatan</th>
            <th>Pelaksana Kegiatan</th>
            <th>Peran Kegiatan</th>
            <th>Topik</th>
            <th>Tanggal</th>
            <th>Tempat</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nip_nim']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_unit']) . "</td>";
            echo "<td>" . htmlspecialchars($row['jabatan_angkatan']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_jenis']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_pelaksana']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_peran']) . "</td>";
            echo "<td>" . htmlspecialchars($row['topik']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_tempat']) . "</td>";
            echo "<td><a href='detail_dokumentasi.php?id={$row['id']}' class='btn btn-detail'>Detail</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='12'>Tidak ada data</td></tr>";
    }
    ?>
    </tbody>
</table>



<div class="center">
    <a href="rekapdokumentasi.php" class="btn btn-rekap">Lihat Rekap Dokumentasi</a>
    <a href="exceldokumentasi.php" class="btn btn-download">Download Excel</a>
    <a href="admin.php" class="btn btn-admin">Kembali ke Admin</a>
</div>

</body>
</html>

<?php $conn->close(); ?>