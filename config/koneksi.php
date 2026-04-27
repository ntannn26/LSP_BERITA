<?php
// BUAT KONEKSI KE DATABASE
$conn = new mysqli("localhost","root","","db_berita");

// CEK KONEKSI BERHASIL ATAU TIDAK
if ($conn->connect_error) {
    // kalau gagal, tampilkan pesan error
    die("Koneksi gagal: " . $conn->connect_error);
}

// kalau tidak masuk ke if, berarti koneksi berhasil
?>