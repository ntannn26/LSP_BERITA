<?php
session_start(); // mulai session

// CEK APAKAH SUDAH LOGIN DAN ROLE ADMIN
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php"); // kalau bukan admin, tendang ke index
    exit;
}

include '../config/koneksi.php'; // koneksi ke database

// AMBIL DATA SEMUA USER (URUT TERBARU)
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

// HITUNG TOTAL USER
$total_user = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));

// HITUNG USER YANG LOGIN HARI INI
$user_aktif = mysqli_num_rows(mysqli_query($conn, "
    SELECT id FROM users 
    WHERE DATE(last_login) = CURDATE()
"));

// HITUNG PERSENTASE USER AKTIF
$persen = 0;
if($total_user > 0){
    $persen = round(($user_aktif / $total_user) * 100);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Admin Dashboard</title>

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

/* LINK DI SIDEBAR */
.sidebar a {
    display: block;
    padding: 10px;
    margin: 8px 0;
    color: #555;
    text-decoration: none;
    border-radius: 8px;
}

/* HOVER & ACTIVE */
.sidebar a:hover,
.sidebar a.active {
    background: #0d6efd;
    color: white;
}

/* KONTEN UTAMA */
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

/* CARD USER */
.user-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    transition: 0.3s;
}

/* EFEK HOVER */
.user-card:hover {
    transform: translateY(-5px);
}

/* FOTO USER */
.user-img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    margin-bottom: 10px;
}

/* PANEL KANAN */
.right-panel {
    background: white;
    border-radius: 15px;
    padding: 20px;
}

/* PROGRESS BULAT */
.progress-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin: auto;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h5> Admin</h5>
    <a href="#" class="active">Dashboard</a>
    <a href="berita.php">Berita</a>
    <a href="kategori.php">Kategori</a>
    <a href="histori.php">histori</a>
    <hr>
    <a href="../auth/logout.php" class="text-danger">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar d-flex justify-content-between align-items-center">

        <!-- INPUT SEARCH USER -->
        <input type="text" id="searchUser" class="form-control w-50" placeholder="Cari user...">

        <!-- NAMA USER LOGIN -->
        <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>
    </div>

    <div class="row">

        <!-- BAGIAN KIRI (LIST USER) -->
        <div class="col-md-9">

            <h4 class="mb-3">People</h4>

            <!-- CONTAINER USER -->
            <div class="row g-3" id="userContainer">

                <?php if($users && mysqli_num_rows($users) > 0): ?>
                    <?php while($user = mysqli_fetch_assoc($users)): ?>

                        <div class="col-md-4">
                            <div class="user-card shadow-sm">

                                <!-- FOTO RANDOM -->
                                <img src="https://i.pravatar.cc/150?u=<?= htmlspecialchars($user['username']); ?>" class="user-img">

                                <!-- USERNAME -->
                                <h6><?= htmlspecialchars($user['username'] ?? '-'); ?></h6>

                                <!-- ROLE -->
                                <small class="text-muted">
                                    <?= htmlspecialchars($user['role'] ?? '-'); ?>
                                </small>

                                <!-- CEK APAKAH USER DIBLOKIR -->
                                <?php if(!empty($user['banned_until']) && strtotime($user['banned_until']) > time()): ?>
                                    <span class="badge bg-danger mt-2">
                                        DIBLOKIR sampai <?= date('d M Y H:i', strtotime($user['banned_until'])); ?>
                                    </span>
                                <?php endif; ?>

                                <!-- BUTTON BAN -->
                                <div class="mt-3">
                                    <div class="btn-group w-100">

                                        <!-- BAN 1 JAM -->
                                        <a href="ban_user.php?id=<?= $user['id']; ?>&durasi=1h" class="btn btn-warning btn-sm">1H</a>

                                        <!-- BAN 24 JAM -->
                                        <a href="ban_user.php?id=<?= $user['id']; ?>&durasi=24h" class="btn btn-danger btn-sm">24H</a>

                                        <!-- BAN 7 HARI -->
                                        <a href="ban_user.php?id=<?= $user['id']; ?>&durasi=7d" class="btn btn-dark btn-sm">7D</a>

                                    </div>
                                </div>

                                <!-- LAST LOGIN -->
                                <div class="mt-1">
                                    <small class="text-muted">
                                        <?php
                                        if(!empty($user['last_login'])){
                                            echo "Last login: " . date('d M Y H:i', strtotime($user['last_login']));
                                        } else {
                                            echo "Belum login";
                                        }
                                        ?>
                                    </small>
                                </div>

                                <!-- PROGRESS RANDOM -->
                                <div class="mt-2">
                                    <small>Activity</small>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: <?= rand(40,100) ?>%"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-muted">Belum ada user</p>
                <?php endif; ?>

            </div>

        </div>

        <!-- BAGIAN KANAN (STATISTIK) -->
        <div class="col-md-3">
            <div class="right-panel shadow-sm">

                <h6 class="text-center">AKTIVITAS USER</h6>

                <!-- PERSENTASE AKTIF -->
                <div class="progress-circle mb-3"
                     style="background: conic-gradient(#0d6efd <?= $persen ?>%, #eee 0);">
                    <?= $persen ?>%
                </div>

                <hr>

                <h6>Detail</h6>

                <!-- TOTAL USER -->
                <div class="d-flex justify-content-between">
                    <span>Total User</span>
                    <strong><?= $total_user ?></strong>
                </div>

                <!-- USER AKTIF -->
                <div class="d-flex justify-content-between">
                    <span>Aktif Hari Ini</span>
                    <strong><?= $user_aktif ?></strong>
                </div>

                <!-- USER TIDAK AKTIF -->
                <div class="d-flex justify-content-between">
                    <span>Tidak Aktif</span>
                    <strong><?= $total_user - $user_aktif ?></strong>
                </div>

            </div>
        </div>

        <!-- BUTTON KEMBALI -->
        <div class="mt-3">
            <a href="../index.php" class="btn btn-outline-primary w-100">
                ⬅ Kembali ke Index
            </a>
        </div>
        
    </div>

</div>

<script>
// FITUR SEARCH USER TANPA RELOAD (AJAX)
document.getElementById("searchUser").addEventListener("keyup", function () {

    let keyword = this.value;

    fetch("cari_user.php?cari=" + encodeURIComponent(keyword))
        .then(res => res.text())
        .then(data => {
            document.getElementById("userContainer").innerHTML = data;
        })
        .catch(err => console.log(err));

});
</script>

</body>
</html>