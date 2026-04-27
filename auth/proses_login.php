<?php
session_start(); // mulai session untuk simpan data login
include '../config/koneksi.php'; // koneksi ke database

// AMBIL INPUT DARI FORM LOGIN
$username = mysqli_real_escape_string($conn, $_POST['username']); // amankan input username
$password = $_POST['password']; // password tidak di-escape karena akan dicek pakai hash

// CEK USER DI DATABASE BERDASARKAN USERNAME
$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
$data = mysqli_fetch_assoc($query); // ambil data user

// CEK USER ADA DAN PASSWORD BENAR
if ($data && password_verify($password, $data['password'])) {

    // CEK APAKAH AKUN SEDANG DI-BANNED
    if (!empty($data['banned_until']) && strtotime($data['banned_until']) > time()) {

        // format tanggal banned
        $sisa = date('d M Y H:i', strtotime($data['banned_until']));

        // tampilkan alert kalau diblokir
        echo "<script>
                alert('Akun Anda diblokir sampai $sisa');
                window.location='../index.php';
              </script>";
        exit; // hentikan proses
    }

    // SIMPAN DATA KE SESSION
    $_SESSION['login'] = true;
    $_SESSION['id'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];

    // UPDATE TERAKHIR LOGIN USER
    mysqli_query($conn, "
        UPDATE users 
        SET last_login = NOW() 
        WHERE id = ".$data['id']
    );

    // ARAHKAN KE DASHBOARD ADMIN
    header("Location: ../admin/dashboard.php");
    exit;

} else {
    // JIKA LOGIN GAGAL
    echo "<script>
            alert('Username atau password salah!');
            window.location='../index.php';
          </script>";
    exit;
}
?>