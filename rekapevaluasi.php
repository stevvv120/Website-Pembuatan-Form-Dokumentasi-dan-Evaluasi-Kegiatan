<?php
include 'db/db.php';

$filterYear = $_GET['year'] ?? '';
$whereClause = $filterYear ? "WHERE YEAR(e.tanggal) = $filterYear" : "";
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

// Hitung total data evaluasi
$totalQuery = "SELECT COUNT(*) AS total FROM evaluasi e $whereClause";
$totalData = $conn->query($totalQuery)->fetch_assoc()['total'];

// Fungsi ambil data untuk grafik
function getChartData($conn, $query, $labelCol = 'label') {
    $result = $conn->query($query);
    $labels = [];
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row[$labelCol] ?? 'Tidak diketahui';
        $data[] = $row['jumlah'];
    }
    return ['labels' => $labels, 'data' => $data];
}


$jenisQuery = "SELECT j.nama_jenis AS label, COUNT(e.id) AS jumlah FROM evaluasi e LEFT JOIN jenis_kegiatan_evaluasi j ON e.jenis_kegiatan_id = j.id $whereClause GROUP BY j.nama_jenis";
$chartJenis = getChartData($conn, $jenisQuery);

$tempatQuery = "SELECT tempat AS label, COUNT(*) AS jumlah FROM evaluasi e $whereClause GROUP BY tempat";
$chartTempat = getChartData($conn, $tempatQuery);

$namaQuery = "SELECT nama_kegiatan AS label, COUNT(*) AS jumlah FROM evaluasi e $whereClause GROUP BY nama_kegiatan";
$chartNama = getChartData($conn, $namaQuery);

$tanggalQuery = "SELECT DATE_FORMAT(tanggal, '%Y-%m') AS label, COUNT(*) AS jumlah FROM evaluasi e $whereClause GROUP BY label";
$chartTanggal = getChartData($conn, $tanggalQuery);

