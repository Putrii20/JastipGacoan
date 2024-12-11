<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi | Jastip Gacoan</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <!-- Registrasi -->
    <div class="login-container">
        <div class="login-box">
            <b class="sl">Buat Akun Baru</b>
            <form method="POST" action="register.php">
                <input type="text" placeholder="Masukkan Username" name="username" class="input-field" required><br>
                <input type="password" placeholder="Masukkan Password" name="password" class="input-field" required><br>
                <input type="password" placeholder="Konfirmasi Password" name="confirm_password" class="input-field" required><br>
                <button type="submit" class="button-login" name="register">Daftar</button>
                <p class="y">Sudah Punya Akun? <a href="login.php">Login</a></p>
            </form>
        </div>
    </div>

</body>
</html>


<?php
include 'connection.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi password
    if ($password !== $confirm_password) {
        echo "<script>
            alert('Password dan konfirmasi password tidak cocok!');
            window.location.href = 'register.php';
        </script>";
        exit;
    }

    // Cek apakah username atau email sudah digunakan
    $cek = $koneksi->query("SELECT * FROM users WHERE username='$email'");
    if ($cek->num_rows > 0) {
        echo "<script>
            alert('Username atau Email sudah digunakan!');
            window.location.href = 'register.php';
        </script>";
        exit;
    }

    // Insert data ke database
    $password_hash = password_hash($password, PASSWORD_DEFAULT); // Hash password
    $role = 'user'; // Default role user

    $query = "INSERT INTO users (username, password, role) 
              VALUES ('$username','$password_hash', '$role')";
    
    if ($koneksi->query($query)) {
        echo "<script>
            alert('Registrasi berhasil! Silakan login.');
            window.location.href = 'login.php';
        </script>";
    } else {
        echo "<script>
            alert('Registrasi gagal, coba lagi!');
            window.location.href = 'register.php';
        </script>";
    }
}
?>
