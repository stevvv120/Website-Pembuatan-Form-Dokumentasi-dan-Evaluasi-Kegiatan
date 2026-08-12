<?php
session_start();
include 'db/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if ($password === $user['password']) {
            $_SESSION['user'] = $user['username'];
            header("Location: admin.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="login-container">
    <h2>Login Admin</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>

        <div class="password-container">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <span class="toggle-password">🔒</span>
        </div>

        <button type="submit">Login</button>
    </form>
</div>

<script>
    document.querySelector(".toggle-password").addEventListener("click", function () {
        let passwordInput = document.getElementById("password");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            this.textContent = "👁️";
        } else {
            passwordInput.type = "password";
            this.textContent = "🔒";
        }
    });
</script>

</body>
</html>