$pertanyaanChart = [];
for ($i = 0; $i <= 14; $i++) {
    $query = "SELECT pertanyaan$i AS label, COUNT(*) AS jumlah FROM evaluasi e $whereClause GROUP BY pertanyaan$i ORDER BY pertanyaan$i";
    $pertanyaanChart[$i] = getChartData($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Evaluasi Kegiatan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1100px;
            margin: auto;
            padding: 30px 20px;
        }
        h2 {
            color: #3f37c9;
            text-align: center;
            margin-bottom: 10px;
        }
        .rekap-summary {
            text-align: center;
            font-size: 1.2em;
            margin-bottom: 30px;
        }

        .filter-form {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filter-form label {
            font-weight: 600;
            color: #3f37c9;
        }

        .filter-form select {
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #fff;
            font-size: 14px;
            color: #333;
            transition: border-color 0.2s;
        }

        .filter-form select:focus {
            outline: none;
            border-color: #3f37c9;
        }

        .chart-box {
            background: #fff;
            padding: 20px;
            margin: 25px 0;
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        .legend-box {
            margin-top: 10px;
            font-size: 0.95em;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        canvas {
            max-width: 100%;
            margin: auto;
        }
        .btn-back {
            display: inline-block;
            margin: 40px auto 0;
            background-color: #3f37c9;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
        }
        .btn-back:hover {
            background-color: #4895ef;
        }
        .center { text-align: center; }
        .filter-form { margin-bottom: 20px; text-align: center; }
        .table-rekap {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table-rekap th, .table-rekap td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: left;
        }
        .table-rekap th {
            background-color: #e4e4ff;
            font-weight: bold;
        }
        .rekap-subjudul {
            margin-top: 40px;
            font-size: 1.25rem;
            color: #333;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Rekapitulasi Evaluasi Kegiatan</h2>

    <form method="GET" class="filter-form">
        <label for="filterYear">Filter Tahun:</label>
        <select name="year" id="filterYear" onchange="this.form.submit()">
            <option value="">Semua</option>
            <?php
            $queryYears = "SELECT DISTINCT YEAR(tanggal) as year FROM evaluasi ORDER BY year DESC";
            $resultYears = $conn->query($queryYears);
            while ($row = $resultYears->fetch_assoc()) {
                $selected = ($filterYear == $row['year']) ? 'selected' : '';
                echo "<option value='{$row['year']}' $selected>{$row['year']}</option>";
            }
            ?>
        </select>
    </form>

    <p class="rekap-summary">Total Data Evaluasi: <strong><?= $totalData ?></strong></p>

    <h3 class="rekap-subjudul">Ringkasan Jenis Kegiatan</h3>
    <table class="table-rekap">
        <thead>
            <tr><th>Jenis Kegiatan</th><th>Jumlah</th></tr>
        </thead>
        <tbody>
        <?php foreach ($chartJenis['labels'] as $i => $label): ?>
            <tr>
                <td><?= $label ?></td>
                <td><?= $chartJenis['data'][$i] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h3 class="rekap-subjudul">Ringkasan Tempat Pelaksanaan</h3>
    <table class="table-rekap">
        <thead>
            <tr><th>Tempat</th><th>Jumlah</th></tr>
        </thead>
        <tbody>
        <?php foreach ($chartTempat['labels'] as $i => $label): ?>
            <tr>
                <td><?= $label ?></td>
                <td><?= $chartTempat['data'][$i] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h3 class="rekap-subjudul">Ringkasan Tanggal Pelaksanaan(Per Bulan)</h3>
    <table class="table-rekap">
        <thead>
            <tr><th>Bulan</th><th>Jumlah</th></tr>
        </thead>
        <tbody>
        <?php foreach ($chartTanggal['labels'] as $i => $label): ?>
            <tr>
                <td><?= $label ?></td>
                <td><?= $chartTanggal['data'][$i] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


    <div class="chart-box"><h3>Jenis Kegiatan</h3><canvas id="chartJenis"></canvas><div id="chartJenis-legend" class="legend-box"></div></div>
    <div class="chart-box"><h3>Tempat Pelaksanaan</h3><canvas id="chartTempat"></canvas><div id="chartTempat-legend" class="legend-box"></div></div>
    <div class="chart-box"><h3>Nama Kegiatan</h3><canvas id="chartNama"></canvas><div id="chartNama-legend" class="legend-box"></div></div>
    <div class="chart-box"><h3>Tanggal Pelaksanaan per Bulan</h3><canvas id="chartTanggal"></canvas><div id="chartTanggal-legend" class="legend-box"></div></div>

    <?php foreach ($pertanyaanChart as $i => $data): ?>
    <div class="chart-box">
        <h3><?= $daftarPertanyaan[$i] ?></h3>
        <canvas id="chartPertanyaan<?= $i ?>"></canvas>
        <div id="chartPertanyaan<?= $i ?>-legend" class="legend-box"></div>
    </div>
    <?php endforeach; ?>

    <?php
// Hitung rata-rata keseluruhan semua pertanyaan
$totalRata = 0;
for ($i = 0; $i < 15; $i++) {
    $query = "SELECT AVG(pertanyaan$i) as rata2 FROM evaluasi e $whereClause";
    $result = $conn->query($query);
    $r = $result->fetch_assoc();
    $totalRata += $r['rata2'];
}
$avgTotal = round($totalRata / 15, 2);

// Fungsi kategori
function getKategoriSkor($rata) {
    if ($rata <= 1.75) return 'Sangat Kurang';
    elseif ($rata <= 2.5) return 'Cukup';
    elseif ($rata <= 3.25) return 'Baik';
    else return 'Sangat Baik';
}

// Rata-rata keseluruhan
    $totalRata = 0;
    for ($i = 0; $i < 15; $i++) {
        $query = "SELECT AVG(pertanyaan$i) AS rata FROM evaluasi e $whereClause";
        $res = $conn->query($query);
        $row = $res->fetch_assoc();
        $totalRata += floatval($row['rata']);
    }
    $rataKeseluruhan = $totalRata / 15;
    $kategoriKeseluruhan = getKategoriSkor($rataKeseluruhan);
    
$kategoriKesimpulan = getKategoriSkor($avgTotal);
?>

<div class="chart-box">
    <p><strong>Rata-rata keseluruhan evaluasi kegiatan:</strong> <?= $kategoriKeseluruhan ?></p>

    <h4>Rata-rata per Pertanyaan</h4>
    <table class="table-rekap">
        <thead>
            <tr>
                <th>No</th>
                <th>Pertanyaan</th>
                <th>Kategori</th>
                <th>Jumlah Responden</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($daftarPertanyaan); $i++): 
                $sql = "SELECT AVG(pertanyaan$i) AS rata, COUNT(pertanyaan$i) AS jumlah FROM evaluasi e $whereClause";
                $result = $conn->query($sql);
                $data = $result->fetch_assoc();
                $kategori = getKategoriSkor(floatval($data['rata']));
                ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $daftarPertanyaan[$i] ?></td>
                    <td><?= $kategori ?></td>
                    <td><?= $data['jumlah'] ?></td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <?php
    $kesimpulanTeks = '';
    switch ($kategoriKeseluruhan) {
        case 'Sangat Kurang':
            $kesimpulanTeks = "Berdasarkan hasil evaluasi, pelaksanaan kegiatan dinilai <strong>Sangat Kurang</strong>. Hal ini menunjukkan perlunya peningkatan signifikan pada aspek-aspek pelaksanaan kegiatan.";
            break;
        case 'Cukup':
            $kesimpulanTeks = "Berdasarkan hasil evaluasi, pelaksanaan kegiatan tergolong <strong>Cukup</strong>. Beberapa aspek berjalan cukup baik, namun masih terdapat ruang untuk perbaikan.";
            break;
        case 'Baik':
            $kesimpulanTeks = "Berdasarkan hasil evaluasi, pelaksanaan kegiatan dinilai <strong>Baik</strong>. Mayoritas peserta merasa puas, namun tetap dapat ditingkatkan untuk hasil yang lebih optimal.";
            break;
        case 'Sangat Baik':
            $kesimpulanTeks = "Berdasarkan hasil evaluasi, pelaksanaan kegiatan mendapatkan penilaian <strong>Sangat Baik</strong>. Hal ini mencerminkan kepuasan tinggi dari peserta dan keberhasilan pelaksanaan kegiatan.";
            break;
    }
    ?>

    <p><strong>Kesimpulan:</strong> <?= $kesimpulanTeks ?></p>



    <div class="center">
        <a href="data_evaluasi.php" class="btn-back">&larr; Kembali ke Data Evaluasi</a>
    </div>

</div>

<script>
function renderChart(id, labels, data, type = 'bar', customLegend = null) {
    const nilaiTetap = ['sangat_kurang', 'cukup', 'baik', 'sangat_baik'];
    const warnaMap = {
        'sangat_kurang': '#e63946',
        'cukup': '#f4a261',
        'baik': '#2a9d8f',
        'sangat_baik': '#457b9d'
    };
    const isPertanyaan = labels.every(label => nilaiTetap.includes(label));
    const backgroundColors = labels.map((label, i) => isPertanyaan ? (warnaMap[label] || '#999') : `hsl(${i * 360 / labels.length}, 70%, 60%)`);
    const chart = new Chart(document.getElementById(id), {
        type: type,
        data: {
            labels: labels,
            datasets: [{ data: data, backgroundColor: backgroundColors, borderRadius: 4 }]
        },
        options: {
            indexAxis: (type === 'bar') ? 'x' : undefined,
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed.y ?? context.parsed}`;
                        }
                    }
                },
                title: { display: false }
            },
            scales: type === 'bar' ? {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            } : {}
        }
    });
    const legendContainer = document.getElementById(`${id}-legend`);
    if (legendContainer) {
        legendContainer.innerHTML = labels.map((label, i) => `
            <span style="display:inline-flex; align-items:center; margin-right:10px;">
                <span style="display:inline-block; width:16px; height:16px; background:${backgroundColors[i]}; margin-right:6px; border-radius:3px;"></span>
                <span>${customLegend?.[label] ?? label}</span>
            </span>
        `).join('');
    }
}

renderChart("chartJenis", <?= json_encode($chartJenis['labels']) ?>, <?= json_encode($chartJenis['data']) ?>, 'pie');
renderChart("chartTempat", <?= json_encode($chartTempat['labels']) ?>, <?= json_encode($chartTempat['data']) ?>, 'pie');
renderChart("chartNama", <?= json_encode($chartNama['labels']) ?>, <?= json_encode($chartNama['data']) ?>, 'pie');
renderChart("chartTanggal", <?= json_encode($chartTanggal['labels']) ?>, <?= json_encode($chartTanggal['data']) ?>, 'bar');
<?php foreach ($pertanyaanChart as $i => $data): ?>
renderChart("chartPertanyaan<?= $i ?>", <?= json_encode($data['labels']) ?>, <?= json_encode($data['data']) ?>, 'bar', {
    'sangat_kurang': 'Sangat Kurang',
    'cukup': 'Cukup',
    'baik': 'Baik',
    'sangat_baik': 'Sangat Baik'
});
<?php endforeach; ?>
</script>
</body>
</html>
<?php $conn->close(); ?>