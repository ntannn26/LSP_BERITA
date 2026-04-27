<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"> <!-- set karakter biar support huruf -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- biar responsive di HP -->
    <title>Register Sederhana</title>

    <style>
        /* STYLE BODY */
        body {
            font-family: Arial, sans-serif; /* font sederhana */
            background-color: #f4f4f4; /* warna background abu */
            display: flex; /* pakai flexbox */
            justify-content: center; /* posisi tengah horizontal */
            align-items: center; /* posisi tengah vertikal */
            height: 100vh; /* full tinggi layar */
            margin: 0;
        }

        /* BOX REGISTER */
        .register-box {
            width: 320px; /* lebar box */
            background: white;
            padding: 30px;
            border-radius: 15px; /* sudut melengkung */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* bayangan */
        }

        /* JUDUL */
        .register-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* INPUT WRAPPER */
        .input-box {
            margin-bottom: 15px;
        }

        /* INPUT */
        .input-box input {
            width: 100%; /* full lebar */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box; /* biar padding ga nambah ukuran */
        }

        /* BUTTON REGISTER */
        .btn-register {
            width: 100%;
            padding: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
        }

        /* HOVER BUTTON */
        .btn-register:hover {
            background-color: #333;
        }

        /* TEXT LOGIN */
        .login {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        /* LINK LOGIN */
        .login a {
            text-decoration: none;
        }
    </style>
</head>
<body>

    <!-- BOX UTAMA REGISTER -->
    <div class="register-box">

        <!-- FORM REGISTER -->
        <form method="POST" action="proses_register.php">
            <h2>Register</h2>

            <!-- INPUT USERNAME -->
            <div class="input-box">
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>

            <!-- INPUT PASSWORD -->
            <div class="input-box">
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>

            <!-- INPUT KONFIRMASI PASSWORD -->
            <!-- ini buat memastikan password sama -->
            <div class="input-box">
                <input type="password" name="konfirmasi" placeholder="Konfirmasi password" required>
            </div>

            <!-- BUTTON SUBMIT -->
            <button type="submit" class="btn-register">Daftar</button>
        </form>

        <!-- LINK KE LOGIN -->
        <div class="login">
            Sudah punya akun? <a href="login.php">Login</a>
        </div>
    </div>

</body>
</html>