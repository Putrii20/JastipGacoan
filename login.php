<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Jastip Gacoan</title>
    <link rel="stylesheet" href="login.css">
    <script>
        
    </script>
</head>
<body>

    <!-- Login -->
    <div class="login-container" >
        <div class="login-box">
            <b class="sl">Silahkan Login</b>
        </image>
            <form method="POST">
                <input type="text" placeholder="Masukkan Email atau Username" name="email" class="input-field" required><br>
                <input type="password" placeholder="Masukkan Password" name="password" class="input-field" required><br>
                <p class="y"><a href="#">Lupa Password?</a></p>
                <button type="submit" class="button-login" name="login">Login</button>
                <p class="y">Belum Punya Akun? <a href="#">Daftar</a></p>
            </form>
        </div>
    </div>

</body>
</html>

<?php 
            include 'connection.php';
            
            if (isset($_POST["login"]))
            {
                $email = $_POST["email"];
                $password = $_POST["password"];
        
                // Cek login untuk role user
                $ambil = $koneksi->query("SELECT * FROM users WHERE username='$email' AND password='$password' AND role='user'");
                $akunyangcocok = $ambil->num_rows;
        
                if ($akunyangcocok == 1) {
                    $akun = $ambil->fetch_assoc();
                    $_SESSION["users"] = $akun;
        
                    // Menampilkan pop-up menggunakan JavaScript setelah login sukses
                    echo "<script>
                        alert('Anda berhasil login');
                        window.location.href = 'user/index.php';
                    </script>";
                }
                // Cek login untuk role admin
                else {
                    $data = $koneksi->query("SELECT * FROM users WHERE username='$email' AND password='$password' AND role='admin'");
                    $cocok = $data->num_rows;
        
                    if ($cocok == 1) {
                        $akun01 = $data->fetch_assoc();
                        $_SESSION["admin"] = $akun01;
                        echo "<script>
                            alert('Anda berhasil login sebagai Admin');
                            window.location.href = 'index.php';
                        </script>";
                    } else {
                        echo "<script>
                            alert('Anda gagal login, periksa akun anda!');
                            window.location.href = 'login.php';
                        </script>";
                    }
                }
            }
        ?>

