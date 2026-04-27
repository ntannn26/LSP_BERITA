<?php
session_start(); // mulai session

// CEK LOGIN DAN ROLE ADMIN
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php"); // kalau bukan admin, balik ke halaman utama
    exit;
}

include '../config/koneksi.php'; // koneksi database
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Data Kategori</title>

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* BACKGROUND */
body {
    background: #f5f7fb;
    font-family: 'Segoe UI', sans-serif;
}

/* SIDEBAR */
.sidebar {
    width: 230px;
    height: 100vh;
    position: fixed;
    background: white;
    padding: 20px;
    border-right: 1px solid #eee;
}

/* LINK SIDEBAR */
.sidebar a {
    display: block;
    padding: 10px;
    margin: 8px 0;
    color: #555;
    text-decoration: none;
    border-radius: 8px;
}

/* HOVER / ACTIVE */
.sidebar a:hover,
.sidebar a.active {
    background: #0d6efd;
    color: white;
}

/* MAIN CONTENT */
.main {
    margin-left: 250px;
    padding: 20px;
}

/* TOPBAR */
.topbar {
    background: white;
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
}

/* CARD */
.card-box {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

/* TABLE HEADER */
.table thead {
    background: #0d6efd;
    color: white;
}

/* TABLE ALIGN */
.table td, .table th {
    vertical-align: middle;
}

/* BUTTON */
.btn {
    border-radius: 10px;
}

/* TITLE */
.title {
    font-weight: 700;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h5>⚙ Admin</h5>

    <!-- MENU SIDEBAR -->
    <a href="dashboard.php">Dashboard</a>
    <a href="berita.php">Berita</a>
    <a href="kategori.php" class="active">Kategori</a>
    <a href="histori.php">Histori</a>

    <hr>

    <!-- LOGOUT -->
    <a href="../auth/logout.php" class="text-danger">Logout</a>
</div>

<!-- MAIN CONTENT -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar d-flex justify-content-between align-items-center">

        <!-- INPUT SEARCH -->
        <input type="text" id="searchKategori" class="form-control w-50" placeholder="Cari kategori...">

        <!-- NAMA USER LOGIN -->
        <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>
    </div>

    <!-- CARD UTAMA -->
    <div class="card-box">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="title mb-0"> Data Kategori</h3>
                <small class="text-muted">Kelola semua kategori berita</small>
            </div>

            <!-- BUTTON TAMBAH -->
            <a href="tambah_kategori.php" class="btn btn-success btn-sm px-3">
                 Tambah Kategori
            </a>

        </div>

        <!-- TABLE -->
        <div class="table-responsive">

            <table class="table table-hover align-middle text-center">

                <!-- HEADER TABLE -->
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th class="text-start">Nama Kategori</th>
                        <th width="25%">Aksi</th>
                    </tr>
                </thead>

                <!-- ISI TABLE -->
                <tbody id="tableKategori">

                <?php
                $no = 1;

                // AMBIL DATA KATEGORI
                $query = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id DESC");

                // CEK ADA DATA ATAU TIDAK
                if (mysqli_num_rows($query) > 0) {

                    // LOOP DATA
                    while ($row = mysqli_fetch_assoc($query)) {
                ?>

                    <tr>
                        <!-- NOMOR -->
                        <td><?= $no++ ?></td>

                        <!-- NAMA KATEGORI -->
                        <td class="text-start fw-semibold">
                            <?= htmlspecialchars($row['nama_kategori']) ?>
                        </td>

                        <!-- AKSI -->
                        <td>

                            <!-- BUTTON EDIT -->
                            <a href="update_kategori.php?id=<?= (int)$row['id'] ?>"
                               class="btn btn-warning btn-sm me-1">
                                 Edit
                            </a>

                            <!-- BUTTON HAPUS -->
                            <a href="hapus_kategori.php?id=<?= (int)$row['id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                 Hapus
                            </a>

                        </td>
                    </tr>

                <?php
                    }

                } else {
                    // KALAU DATA KOSONG
                    echo "
                    <tr>
                        <td colspan='3' class='text-muted py-4'>
                            Belum ada data kategori
                        </td>
                    </tr>";
                }
                ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
// EVENT SAAT KETIK DI SEARCH
document.getElementById("searchKategori").addEventListener("keyup", function () {

    let keyword = this.value; // ambil input

    // KIRIM REQUEST KE FILE PENCARIAN
    fetch("cari_kategori.php?cari=" + encodeURIComponent(keyword))
        .then(res => res.text()) // ambil hasil
        .then(data => {
            // TAMPILKAN HASIL KE TABLE
            document.getElementById("tableKategori").innerHTML = data;
        })
        .catch(err => console.log(err)); // kalau error

});
</script>
</body>
</html>