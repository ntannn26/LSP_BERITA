<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"> <!-- set encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- biar responsive -->
    <title>Login Sederhana</title>

    <style>
        /* STYLE BODY */
        body {
            font-family: Arial, sans-serif; /* font */
            background-color: #f4f4f4; /* background abu */
            display: flex; /* pakai flex */
            justify-content: center; /* tengah horizontal */
            align-items: center; /* tengah vertikal */
            height: 100vh; /* full layar */
            margin: 0;
        }

        /* BOX LOGIN */
        .login-box {
            width: 320px; /* lebar box */
            background: white;
            padding: 30px;
            border-radius: 15px; /* sudut melengkung */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* bayangan */
        }

        /* JUDUL */
        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* WRAPPER INPUT */
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

        /* BUTTON LOGIN */
        .btn-login {
            width: 100%;
            padding: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
        }

        /* HOVER BUTTON */
        .btn-login:hover {
            background-color: #333;
        }

        /* TEXT REGISTER */
        .register {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        /* LINK REGISTER */
        .register a {
            text-decoration: none;
        }
    </style>
</head>
<body>

    <!-- BOX UTAMA LOGIN -->
    <div class="login-box">

        <!-- FORM LOGIN -->
        <form method="POST" action="proses_login.php">
            <h2>Login</h2>

            <!-- INPUT USERNAME -->
            <div class="input-box">
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>

            <!-- INPUT PASSWORD -->
            <div class="input-box">
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>

            <!-- BUTTON SUBMIT -->
            <button type="submit" class="btn-login">Login</button>
        </form>

        <!-- LINK KE REGISTER -->
        <div class="register">
            Belum punya akun? <a href="register.php">Daftar</a>
        </div>
    </div>

</body>
</html>