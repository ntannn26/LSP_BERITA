<?php
include '../config/koneksi.php'; // koneksi ke database

// AMBIL ID DARI URL
$id = $_GET['id'];

// HAPUS DATA BERITA BERDASARKAN ID
mysqli_query($conn, "DELETE FROM berita WHERE id=$id");

// REDIRECT KEMBALI KE HALAMAN BERITA
header("Location: berita.php");
?>