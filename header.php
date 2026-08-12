<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo-container">
                <a href="index.php" class="logo">
                    <img src="assets/logo.png" alt="Logo">
                    <span class="faculty-text">Fakultas Psikologi</span>
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="survey.php">Survey</a></li>
                <li><a href="dokumentasi.php">Dokumentasi</a></li>
                <li><a href="evaluasi.php">Evaluasi</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            <div class="menu-toggle">☰</div>
        </nav>
    </header>
    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.nav-links').classList.toggle('active');
        });
    </script>
</body>
</html>
