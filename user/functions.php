<?php
include('connection.php');

function getMenu()
{
    global $conn; // Menggunakan koneksi database global
    $sql = "SELECT * FROM menu;"; // Query untuk mengambil semua data dari tabel menu
    $result = $conn->query($sql); // Menjalankan query
    if ($result === false) {
        die("Error executing query: " . $conn->error); // Menampilkan error jika query gagal
    }
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC); // Mengambil hasil query dalam bentuk array asosiatif
    return $rows; // Mengembalikan hasil query
}


function getCategory()
{
    global $conn; // Menggunakan koneksi database global
    $sql = "SELECT * FROM category;"; // Query untuk mengambil semua data dari tabel category
    $result = $conn->query($sql); // Menjalankan query
    if ($result === false) {
        die("Error executing query: " . $conn->error); // Menampilkan error jika query gagal
    }
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC); // Mengambil hasil query dalam bentuk array asosiatif
    return $rows; // Mengembalikan hasil query
}


function addToCart($item_id, $desc)
{
    global $conn;

    // Mengecek apakah ada order dengan status 'belum lunas'. Jika tidak ada, buat order baru
    $count_query = "SELECT * FROM orders WHERE status = 'belum lunas' ORDER BY id ASC LIMIT 1";
    $result = mysqli_query($conn, $count_query);
    if (mysqli_num_rows($result) == 0) {
        // Membuat order baru jika semua sudah lunas
        $query = "INSERT INTO orders (status) VALUES ('belum lunas')";
        $result = mysqli_query($conn, $query);
    }

    // Mengambil order yang statusnya 'belum lunas'
    $count_query = "SELECT * FROM orders WHERE status = 'belum lunas' ORDER BY id ASC LIMIT 1";
    $result = mysqli_query($conn, $count_query);
    $row = mysqli_fetch_assoc($result);
    $order_id = intval($row['id']);
    $menu_id = intval($item_id);

    // Mengecek apakah ada deskripsi (level) untuk item yang ditambahkan ke keranjang
    if ($desc != '0') {
        $descCheck = 'Level' . ' ' . $desc;
        $count_query = "SELECT * FROM cart where order_id = '$order_id' and menu_id = '$menu_id' and description = '$descCheck'";
    } else {
        $count_query = "SELECT * FROM cart where order_id = '$order_id' and menu_id = '$menu_id' and description is null";
    }

    $result = mysqli_query($conn, $count_query);

    // Jika item sudah ada dalam keranjang, update jumlahnya
    if (mysqli_num_rows($result) > 0) {
        $update_query = "UPDATE cart set total = (total+1) where order_id = '$order_id' and menu_id = '$menu_id'";
        mysqli_query($conn, $update_query);
    } else {
        // Jika item belum ada dalam keranjang, tambahkan ke keranjang
        if ($desc != '0') {
            $desc = 'Level' . ' ' . $desc;
            $query = "INSERT INTO cart (`order_id`, `menu_id`, `total`, `description`) VALUES ($order_id, $menu_id, 1, '$desc')";
        } else {
            $query = "INSERT INTO cart (`order_id`, `menu_id`, `total`) VALUES ($order_id, $menu_id, 1)";
        }
        $result = mysqli_query($conn, $query);
    }
}

function getCart()
{
    global $conn;

    // Mengecek apakah ada order dengan status 'belum lunas'. Jika tidak ada, kembalikan '404'
    $count_query = "SELECT * FROM orders WHERE status = 'belum lunas' ORDER BY id ASC LIMIT 1";
    $result = mysqli_query($conn, $count_query);
    if (mysqli_num_rows($result) == 0) {
        return '404'; // Jika tidak ada order, kembalikan '404'
    }

    // Mengambil order yang statusnya 'belum lunas'
    $row = mysqli_fetch_assoc($result);
    $order_id = $row['id'];

    // Mengambil data cart dan menu berdasarkan order_id
    $query = "SELECT * FROM cart c, menu m WHERE order_id = '$order_id' and c.menu_id = m.id";
    $result = mysqli_query($conn, $query);

    // Mengambil semua hasil query dan mengembalikannya
    $row = mysqli_fetch_all($result);
    return $row;
}
function getPayment()
{
    global $conn;
    $query = "SELECT * FROM payment"; // Query untuk mengambil semua data pembayaran
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_all($result); // Mengambil hasil query dalam bentuk array
    return $row; // Mengembalikan hasil query
}

