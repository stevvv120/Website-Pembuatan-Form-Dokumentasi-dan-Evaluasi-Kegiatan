<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include 'db/db.php';

$total_dokumentasi = 0;
$total_survey = 0;
$total_evaluasi = 0;
$total_pesan = 0;

// Hitung total dokumentasi
$sql_dokumentasi = "SELECT COUNT(*) as total FROM dokumentasi";
$result_dokumentasi = $conn->query($sql_dokumentasi);
if ($result_dokumentasi && $row = $result_dokumentasi->fetch_assoc()) {
    $total_dokumentasi = $row['total'];
}

// Hitung total survey
$sql_survey = "SELECT COUNT(*) as total FROM survey_responses";
$result_survey = $conn->query($sql_survey);
if ($result_survey && $row = $result_survey->fetch_assoc()) {
    $total_survey = $row['total'];
}

// Hitung total evaluasi
$sql_evaluasi = "SELECT COUNT(*) as total FROM evaluasi";
$result_evaluasi = $conn->query($sql_evaluasi);
if ($result_evaluasi && $row = $result_evaluasi->fetch_assoc()) {
    $total_evaluasi = $row['total'];
}

// Hitung total pesan kirim
$sql_pesan = "SELECT COUNT(*) as total FROM kirimpesan";
$result_pesan = $conn->query($sql_pesan);
if ($result_pesan && $row = $result_pesan->fetch_assoc()) {
    $total_pesan = $row['total'];
}

// Ambil aktivitas terbaru dari semua tabel
$aktivitas_terbaru = [];

// Dokumentasi terbaru
$sql_dok_terbaru = "SELECT 'dokumentasi' as jenis, nama, created_at FROM dokumentasi ORDER BY created_at DESC LIMIT 3";
$result_dok = $conn->query($sql_dok_terbaru);
if ($result_dok) {
    while ($row = $result_dok->fetch_assoc()) {
        $aktivitas_terbaru[] = $row;
    }
}

// Survey terbaru
$sql_survey_terbaru = "SELECT 'survey' as jenis, nama, created_at FROM survey_responses ORDER BY created_at DESC LIMIT 3";
$result_survey_new = $conn->query($sql_survey_terbaru);
if ($result_survey_new) {
    while ($row = $result_survey_new->fetch_assoc()) {
        $aktivitas_terbaru[] = $row;
    }
}

// Evaluasi terbaru
$sql_eval_terbaru = "SELECT 'evaluasi' as jenis, nama, created_at FROM evaluasi ORDER BY created_at DESC LIMIT 3";
$result_eval = $conn->query($sql_eval_terbaru);
if ($result_eval) {
    while ($row = $result_eval->fetch_assoc()) {
        $aktivitas_terbaru[] = $row;
    }
}

// Pesan terbaru
$sql_pesan_terbaru = "SELECT 'pesan' as jenis, name as nama, created_at FROM kirimpesan ORDER BY created_at DESC LIMIT 3";
$result_pesan_new = $conn->query($sql_pesan_terbaru);
if ($result_pesan_new) {
    while ($row = $result_pesan_new->fetch_assoc()) {
        $aktivitas_terbaru[] = $row;
    }
}

// Sort aktivitas terbaru berdasarkan tanggal descending
usort($aktivitas_terbaru, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});

// Ambil 5 aktivitas terbaru saja
$aktivitas_terbaru = array_slice($aktivitas_terbaru, 0, 5);

// Fungsi waktu relatif (time ago)
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'Baru saja';
    if ($time < 3600) return floor($time/60) . ' menit lalu';
    if ($time < 86400) return floor($time/3600) . ' jam lalu';
    if ($time < 2592000) return floor($time/86400) . ' hari lalu';
    if ($time < 31536000) return floor($time/2592000) . ' bulan lalu';
    return floor($time/31536000) . ' tahun lalu';
}

// Fungsi ikon untuk jenis aktivitas
function getIcon($jenis) {
    switch($jenis) {
        case 'dokumentasi': return '📝';
        case 'survey': return '📊';
        case 'evaluasi': return '⭐';
        case 'pesan': return '✉️';
        default: return '📄';
    }
}

// Deskripsi aktivitas sesuai jenis
function getActivityDescription($jenis, $nama) {
    switch($jenis) {
        case 'dokumentasi': 
            return "Dokumentasi baru oleh " . htmlspecialchars($nama);
        case 'survey': 
            return "Survey baru dari " . htmlspecialchars($nama);
        case 'evaluasi': 
            return "Evaluasi baru dari " . htmlspecialchars($nama);
        case 'pesan':
            return "Pesan baru dari " . htmlspecialchars($nama);
        default: 
            return "Aktivitas baru";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Dashboard Admin</h1>
            <a href="logout.php" class="logout-btn">
                <span class="logout-icon">🚪</span>
                Logout
            </a>
        </div>
    </header>

    <main class="container">
        <div class="stats-section">
            <div class="stat-item">
                <div class="stat-number"><?php echo $total_dokumentasi; ?></div>
                <div class="stat-label">Total Dokumentasi</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $total_survey; ?></div>
                <div class="stat-label">Total Survey</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $total_evaluasi; ?></div>
                <div class="stat-label">Total Evaluasi</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $total_pesan; ?></div>
                <div class="stat-label">Total Pesan Masuk</div>
            </div>
        </div>

        <div class="cards-grid">
            <a href="data_dokumentasi.php" class="card blue">
                <div class="card-content">
                    <h3>Data Dokumentasi</h3>
                    <p>Kelola dan lihat semua dokumentasi sistem</p>
                    <div class="card-stats">
                        <span class="count"><?php echo $total_dokumentasi; ?> Dokumen</span>
                    </div>
                </div>
                <div class="card-icon">📝</div>
            </a>

            <a href="data_survey.php" class="card orange">
                <div class="card-content">
                    <h3>Data Survey</h3>
                    <p>Analisis hasil survey dan feedback pengguna</p>
                    <div class="card-stats">
                        <span class="count"><?php echo $total_survey; ?> Survey</span>
                    </div>
                </div>
                <div class="card-icon">📊</div>
            </a>

            <a href="data_evaluasi.php" class="card purple">
                <div class="card-content">
                    <h3>Data Evaluasi</h3>
                    <p>Review dan evaluasi kinerja sistem</p>
                    <div class="card-stats">
                        <span class="count"><?php echo $total_evaluasi; ?> Evaluasi</span>
                    </div>
                </div>
                <div class="card-icon">📈</div>
            </a>

            <a href="data_kirimpesan.php" class="card green">
                <div class="card-content">
                    <h3>Data Pesan Masuk</h3>
                    <p>Kelola dan lihat semua pesan dari kontak</p>
                    <div class="card-stats">
                        <span class="count"><?php echo $total_pesan; ?> Pesan</span>
                    </div>
                </div>
                <div class="card-icon">✉️</div>
            </a>
        </div>

        <div class="quick-info">
            <div class="info-card">
                <h4>Aktivitas Terbaru</h4>
                <ul>
                    <?php if (empty($aktivitas_terbaru)): ?>
                        <li>🔍 Belum ada aktivitas terbaru</li>
                    <?php else: ?>
                        <?php foreach ($aktivitas_terbaru as $aktivitas): ?>
                            <li>
                                <?php echo getIcon($aktivitas['jenis']); ?> 
                                <?php echo getActivityDescription($aktivitas['jenis'], $aktivitas['nama']); ?> 
                                - <?php echo timeAgo($aktivitas['created_at']); ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </main>
</body>
</html>
