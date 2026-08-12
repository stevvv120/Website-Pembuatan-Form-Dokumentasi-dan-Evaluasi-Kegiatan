<?php 
include 'db/db.php';

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
$filename = $id > 0 ? "data_dokumentasi_id_{$id}.xls" : "data_dokumentasi.xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1' cellspacing='0' cellpadding='5'>";

$headers = [
    'ID', 'Nama', 'NIP/NIM', 'Unit/Fakultas', 'Jabatan/Angkatan',
    'Jenis Kegiatan', 'Pelaksana Kegiatan', 'Peran Kegiatan',
    'Topik', 'Tanggal', 'Waktu', 'Tempat',
    'Agenda', 'Kesimpulan', 'Rencana Tindak Lanjut',
    'Dokumentasi', 'Notulensi',
    'Kesesuaian agenda kegiatan dengan pembahasan kegiatan',
    'Kejelasan informasi dalam kegiatan',
    'Saran', 'Created At'
];

echo "<tr>";
foreach ($headers as $header) {
    echo "<th style='background-color: #ccc; font-weight: bold;'>$header</th>";
}
echo "</tr>";

$id_filter = $id > 0 ? "WHERE d.id = $id" : "";

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
        $id_filter
        ORDER BY d.id DESC";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
    echo "<td>" . htmlspecialchars($row['nip_nim']) . "</td>";
    echo "<td>" . htmlspecialchars($row['nama_unit'] ?? '-') . "</td>";
    echo "<td>" . htmlspecialchars($row['jabatan_angkatan']) . "</td>";
    echo "<td>" . htmlspecialchars($row['nama_jenis'] ?? '-') . "</td>";
    echo "<td>" . htmlspecialchars($row['nama_pelaksana'] ?? '-') . "</td>";
    echo "<td>" . htmlspecialchars($row['nama_peran'] ?? '-') . "</td>";
    echo "<td>" . htmlspecialchars($row['topik']) . "</td>";
    echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
    echo "<td>" . htmlspecialchars($row['waktu']) . "</td>";
    echo "<td>" . htmlspecialchars($row['nama_tempat'] ?? '-') . "</td>";
    echo "<td>" . nl2br(htmlspecialchars($row['agenda'])) . "</td>";
    echo "<td>" . nl2br(htmlspecialchars($row['kesimpulan'])) . "</td>";
    echo "<td>" . nl2br(htmlspecialchars($row['rencana_tindak_lanjut'])) . "</td>";
    echo "<td>" . htmlspecialchars($row['dokumentasi_path']) . "</td>";
    echo "<td>" . htmlspecialchars($row['notulensi_path']) . "</td>";

    // Ganti label pertanyaan0 dan pertanyaan1
    echo "<td>" . ucwords(str_replace('_', ' ', htmlspecialchars($row['pertanyaan0']))) . "</td>";
    echo "<td>" . ucwords(str_replace('_', ' ', htmlspecialchars($row['pertanyaan1']))) . "</td>";

    echo "<td>" . nl2br(htmlspecialchars($row['saran'])) . "</td>";
    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
    echo "</tr>";
}

echo "</table>";
exit;
?>
