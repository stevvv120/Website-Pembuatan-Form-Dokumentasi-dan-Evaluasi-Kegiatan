<?php
include 'db/db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_column = isset($_GET['filter_column']) ? $_GET['filter_column'] : 'nama';

$filter_options = [
    "nama" => "Nama",
    "NIP_NIM" => "NIP/NIM",
    "jabatan_angkatan" => "Jabatan/Angkatan",
    "jenis_kegiatan" => "Jenis Kegiatan",
    "nama_kegiatan" => "Nama Kegiatan",
    "tanggal" => "Tanggal"
];

$sql = "SELECT e.id, e.nama, e.NIP_NIM, e.jabatan_angkatan, j.nama_jenis AS jenis_kegiatan, 
               e.nama_kegiatan, e.tanggal, e.tempat
        FROM evaluasi e
        LEFT JOIN jenis_kegiatan_evaluasi j ON e.jenis_kegiatan_id = j.id";

if (!empty($search)) {
    $sql .= " WHERE $filter_column LIKE '%$search%'";
}

$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Data Evaluasi</title>
    <link rel="stylesheet" type="text/css" href="css/data_evaluasi.css?">
    </head>
<body>
<h2>Admin Panel - Data Evaluasi</h2>

<form method="GET" action="">
    <label for="filter_column">Cari berdasarkan:</label>
    <select name="filter_column" id="filter_column">
        <?php foreach ($filter_options as $key => $label): ?>
            <option value="<?= $key ?>" <?= $filter_column == $key ? 'selected' : '' ?>><?= $label ?></option>
        <?php endforeach; ?>
    </select>

    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Masukkan kata kunci">
    
    <button type="submit">Cari</button>
    <button type="button" class="btn-Clear" onclick="window.location.href='data_evaluasi.php'">Clear</button>
</form>

<a href="evaluasi.php" class="btn btn-add">+ Tambah Evaluasi</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>NIP/NIM</th>
            <th>Jabatan/Angkatan</th>
            <th>Jenis Kegiatan</th>
            <th>Nama Kegiatan</th>
            <th>Tanggal</th>
            <th>Tempat Pelaksanaan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['nama']}</td>";
                echo "<td>{$row['NIP_NIM']}</td>";
                echo "<td>{$row['jabatan_angkatan']}</td>";
                echo "<td>{$row['jenis_kegiatan']}</td>";
                echo "<td>{$row['nama_kegiatan']}</td>";
                echo "<td>{$row['tanggal']}</td>";
                echo "<td>{$row['tempat']}</td>";
                echo "<td>
                        <a href='detail_evaluasi.php?id={$row['id']}' class='btn btn-detail'>Detail</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Tidak ada data</td></tr>";
        }
        ?>
    </tbody>
</table>


<div class="center">
    <a href="rekapevaluasi.php" class="btn btn-rekap">Lihat Rekap Evaluasi</a>

    <a href="excelevaluasi.php" class="btn btn-download">Download Excel </a>

    <a href="admin.php" class="btn btn-admin">Kembali ke Admin</a>
</div>

</body>
</html>

<?php $conn->close(); ?>
