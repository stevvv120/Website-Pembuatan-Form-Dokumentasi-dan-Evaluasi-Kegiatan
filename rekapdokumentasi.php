<?php
include 'db/db.php';

$filterYear = $_GET['year'] ?? '';
$whereClause = $filterYear ? "WHERE YEAR(d.tanggal) = $filterYear" : '';

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

function konversiJawabanKeSkor($jawaban, $index) {
    $skor = [
        0 => [ 
            'sangat_tidak_sesuai' => 1,
            'tidak_sesuai' => 2,
            'sesuai' => 3,
            'sangat_sesuai' => 4
        ],
        1 => [ 
            'sangat_tidak_jelas' => 1,
            'tidak_jelas' => 2,
            'jelas' => 3,
            'sangat_jelas' => 4
        ]
    ];
    return $skor[$index][$jawaban] ?? null;
}

function getKategoriSkor($skor) {
    if ($skor < 1.5) return 'Sangat Kurang';
    elseif ($skor < 2.5) return 'Cukup';
    elseif ($skor < 3.5) return 'Baik';
    else return 'Sangat Baik';
}

$totalQuery = "SELECT COUNT(*) AS total FROM dokumentasi d $whereClause";
$totalData = $conn->query($totalQuery)->fetch_assoc()['total'];

$unitQuery = "SELECT u.nama_unit AS label, COUNT(*) AS jumlah 
              FROM dokumentasi d 
              LEFT JOIN unit u ON d.unit_id = u.id 
              $whereClause GROUP BY u.nama_unit";
$chartUnit = getChartData($conn, $unitQuery);

$jenisQuery = "SELECT j.nama_jenis AS label, COUNT(*) AS jumlah 
               FROM dokumentasi d 
               LEFT JOIN jenis_kegiatan j ON d.jenis_kegiatan_id = j.id 
               $whereClause GROUP BY j.nama_jenis";
$chartJenis = getChartData($conn, $jenisQuery);

$tempatQuery = "SELECT t.nama_tempat AS label, COUNT(*) AS jumlah 
                FROM dokumentasi d 
                LEFT JOIN tempat_pelaksanaan t ON d.tempat_id = t.id 
                $whereClause GROUP BY t.nama_tempat";
$chartTempat = getChartData($conn, $tempatQuery);

$tanggalQuery = "SELECT DATE_FORMAT(d.tanggal, '%Y-%m') AS label, COUNT(*) AS jumlah 
                 FROM dokumentasi d $whereClause GROUP BY label";
$chartTanggal = getChartData($conn, $tanggalQuery);

$pelaksanaQuery = "SELECT p.nama_pelaksana AS label, COUNT(*) AS jumlah 
                   FROM dokumentasi d 
                   LEFT JOIN pelaksana_kegiatan p ON d.pelaksana_kegiatan_id = p.id 
                   $whereClause GROUP BY p.nama_pelaksana";
$chartPelaksana = getChartData($conn, $pelaksanaQuery);

$daftarPertanyaanDokumentasi = [
    "Kesesuaian agenda kegiatan dengan pembahasan kegiatan",
    "Kejelasan informasi dalam kegiatan"
];

$pertanyaanLabels = [
    'pertanyaan0' => 'Kesesuaian agenda kegiatan dengan pembahasan kegiatan',
    'pertanyaan1' => 'Kejelasan informasi dalam kegiatan'
];

$chartPertanyaan = [];

