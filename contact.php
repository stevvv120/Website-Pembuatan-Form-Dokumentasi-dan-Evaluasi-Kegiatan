<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi Kami - Survey Fakultas Psikologi</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/contact.css">
</head>
<body>

<div class="contact-container">
    <div class="hero-section">
        <h1>Hubungi Kami</h1>
        <p class="hero-subtitle">Kami siap membantu Anda dengan pertanyaan atau masukan</p>
    </div>

    <div class="content-section">
        <div class="contact-info">
            <div class="info-card">
                <div class="icon">📍</div>
                <h3>Alamat</h3>
                <p>Kampus 1<br> 
                    Universitas Kristen Krida Wacana, Gedung E, Lt. 2.<br>
                    Jl. Tanjung Duren Raya No. 4, Grogol Petamburan, Jakarta Barat, 11470<br>
                    Jakarta, Indonesia </p>
            </div>
            
            <div class="info-card">
                <div class="icon">📞</div>
                <h3>Telepon</h3>
                <p>+62 877 9737 7713<br>
                (Sekretariat Fakultas Psikologi Ukrida)</p>
            </div>
            
            <div class="info-card">
                <div class="icon">✉</div>
                <h3>Email</h3>
                <p>tu.fpsi@ukrida.ac.id</p>
            </div>
            
            <div class="info-card">
                <div class="icon">🕒</div>
                <h3>Jam Operasional</h3>
                <p>Senin - Jumat: 08:00 - 16:00</p>
            </div>
        </div>

        <div class="contact-form-section">
            <h2>Kirim Pesan</h2>
            <form class="contact-form" action="kirimpesan.php" method="POST">
        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="nim">NIM (Opsional)</label>
            <input type="text" id="nim" name="nim" placeholder="Masukkan NIM jika Anda mahasiswa">
        </div>

        <div class="form-group">
            <label for="subject">Subjek</label>
            <select id="subject" name="subject" required>
                <option value="">Pilih Subjek</option>
                <option value="survey">Pertanyaan tentang Survey</option>
                <option value="dokumentasi">Dokumentasi Kegiatan</option>
                <option value="evaluasi">Evaluasi Kegiatan</option>
                <option value="teknis">Masalah Teknis</option>
                <option value="saran">Saran dan Masukan</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>

        <div class="form-group">
            <label for="message">Pesan</label>
            <textarea id="message" name="message" rows="6" placeholder="Tulis pesan Anda di sini..." required></textarea>
        </div>

            <button type="submit" class="submit-btn">Kirim Pesan</button>
        </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>