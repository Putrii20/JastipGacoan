<?php
include('connection.php');
include('functions.php');

// Mendapatkan cart dari database
$menu = getCart();
$total = getTotalPrice();
$payment = getPayment();
$delivery = getDelivery();
$order = getOrder();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Ordering System</title>

    <link rel="stylesheet" href="style2.css">
</head>

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
        <section id="order">
            <form action="" method="post">

                <h2>Your Order</h2>
                <?php if ($menu != '404' && $total > 0) : ?>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="" required>
                    <label for="name">Phone:</label>
                    <input type="text" id="phone" name="phone" placeholder="" required>
                    <label for="name">Address:</label>
                    <input type="text" id="address" name="address" placeholder="" required>
                    <input type="hidden" name="order_id" value="<?= $order ?>">
                    <!-- Menampilkan tabel -->
                    <table id="cart-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Level</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menu as $item): ?>
                                <tr>
                                    <!-- Menampilkan gambar item dengan lebar dan tinggi yang telah ditentukan -->
                                    <td><img src="<?php echo htmlspecialchars($item[9]); ?>" alt="Item Image" style="width: 100px; height: 100px;"></td>

                                    <!-- Menampilkan nama item dan kategori dalam format 'Nama Item (Kategori)' -->
                                    <td><?php echo htmlspecialchars($item[7]); ?> (<?php echo htmlspecialchars($item[3]); ?>)</td>

                                    <!-- Menampilkan harga item dengan format mata uang (Rp.) -->
                                    <td>Rp. <?php echo htmlspecialchars($item[8]); ?></td>

                                    <!-- Menampilkan deskripsi item jika ada, jika tidak akan menampilkan '-' -->
                                    <td><?php echo htmlspecialchars($item[4] ?? '-'); ?></td>

                                    <!-- Tombol untuk menghapus item dari keranjang -->
                                    <td>
                                        <button style="background-color: red;" onclick="removeToCart('<?= $item[1] ?>', '<?= $item[2] ?>')">Batal</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>

                    <p>Total Food Price: Rp. <span id="total-price"><?= $total ?></span></p>

                    <!-- Menampilkan opsi pembayaran -->
                    <div id="payment-options">
                        <h3>Choose Payment Option</h3>
                        <select name="payment" id="payment">
                            <!-- Looping melalui array $payment untuk menampilkan setiap opsi pembayaran -->
                            <?php foreach ($payment as $option): ?>
                                <!-- Menampilkan opsi pembayaran dalam tag <option> -->
                                <option value="<?php echo htmlspecialchars($option[0]); ?>">
                                    <?php echo htmlspecialchars($option[1]); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Menampilkan opsi pengiriman -->
                    <div id="delivery-options">
                        <h3>Choose Delivery Option</h3>
                        <select name="delivery" id="delivery">
                            <!-- Looping melalui array $delivery untuk menampilkan setiap opsi pengiriman -->
                            <?php foreach ($delivery as $option): ?>
                                <!-- Menampilkan opsi pengiriman dalam tag <option> dengan harga jika tersedia -->
                                <option value="<?php echo htmlspecialchars($option[0]); ?>">
                                    <?php echo htmlspecialchars($option[1]); ?> (Rp. <?= $option[2] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <button type="submit" name="place">Place Order</button>
            </form>
        <?php else: ?>
            <p>Tidak ada menu yang ditambahkan</p>
        <?php endif ?>
        </section>

    </main>

    <footer>
        <p>© 2024 Food Ordering System</p>
    </footer>

    <script src="script4.js"></script>
</body>

</html>
<?php



// Mengecek jika ada parameter 'remove', 'order', dan 'menu' pada URL
if (isset($_GET['remove']) && isset($_GET['order']) && isset($_GET['menu'])) {
    // Memanggil fungsi removeCart untuk menghapus item dari keranjang berdasarkan order_id dan menu_id
    $removeCart = removeCart($_GET['order'], $_GET['menu']);

    // Mengecek apakah proses penghapusan berhasil atau gagal
    if ($removeCart == '404') {
        // Menampilkan pesan gagal menghapus item dari keranjang
        echo "<script>alertInfo('Gagal menghapus dari keranjang');</script>";
    } else {
        // Menampilkan pesan berhasil menghapus item dari keranjang
        echo "<script>alertInfo('Berhasil menghapus dari keranjang');</script>";
    }
}

// Mengecek jika form dengan parameter 'place' dikirimkan melalui metode POST
if (isset($_POST['place'])) {
    // Memanggil fungsi placeOrder untuk memproses pesanan
    $place = placeOrder($_POST);

    // Mengecek apakah pemesanan berhasil atau gagal
    if ($place == '404') {
        // Menampilkan pesan gagal melakukan pemesanan
        echo "<script>alertInfo('Gagal melakukan order');</script>";
    } else {
        // Menampilkan pesan berhasil melakukan pemesanan
        echo "<script>alertInfo('Berhasil melakukan order');</script>";
    }
}

?>