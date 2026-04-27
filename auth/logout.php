<?php
session_start(); // mulai session dulu 

// HAPUS SEMUA DATA SESSION
session_destroy(); // logout user

// ARAHKAN KEMBALI KE HALAMAN UTAMA
header("Location: ../index.php");
?>