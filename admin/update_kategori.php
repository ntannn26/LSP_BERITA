<?php
include '../config/koneksi.php'; // koneksi ke database

// AMBIL ID DARI URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0; // ubah ke integer biar aman

// VALIDASI ID
if ($id <= 0) {
    die("ID tidak valid"); // hentikan kalau id salah
}

// AMBIL DATA KATEGORI BERDASARKAN ID
$result = mysqli_query($conn, "SELECT * FROM kategori WHERE id=$id");
$d = mysqli_fetch_assoc($result); // ambil data jadi array

// CEK DATA ADA ATAU TIDAK
if (!$d) {
    die("Data tidak ditemukan"); // kalau tidak ada
}

// PROSES UPDATE SAAT FORM DISUBMIT
if (isset($_POST['update'])) {

    // AMBIL INPUT NAMA
    $nama = trim($_POST['nama']); // hilangkan spasi depan belakang
    $nama = mysqli_real_escape_string($conn, $nama); // amankan dari SQL injection

    // VALIDASI KOSONG
    if ($nama == "") {
        echo "<script>alert('Nama kategori tidak boleh kosong!');</script>";
    } else {

        // QUERY UPDATE DATA
        $query = mysqli_query($conn, "UPDATE kategori SET 
            nama_kategori='$nama' 
            WHERE id=$id
        ");

        // CEK BERHASIL ATAU TIDAK
        if ($query) {
            echo "<script>
                alert('Kategori berhasil diupdate!');
                window.location='kategori.php';
            </script>";
        } else {
            echo "<script>alert('Gagal update kategori!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Kategori</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* BACKGROUND */
        body {
            background: #f4f6f9;
        }

        /* CARD BOX */
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
    </style>
</head>

<body>

<div class="container mt-5">

    <!-- POSISI TENGAH -->
    <div class="col-md-5 mx-auto">

        <div class="card-box">

            <!-- JUDUL -->
            <h3 class="mb-4 title">✏️ Update Kategori</h3>

            <!-- FORM UPDATE -->
            <form method="POST">

                <!-- INPUT NAMA KATEGORI -->
                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control"
                           value="<?= htmlspecialchars($d['nama_kategori']) ?>" required>
                </div>

                <!-- BUTTON UPDATE -->
                <button type="submit" name="update" class="btn btn-primary w-100">
                    💾 Update
                </button>

                <!-- BUTTON KEMBALI -->
                <a href="kategori.php" class="btn btn-secondary w-100 mt-2">
                    ↩ Kembali
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>