<?php
include '../config/koneksi.php'; // koneksi ke database

// AMBIL KEYWORD PENCARIAN DARI URL
$cari = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';

// QUERY DATA KATEGORI BERDASARKAN NAMA
$query = mysqli_query($conn, "
    SELECT * FROM kategori
    WHERE nama_kategori LIKE '%$cari%'
    ORDER BY id DESC
");

// NOMOR URUT
$no = 1;

// CEK ADA DATA ATAU TIDAK
if(mysqli_num_rows($query) > 0){

    // LOOP DATA KATEGORI
    while($row = mysqli_fetch_assoc($query)){
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
            ✏ Edit
        </a>

        <!-- BUTTON HAPUS -->
        <a href="hapus_kategori.php?id=<?= (int)$row['id'] ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Yakin ingin menghapus kategori ini?')">
            🗑 Hapus
        </a>
    </td>
</tr>

<?php
    }

} else {

    // JIKA TIDAK ADA HASIL PENCARIAN
    echo "<tr><td colspan='3' class='text-muted py-4'>Kategori tidak ditemukan</td></tr>";
}
?>