<?php include 'db/db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/evaluasi.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Evaluasi </title>
    <script src="js/evaluasi.js" defer> </script>
</head>
<body>
<?php include 'header.php'; ?>

    <div class="evaluasi-container">
        <div class="progress-container">
        <div class="progress-bar">
            <div id="progressFill" class="progress-fill"></div>
        </div>
        <div id="progressText" class="progress-text">0% selesai</div>
        </div>
        
        <h2 class="evaluasi-form-title">Form Evaluasi</h2>

        <form action="dataevaluasi.php" method="POST" enctype="multipart/form-data">
            <div class="evaluasi-form-section">
            <div class="evaluasi-section-title">Rincian Kegiatan</div>

            <div class="evaluasi-form-group">
                <label class="evaluasi-form-label">Jenis Kegiatan</label>
                <select class="evaluasi-form-select" name="jenis_kegiatan_id" required>
                    <option value="" disabled selected>Jenis Kegiatan</option>   
                    <option>Sosialisasi</option>
                    <option>Pembekalan</option>
                    <option>Kuliah umum</option>
                    <option>Bimbingan dengan prodi</option>
                    <option>Kegiatan Lembaga Kemahasiswaan (contohnya: PSMB, LKMM, Psychoship)</option>
                    <option>Yudisium</option>
                    <option>Dies Natalis Fakultas Psikologi</option>
                </select>
            </div>

            <div class="evaluasi-form-group">
                <label class="evaluasi-form-label">Nama/topik kegiatan</label>
                <input type="text" class="evaluasi-form-input" name="nama_kegiatan" required>
            </div>

            <div class="evaluasi-form-row">
                <div class="evaluasi-form-column">
                    <label class="evaluasi-form-label">Tanggal Pelaksanaan</label>
                    <input type="date" class="evaluasi-form-input no-calendar" name="tanggal" required>
                </div>

                <div class="evaluasi-form-column">
                    <label class="evaluasi-form-label">Waktu Pelaksanaan</label>
                    <input type="time" class="evaluasi-form-input" name="waktu" required>
                </div>
            </div>

            <div class="evaluasi-form-group">
                <label class="evaluasi-form-label">Tempat Pelaksanaan</label>
                <input type="text" class="evaluasi-form-input" name="tempat" required>
            </div>

            <div class="navigation-buttons first-section">
                <button type="button" class="nextBtn">Berikutnya</button>
            </div>
            </div>

        <div class="evaluasi-form-section">
            <h4 class="evaluasi-section-title">Dimensi Pelaksanaan Kegiatan</h4>

            <div class="evaluasi-question">
                <p>Kesesuaian jadwal pelaksanaan kegiatan dengan yang telah ditemukan</p>
                <label>
                    <input type="radio" name="pertanyaan0" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan0" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan0" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan0" value="sangat_baik" required> Sangat baik
                </label>  
            </div>

            <div class="evaluasi-question">
                <p>Kejelasan informasi yang diberikan sebelum dan selama kegiatan berlangsung</p>
                <label>
                    <input type="radio" name="pertanyaan1" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan1" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan1" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan1" value="sangat_baik" required> Sangat baik
                </label>  
            </div>


            <div class="evaluasi-question">
                <p>Koordinasi antara panitia/pelaksana dan peserta selama kegiatan</p>
                <label>
                    <input type="radio" name="pertanyaan2" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan2" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan2" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan2" value="sangat_baik" required> Sangat baik
                </label>  
            </div>

            <div class="navigation-buttons">
                <button type="button" class="prevBtn">Sebelumnya</button>
                <button type="button" class="nextBtn">Berikutnya</button>
            </div>
        </div>


        <div class="evaluasi-form-section">
            <h4 class="evaluasi-section-title">Dimensi narasumber</h4>

           <div class="evaluasi-question">
                <p>Kompetensi dan pengalaman narasumber dalam menyampaikan materi</p>
                <label>
                    <input type="radio" name="pertanyaan3" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan3" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan3" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan3" value="sangat_baik" required> Sangat baik
                </label>  
            </div>

            <div class="evaluasi-question">
                <p>Penyajian materi yang menarik dan mudah dipahami </p>
                <label>
                    <input type="radio" name="pertanyaan4" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan4" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan4" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan4" value="sangat_baik" required> Sangat baik
                </label>  
            </div>


            <div class="evaluasi-question">
                <p>Kesempatan yang diberikan oleh narasumber untuk diskusi dan tanya jawab</p>
                <label>
                    <input type="radio" name="pertanyaan5" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan5" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan5" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan5" value="sangat_baik" required> Sangat baik
                </label>  
            </div>
 

            <div class="evaluasi-question">
                <p>Kemampuan narasumber dalam menjawab pertanyaan dan memberikan jawaban yang jelas</p>
                <label>
                    <input type="radio" name="pertanyaan6" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan6" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan6" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan6" value="sangat_baik" required> Sangat baik
                </label>  
            </div>
 
      

            <div class="navigation-buttons">
                <button type="button" class="prevBtn">Sebelumnya</button>
                <button type="button" class="nextBtn">Berikutnya</button>
            </div>
        </div>   
        


        <div class="evaluasi-form-section">
            <h4 class="evaluasi-section-title">Dimensi sarana dan prasarana</h4>

            <div class="evaluasi-question">
                <p>Kenyamanan dan kebersihan tempat/ruangan yang digunakan dalam kegiatan</p>
                <label>
                    <input type="radio" name="pertanyaan7" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan7" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan7" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan7" value="sangat_baik" required> Sangat baik
                </label>  
            </div>

            <div class="evaluasi-question">
                <p>Kelengkapan dan sarana yang memadai untuk menunjang kegiatan (PC, LCD proyektor, dll)</p>
                <label>
                    <input type="radio" name="pertanyaan8" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan8" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan8" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan8" value="sangat_baik" required> Sangat baik
                </label>  
            </div>


            <div class="evaluasi-question">
                <p>Tata letak ruangan (pengaturan tempat duduk, jarak pandang, ventilasi)</p>
                <label>
                    <input type="radio" name="pertanyaan9" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan9" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan9" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan9" value="sangat_baik" required> Sangat baik
                </label>  
            </div>
 

            <div class="evaluasi-question">
                <p>Ketersediaan dan kualitas konsumsi (makanan/minuman) yang disediakan selama kegiatan</p>
                <label>
                    <input type="radio" name="pertanyaan10" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan10" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan10" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan10" value="sangat_baik" required> Sangat baik
                </label>  
            </div>

            <div class="navigation-buttons">
                <button type="button" class="prevBtn">Sebelumnya</button>
                <button type="button" class="nextBtn">Berikutnya</button>
            </div>
        </div>   
        

        <div class="evaluasi-form-section">
            <h4 class="evaluasi-section-title">Dimensi kinerja panitia/pelaksana</h4>

            <div class="evaluasi-question">
                <p>Kejelasan informasi dan sosialisasi yang diberikan oleh panitia/pelaksana sebelum acara</p>
                <label>
                    <input type="radio" name="pertanyaan11" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan11" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan11" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan11" value="sangat_baik" required> Sangat baik
                </label>  
            </div>

            <div class="evaluasi-question">
                <p>Keramahan dan responsivitas panitia dalam membantu peserta</p>
                <label>
                    <input type="radio" name="pertanyaan12" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan12" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan12" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan12" value="sangat_baik" required> Sangat baik
                </label>  
            </div>


            <div class="evaluasi-question">
                <p>Kemampuan panitia/pelaksana dalam mengatasi kendala atau permasalahan teknis selama kegiatan</p>
                <label>
                    <input type="radio" name="pertanyaan13" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan13" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan13" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan13" value="sangat_baik" required> Sangat baik
                </label>  
            </div>
 

            <div class="evaluasi-question">
                <p>Koordinasi dan manajemen acara yang dilakukan oleh panitia secara keseluruhan</p>
                <label>
                    <input type="radio" name="pertanyaan14" value="sangat_kurang" required> Sangat kurang
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan14" value="cukup" required> Cukup
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan14" value="baik" required> Baik
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan14" value="sangat_baik" required> Sangat baik
                </label>  
            </div>

            <div class="navigation-buttons">
                <button type="button" class="prevBtn">Sebelumnya</button>
                <button type="button" class="nextBtn">Berikutnya</button>
            </div>
        </div>
        
       
            <div class="evaluasi-form-section">
                <h4 class="evaluasi-section-title">Aspirasi dan Saran</h4>
                
                <div class="evaluasi-form-group">
                    <label class="evaluasi-form-label">Aspek terbaik dari kegiatan ini menurut saya adalah…</label>
                    <textarea class="evaluasi-form-input evaluasi-textarea" id="aspekTerbaik" name="aspekTerbaik" rows="4" required></textarea>

                    <label class="evaluasi-form-label">Hal yang perlu diperbaiki dari kegiatan ini menurut saya adalah...</label>
                    <textarea class="evaluasi-form-input evaluasi-textarea" id="perbaikan" name="perbaikan" rows="4" required></textarea>

                    <label class="evaluasi-form-label">Saran atau rekomendasi saya untuk kegiatan serupa di masa mendatang adalah...</label>
                    <textarea class="evaluasi-form-input evaluasi-textarea" id="saran" name="saran" rows="4" required></textarea>
                </div>

            <div class="navigation-buttons">
                    <button type="button" class="prevBtn">Sebelumnya</button>
                    <button type="submit" id="submitBtn">Kirim</button>
            </div>
            </div>
        </form>
    </div>


</body>
</html>
<?php include 'footer.php'; ?>
