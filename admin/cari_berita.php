<?php
include '../config/koneksi.php'; // koneksi ke database

// AMBIL KEYWORD PENCARIAN DARI URL (AJAX)
$cari = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';

// QUERY DATA BERITA + JOIN KATEGORI
$query = mysqli_query($conn, "
    SELECT berita.*, kategori.nama_kategori 
    FROM berita 
    JOIN kategori ON berita.kategori_id = kategori.id
    WHERE berita.judul LIKE '%$cari%' 
       OR kategori.nama_kategori LIKE '%$cari%'  -- cari juga berdasarkan kategori
    ORDER BY berita.id DESC
");

// NOMOR URUT
$no = 1;

// CEK ADA DATA ATAU TIDAK
if(mysqli_num_rows($query) > 0){

    // LOOP DATA BERITA
    while($row = mysqli_fetch_assoc($query)){
?>

<tr>
    <!-- NOMOR -->
    <td><?= $no++ ?></td>

    <!-- GAMBAR BERITA -->
    <td>
        <img src="../gambar/<?= htmlspecialchars($row['gambar']) ?>" class="img-thumb">
    </td>

    <!-- JUDUL -->
    <td class="text-start fw-semibold">
        <?= htmlspecialchars($row['judul']) ?>
    </td>

    <!-- KATEGORI -->
    <td>
        <span class="badge bg-success">
            <?= htmlspecialchars($row['nama_kategori']) ?>
        </span>
    </td>

    <!-- AKSI -->
    <td>

        <!-- BUTTON EDIT -->
        <a href="edit_berita.php?id=<?= (int)$row['id'] ?>"
           class="btn btn-warning btn-sm me-1">
            ✏ Edit
        </a>

        <!-- BUTTON HAPUS -->
        <a href="hapus_berita.php?id=<?= (int)$row['id'] ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Yakin ingin hapus berita ini?')">
            🗑 Hapus
        </a>
    </td>
</tr>

<?php
    }

} else {

    // JIKA DATA TIDAK DITEMUKAN
    echo "<tr><td colspan='5' class='text-muted py-4'>Data tidak ditemukan</td></tr>";
}
?>