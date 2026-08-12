<?php 
include 'db/db.php';

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
$filename = $id > 0 ? "data_evaluasi_id_{$id}.xls" : "data_evaluasi.xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

// Pertanyaan evaluasi
$daftarPertanyaan = [
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

echo "<table border='1' cellspacing='0' cellpadding='5'>";

// Header tabel
$headers = [
    'ID', 'Nama', 'NIP/NIM', 'Jabatan/Angkatan', 'Jenis Kegiatan', 'Nama Kegiatan',
    'Tanggal', 'Waktu', 'Tempat'
];

$headers = array_merge($headers, $daftarPertanyaan, ['Aspek Terbaik', 'Perbaikan', 'Saran', 'Created At']);

echo "<tr>";
foreach ($headers as $header) {
    echo "<th style='background-color: #ccc; font-weight: bold;'>$header</th>";
}
echo "</tr>";

// Filter berdasarkan ID jika ada
$id_filter = $id > 0 ? "WHERE e.id = $id" : "";

$sql = "SELECT 
    e.id, e.nama, e.NIP_NIM, e.jabatan_angkatan, j.nama_jenis AS jenis_kegiatan, 
    e.nama_kegiatan, e.tanggal, e.waktu, e.tempat,
    e.pertanyaan0, e.pertanyaan1, e.pertanyaan2, e.pertanyaan3, e.pertanyaan4,
    e.pertanyaan5, e.pertanyaan6, e.pertanyaan7, e.pertanyaan8, e.pertanyaan9,
    e.pertanyaan10, e.pertanyaan11, e.pertanyaan12, e.pertanyaan13, e.pertanyaan14,
    e.aspekTerbaik, e.perbaikan, e.saran, e.created_at
FROM evaluasi e
LEFT JOIN jenis_kegiatan_evaluasi j ON e.jenis_kegiatan_id = j.id
$id_filter
ORDER BY e.id DESC";


$result = $conn->query($sql);

// Menampilkan isi data
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
    echo "<td>" . htmlspecialchars($row['NIP_NIM']) . "</td>";
    echo "<td>" . htmlspecialchars($row['jabatan_angkatan']) . "</td>";
    echo "<td>" . htmlspecialchars($row['jenis_kegiatan']) . "</td>";
    echo "<td>" . htmlspecialchars($row['nama_kegiatan']) . "</td>";
    echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
    echo "<td>" . htmlspecialchars($row['waktu']) . "</td>";
    echo "<td>" . htmlspecialchars($row['tempat']) . "</td>";

    // Pertanyaan 0 - 14 dengan label asli
    for ($i = 0; $i < count($daftarPertanyaan); $i++) {
        $key = "pertanyaan$i";
        echo "<td>" . ucwords(str_replace('_', ' ', htmlspecialchars($row[$key]))) . "</td>";
    }

    echo "<td>" . nl2br(htmlspecialchars($row['aspekTerbaik'])) . "</td>";
    echo "<td>" . nl2br(htmlspecialchars($row['perbaikan'])) . "</td>";
    echo "<td>" . nl2br(htmlspecialchars($row['saran'])) . "</td>";
    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
    echo "</tr>";
}

echo "</table>";
exit;
?>
