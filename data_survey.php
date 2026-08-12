<?php
include 'db/db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_column = isset($_GET['filter_column']) ? $_GET['filter_column'] : 'nama';

$pertanyaan = [
    "mentor" => "Mentor",
    "MBKM_1" => "MBKM",
    "PPP"    => "PPP",
    "TA"     => "TA",
];

$filter_options = [
    "nama" => "Nama",
    "nim" => "NIM",
    "angkatan" => "Angkatan",
    "mentor" => "Mentor",
    "MBKM_1" => "MBKM",
    "PPP" => "PPP",
    "TA" => "TA",
    "keyword" => "keyword" 
];

$sql = "SELECT DISTINCT angkatan FROM survey_responses ORDER BY angkatan ASC";
$angkatan_result = $conn->query($sql);
$angkatan_options = [];
while ($row = $angkatan_result->fetch_assoc()) {
    $angkatan_options[] = $row['angkatan'];
}

$binary_options = ['ya', 'tidak'];

$sql = "SELECT id, nama, nim, angkatan, mentor, MBKM_1, PPP, TA FROM survey_responses";

if (!empty($search)) {
    if (in_array($filter_column, ["mentor", "MBKM_1", "PPP", "TA"])) {
        $search = strtolower($search) == 'ya' ? 'ya' : 'tidak';
        $sql .= " WHERE LOWER($filter_column) = '$search'";
    } elseif ($filter_column == "keyword") {
        $search_escaped = $conn->real_escape_string($search);
        $komentar_conditions = [];
        for ($i = 1; $i <= 18; $i++) {
            $komentar_conditions[] = "komentar_$i LIKE '%$search_escaped%'";
        }
        $sql .= " WHERE (" . implode(" OR ", $komentar_conditions) . ")";
    } else {
        $search_escaped = $conn->real_escape_string($search);
        $sql .= " WHERE $filter_column LIKE '%$search_escaped%'";
    }
}

$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Survey</title>
    <link rel="stylesheet" type="text/css" href="css/data_survey.css">
    <script>
        function toggleSearchInput() {
            var filter = document.getElementById("filter_column").value;
            var textInput = document.getElementById("text_input");
            var selectInput = document.getElementById("select_input");
            var selectDropdown = document.getElementById("select_dropdown");
            var currentSearch = "<?= htmlspecialchars($search) ?>";
            
            textInput.style.display = "none";
            selectInput.style.display = "none";
            
            if (filter === "nama" || filter === "nim" || filter === "keyword") {
                textInput.style.display = "inline-block";
                if (filter === "keyword") {
                    textInput.placeholder = "Masukkan keyword";
                } else {
                    textInput.placeholder = "Masukkan kata kunci";
                }
            } else if (filter === "angkatan") {
                selectDropdown.innerHTML = "";
                var defaultOption = new Option("-- Pilih Angkatan --", "");
                selectDropdown.add(defaultOption);
                
                <?php foreach ($angkatan_options as $angkatan) : ?>
                    var option = new Option("<?= $angkatan ?>", "<?= $angkatan ?>");
                    if ("<?= $angkatan ?>" === currentSearch) {
                        option.selected = true;
                    }
                    selectDropdown.add(option);
                <?php endforeach; ?>
                selectInput.style.display = "inline-block";
                
                if (currentSearch === "") {
                    selectDropdown.selectedIndex = 0;
                }
            } else {
                selectDropdown.innerHTML = "";
                var defaultOption = new Option("-- Pilih Status --", "");
                selectDropdown.add(defaultOption);
                
                <?php foreach ($binary_options as $option) : ?>
                    var option = new Option("<?= ucfirst($option) ?>", "<?= $option ?>");
                    if ("<?= $option ?>" === currentSearch.toLowerCase()) {
                        option.selected = true;
                    }
                    selectDropdown.add(option);
                <?php endforeach; ?>
                selectInput.style.display = "inline-block";
                
                if (currentSearch === "") {
                    selectDropdown.selectedIndex = 0;
                }
            }
        }

        function submitFormOnChange() {
            var selectDropdown = document.getElementById("select_dropdown");
            if (selectDropdown.value !== "") {
                document.getElementById("search-form").submit();
            }
        }

        window.onload = function() {
            toggleSearchInput();
        }
    </script>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Data Hasil Survey</h1>
        </header>

        <div class="search-container">
            <form method="GET" action="" id="search-form" class="search-form">
                <div class="form-group">
                    <label for="filter_column" class="form-label">Cari berdasarkan:</label>
                    <select name="filter_column" id="filter_column" class="form-select" onchange="toggleSearchInput()">
                        <?php foreach ($filter_options as $key => $label): ?>
                            <option value="<?= $key ?>" <?= $filter_column == $key ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <input type="text" id="text_input" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Masukkan kata kunci" class="form-input" style="display: none;">
                    
                    <span id="select_input" class="form-select-wrapper" style="display: none;">
                        <select name="search" id="select_dropdown" class="form-select" onchange="submitFormOnChange()"></select>
                    </span>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <a href="data_survey.php" class="btn btn-clear">Clear</a>
                </div>
            </form>
        </div>

        <?php if ($filter_column == "keyword" && !empty($search)): ?>
        <div class="search-info">
            <strong>Mencari keyword "<?= htmlspecialchars($search) ?>"</strong>
        </div>
        <?php endif; ?>

        <div class="table-container">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Angkatan</th>
                            <?php foreach ($pertanyaan as $label): ?>
                                <th><?= $label ?></th>
                            <?php endforeach; ?>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td data-label='ID'>{$row['id']}</td>";
                                echo "<td data-label='Nama'>{$row['nama']}</td>";
                                echo "<td data-label='NIM'>{$row['nim']}</td>";
                                echo "<td data-label='Angkatan'>{$row['angkatan']}</td>";
                                foreach ($pertanyaan as $field => $label) {
                                    $jawaban = ucwords(str_replace("_", " ", $row[$field] ?? "-"));
                                    echo "<td data-label='$label'>$jawaban</td>";
                                }
                                echo "<td data-label='Aksi'><a href='detail.php?id={$row['id']}' class='btn btn-detail'>Detail</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='no-data'>Tidak ada data ditemukan</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="action-buttons">
            <a href="admin.php" class="btn btn-admin">Kembali ke Admin</a>
            <a href="export_excel.php?filter_column=<?= $filter_column ?>&search=<?= urlencode($search) ?>" class="btn btn-export">Download Excel</a>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>