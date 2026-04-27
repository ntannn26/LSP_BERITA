<?php
session_start(); // mulai session 
include '../config/koneksi.php'; // koneksi ke database

// PROSES SIMPAN DATA SAAT FORM DISUBMIT
if (isset($_POST['simpan'])) {

    // AMBIL INPUT DARI FORM
    $judul = $_POST['judul'] ?? '';
    $isi = $_POST['isi'] ?? '';
    $kategori = $_POST['kategori'] ?? '';

    // VALIDASI INPUT WAJIB
    if (empty($judul) || empty($isi) || empty($kategori)) {
        echo "<script>alert('Semua field wajib diisi!');</script>";
    } else {

        // AMBIL DATA GAMBAR
        $gambar = $_FILES['gambar']['name'] ?? '';
        $tmp = $_FILES['gambar']['tmp_name'] ?? '';

        // CEK ADA GAMBAR ATAU TIDAK
        if (!empty($gambar)) {
            // pindahkan file ke folder gambar
            move_uploaded_file($tmp, "../gambar/" . $gambar);
        } else {
            // kalau tidak upload, pakai default
            $gambar = "default.jpg";
        }

        // SIMPAN DATA KE DATABASE
        mysqli_query($conn, "INSERT INTO berita 
        (judul, isi, kategori_id, gambar, tanggal)
        VALUES 
        ('$judul', '$isi', '$kategori', '$gambar', NOW())");

        // NOTIFIKASI DAN REDIRECT
        echo "<script>alert('Berita berhasil ditambahkan!'); window.location='berita.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Berita</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* BACKGROUND */
        body {
            background: #f4f6f9;
        }

        /* BOX FORM */
        .card-box {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        /* JUDUL */
        .title {
            font-weight: bold;
        }

        /* TEXTAREA */
        textarea {
            resize: none; /* biar tidak bisa diresize */
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <!-- POSISI FORM DI TENGAH -->
    <div class="col-md-7 mx-auto">

        <div class="card-box">

            <!-- JUDUL HALAMAN -->
            <h3 class="mb-4 title"> Tambah Berita</h3>

            <!-- FORM INPUT -->
            <form method="POST" enctype="multipart/form-data">

                <!-- INPUT JUDUL -->
                <div class="mb-3">
                    <label class="form-label">Judul Berita</label>
                    <input type="text" name="judul" class="form-control" placeholder="Masukkan judul berita">
                </div>

                <!-- INPUT ISI -->
                <div class="mb-3">
                    <label class="form-label">Isi Berita</label>
                    <textarea name="isi" rows="6" class="form-control" placeholder="Tulis isi berita..."></textarea>
                </div>

                <!-- PILIH KATEGORI -->
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">-- Pilih Kategori --</option>

                        <!-- LOOP DATA KATEGORI -->
                        <?php
                        $kat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                        while ($k = mysqli_fetch_assoc($kat)) {
                            echo "<option value='{$k['id']}'>{$k['nama_kategori']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- INPUT GAMBAR -->
                <div class="mb-3">
                    <label class="form-label">Gambar Berita</label>
                    <input type="file" name="gambar" class="form-control">
                </div>

                <!-- BUTTON SIMPAN -->
                <button type="submit" name="simpan" class="btn btn-success w-100">
                     Simpan Berita
                </button>

                <!-- BUTTON KEMBALI -->
                <a href="berita.php" class="btn btn-secondary w-100 mt-2">
                     Kembali
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>