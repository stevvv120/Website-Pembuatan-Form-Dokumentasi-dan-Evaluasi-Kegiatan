<?php include 'header.php'; ?>

<?php include 'db/db.php'; ?>
<?php include 'survey_mappings.php'; ?>
<?php include 'survey_renderer.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="css/survey.css">
    <link rel="stylesheet" href="css/survey-validation.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/survey.js"></script>
</head>
<body>

<div class="survey-container">
    <form action="submit.php" method="POST">
        <div class="step">
            <h2 class="survey-title">Kinerja Personal</h2>
            <?php 
                for ($i = 0; $i <= 4; $i++) {
                    renderSetujuQuestion($i, $pertanyaan_setuju[$i]);
                }
            ?>
            <h2 class="survey-title">Evaluasi Personal</h2>
            <?php 
                renderKepuasanQuestion('usaha_puas_1', $tambahan_deskripsi['usaha_puas_1']);
                renderKepuasanQuestion('hasil_puas_1', $tambahan_deskripsi['hasil_puas_1']);
                
                renderKomentarQuestion(1, $pertanyaan_komentar[1]);
            ?>
        </div>

        <div class="step">
            <h2 class="survey-title">Materi perkuliahan</h2>
            <?php 
                for ($i = 5; $i <= 8; $i++) {
                    renderSetujuQuestion($i, $pertanyaan_setuju[$i]);
                }
                
                renderRatingQuestion('efektivitas_1', $tambahan_deskripsi['efektivitas_1'], 'Sangat tidak efektif', 'Sangat efektif');
                
                renderKomentarQuestion(2, $pertanyaan_komentar[2]);
            ?>
        </div>

        <div class="step">
            <h2 class="survey-title">Evaluasi terhadap proses penilaian belajar</h2>
            <?php 
                for ($i = 9; $i <= 13; $i++) {
                    renderSetujuQuestion($i, $pertanyaan_setuju[$i]);
                }
                
                renderRatingQuestion('efektivitas_2', $tambahan_deskripsi['efektivitas_2'], 'Sangat tidak baik', 'Sangat baik');
            ?>
        </div>

        <div class="step">
            <h2 class="survey-title">Evaluasi terhadap kinerja dosen</h2>
            <?php 
                for ($i = 14; $i <= 18; $i++) {
                    renderSetujuQuestion($i, $pertanyaan_setuju[$i]);
                }
                
                renderRatingQuestion('efektivitas_3', $tambahan_deskripsi['efektivitas_3'], 'Sangat tidak berhasil', 'Sangat berhasil');
            ?>
        </div>

        <div class="step">
            <h2 class="survey-title">Evaluasi terhadap asmatkul/mentor</h2>
            <?php 
                for ($i = 19; $i <= 23; $i++) {
                    renderSetujuQuestion($i, $pertanyaan_setuju[$i]);
                }
                
                renderKomentarQuestion(3, $pertanyaan_komentar[3]);
            ?>
        </div>
        
        <div class="step">
            <?php renderKomentarQuestion(4, $pertanyaan_komentar[4]); ?>
        </div>

        <div class="step">
            <?php 
                renderKomentarQuestion(5, $pertanyaan_komentar[5]);
                renderKomentarQuestion(6, $pertanyaan_komentar[6]);
                renderKomentarQuestion(7, $pertanyaan_komentar[7]);
                renderKomentarQuestion(8, $pertanyaan_komentar[8]);
                
                renderYesNoQuestion('mentor', $tambahan_deskripsi['mentor'], 'mentor_iya', 'mentor_tidak');
            ?>
        </div>

        <div class="step">
            <h2 class="survey-title">Evaluasi personal sebagai asmatkul/mentor</h2>
            <?php 
                for ($i = 24; $i <= 31; $i++) {
                    renderSetujuQuestion($i, $pertanyaan_setuju[$i]);
                }
            ?>
            <h2 class="survey-title">Evaluasi terhadap mahasiswa</h2>
            <?php 
                for ($i = 32; $i <= 38; $i++) {
                    renderSetujuQuestion($i, $pertanyaan_setuju[$i]);
                }
            ?>
            <h2 class="survey-title">Evaluasi terhadap sesama asmatkul/mentor</h2>
            <?php 
                for ($i = 39; $i <= 43; $i++) {
                    renderSetujuQuestion($i, $pertanyaan_setuju[$i]);
                }
            ?>
            <h2 class="survey-title">Evaluasi terhadap dosen mata kuliah</h2>
            <?php 
                for ($i = 44; $i <= 47; $i++) {
                    renderSetujuQuestion($i, $pertanyaan_setuju[$i]);
                }
                
                renderKomentarQuestion(9, $pertanyaan_komentar[9]);
            ?>
        </div>

        <div class="step">
            <?php renderYesNoQuestion('MBKM_1', $tambahan_deskripsi['MBKM_1'], 'MBKM_iya', 'MBKM_tidak'); ?>
        </div>

        <div class="step">
            <?php 
                renderMBKMQuestion('MBKM_2', $tambahan_deskripsi['MBKM_2']);
            ?>
            <h2 class="survey-title">Evaluasi personal terhadap program MBKM yang diikuti</h2>
            <?php 
                for ($i = 48; $i <= 55; $i++) {
                    renderSetujuQuestion($i, $pertanyaan_setuju[$i]);
                }
            ?>
            <h2 class="survey-title">Evaluasi terhadap mitra tempat melaksanakan MBKM</h2>
            <?php 
                for ($i = 56; $i <= 63; $i++) {
                    renderSetujuQuestion($i, $pertanyaan_setuju[$i]);
                }
            ?>
            <h2 class="survey-title">Evaluasi terhadap supervisor MBKM</h2>
            <?php 
                for ($i = 64; $i <= 67; $i++) {
                    renderSetujuQuestion($i, $pertanyaan_setuju[$i]);
                }
            ?>
            <h2 class="survey-title">Evaluasi terhadap dosen pembimbing MBKM</h2>
            <?php 
                for ($i = 68; $i <= 71; $i++) {
                    renderSetujuQuestion($i, $pertanyaan_setuju[$i]);
                }
                
                renderKomentarQuestion(10, $pertanyaan_komentar[10]);
            ?>
        </div>

        <div class="step">
            <?php renderYesNoQuestion('PPP', $tambahan_deskripsi['PPP'], 'PPP_iya', 'PPP_tidak'); ?>
        </div>

        <div class="step">
            <?php 
                renderKomentarQuestion(11, $pertanyaan_komentar[11]);
                renderKomentarQuestion(12, $pertanyaan_komentar[12]);
            ?>
        </div>

        <div class="step">
            <?php renderYesNoQuestion('TA', $tambahan_deskripsi['TA'], 'TA_iya', 'TA_tidak'); ?>
        </div>

        <div class="step">
            <?php 
                renderKomentarQuestion(13, $pertanyaan_komentar[13]);
                renderKomentarQuestion(14, $pertanyaan_komentar[14]);
            ?>
        </div>

        <div class="step">
            <?php 
                renderKomentarQuestion(15, $pertanyaan_komentar[15]);
                renderKomentarQuestion(16, $pertanyaan_komentar[16]);
            ?>
        </div>

        <div class="step">
            <?php 
                renderKomentarQuestion(17, $pertanyaan_komentar[17]);
                renderKomentarQuestion(18, $pertanyaan_komentar[18]);
            ?>
        </div>

        <div class="step">
            <h2 class="survey-title">Angkatan</h2>
                <div class="survey-question">
                    <label for="angkatan-slider" class="angkatan-label">Pilih Tahun Angkatan:</label>
                    <div class="angkatan-slider-container">
                        <div class="angkatan-value-display" id="angkatan-value">2024</div>
                        <input type="range" id="angkatan-slider" class="angkatan-slider" min="2000" max="2100" value="2024" step="1" oninput="updateAngkatanValue(this.value)">
                        <div class="angkatan-range-labels">
                            <span>2000</span>
                            <span>2050</span>
                            <span>2100</span>
                        </div>
                        <input type="hidden" id="angkatan" name="angkatan" value="2024" required>
                    </div>
                </div>
            <h2 class="survey-title">Nama Lengkap</h2>
            <div class="survey-question">
                <textarea class="survey-textarea" name="komentar_19" placeholder="Tulis komentar Anda..." required></textarea>
            </div>
            <h2 class="survey-title">NIM</h2>
            <div class="survey-question">
                <textarea class="survey-textarea" name="komentar_20" placeholder="Tulis komentar Anda..." required></textarea>
            </div>
        </div>

        <button type="button" id="prevBtn" onclick="prevStep()">Sebelumnya</button>
        <button type="button" id="nextBtn" onclick="nextStep()">Berikutnya</button>
        <button type="submit" id="submitBtn" style="display: none;">Kirim</button>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>