<?php
include 'db/db.php';
include 'survey_mappings.php'; 

$single_id = isset($_GET['single_id']) ? trim($_GET['single_id']) : '';

if (empty($single_id)) {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $filter_column = isset($_GET['filter_column']) ? $_GET['filter_column'] : 'angkatan';
} else {
    $search = '';
    $filter_column = '';
}

if (!empty($single_id)) {
    $sql_name = "SELECT nama FROM survey_responses WHERE id = '$single_id'";
    $result_name = $conn->query($sql_name);
    if ($result_name->num_rows > 0) {
        $row_name = $result_name->fetch_assoc();
        $nama_file = preg_replace("/[^a-zA-Z0-9_-]/", "", str_replace(" ", "_", $row_name['nama']));
        $filename = "Survey_{$nama_file}_ID_{$single_id}.xls"; 
    } else {
        $filename = "Survey_ID_{$single_id}.xls"; 
    }
} else {
    $filename = "Data_Survey";
    if (!empty($search)) {
        $filename .= "_" . ucfirst($filter_column) . "_" . $search;
    }
    $filename .= ".xls";
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

if (!empty($single_id)) {
    $sql = "SELECT * FROM survey_responses WHERE id = '$single_id'";
} else {
    $sql = "SELECT * FROM survey_responses";
    
    if (!empty($search)) {
        if (in_array($filter_column, ["mentor", "MBKM_1", "PPP", "TA"])) {
            $search_value = strtolower($search) == 'ya' ? 'ya' : 'tidak';
            $sql .= " WHERE LOWER($filter_column) = '$search_value'";
        } else {
            $sql .= " WHERE $filter_column LIKE '%$search%'";
        }
    }
}

$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);

echo "<table border='1'>";
echo "<tr>";
echo "<th>ID</th>";
echo "<th>Nama</th>";
echo "<th>NIM</th>";
echo "<th>Angkatan</th>";

foreach ($tambahan as $field) {
    $label = $tambahan_deskripsi[$field] ?? ucwords(str_replace("_", " ", $field));
    echo "<th>$label</th>";
}

foreach ($pertanyaan_setuju as $index => $pertanyaan) {
    echo "<th>$pertanyaan</th>";
}

foreach ($pertanyaan_komentar as $index => $pertanyaan) {
    echo "<th>$pertanyaan</th>";
}

echo "</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['nama']}</td>";
    echo "<td>{$row['nim']}</td>";
    echo "<td>{$row['angkatan']}</td>";
    
    foreach ($tambahan as $field) {
        $value = isset($row[$field]) ? ucwords(str_replace("_", " ", $row[$field])) : "-";
        echo "<td>$value</td>";
    }
    
    foreach ($pertanyaan_setuju as $index => $pertanyaan) {
        $jawaban = isset($row['setuju_'.$index]) ? ucwords(str_replace("_", " ", $row['setuju_'.$index])) : "-";
        echo "<td>$jawaban</td>";
    }
    
    foreach ($pertanyaan_komentar as $index => $pertanyaan) {
        $komentar = isset($row['komentar_'.$index]) ? $row['komentar_'.$index] : "-";
        echo "<td>$komentar</td>";
    }
    
    echo "</tr>";
}

echo "</table>";
exit;