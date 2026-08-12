<?php include 'db/db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/dokumentasi.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Dokumentasi Kegiatan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"/>  
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="js/dokumentasi.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

    <div class="dokumentasi-container">

        <div class="progress-container">
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        <div class="progress-text" id="progressText">0% selesai</div>
    </div>

        <h2 class="dokumentasi-form-title">Form Dokumentasi Kegiatan</h2>

        <form action="datadokumentasi.php" method="POST" enctype="multipart/form-data">
            <div class="dokumentasi-form-section">
            <div class="dokumentasi-section-title">Bagian I - Identitas</div>
       
            <div class="dokumentasi-form-group">
                <label class="dokumentasi-form-label">Unit/Fakultas</label>
                <select class="dokumentasi-form-select" name="unit_id" id="unitSelect" onchange="showOtherUnit(this)" required>
                    <option value="Fakultas Psikologi ">Fakultas Psikologi</option>
                    <option value="other">Other...</option>
                </select>
                <input type="text" id="otherUnit" name="other_unit" placeholder="Masukkan unit/fakultas lain" 
                    style="display: none; margin-top: 5px; margin-bottom: 15px; 
                    padding: 8px; width: 100%; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <div class="dokumentasi-form-group">
                <label class="dokumentasi-form-label">Jenis Kegiatan</label>
                <select class="dokumentasi-form-select" name="jenis_kegiatan_id" id="jenisKegiatanSelect" onchange="showOtherJenisKegiatan(this)" required>
                    <option value="" disabled selected>Pilih Jenis Kegiatan</option>
                    <option value="Rapat perencanaan ">Rapat perencanaan</option>
                    <option value="Rapat koordinasi">Rapat koordinasi</option>
                    <option value="Rapat monitoring">Rapat monitoring</option>
                    <option value="Rapat evaluasi">Rapat evaluasi</option>
                    <option value="Sosialisasi">Sosialisasi</option>
                    <option value="Sharing dan diskusi">Sharing dan diskusi</option>
                    <option value="other">Lainnya...</option>
                </select>
                <input type="text" id="otherJenisKegiatan" name="other_jenis_kegiatan" placeholder="Masukkan jenis kegiatan lain" 
                    style="display: none; margin-top: 5px; margin-bottom: 15px; padding: 8px; width: 100%; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <div class="dokumentasi-form-group">
                <label class="dokumentasi-form-label">Pelaksana Kegiatan</label>
                <select class="dokumentasi-form-select" name="pelaksana_kegiatan_id" 
                    id="pelaksanaKegiatanSelect" onchange="showOtherPelaksanaKegiatan(this)" required>
                    <option value="" disabled selected>Pilih Pelaksana</option>
                    <option value="Fakultas Psikologi ">Fakultas Psikologi</option>
                    <option value="other">Lainnya...</option>
                </select>
                <input type="text" id="otherPelaksanaKegiatan" name="other_pelaksana_kegiatan" placeholder="Masukkan pelaksana kegiatan lain" 
                    style="display: none; margin-top: 5px; margin-bottom: 15px; padding: 8px; width: 100%; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <div class="dokumentasi-form-group">
                <label class="dokumentasi-form-label">Peran dalam Kegiatan</label>
                <select class="dokumentasi-form-select" name="peran_kegiatan_id" required>
                    <option value="" disabled selected>Pilih Peran</option>
                    <option value="Pimpinan">Pimpinan</option>
                    <option value="Sekretaris/notulis">Sekretaris/Notulis</option>
                    <option value="Anggota/peserta">Anggota/Peserta</option>
                </select>
        
            </div>

            <div class="navigation-buttons first-section">
                <button type="button" class="nextBtn">Berikutnya</button>
            </div>      
        </div>
             <div class="dokumentasi-form-section">
            <h4 class="dokumentasi-section-title">Rincian Kegiatan</h4>

            <div class="dokumentasi-form-group">
                <label class="dokumentasi-form-label">Nama/Topik Kegiatan</label>
                <input type="text" class="dokumentasi-form-input" name="topik" required>
            </div>

            <div class="dokumentasi-form-row">
            <div class="dokumentasi-form-column">
                <label class="dokumentasi-form-label">Tanggal Pelaksanaan</label>
                <input type="date" name="tanggal" class="dokumentasi-form-input no-calendar" required>
            </div>

                <div class="dokumentasi-form-column">
                    <label class="dokumentasi-form-label">Waktu Pelaksanaan</label>
                    <input type="time" class="dokumentasi-form-input" name="waktu" required>
                </div>
            </div>

            <div class="dokumentasi-form-group">
                <label class="dokumentasi-form-label">Tempat Pelaksanaan</label>
                <select class="dokumentasi-form-input" name="tempat_id" id="tempatPelaksanaan" onchange="showOtherTempatPelaksanaan(this)" required>
                    <option value="" disabled selected>Pilih Pelaksana</option>
                    <option value="Ruang Rapat Psikologi">Ruang Rapat Psikologi</option>
                    <option value="Ruang Budaya">Ruang Budaya</option>
                    <option value="Auditorium">Auditorium</option>
                    <option value="Junction">Junction</option>
                    <option value="other">Other...</option>
                </select>
                <input type="text" id="otherTempatPelaksanaan" name="other_tempat_pelaksanaan" placeholder="Masukkan tempat pelaksana lainnya" 
                    style="display: none; margin-top: 5px; margin-bottom: 15px; padding: 8px; width: 100%; border: 1px solid #ccc; border-radius: 5px;">
                </div>

            <div class="navigation-buttons">
        <button type="button" class="prevBtn">Sebelumnya</button>
        <button type="button" class="nextBtn">Berikutnya</button>
    </div>

        </div>
            <div class="dokumentasi-form-section">
            <h4 class="dokumentasi-section-title">Dokumentasi Kegiatan</h4>

            <div class="dokumentasi-form-group">
                <label class="dokumentasi-form-label">Isi/Agenda Kegiatan</label>
                <textarea class="dokumentasi-form-input dokumentasi-textarea" name="agenda" required></textarea>
            </div>

            <div class="dokumentasi-form-group">
                <label class="dokumentasi-form-label">Kesimpulan Kegiatan</label>
                <textarea class="dokumentasi-form-input dokumentasi-textarea" name="kesimpulan" required></textarea>
            </div>

            <div class="dokumentasi-form-group">
                <label class="dokumentasi-form-label">Rencana Tindak Lanjut</label>
                <textarea class="dokumentasi-form-input dokumentasi-textarea" name="rencana_tindak_lanjut" required></textarea>
            </div>

            <div class="dokumentasi-form-group">
                <label class="dokumentasi-form-label">Dokumentasi Kegiatan (Upload File)</label>
                <input type="file" class="dokumentasi-form-input" name="dokumentasi" accept="image/*" required>
            </div>

            <div class="dokumentasi-form-group">
                <label class="dokumentasi-form-label">Notulensi Kegiatan (Jika Ada)</label>
                <input type="file" class="dokumentasi-form-input" name="notulensi" accept=".pdf,.doc,.docx" >
            </div>

            <div class="navigation-buttons">
        <button type="button" class="prevBtn">Sebelumnya</button>
        <button type="button" class="nextBtn">Berikutnya</button>
    </div>

        </div>            
            <div class="dokumentasi-form-section">
            <h4 class="dokumentasi-section-title">Evaluasi Kegiatan</h4>
            <div class="dokumentasi-question">
                <p>Kesesuaian agenda kegiatan dengan pembahasan kegiatan</p>
                <label>
                    <input type="radio" name="pertanyaan0" value="sangat_tidak_sesuai" required> Sangat tidak sesuai
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan0" value="tidak_sesuai" required> Tidak sesuai
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan0" value="sesuai" required> Sesuai
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan0" value="sangat_sesuai" required> Sangat sesuai
                </label>  
            </div>


            <div class="dokumentasi-question">
                <p>Kejelasan informasi dalam kegiatan</p>
                <label>
                    <input type="radio" name="pertanyaan1" value="sangat_tidak_jelas" required> Sangat tidak jelas
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan1" value="tidak_jelas" required> Tidak jelas
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan1" value="jelas" required> Jelas
                </label>  
                <br>
                <label>
                    <input type="radio" name="pertanyaan1" value="sangat_jelas" required> Sangat jelas
                </label>  
            </div>

            <div class="dokumentasi-form-group mb-3">
                <label class="dokumentasi-form-label">Saran/Masukan untuk Tim Pelaksana</label>
                <textarea class="dokumentasi-form-input dokumentasi-textarea" name="saran" required></textarea>
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