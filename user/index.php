<?php
include('connection.php');
include('functions.php');

// Ambil menu dan kategori
$menu = getMenu();
$category = getCategory();

// Cek apakah ada parameter 'item' dan 'desc' di URL
if (isset($_GET['item']) && isset($_GET['desc'])) {
    // Sanitasi input sebelum menambahkannya ke keranjang
    $item = mysqli_real_escape_string($conn, $_GET['item']);
    $desc = mysqli_real_escape_string($conn, $_GET['desc']);

    // Tambahkan item ke keranjang
    addToCart($item, $desc);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Ordering System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arima:wght@100..700&family=Rowdies:wght@300;400;700&family=Tiny5&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tiny5&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arima:wght@100..700&family=Tiny5&display=swap" rel="stylesheet">

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
        <section id="menu">
            <h2>Our Menu</h2>

            <?php foreach ($category as $cat): ?>
                <!-- Menampilkan kategori menu dengan menggunakan nama kategori sebagai header -->
                <div class="menu-category">
                    <h3 id="<?= strtolower($cat['name']) ?>"><?= $cat['name'] ?></h3>

                    <?php foreach ($menu as $item): ?>
                        <!-- Mengecek apakah item menu termasuk dalam kategori yang sedang diproses -->
                        <?php if ($item['category_id'] == $cat['id']): ?>
                            <div class="menu-item">
                                <!-- Menampilkan gambar item dan nama item beserta harga -->
                                <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>">
                                <p><?= $item['name'] ?> - Rp. <?= $item['price'] ?></p>

                                <?php
                                // Membuat ID unik untuk dropdown level dengan mengubah nama item menjadi huruf kecil dan mengganti spasi dengan tanda hubung
                                $levelSelectId = strtolower(str_replace(' ', '-', $item['name'])) . '-level';
                                ?>

                                <?php if ($item['is_level'] == 1): ?>
                                    <!-- Jika item memiliki level (is_level == 1), tampilkan dropdown untuk memilih level -->
                                    <select class="level-select" id="<?= $levelSelectId ?>">
                                        <!-- Menampilkan opsi level dari 1 hingga 8 -->
                                        <?php for ($i = 1; $i <= 8; $i++): ?>
                                            <option value="<?= $i ?>">lv <?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                <?php endif; ?>

                                <!-- Menampilkan tombol untuk menambahkan item ke keranjang -->
                                <?php if ($item['is_level'] == 1): ?>
                                    <!-- Jika item memiliki level, kirimkan ID item dan ID dropdown level saat tombol ditekan -->
                                    <button onclick="addToCart('<?= $item['id'] ?>', '<?= $levelSelectId ?>')">Add to Cart</button>
                                <?php else: ?>
                                    <!-- Jika item tidak memiliki level, kirimkan ID item saja -->
                                    <button onclick="addToCart('<?= $item['id'] ?>', null)">Add to Cart</button>
                                <?php endif; ?>

                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>


        </section>

    </main>

    <footer>
        <p>© 2024 Food Ordering System</p>
    </footer>



    <script src="./script4.js"></script>
</body>

</html>