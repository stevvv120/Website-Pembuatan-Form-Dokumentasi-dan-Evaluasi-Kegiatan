<?php
include 'db/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login/loginuser.php");
    exit;
}


function getIdFromName($conn, $table, $column, $value) {
    $stmt = $conn->prepare("SELECT id FROM $table WHERE $column = ?");
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $stmt->bind_result($id);
    if ($stmt->fetch()) {
        $stmt->close();
        return $id;
    } else {
        $stmt->close();
        // Insert baru
        $insert = $conn->prepare("INSERT INTO $table ($column) VALUES (?)");
        $insert->bind_param("s", $value);
        $insert->execute();
        $new_id = $insert->insert_id;
        $insert->close();
        return $new_id;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    // Ambil info user
    $stmt_user = $conn->prepare("SELECT nama, role, nip, nim, angkatan FROM users WHERE id = ?");
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $stmt_user->bind_result($nama, $role, $nip, $nim, $angkatan);
    $stmt_user->fetch();
    $stmt_user->close();

    $nip_nim = ($role == 'mahasiswa') ? $nim : $nip;
    $jabatan_angkatan = ($role == 'mahasiswa') ? $angkatan : ucfirst($role);


    // Ambil input utama
    $topik = trim($_POST['topik']);
    $tanggal = trim($_POST['tanggal']);
    $waktu = trim($_POST['waktu']);
    $agenda = trim($_POST['agenda']);
    $kesimpulan = trim($_POST['kesimpulan']);
    $rencana_tindak_lanjut = trim($_POST['rencana_tindak_lanjut']);
    $pertanyaan0 = $_POST['pertanyaan0'];
    $pertanyaan1 = $_POST['pertanyaan1'];
    $saran = trim($_POST['saran']);

    $unit_nama = ($_POST['unit_id'] === 'other') ? trim($_POST['other_unit']) : $_POST['unit_id'];
    $unit_id = getIdFromName($conn, 'unit', 'nama_unit', $unit_nama);

    $jenis_kegiatan_nama = ($_POST['jenis_kegiatan_id'] === 'other') ? trim($_POST['other_jenis_kegiatan']) : $_POST['jenis_kegiatan_id'];
    $jenis_kegiatan_id = getIdFromName($conn, 'jenis_kegiatan', 'nama_jenis', $jenis_kegiatan_nama);

    $peksana_kegiatan_nama = ($_POST['pelaksana_kegiatan_id'] === 'other') ? trim($_POST['other_pelaksana_kegiatan']) : $_POST['pelaksana_kegiatan_id'];
    $pelaksana_kegiatan_id = getIdFromName($conn, 'pelaksana_kegiatan', 'nama_pelaksana', $peksana_kegiatan_nama);

    $peran_kegiatan_id = getIdFromName($conn, 'peran_kegiatan', 'nama_peran', $_POST['peran_kegiatan_id']);

    $tempat_nama = ($_POST['tempat_id'] === 'other') ? trim($_POST['other_tempat_pelaksanaan']) : $_POST['tempat_id'];
    $tempat_id = getIdFromName($conn, 'tempat_pelaksanaan', 'nama_tempat', $tempat_nama);    



    // Validasi foreign key tidak NULL
    if (!$unit_id || !$jenis_kegiatan_id || !$pelaksana_kegiatan_id || !$peran_kegiatan_id || !$tempat_id) {
        echo "<script>alert('Semua pilihan wajib diisi.'); window.history.back();</script>";
        exit;
    }

    // Upload file
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $dokumentasi_path = null;
    if (!empty($_FILES["dokumentasi"]["name"])) {
        $file_name = time() . "_" . basename($_FILES["dokumentasi"]["name"]);
        $dokumentasi_path = $upload_dir . $file_name;
        move_uploaded_file($_FILES["dokumentasi"]["tmp_name"], $dokumentasi_path);
    }

    $notulensi_path = null;
    if (!empty($_FILES["notulensi"]["name"])) {
        $file_name = time() . "_" . basename($_FILES["notulensi"]["name"]);
        $notulensi_path = $upload_dir . $file_name;
        move_uploaded_file($_FILES["notulensi"]["tmp_name"], $notulensi_path);
    }

    // Simpan ke tabel dokumentasi
    $sql = "INSERT INTO dokumentasi (
                nama, nip_nim, unit_id, jabatan_angkatan, jenis_kegiatan_id, pelaksana_kegiatan_id, 
                peran_kegiatan_id, topik, tanggal, waktu, tempat_id, agenda, kesimpulan, 
                rencana_tindak_lanjut, dokumentasi_path, notulensi_path, pertanyaan0, pertanyaan1, 
                saran, user_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssississsssssssssssi",
        $nama, $nip_nim, $unit_id, $jabatan_angkatan,
        $jenis_kegiatan_id, $pelaksana_kegiatan_id, $peran_kegiatan_id, $topik,
        $tanggal, $waktu, $tempat_id, $agenda, $kesimpulan,
        $rencana_tindak_lanjut, $dokumentasi_path, $notulensi_path,
        $pertanyaan0, $pertanyaan1, $saran, $user_id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil disimpan!'); window.location.href='dokumentasi.php';</script>";
    } else {
        error_log("Insert Error: " . $stmt->error);
        echo "<script>alert('Gagal menyimpan data.'); window.history.back();</script>";
    }

    $stmt->close();
}

// Handle Hapus
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); 
    $sql = "SELECT dokumentasi_path, notulensi_path FROM dokumentasi WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($dokumentasi_file, $notulensi_file);
    $stmt->fetch();
    $stmt->close();

    if ($dokumentasi_file && file_exists($dokumentasi_file)) unlink($dokumentasi_file);
    if ($notulensi_file && file_exists($notulensi_file)) unlink($notulensi_file);

    $stmt = $conn->prepare("DELETE FROM dokumentasi WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href='datadokumentasi.php';</script>";
    } else {
        error_log("Delete Error: " . $stmt->error);
        echo "<script>alert('Gagal menghapus data.'); window.history.back();</script>";
    }
    $stmt->close();
}
?>
