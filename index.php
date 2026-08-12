<?php 

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login/loginuser.php");
    exit;
}

include 'db/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT nama, nip, nim, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nama, $nip, $nim, $role);
$stmt->fetch();
$stmt->close();

include 'header.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

<div class="home-container">
    <h2>Selamat Datang, <?php echo htmlspecialchars($nama); ?>!</h2>
    <p>
        <?php if ($role == 'mahasiswa'): ?>
            NIM: <strong><?php echo htmlspecialchars($nim); ?></strong>
        <?php else: ?>
            NIP: <strong><?php echo htmlspecialchars($nip); ?></strong>
        <?php endif; ?>
        <br>
        Peran: <strong><?php echo htmlspecialchars(ucfirst($role)); ?></strong>
    </p>


    <p>Bagikan pengalaman dan kepuasan Anda terhadap nilai yang diperoleh.</p>
    
        <form action="logoutuser.php" method="post" style="margin-bottom: 24px; text-align:center;">
            <button type="submit" 
                style="
                    background: linear-gradient(90deg, #e74c3c 0%, #ff7675 100%);
                    color: #fff;
                    padding: 10px 28px;
                    border: none;
                    border-radius: 8px;
                    font-size: 1rem;
                    font-weight: bold;
                    cursor: pointer;
                    box-shadow: 0 2px 8px rgba(231,76,60,0.08);
                    transition: background 0.2s, transform 0.1s;
                "
                onmouseover="this.style.background='linear-gradient(90deg, #ff7675 0%, #e74c3c 100%)';this.style.transform='translateY(-2px) scale(1.03)'"
                onmouseout="this.style.background='linear-gradient(90deg, #e74c3c 0%, #ff7675 100%)';this.style.transform='none'"
            >Logout</button>
        </form>

    <div class="card-container">
        <a href="survey.php" class="card green">
            <div class="card-content">
                <h3>Survey</h3>
                <p>Mulai Survey Kepuasan</p>
            </div>
            <div class="card-icon">📝</div>
        </a>
        <a href="login.php" class="card orange">
            <div class="card-content">
                <h3>Admin</h3>
                <p>Data Survey</p>
            </div>
            <div class="card-icon">👤</div>
        </a>
        <a href="dokumentasi.php" class="card blue">
            <div class="card-content">
                <h3>Dokumentasi</h3>
                <p>Dokumentasi Kegiatan</p>
            </div>
            <div class="card-icon">🏛️</div>
        </a>   
        <a href="evaluasi.php" class="card pink">
            <div class="card-content">
                <h3>Evaluasi</h3>
                <p>Evaluasi Kegiatan</p>
            </div>
            <div class="card-icon">🎟️</div>
        </a>    
            </div>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
