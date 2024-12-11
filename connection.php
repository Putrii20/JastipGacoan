<?php
$koneksi = mysqli_connect("localhost","root","","jastipgacoan");

// Cek koneksi
if (mysqli_connect_errno()) {
    echo "Koneksi ke database gagal: " . (mysqli_connect_errno());
}
?>
