<?php
session_start(); // mulai session

// cek apakah user sudah login dan role nya admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php"); // kalau bukan admin, tendang ke halaman utama
    exit;
}

include '../config/koneksi.php'; // koneksi ke database
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard</title>

<!-- pakai bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f5f7fb; /* warna background */
    font-family: 'Segoe UI', sans-serif;
}

/* SIDEBAR */
.sidebar {
    width: 230px;
    height: 100vh;
    position: fixed; /* biar tetap di samping */
    background: white;
    padding: 20px;
    border-right: 1px solid #eee;
}

.sidebar a {
    display: block;
    padding: 10px;
    margin: 8px 0;
    color: #555;
    text-decoration: none;
    border-radius: 8px;
}

/* efek hover menu */
.sidebar a:hover,
.sidebar a.active {
    background: #0d6efd;
    color: white;
}

/* MAIN */
.main {
    margin-left: 250px; /* kasih jarak dari sidebar */
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

/* TABLE */
.table thead {
    background: #0d6efd; /* warna header tabel */
    color: white;
}

.table td, .table th {
    vertical-align: middle;
}

/* IMAGE */
.img-thumb {
    width: 80px;
    height: 55px;
    object-fit: cover; /* biar gambar rapi */
    border-radius: 8px;
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

    <!-- menu navigasi -->
    <a href="dashboard.php">Dashboard</a>
    <a href="berita.php" class="active">Berita</a>
    <a href="kategori.php">Kategori</a>
    <a href="histori.php">Histori</a>

    <hr>

    <!-- tombol logout -->
    <a href="../auth/logout.php" class="text-danger">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar d-flex justify-content-between align-items-center">

        <!-- input pencarian -->
        <input type="text" id="searchNews" class="form-control w-50" placeholder="Cari berita...">

        <!-- tampilkan username -->
        <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>
    </div>

    <!-- CARD -->
    <div class="card-box">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="title mb-0"> Data Berita</h3>
                <small class="text-muted">Kelola semua artikel berita</small>
            </div>

            <div>
                <!-- tombol tambah berita -->
                <a href="tambah_berita.php" class="btn btn-primary btn-sm">
                     Tambah Berita
                </a>
            </div>

        </div>

        <!-- TABLE -->
        <div class="table-responsive">

            <table class="table table-hover align-middle text-center">

                <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Gambar</th>
                    <th class="text-start">Judul</th>
                    <th>Kategori</th>
                    <th width="20%">Aksi</th>
                </tr>
                </thead>

                <tbody id="tableNews">

                <?php
                $no = 1; // nomor urut

                // ambil data berita + kategori
                $query = mysqli_query($conn, "
                    SELECT berita.*, kategori.nama_kategori 
                    FROM berita 
                    JOIN kategori ON berita.kategori_id = kategori.id
                    ORDER BY berita.id DESC
                ");

                // cek apakah ada data
                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                ?>

                <tr>
                    <!-- nomor -->
                    <td><?= $no++ ?></td>

                    <!-- gambar -->
                    <td>
                        <img src="../gambar/<?= htmlspecialchars($row['gambar']) ?>" class="img-thumb">
                    </td>

                    <!-- judul -->
                    <td class="text-start fw-semibold">
                        <?= htmlspecialchars($row['judul']) ?>
                    </td>

                    <!-- kategori -->
                    <td>
                        <span class="badge bg-success">
                            <?= htmlspecialchars($row['nama_kategori']) ?>
                        </span>
                    </td>

                    <!-- tombol aksi -->
                    <td>
                        <!-- edit -->
                        <a href="edit_berita.php?id=<?= (int)$row['id'] ?>"
                           class="btn btn-warning btn-sm me-1">
                             Edit
                        </a>

                        <!-- hapus -->
                        <a href="hapus_berita.php?id=<?= (int)$row['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin ingin hapus berita ini?')">
                             Hapus
                        </a>
                    </td>
                </tr>

                <?php
                    }
                } else {
                    // kalau belum ada data
                    echo "
                    <tr>
                        <td colspan='5' class='text-muted py-4'>
                            Belum ada data berita
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
// fitur search (ajax sederhana)
document.getElementById("searchNews").addEventListener("keyup", function () {

    let keyword = this.value; // ambil input user

    // kirim ke file cari_berita.php
    fetch("cari_berita.php?cari=" + encodeURIComponent(keyword))
        .then(res => res.text())
        .then(data => {
            // tampilkan hasil ke tabel
            document.getElementById("tableNews").innerHTML = data;
        })
        .catch(err => console.log(err)); // kalau error tampil di console

});
</script>
</body>
</html>