<?php
include 'db/db.php';
session_start();

header("Content-Type: text/html"); 

$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $nim = isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : NULL;
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    $sql = "INSERT INTO kirimpesan (name, email, nim, subject, message)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssss", $name, $email, $nim, $subject, $message);
        $success = $stmt->execute();
        $stmt->close();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Respon</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        Swal.fire({
            icon: '<?php echo $success ? 'success' : 'error'; ?>',
            title: '<?php echo $success ? 'Berhasil!' : 'Gagal!'; ?>',
            text: '<?php echo $success ? 'Pesan sudah dikirim!' : 'Terjadi kesalahan saat mengirim pesan.'; ?>',
            confirmButtonText: 'OK'
        }).then(function() {
            window.location.href = 'contact.php';
        });
    </script>
</body>
</html>
