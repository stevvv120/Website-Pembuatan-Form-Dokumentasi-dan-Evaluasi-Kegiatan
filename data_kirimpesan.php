<?php
include 'db/db.php';
session_start();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_column = isset($_GET['filter_column']) ? $_GET['filter_column'] : 'name';

$filter_options = [
    "name" => "Nama",
    "email" => "Email",
    "nim" => "NIM",
    "subject" => "Subjek"
];

$sql = "SELECT * FROM kirimpesan";

if (!empty($search)) {
    $sql .= " WHERE $filter_column LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$sql .= " ORDER BY created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Data Pesan Kontak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .thead-purple th {
            background: linear-gradient(135deg, rgb(139, 100, 211), #9f7aea);
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4 text-center">Data Pesan Kontak</h2>

    <form method="GET" class="row mb-4">
        <div class="col-md-4">
            <select name="filter_column" class="form-select">
                <?php foreach ($filter_options as $key => $label): ?>
                    <option value="<?= $key ?>" <?= $filter_column == $key ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Masukkan kata kunci"
                   value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="data_kirimpesan.php" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="thead-purple">
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>NIM</th>
                <th>Subjek</th>
                <th>Pesan</th>
                <th>Tanggal Kirim</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='text-center'>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nim']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                    echo "<td>" . nl2br(htmlspecialchars($row['message'])) . "</td>";
                    echo "<td class='text-center'>" . $row['created_at'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>Belum ada pesan masuk.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="text-center mt-4">
        <button class="btn btn-secondary" onclick="history.back()">Kembali</button>
    </div>
</div>

</body>
</html>
