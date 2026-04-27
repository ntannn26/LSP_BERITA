<?php
include '../config/koneksi.php'; // koneksi ke database

// AMBIL KEYWORD PENCARIAN DARI URL
$cari = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';

// QUERY DATA USER BERDASARKAN USERNAME
$query = mysqli_query($conn, "
    SELECT * FROM users 
    WHERE username LIKE '%$cari%' 
    ORDER BY id DESC
");

// CEK APAKAH ADA HASIL
if(mysqli_num_rows($query) > 0){

    // LOOP DATA USER
    while($user = mysqli_fetch_assoc($query)){
?>

<!-- CARD USER -->
<div class="col-md-4">
    <div class="user-card shadow-sm">

        <!-- FOTO USER (RANDOM DARI API) -->
        <img src="https://i.pravatar.cc/150?u=<?= $user['username']; ?>" class="user-img">

        <!-- USERNAME -->
        <h6><?= htmlspecialchars($user['username']); ?></h6>

        <!-- ROLE -->
        <small class="text-muted">
            <?= htmlspecialchars($user['role']); ?>
        </small>

        <!-- LAST LOGIN -->
        <div class="mt-1">
            <small class="text-muted">
                <?= !empty($user['last_login']) 
                    ? "Last login: " . date('d M Y H:i', strtotime($user['last_login']))
                    : "Belum login"; ?>
            </small>
        </div>

    </div>
</div>

<?php
    }
} else {

    // JIKA TIDAK ADA DATA
    echo "<p class='text-muted'>User tidak ditemukan</p>";
}
?>