<?php
include '../config/koneksi.php'; // koneksi ke database

// AMBIL KEYWORD DARI URL (UNTUK SEARCH AJAX)
$cari = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';

// QUERY DATA HISTORI BERITA + JOIN KATEGORI
$query = mysqli_query($conn, "
    SELECT berita.judul, berita.created_at, kategori.nama_kategori
    FROM berita
    LEFT JOIN kategori ON berita.kategori_id = kategori.id
    WHERE berita.judul LIKE '%$cari%'  -- filter berdasarkan judul
    ORDER BY berita.created_at DESC     -- urut dari terbaru
    LIMIT 5                             -- batasi hanya 5 data
");

// NOMOR URUT
$no = 1;

// CEK ADA DATA ATAU TIDAK
if(mysqli_num_rows($query) > 0){

    // LOOP DATA DAN TAMPILKAN KE TABLE
    while($row = mysqli_fetch_assoc($query)){

        echo "<tr>
                <td>".$no++."</td> <!-- nomor -->

                <td>".htmlspecialchars($row['judul'])."</td> <!-- judul -->

                <td>".htmlspecialchars($row['nama_kategori'])."</td> <!-- kategori -->

                <td>".date('d M Y H:i', strtotime($row['created_at']))."</td> <!-- tanggal -->
              </tr>";
    }

}else{

    // JIKA DATA TIDAK DITEMUKAN
    echo "<tr>
            <td colspan='4' class='text-center text-muted'>Data tidak ditemukan</td>
          </tr>";
}
?>