foreach ($pertanyaanLabels as $field => $label) {
    $query = "SELECT $field AS label, COUNT(*) AS jumlah FROM dokumentasi $whereClause GROUP BY $field";
    $chartPertanyaan[$field] = [
        'label' => $label,
        'data' => getChartData($conn, $query)
    ];
}


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Dokumentasi Kegiatan</title>
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
    <h2>Rekapitulasi Dokumentasi Kegiatan</h2>

    <!-- Filter Tahun -->
    <form method="GET" class="filter-form">
        <label for="filterYear">Filter Tahun:</label>
        <select name="year" id="filterYear" onchange="this.form.submit()">
            <option value="">Semua</option>
            <?php
            $yearQuery = "SELECT DISTINCT YEAR(tanggal) as year FROM dokumentasi ORDER BY year DESC";
            $years = $conn->query($yearQuery);
            while ($row = $years->fetch_assoc()) {
                $selected = ($filterYear == $row['year']) ? 'selected' : '';
                echo "<option value='{$row['year']}' $selected>{$row['year']}</option>";
            }
            ?>
        </select>
    </form>

    <p class="rekap-summary">Total Dokumentasi: <strong><?= $totalData ?></strong> kegiatan</p>

    

        <h3 class="rekap-subjudul">Tabel Rekap Jenis Kegiatan</h3>
        <table class="table-rekap">
            <thead><tr><th>Jenis Kegiatan</th><th>Jumlah</th></tr></thead>
            <tbody>
                <?php
                $res = $conn->query($jenisQuery);
                while ($row = $res->fetch_assoc()) {
                    echo "<tr><td>{$row['label']}</td><td>{$row['jumlah']}</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h3 class="rekap-subjudul">Tabel Rekap Tempat Pelaksanaan</h3>
        <table class="table-rekap">
            <thead><tr><th>Tempat</th><th>Jumlah</th></tr></thead>
            <tbody>
                <?php
                $res = $conn->query($tempatQuery);
                while ($row = $res->fetch_assoc()) {
                    echo "<tr><td>{$row['label']}</td><td>{$row['jumlah']}</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h3 class="rekap-subjudul">Tabel Rekap Pelaksana Kegiatan</h3>
            <table class="table-rekap">
                <thead><tr><th>Pelaksana</th><th>Jumlah</th></tr></thead>
                <tbody>
                    <?php
                    $res = $conn->query($pelaksanaQuery);
                    while ($row = $res->fetch_assoc()) {
                        echo "<tr><td>{$row['label']}</td><td>{$row['jumlah']}</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

    <div class="chart-box">
        <h3>Jenis Kegiatan</h3>
        <canvas id="chartJenis"></canvas>
        <div id="chartJenis-legend" class="legend-box"></div>
    </div>

    <div class="chart-box">
        <h3>Unit/Fakultas</h3>
        <canvas id="chartUnit"></canvas>
        <div id="chartUnit-legend" class="legend-box"></div>
    </div>

    <div class="chart-box">
        <h3>Tempat Pelaksanaan</h3>
        <canvas id="chartTempat"></canvas>
        <div id="chartTempat-legend" class="legend-box"></div>
    </div>

    <div class="chart-box">
        <h3>Tanggal Pelaksanaan (per Bulan)</h3>
        <canvas id="chartTanggal"></canvas>
        <div id="chartTanggal-legend" class="legend-box"></div>
    </div>

    <div class="chart-box">
        <h3><?= $chartPertanyaan['pertanyaan0']['label'] ?></h3>
        <canvas id="chartPertanyaan0"></canvas>
        <div id="chartPertanyaan0-legend" class="legend-box"></div>
    </div>

    <div class="chart-box">
        <h3><?= $chartPertanyaan['pertanyaan1']['label'] ?></h3>
        <canvas id="chartPertanyaan1"></canvas>
        <div id="chartPertanyaan1-legend" class="legend-box"></div>
    </div>

    <h3 class="rekap-subjudul">Rata-rata Evaluasi Kegiatan</h3>
<table class="table-rekap">
    <thead>
        <tr>
            <th>No</th>
            <th>Pertanyaan</th>
            <th>Kategori Mayoritas</th>
            <th>Jumlah Responden</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $kategoriGlobal = [];
        for ($i = 0; $i < count($daftarPertanyaanDokumentasi); $i++):
            $field = "pertanyaan$i";
            $query = "SELECT $field AS jawaban, COUNT(*) as jumlah FROM dokumentasi $whereClause GROUP BY $field";
            $result = $conn->query($query);

            $kategoriTerbanyak = '-';
            $jumlahTerbanyak = 0;
            $totalResponden = 0;

            $labelMap = [
                0 => [
                    'sangat_tidak_sesuai' => 'Sangat Tidak Sesuai',
                    'tidak_sesuai' => 'Tidak Sesuai',
                    'sesuai' => 'Sesuai',
                    'sangat_sesuai' => 'Sangat Sesuai'
                ],
                1 => [
                    'sangat_tidak_jelas' => 'Sangat Tidak Jelas',
                    'tidak_jelas' => 'Tidak Jelas',
                    'jelas' => 'Jelas',
                    'sangat_jelas' => 'Sangat Jelas'
                ]
            ];

            while ($row = $result->fetch_assoc()) {
                $jawaban = $row['jawaban'];
                $jumlah = $row['jumlah'];
                $totalResponden += $jumlah;
                if ($jumlah > $jumlahTerbanyak) {
                    $jumlahTerbanyak = $jumlah;
                    $kategoriTerbanyak = $labelMap[$i][$jawaban] ?? '-';
                }
            }

            $kategoriGlobal[] = $kategoriTerbanyak;
        ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= $daftarPertanyaanDokumentasi[$i] ?></td>
            <td><?= $kategoriTerbanyak ?></td>
            <td><?= $totalResponden ?></td>
        </tr>
        <?php endfor; ?>
    </tbody>
</table>

<?php
$kategoriCount = array_count_values($kategoriGlobal);
arsort($kategoriCount);
$kategoriMayoritas = array_key_first($kategoriCount);

switch ($kategoriMayoritas) {
    case 'Sangat Tidak Sesuai':
    case 'Sangat Tidak Jelas':
        $kesimpulanTeks = "Berdasarkan hasil evaluasi, pelaksanaan kegiatan dinilai <strong>Sangat Kurang</strong>. Hal ini menunjukkan perlunya peningkatan signifikan pada aspek-aspek pelaksanaan kegiatan.";
        break;
    case 'Tidak Sesuai':
    case 'Tidak Jelas':
        $kesimpulanTeks = "Berdasarkan hasil evaluasi, pelaksanaan kegiatan tergolong <strong>Cukup</strong>. Beberapa aspek berjalan cukup baik, namun masih terdapat ruang untuk perbaikan.";
        break;
    case 'Sesuai':
    case 'Jelas':
        $kesimpulanTeks = "Berdasarkan hasil evaluasi, pelaksanaan kegiatan dinilai <strong>Baik</strong>. Mayoritas peserta merasa puas, namun tetap dapat ditingkatkan untuk hasil yang lebih optimal.";
        break;
    case 'Sangat Sesuai':
    case 'Sangat Jelas':
        $kesimpulanTeks = "Berdasarkan hasil evaluasi, pelaksanaan kegiatan mendapatkan penilaian <strong>Sangat Baik</strong>. Hal ini mencerminkan kepuasan tinggi dari peserta dan keberhasilan pelaksanaan kegiatan.";
        break;
    default:
        $kesimpulanTeks = "Belum ada data evaluasi yang cukup untuk disimpulkan.";
        break;
}
?>

<p><strong>Kesimpulan:</strong> <?= $kesimpulanTeks ?></p>


    <div class="center">
        <a href="data_dokumentasi.php" class="btn-back">&larr; Kembali ke Data Dokumentasi</a>
    </div>
</div>

<script>
function renderChart(id, labels, data, type = 'bar') {
    const backgroundColors = labels.map((_, i) => `hsl(${i * 360 / labels.length}, 70%, 60%)`);
    new Chart(document.getElementById(id), {
        type: type,
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: backgroundColors,
                borderRadius: 4
            }]
        },
        options: {
            plugins: { legend: { display: false }},
            responsive: true,
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
            <span style="display:inline-flex; align-items:center;">
                <span style="width:16px; height:16px; background:${backgroundColors[i]}; margin-right:6px; border-radius:3px;"></span>
                ${label}
            </span>
        `).join(' ');
    }
}

renderChart("chartJenis", <?= json_encode($chartJenis['labels']) ?>, <?= json_encode($chartJenis['data']) ?>, 'pie');
renderChart("chartUnit", <?= json_encode($chartUnit['labels']) ?>, <?= json_encode($chartUnit['data']) ?>, 'pie');
renderChart("chartTempat", <?= json_encode($chartTempat['labels']) ?>, <?= json_encode($chartTempat['data']) ?>, 'pie');
renderChart("chartTanggal", <?= json_encode($chartTanggal['labels']) ?>, <?= json_encode($chartTanggal['data']) ?>, 'bar');
renderChart("chartPertanyaan0", <?= json_encode($chartPertanyaan['pertanyaan0']['data']['labels']) ?>, <?= json_encode($chartPertanyaan['pertanyaan0']['data']['data']) ?>, 'bar');
renderChart("chartPertanyaan1", <?= json_encode($chartPertanyaan['pertanyaan1']['data']['labels']) ?>, <?= json_encode($chartPertanyaan['pertanyaan1']['data']['data']) ?>, 'bar');

</script>
</body>
</html>
<?php $conn->close(); ?>