function getDelivery()
{
    global $conn;
    $query = "SELECT * FROM delivery"; // Query untuk mengambil semua data pengiriman
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_all($result); // Mengambil hasil query dalam bentuk array
    return $row; // Mengembalikan hasil query
}

function getTotalPrice()
{
    global $conn;

    // Mengecek apakah ada order dengan status 'belum lunas'. Jika tidak ada, kembalikan '404'
    $count_query = "SELECT * FROM orders WHERE status = 'belum lunas' ORDER BY id ASC LIMIT 1";
    $result = mysqli_query($conn, $count_query);
    if (mysqli_num_rows($result) == 0) {
        return '404'; // Jika tidak ada order, kembalikan '404'
    }

    // Mengambil order yang statusnya 'belum lunas'
    $row = mysqli_fetch_assoc($result);
    $order_id = $row['id'];

    // Menghitung total harga berdasarkan item yang ada dalam keranjang
    $count_query = "SELECT SUM(m.price * c.total) AS total_price
                    FROM cart c
                    JOIN menu m ON c.menu_id = m.id
                    WHERE c.order_id = $order_id";
    $result = mysqli_query($conn, $count_query);

    // Cek jika query berhasil dijalankan
    if ($result) {
        // Mengambil total harga dari hasil query
        $row = mysqli_fetch_assoc($result);
        return $row['total_price']; // Mengembalikan total harga
    } else {
        // Jika query gagal
        echo "Error: " . mysqli_error($conn);
        return 0; // Kembalikan 0 jika query gagal
    }
}
function removeCart($order_id, $menu_id)
{
    global $conn;

    // Mengecek apakah item ada dalam cart untuk order_id dan menu_id yang diberikan
    $count_query = "SELECT * FROM cart WHERE order_id = $order_id and menu_id = $menu_id";
    $result = mysqli_query($conn, $count_query);

    // Jika item tidak ditemukan, kembalikan '404'
    if (mysqli_num_rows($result) == 0) {
        return '404';
    }

    // Menghapus item dari cart
    $delete_query = "DELETE FROM cart WHERE order_id = $order_id AND menu_id = $menu_id";

    // Mengecek apakah penghapusan berhasil
    if (mysqli_query($conn, $delete_query)) {
        return '200'; // Kembalikan '200' jika berhasil
    } else {
        return '404'; // Kembalikan '404' jika gagal
    }
}

function getOrder()
{
    global $conn;

    // Mengecek apakah ada order dengan status 'belum lunas'
    $count_query = "SELECT * FROM orders WHERE status = 'belum lunas' ORDER BY id ASC LIMIT 1";
    $result = mysqli_query($conn, $count_query);

    // Jika tidak ada order, kembalikan string kosong
    if (mysqli_num_rows($result) == 0) {
        return '';
    } else {
        // Mengembalikan ID order yang masih belum lunas
        $row = mysqli_fetch_assoc($result);
        return $row['id'];
    }
}

function getDeliveryId($id)
{
    global $conn;

    // Mengecek apakah ada data pengiriman dengan ID yang diberikan
    $count_query = "SELECT * FROM delivery WHERE id = $id";
    $result = mysqli_query($conn, $count_query);

    // Jika data pengiriman ditemukan, kembalikan data pengiriman tersebut
    if (mysqli_num_rows($result) == 0) {
        return ''; // Jika tidak ada, kembalikan string kosong
    } else {
        $row = mysqli_fetch_assoc($result);
        return $row; // Mengembalikan data pengiriman
    }
}

