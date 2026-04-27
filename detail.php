<?php
session_start(); // mulai session untuk cek login user
include 'config/koneksi.php'; // koneksi ke database

// VALIDASI ID DARI URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0; // ambil id dan ubah ke integer

if ($id <= 0) {
    echo "ID tidak valid!"; // kalau id kosong / salah
    exit; // hentikan program
}

// QUERY DETAIL BERITA (PAKAI PREPARED STATEMENT BIAR AMAN)
$stmt = mysqli_prepare($conn, "
    SELECT berita.*, kategori.nama_kategori 
    FROM berita 
    LEFT JOIN kategori ON berita.kategori_id = kategori.id
    WHERE berita.id = ?
");
mysqli_stmt_bind_param($stmt, "i", $id); // bind id ke query
mysqli_stmt_execute($stmt); // jalankan query
$result = mysqli_stmt_get_result($stmt); // ambil hasil
$data = mysqli_fetch_assoc($result); // ambil data jadi array

// CEK DATA ADA ATAU TIDAK
if (!$data) {
    echo "Berita tidak ditemukan!";
    exit;
}

// AMBIL BERITA TERKAIT (BERDASARKAN KATEGORI SAMA)
$kategori_id = $data['kategori_id'];
$related = mysqli_query($conn, "
    SELECT * FROM berita 
    WHERE kategori_id = '$kategori_id' 
    AND id != '$id'
    ORDER BY id DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <!-- JUDUL HALAMAN -->
    <title><?= htmlspecialchars($data['judul']); ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* BACKGROUND */
        body {
            background-color: #f1f3f6;
        }

        /* NAVBAR */
        .navbar {
            position: sticky; /* biar nempel di atas */
            top: 0;
            z-index: 999;
            background: linear-gradient(135deg, #0f172a, #293446);
        }

        /* BATAS LEBAR KONTEN */
        .container-fluid {
            max-width: 1400px;
        }

        /* KONTEN UTAMA */
        .content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            min-height: 80vh;
        }

        /* GAMBAR BERITA */
        .news-img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* TEXT KATEGORI */
        .kategori {
            font-size: 13px;
            font-weight: bold;
            color: red;
        }

        /* JUDUL */
        .judul {
            font-weight: bold;
            line-height: 1.3;
        }

        /* ISI BERITA */
        .isi {
            text-align: justify;
            line-height: 1.8;
        }

        /* SIDEBAR */
        .sidebar {
            position: sticky;
            top: 80px;
        }

        /* CARD BERITA TERKAIT */
        .card {
            border: none;
            border-radius: 10px;
            background: #e5e7eb;
        }

        .card:hover {
            background: #d1d5db;
            cursor: pointer;
        }

        /* FOOTER */
        .footer-custom {
            background: #f4f4f4;
            border-top: 5px solid #111827;
            font-size: 14px;
            margin-top: 50px;
        }

        .brand { font-weight: bold; }
        .slogan { color: red; font-size: 13px; }
        .desc { font-size: 13px; color: #555; }

        .footer-list {
            list-style: none;
            padding: 0;
        }

        .footer-list li {
            margin-bottom: 6px;
            color: #444;
            cursor: pointer;
        }

        .footer-list li:hover {
            color: red;
        }

        /* ICON SOSIAL */
        .social-icons span {
            display: inline-block;
            width: 32px;
            height: 32px;
            line-height: 32px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 50%;
            margin-right: 5px;
            font-size: 12px;
            cursor: pointer;
        }

        .social-icons span:hover {
            background: red;
            color: white;
        }

        .footer-bottom {
            background: #111827;
            color: white;
            padding: 15px 0;
            font-size: 13px;
        }

        .footer-bottom a {
            color: #ccc;
            text-decoration: none;
            margin-right: 10px;
        }

        .footer-bottom a:hover {
            color: red;
        }

        /* MENU LIST */
        .list-group-item {
            border: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.2s;
        }

        .list-group-item:hover {
            background-color: #f1f3f6;
            padding-left: 10px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark shadow">
    <div class="container-fluid px-3">

        <!-- LOGO -->
        <a class="navbar-brand" href="index.php">NewsApp</a>

        <div class="d-flex align-items-center">

            <!-- CEK LOGIN -->
            <?php if (isset($_SESSION['login'])) { ?>
                <span class="text-white me-3">
                    <?= htmlspecialchars($_SESSION['username']); ?>
                </span>
            <?php } else { ?>
                <!-- JIKA BELUM LOGIN -->
                <a href="auth/login.php" class="btn btn-outline-light me-2">Login</a>
                <a href="auth/register.php" class="btn btn-warning me-2">Daftar</a>
            <?php } ?>

            <!-- BUTTON MENU -->
            <button class="btn btn-light" data-bs-toggle="offcanvas" data-bs-target="#menuSamping">
                ☰
            </button>

        </div>
    </div>
</nav>

<!-- OFFCANVAS MENU -->
<div class="offcanvas offcanvas-end" id="menuSamping">
    <div class="offcanvas-header border-bottom">
        <h5 class="fw-bold">Menu</h5>
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column justify-content-between">

        <!-- BAGIAN ATAS -->
        <div>
            <?php if (isset($_SESSION['login'])) { ?>

                <!-- PROFILE USER -->
                <div class="text-center mb-4">
                    <img src="https://i.pravatar.cc/100" class="rounded-circle mb-2" width="80">
                    <h6 class="mb-0"><?= $_SESSION['username']; ?></h6>
                    <small class="text-muted"><?= $_SESSION['role'] ?? 'user'; ?></small>
                </div>

                <!-- MENU LIST -->
                <div class="list-group">
                    <a href="index.php" class="list-group-item list-group-item-action">Home</a>

                    <hr class="my-1">

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
                        <a href="admin/dashboard.php" class="list-group-item list-group-item-action">Dashboard</a>
                        <hr class="my-1">
                    <?php } ?>

                    <a href="#" class="list-group-item list-group-item-action">Berita</a>

                    <hr class="my-1">

                    <!-- KATEGORI -->
                    <div class="list-group-item"><strong>Kategori</strong></div>

                    <?php
                    $kategori_menu = mysqli_query($conn, "SELECT * FROM kategori");
                    while ($menu = mysqli_fetch_assoc($kategori_menu)) {
                    ?>
                        <a href="#kategori-<?= $menu['id']; ?>" class="list-group-item list-group-item-action">
                            <?= $menu['nama_kategori']; ?>
                        </a>
                    <?php } ?>
                </div>

            <?php } else { ?>

                <!-- BELUM LOGIN -->
                <div class="text-center">
                    <p>Silakan login dulu</p>
                    <a href="auth/login.php" class="btn btn-dark w-100 mb-2">Login</a>
                    <a href="auth/register.php" class="btn btn-warning w-100">Daftar</a>
                </div>

            <?php } ?>
        </div>

        <!-- LOGOUT -->
        <?php if (isset($_SESSION['login'])) { ?>
            <div>
                <a href="auth/logout.php" class="btn btn-danger w-100">Logout</a>
            </div>
        <?php } ?>

    </div>
</div>

<!-- KONTEN UTAMA -->
<div class="container-fluid px-4 mt-4">
    <div class="row g-4">

        <!-- BAGIAN KIRI (ISI BERITA) -->
        <div class="col-lg-9 col-md-8">
            <div class="content shadow">

                <!-- KATEGORI -->
                <span class="kategori">
                    <?= htmlspecialchars($data['nama_kategori'] ?? 'Umum'); ?>
                </span>

                <!-- JUDUL -->
                <h1 class="judul mt-2">
                    <?= htmlspecialchars($data['judul']); ?>
                </h1>

                <hr>

                <!-- GAMBAR (JIKA ADA) -->
                <?php if (!empty($data['gambar']) && file_exists("gambar/" . $data['gambar'])) { ?>
                    <img src="gambar/<?= htmlspecialchars($data['gambar']); ?>" class="news-img mb-3">
                <?php } ?>

                <!-- ISI BERITA -->
                <div class="isi mt-3">
                    <?= nl2br(htmlspecialchars($data['isi'])); ?>
                </div>

                <!-- TOMBOL KEMBALI -->
                <a href="index.php" class="btn btn-secondary mt-4">
                    ← Kembali
                </a>

            </div>
        </div>

        <!-- SIDEBAR KANAN -->
        <div class="col-lg-3 col-md-4 sidebar">
            <h5 class="fw-bold mb-3">Berita Terkait</h5>

            <!-- LOOP BERITA TERKAIT -->
            <?php while ($r = mysqli_fetch_assoc($related)) { ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold">
                            <?= htmlspecialchars($r['judul']); ?>
                        </h6>

                        <a href="detail.php?id=<?= $r['id']; ?>" class="text-danger text-decoration-none">
                            Baca →
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
</div>

<!-- FOOTER -->
<footer class="footer-custom">

    <div class="container py-4">
        <div class="row">

            <!-- BRAND -->
            <div class="col-md-6 mb-3">
                <h3 class="brand">NewsApp</h3>
                <p class="slogan">Stay Update, Stay Ahead</p>

                <h6 class="mt-4">About Us</h6>
                <p class="desc">
                    NewsApp adalah aplikasi portal berita yang menyajikan informasi terkini,
                    cepat, dan terpercaya dari berbagai kategori dalam satu platform.
                </p>
            </div>

            <!-- COMPANY -->
            <div class="col-md-2 mb-3">
                <h6>Company</h6>
                <ul class="footer-list">
                    <li>Who We Are</li>
                    <li>Our Services</li>
                    <li>Our Clients</li>
                    <li>Pricing</li>
                    <li>Contact Us</li>
                </ul>
            </div>

            <!-- CONTACT -->
            <div class="col-md-4 mb-3">
                <h6>Contact us</h6>

                <p><strong>Telepon :</strong><br> +62 812-3456-7890</p>
                <p><strong>Email :</strong><br> newsapp@email.com</p>
                <p><strong>Alamat :</strong><br> Indonesia</p>

                <div class="social-icons mt-3">
                    <span>F</span>
                    <span>T</span>
                    <span>I</span>
                    <span>W</span>
                    <span>G</span>
                </div>

                <p class="mt-2 fw-bold">Follow Us</p>
            </div>

        </div>
    </div>

    <!-- FOOTER BAWAH -->
    <div class="footer-bottom">
        <div class="container d-flex justify-content-between flex-wrap">
            <div>
                <a href="#">Privacy Policy</a>
                <a href="#">Our History</a>
                <a href="#">What We Do</a>
            </div>

            <div>
                © 2026 NewsApp — All Rights Reserved
            </div>
        </div>
    </div>

</footer>

<!-- SCRIPT BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>