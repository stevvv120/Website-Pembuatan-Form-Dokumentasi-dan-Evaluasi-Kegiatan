<?php
include 'db/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login/loginuser.php");
    exit;
}

function getIdFromName($conn, $table, $column, $value) {
    if (!$value) return null;

    $stmt = $conn->prepare("SELECT id FROM $table WHERE $column = ?");
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $stmt->bind_result($id);
    if ($stmt->fetch()) {
        $stmt->close();
        return $id;
    } else {
        $stmt->close();
        $insert = $conn->prepare("INSERT INTO $table ($column) VALUES (?)");
        $insert->bind_param("s", $value);
        $insert->execute();
        return $insert->insert_id;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data user login
    $user_id = $_SESSION['user_id'];
    $stmt_user = $conn->prepare("SELECT nama, role, nip, nim, angkatan FROM users WHERE id = ?");
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $stmt_user->bind_result($nama, $role, $nip, $nim, $angkatan);
    $stmt_user->fetch();
    $stmt_user->close();

    // Set identitas otomatis
    $nip_nim = ($role == 'mahasiswa') ? $nim : $nip;
    $jabatan_angkatan = ($role == 'mahasiswa') ? $angkatan : ucfirst($role);

    // Ambil data dari form
    $jenis_kegiatan_nama = trim($_POST['jenis_kegiatan_id'] ?? '');
    $jenis_kegiatan_id = getIdFromName($conn, 'jenis_kegiatan_evaluasi', 'nama_jenis', $jenis_kegiatan_nama);
    $nama_kegiatan = trim($_POST['nama_kegiatan'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? '');
    $waktu = trim($_POST['waktu'] ?? '');
    $tempat = trim($_POST['tempat'] ?? '');

    $pertanyaan = [];
    for ($i = 0; $i <= 14; $i++) {
        $pertanyaan[$i] = trim($_POST['pertanyaan' . $i] ?? '');
    }

    $aspekTerbaik = trim($_POST['aspekTerbaik'] ?? '');
    $perbaikan = trim($_POST['perbaikan'] ?? '');
    $saran = trim($_POST['saran'] ?? '');

    // Validasi input wajib
    if (
        $jenis_kegiatan_id <= 0 || empty($nama_kegiatan) ||
        empty($tanggal) || empty($waktu) || empty($tempat)
    ) {
        echo "<script>alert('Harap isi semua kolom yang wajib diisi.'); window.history.back();</script>";
        exit;
    }

    // Query insert
    $sql = "INSERT INTO evaluasi (
        jenis_kegiatan_id, nama_kegiatan, tanggal, waktu, tempat,
        pertanyaan0, pertanyaan1, pertanyaan2, pertanyaan3, pertanyaan4, pertanyaan5,
        pertanyaan6, pertanyaan7, pertanyaan8, pertanyaan9, pertanyaan10,
        pertanyaan11, pertanyaan12, pertanyaan13, pertanyaan14,
        aspekTerbaik, perbaikan, saran, nama, NIP_NIM, jabatan_angkatan, user_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param(
            "isssssssssssssssssssssssssi",
            $jenis_kegiatan_id, $nama_kegiatan, $tanggal, $waktu, $tempat,
            $pertanyaan[0], $pertanyaan[1], $pertanyaan[2], $pertanyaan[3], $pertanyaan[4],
            $pertanyaan[5], $pertanyaan[6], $pertanyaan[7], $pertanyaan[8], $pertanyaan[9],
            $pertanyaan[10], $pertanyaan[11], $pertanyaan[12], $pertanyaan[13], $pertanyaan[14],
            $aspekTerbaik, $perbaikan, $saran, $nama, $nip_nim, $jabatan_angkatan, $user_id
        );

        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil disimpan!'); window.location.href='index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Gagal menyimpan data. Silakan coba lagi.'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Kesalahan query database: " . addslashes($conn->error) . "'); window.history.back();</script>";
    }
}

?>
