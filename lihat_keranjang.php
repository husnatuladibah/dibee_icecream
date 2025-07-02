<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil keranjang dari session
$keranjang = $_SESSION['keranjang'] ?? [];

$produk_keranjang = [];
$total = 0;

if (!empty($keranjang)) {
    $ids = implode(',', array_keys($keranjang));
    $query = "SELECT * FROM produk WHERE id IN ($ids)";
    $result = mysqli_query($koneksi, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $row['jumlah'] = $keranjang[$id];
        $row['subtotal'] = $row['jumlah'] * $row['harga'];
        $total += $row['subtotal'];
        $produk_keranjang[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
  <meta charset="UTF-8">
  <title>Keranjang Belanja</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 text-gray-800">
  <div class="max-w-4xl mx-auto p-6 min-h-screen flex flex-col justify-start">

    <!-- Judul & Tombol Dashboard -->
    <div class="relative mb-8">
      <h1 class="text-2xl font-bold text-center">🛒 Keranjang Belanja</h1>
      <a href="customer_dashboard.php" 
         class="absolute right-0 top-0 bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 px-4 rounded transition">
         ← Kembali ke Dashboard
      </a>
    </div>

    <?php if (empty($produk_keranjang)): ?>
      <!-- Tampilan Jika Keranjang Kosong -->
      <div class="flex flex-col items-center justify-center text-center mt-20">
        <img src="icon/nangis.png" alt="Keranjang Kosong" class="w-40 mb-4 opacity-80" />
        <h2 class="text-xl font-semibold text-gray-700 mb-2">Keranjang kamu masih kosong 😢</h2>
        <p class="text-sm text-gray-500 mb-6">Yuk, tambahkan es krim favoritmu ke dalam keranjang!</p>
        <a href="customer_dashboard.php" class="bg-pink-500 hover:bg-pink-600 text-white px-5 py-2 rounded-lg transition">
          Belanja Sekarang
        </a>
      </div>
    <?php else: ?>
      <!-- Tabel Produk -->
      <table class="w-full mt-4 border border-pink-300">
        <thead class="bg-pink-100 text-left">
          <tr>
            <th class="p-2">Produk</th>
            <th class="p-2">Jumlah</th>
            <th class="p-2">Harga</th>
            <th class="p-2">Subtotal</th>
            <th class="p-2">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($produk_keranjang as $item): ?>
            <tr class="border-t border-pink-200">
              <td class="p-2"><?= htmlspecialchars($item['nama']) ?></td>
              <td class="p-2"><?= $item['jumlah'] ?></td>
              <td class="p-2">Rp <?= number_format($item['harga']) ?></td>
              <td class="p-2">Rp <?= number_format($item['subtotal']) ?></td>
              <td class="p-2">
                <form method="POST" action="hapus_keranjang.php">
                  <input type="hidden" name="id" value="<?= $item['id'] ?>">
                  <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Total & Tombol Checkout -->
      <div class="text-right mt-4 font-bold">Total: Rp <?= number_format($total) ?></div>
      <a href="checkout.php" class="mt-4 inline-block bg-pink-500 text-white px-4 py-2 rounded-md hover:bg-pink-600">
        Checkout
      </a>
    <?php endif; ?>
  </div>
</body>
</html>


