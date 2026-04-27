<?php
include '../config/koneksi.php'; // koneksi ke database

// AMBIL ID DARI URL
$id = $_GET['id'];

// HAPUS DATA KATEGORI BERDASARKAN ID
mysqli_query($conn, "DELETE FROM kategori WHERE id='$id'");

// REDIRECT KEMBALI KE HALAMAN KATEGORI
header("Location: kategori.php");
?>