// Fungsi untuk memproses pemesanan dan mengubah status order menjadi 'order' setelah pembayaran dan pengiriman ditentukan
function placeOrder($data)
{
    global $conn;

    // Ambil order_id dari order yang statusnya 'belum lunas'
    $order_id = intval(getOrder());

    // Cek apakah order dengan status 'belum lunas' ada di database
    $count_query = "SELECT * FROM orders WHERE status = 'belum lunas' and id = $order_id";
    $result = mysqli_query($conn, $count_query);

    // Jika order tidak ditemukan, kembalikan '404'
    if (mysqli_num_rows($result) == 0) {
        return '404';
    }

    // Ambil data dari inputan form
    $payment_id = $data['payment'];
    $delivery_id = $data['delivery'];
    $name = $data['name'];
    $address = $data['address'];
    $phone = $data['phone'];

    // Ambil biaya pengiriman dari fungsi getDeliveryId
    $totalDelivery = getDeliveryId($delivery_id);

    // Hitung total harga = harga menu + biaya pengiriman
    $total = intval(getTotalPrice()) + intval($totalDelivery['charge']);

    // Update data pada tabel orders dengan informasi pembayaran, pengiriman, nama, dan total harga
    $update_query = "UPDATE orders set payment_id = $payment_id, delivery_id = $delivery_id, name = '$name', phone = '$phone', address = '$address', status = 'order', total_price = $total where id = '$order_id'";
    if (mysqli_query($conn, $update_query)) {
        return '200'; // Kembalikan 200 jika berhasil
    } else {
        return '400'; // Kembalikan 400 jika gagal
    }
}

// Fungsi untuk mengambil riwayat pesanan
function getHistory()
{
    global $conn;

    // Ambil order_id yang masih 'belum lunas'
    $order_id = intval(getOrder());

    // Query untuk mendapatkan detail pesanan berdasarkan order_id
    $count_query = "SELECT 
                        o.id AS order_id,
                        o.name AS order_name,
                        o.phone AS order_phone,
                        o.address AS order_address,
                        o.total_price,
                        o.status,
                        p.name AS payment_name,
                        d.name AS delivery_name,
                        CONCAT('[', GROUP_CONCAT(CONCAT(m.name, ' (', c.total, ')') SEPARATOR ', '), ']') AS menu_name,
                        GROUP_CONCAT(c.description SEPARATOR ', ') AS description
                    FROM 
                        orders o
                    JOIN 
                        cart c ON o.id = c.order_id
                    JOIN 
                        menu m ON c.menu_id = m.id
                    LEFT JOIN 
                        payment p ON o.payment_id = p.id
                    LEFT JOIN 
                        delivery d ON o.delivery_id = d.id
                    GROUP BY 
                        o.id, o.name, o.total_price, o.status, p.name, d.name
                    ORDER BY 
                        o.id";

    // Eksekusi query dan ambil hasilnya
    $result = mysqli_query($conn, $count_query);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $rows; // Kembalikan semua riwayat pesanan dalam bentuk array
}

// Fungsi untuk menyelesaikan order dengan mengubah status menjadi 'selesai'
function completeOrder($order_id)
{
    global $conn;

    // Cek apakah order_id ada di database
    $check_query = "SELECT id FROM `orders` WHERE id = '$order_id' LIMIT 1";
    $result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($result) === 0) {
        return '404'; // Jika order tidak ditemukan, kembalikan 404
    }

    // Jika ditemukan, update status order menjadi 'selesai'
    $update_query = "UPDATE `orders` SET status = 'selesai' WHERE id = '$order_id'";
    if (mysqli_query($conn, $update_query)) {
        return '200'; // Kembalikan 200 jika berhasil
    } else {
        return '404'; // Kembalikan 404 jika gagal
    }
}

// Fungsi untuk menghapus order berdasarkan order_id
function deleteOrder($order_id)
{
    global $conn;

    // Cek apakah order_id ada di database
    $check_query = "SELECT id FROM `orders` WHERE id = '$order_id' LIMIT 1";
    $result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($result) === 0) {
        return '404'; // Jika tidak ditemukan, kembalikan 404
    }

    // Hapus order dari tabel orders
    $delete_order_query = "DELETE FROM `orders` WHERE id = '$order_id'";
    if (mysqli_query($conn, $delete_order_query)) {
        return '200'; // Kembalikan 200 jika berhasil
    } else {
        return '500'; // Kembalikan 500 jika ada kesalahan server
    }
}
