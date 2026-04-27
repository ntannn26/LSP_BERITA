<?php
include '../config/koneksi.php'; // koneksi ke database

// CEK APAKAH FORM DISUBMIT
if (isset($_POST['simpan'])) {

    // AMBIL INPUT NAMA KATEGORI
    $nama = $_POST['nama'];

    // SIMPAN KE DATABASE
    mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");

    // TAMPILKAN ALERT DAN REDIRECT
    echo "<script>
        alert('Kategori berhasil ditambahkan!');
        window.location='kategori.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kategori</title>

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
    </style>
</head>

<body>

<div class="container mt-5">

    <!-- POSISI FORM DI TENGAH -->
    <div class="col-md-5 mx-auto">

        <div class="card-box">

            <!-- JUDUL HALAMAN -->
            <h3 class="mb-4 title"> Tambah Kategori</h3>

            <!-- FORM INPUT -->
            <form method="POST">

                <!-- INPUT NAMA KATEGORI -->
                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama kategori" required>
                </div>

                <!-- BUTTON SIMPAN -->
                <button type="submit" name="simpan" class="btn btn-success w-100">
                     Simpan Kategori
                </button>

                <!-- BUTTON KEMBALI -->
                <a href="kategori.php" class="btn btn-secondary w-100 mt-2">
                     Kembali
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>