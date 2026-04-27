<?php
session_start(); // mulai session

// CEK LOGIN DAN ROLE ADMIN
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php"); // kalau bukan admin, redirect
    exit;
}

include '../config/koneksi.php'; // koneksi ke database

/* AMBIL DATA HISTORI BERITA */
$histori = mysqli_query($conn, "
    SELECT berita.judul, berita.created_at, kategori.nama_kategori
    FROM berita
    LEFT JOIN kategori ON berita.kategori_id = kategori.id
    ORDER BY berita.created_at DESC
    LIMIT 10
"); // ambil 10 data terbaru
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Histori Berita</title>

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

/* TITLE */
.title {
    font-weight: 700;
}

/* BUTTON */
.btn {
    border-radius: 10px;
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
    <a href="kategori.php">Kategori</a>
    <a href="histori.php" class="active">Histori</a>

    <hr>

    <!-- LOGOUT -->
    <a href="../auth/logout.php" class="text-danger">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar d-flex justify-content-between align-items-center">

        <!-- INPUT SEARCH -->
        <input type="text" id="search" class="form-control w-50" placeholder="Cari histori berita...">

        <!-- USER LOGIN -->
        <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>
    </div>

    <!-- CARD -->
    <div class="card-box">

        <!-- HEADER -->
        <div class="mb-4">
            <h3 class="title mb-0"> Histori Berita</h3>
            <small class="text-muted">Riwayat berita yang telah dipublikasikan</small>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">

            <table class="table table-hover align-middle text-center">

                <!-- HEADER TABLE -->
                <thead>
                <tr>
                    <th width="5%">No</th>
                    <th class="text-start">Judul</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                </tr>
                </thead>

                <!-- ISI TABLE -->
                <tbody id="tabel-histori">

                <?php if ($histori && mysqli_num_rows($histori) > 0): ?>

                    <?php $no = 1; while ($row = mysqli_fetch_assoc($histori)) { ?>

                        <tr>
                            <!-- NOMOR -->
                            <td><?= $no++; ?></td>

                            <!-- JUDUL -->
                            <td class="text-start fw-semibold">
                                <?= htmlspecialchars($row['judul']); ?>
                            </td>

                            <!-- KATEGORI -->
                            <td>
                                <span class="badge bg-success">
                                    <?= htmlspecialchars($row['nama_kategori']); ?>
                                </span>
                            </td>

                            <!-- TANGGAL -->
                            <td>
                                <?= date('d M Y H:i', strtotime($row['created_at'])); ?>
                            </td>
                        </tr>

                    <?php } ?>

                <?php else: ?>

                    <!-- JIKA DATA KOSONG -->
                    <tr>
                        <td colspan="4" class="text-muted py-4">
                            Belum ada histori berita
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
// EVENT SEARCH SAAT KETIK
document.getElementById("search").addEventListener("keyup", function () {
    let keyword = this.value; // ambil input

    // KIRIM REQUEST KE FILE PENCARIAN
    fetch("cari_histori.php?cari=" + encodeURIComponent(keyword))
        .then(res => res.text())
        .then(data => {
            // TAMPILKAN HASIL KE TABLE
            document.getElementById("tabel-histori").innerHTML = data;
        });
});
</script>

</body>
</html>