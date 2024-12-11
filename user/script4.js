// Fungsi untuk menambahkan item ke keranjang
function addToCart(item_id, level_id) {
  // Default nilai level jika tidak ada dropdown
  let selectedLevel = "0";

  // Cek apakah `level_id` tersedia (dropdown level ada)
  if (level_id) {
    // Mengambil elemen dropdown level berdasarkan `level_id`
    const levelSelect = document.getElementById(level_id);

    // Pastikan elemen dropdown level ditemukan dan ambil nilai yang dipilih
    if (levelSelect) {
      selectedLevel = levelSelect.value;
    }
  }

  // Menampilkan pesan bahwa menu berhasil ditambahkan ke keranjang
  alert(`Menu Berhasil Ditambahkan di Keranjang`);

  // Mengarahkan pengguna ke URL dengan parameter `item_id` dan `desc` (level yang dipilih)
  window.location.replace(`?item=${item_id}&desc=${selectedLevel}`);
}

// Fungsi untuk menghapus item dari keranjang
function removeToCart(order_id, menu_id) {
  // Mengarahkan pengguna ke URL dengan parameter `remove`, `order_id`, dan `menu_id`
  window.location.replace(`?remove=true&order=${order_id}&menu=${menu_id}`);
}

// Fungsi untuk menampilkan alert dan mengarahkan pengguna ke halaman `order.php`
function alertInfo(message) {
  // Menampilkan pesan notifikasi ke pengguna
  alert(message);

  // Mengarahkan pengguna ke halaman `order.php` setelah pesan ditutup
  window.location.replace(`order.php`);
}

// Fungsi untuk menampilkan alert dan mengarahkan pengguna ke halaman `history.php`
function alertHistory(message) {
  // Menampilkan pesan notifikasi ke pengguna
  alert(message);

  // Mengarahkan pengguna ke halaman `history.php` setelah pesan ditutup
  window.location.replace(`history.php`);
}
