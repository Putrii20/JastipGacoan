<?php
include('connection.php');
include('functions.php');

// Mendapatkan history dari database
$history = getHistory();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food History</title>

    <link rel="stylesheet" href="style2.css">
</head>
<style>
    .bg-belum-lunas {
        background-color: #ff4c4c;
        /* Merah */
        color: #fff;
    }

    .bg-order {
        background-color: #ffeb3b;
        /* Kuning */
        color: #000;
    }

    .bg-selesai {
        background-color: #4caf50;
        /* Hijau */
        color: #fff;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    .action-link {
        text-decoration: none;
        color: #007bff;
        margin-right: 10px;
    }
</style>

<body>
    <header>
        <h1 id="title-jastip">Jastip Gacoan</h1>
        <nav>
            <ul>
                <li><a href="index.php">Menu</a></li>
                <li><a href="order.php">Order Now</a></li>
                <li><a href="history.php">History</a></li>
            </ul>
        </nav>
    </header>


    <main>
        <section id="history">
            <h2>Riwayat Pemesanan</h2>
            <?php if (!empty($history)) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Metode Pembayaran</th>
                            <th>Metode Pengiriman</th>
                            <th>Menu</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $order): ?>
                            <tr>
                                <!-- Menampilkan ID order dengan memanfaatkan htmlspecialchars untuk menghindari XSS -->
                                <td><?= htmlspecialchars($order['order_id']) ?></td>

                                <!-- Menampilkan nama order -->
                                <td><?= htmlspecialchars($order['order_name']) ?></td>
                                <td><?= htmlspecialchars($order['order_address']) ?></td>
                                <td><?= htmlspecialchars($order['order_phone']) ?></td>

                                <!-- Menampilkan total harga dengan format Rupiah -->
                                <td>Rp. <?= htmlspecialchars($order['total_price']) ?></td>

                                <!-- Menampilkan status order dan menambahkan kelas CSS berdasarkan status -->
                                <td class="<?php
                                            // Menentukan kelas CSS berdasarkan status order
                                            if ($order['status'] === 'belum lunas') {
                                                echo 'bg-belum-lunas'; // Kelas untuk status belum lunas
                                            } elseif ($order['status'] === 'order') {
                                                echo 'bg-order'; // Kelas untuk status order
                                            } elseif ($order['status'] === 'selesai') {
                                                echo 'bg-selesai'; // Kelas untuk status selesai
                                            }
                                            ?>">
                                    <!-- Menampilkan status order -->
                                    <?= htmlspecialchars($order['status']) ?>
                                </td>

                                <!-- Menampilkan nama metode pembayaran -->
                                <td><?= htmlspecialchars($order['payment_name']) ?></td>

                                <!-- Menampilkan nama pengiriman -->
                                <td><?= htmlspecialchars($order['delivery_name']) ?></td>

                                <!-- Menampilkan nama menu yang dipesan -->
                                <td><?= htmlspecialchars($order['menu_name']) ?></td>

                                <!-- Menampilkan deskripsi menu -->
                                <td><?= htmlspecialchars($order['description']) ?></td>

                                <!-- Menampilkan dua link aksi untuk menghapus atau menyelesaikan order -->
                                <td>
                                    <!-- Link untuk menghapus order, dengan parameter 'delete' dan 'order_id' -->
                                    <a href="?delete=true&order_id=<?= $order['order_id'] ?>" class="action-link" style="color: red;">Hapus</a>

                                    <!-- Link untuk menandai order sebagai selesai, dengan parameter 'complete' dan 'order_id' -->
                                    <a href="?complete=true&order_id=<?= $order['order_id'] ?>" class="action-link">Selesai</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            <?php else: ?>
                <p>Tidak ada riwayat pemesanan.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>© 2024 Food Ordering System</p>
    </footer>

    <script src="script4.js"></script>
</body>

</html>
<?php


// Mengecek apakah parameter 'complete' dan 'order_id' ada di query string
if (isset($_GET['complete']) && isset($_GET['order_id'])) {
    // Mendapatkan ID order dari parameter GET
    $order_id = $_GET['order_id'];

    // Memanggil fungsi completeOrder untuk menandai order sebagai selesai
    $complete = completeOrder($order_id);

    // Mengecek hasil dari fungsi completeOrder
    if ($complete != '404') {
        // Jika berhasil, tampilkan pesan berhasil menggunakan JavaScript
        echo "<script>alertHistory('Berhasil Selesai');</script>";
    } else {
        // Jika gagal, tampilkan pesan gagal menggunakan JavaScript
        echo "<script>alertHistory('Gagal Selesai');</script>";
    }
}

// Mengecek apakah parameter 'delete' dan 'order_id' ada di query string
if (isset($_GET['delete']) && isset($_GET['order_id'])) {
    // Mendapatkan ID order dari parameter GET
    $order_id = $_GET['order_id'];

    // Memanggil fungsi deleteOrder untuk menghapus order
    $delete = deleteOrder($order_id);

    // Debugging: Melihat hasil dari fungsi deleteOrder
    var_dump($delete);

    // Mengecek hasil dari fungsi deleteOrder
    if ($delete != '404') {
        // Jika berhasil, tampilkan pesan berhasil menggunakan JavaScript
        echo "<script>alertHistory('Berhasil Dihapus');</script>";
    } else {
        // Jika gagal, tampilkan pesan gagal menggunakan JavaScript
        echo "<script>alertHistory('Gagal Dihapus');</script>";
    }
}




?>