<?php
include '../config/koneksi.php'; // koneksi ke database

// AMBIL ID DARI URL DAN UBAH JADI INTEGER (BIAR LEBIH AMAN)
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// AMBIL DATA BERITA BERDASARKAN ID
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM berita WHERE id=$id"));

// CEK DATA ADA ATAU TIDAK
if (!$data) {
    die("Data tidak ditemukan"); // kalau tidak ada, hentikan
}

// PROSES UPDATE SAAT FORM DISUBMIT
if (isset($_POST['update'])) {

    // AMBIL INPUT DARI FORM
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $kategori = $_POST['kategori'];

    // AMBIL DATA GAMBAR
    $gambar = $_FILES['gambar']['name'] ?? '';
    $tmp = $_FILES['gambar']['tmp_name'] ?? '';

    // CEK APAKAH USER UPLOAD GAMBAR BARU
    if (!empty($gambar)) {

        // RENAME FILE BIAR TIDAK BENTROK
        $newName = time() . "_" . $gambar;

        // PINDAHKAN FILE KE FOLDER
        move_uploaded_file($tmp, "../gambar/" . $newName);

        // UPDATE DATA TERMASUK GAMBAR
        mysqli_query($conn, "UPDATE berita SET 
            judul='$judul',
            isi='$isi',
            kategori_id='$kategori',
            gambar='$newName'
            WHERE id=$id
        ");

    } else {

        // UPDATE TANPA MENGUBAH GAMBAR
        mysqli_query($conn, "UPDATE berita SET 
            judul='$judul',
            isi='$isi',
            kategori_id='$kategori'
            WHERE id=$id
        ");
    }

    // NOTIFIKASI DAN REDIRECT
    echo "<script>
        alert('Berita berhasil diupdate!');
        window.location='berita.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Berita</title>

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

        /* PREVIEW GAMBAR */
        .preview-img {
            width: 140px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        /* TEXTAREA */
        textarea {
            resize: none; /* biar tidak bisa diubah ukurannya */
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <!-- POSISI FORM DI TENGAH -->
    <div class="col-md-7 mx-auto">

        <div class="card-box">

            <!-- JUDUL HALAMAN -->
            <h3 class="mb-4 title">✏️ Edit Berita</h3>

            <!-- FORM EDIT -->
            <form method="POST" enctype="multipart/form-data">

                <!-- INPUT JUDUL -->
                <div class="mb-3">
                    <label class="form-label">Judul Berita</label>
                    <input type="text" name="judul" class="form-control"
                           value="<?= htmlspecialchars($data['judul']) ?>" required>
                </div>

                <!-- INPUT ISI -->
                <div class="mb-3">
                    <label class="form-label">Isi Berita</label>
                    <textarea name="isi" rows="6" class="form-control" required><?= htmlspecialchars($data['isi']) ?></textarea>
                </div>

                <!-- PILIH KATEGORI -->
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>

                        <!-- LOOP DATA KATEGORI -->
                        <?php
                        $kat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                        while ($k = mysqli_fetch_assoc($kat)) {

                            // CEK KATEGORI YANG SEDANG DIPAKAI
                            $selected = ($k['id'] == $data['kategori_id']) ? "selected" : "";

                            echo "<option value='{$k['id']}' $selected>{$k['nama_kategori']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- TAMPILKAN GAMBAR LAMA -->
                <div class="mb-2">
                    <label class="form-label">Gambar Saat Ini</label><br>
                    <img src="../gambar/<?= $data['gambar'] ?>" class="preview-img">
                </div>

                <!-- INPUT GAMBAR BARU -->
                <div class="mb-3">
                    <label class="form-label">Ganti Gambar (opsional)</label>
                    <input type="file" name="gambar" class="form-control">
                </div>

                <!-- BUTTON UPDATE -->
                <button type="submit" name="update" class="btn btn-primary w-100">
                    💾 Update Berita
                </button>

                <!-- BUTTON KEMBALI -->
                <a href="berita.php" class="btn btn-secondary w-100 mt-2">
                    ↩ Kembali
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>