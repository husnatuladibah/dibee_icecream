<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login dan role-nya customer
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

// Tangkap keyword pencarian dari URL (method GET)
$keyword = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

// Buat query produk
if ($keyword !== '') {
    $query = "SELECT * FROM produk WHERE nama LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%'";
} else {
    $query = "SELECT * FROM produk";
}

$result = mysqli_query($koneksi, $query);
if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - Adibah Ice Cream</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
      animation: fade-in 0.5s ease-out;
    }
  </style>
</head>
<body class="bg-pink-50 font-sans">

  <!-- Navbar -->
  <nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
    <h1 class="text-pink-600 font-bold text-lg"> Selamat datang, <?= htmlspecialchars($_SESSION['user_nama']) ?>! 🍦</h1>
    <div class="space-x-4 text-sm">
      <a href="customer_dashboard.php" class="text-pink-500 hover:underline">🏠 Beranda</a>
      <a href="lihat_keranjang.php" class="text-pink-500 hover:underline">🛒 Lihat Keranjang</a>
      <a href="riwayat_pesanan.php" class="text-pink-500 hover:underline">📦 Riwayat</a>
      <a href="promo.php" class="text-pink-500 hover:underline"> 📢 Promo Bulanan
      <a href="profil.php" class="text-pink-500 hover:underline">👤 Profil</a>
    </div>
  </nav>

  <!-- Search Bar -->
  <div class="p-6 max-w-6xl mx-auto">
    <form class="flex items-center mb-6" method="GET" action="">
      <input type="text" name="search" placeholder="Cari ice cream..." value="<?= htmlspecialchars($keyword) ?>"
             class="flex-grow p-3 rounded-l-lg border border-pink-300 focus:ring-pink-400 focus:outline-none" />
      <button type="submit" class="bg-pink-500 text-white px-5 py-3 rounded-r-lg hover:bg-pink-600">Cari</button>
    </form>

    <!-- Menu Section -->
    <h2 class="text-xl font-bold text-pink-700 mb-4">Menu Ice Cream</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($produk = mysqli_fetch_assoc($result)): ?>
          <?php
            $gambar = !empty($produk['gambar']) ? 'uploads/' . htmlspecialchars($produk['gambar']) : 'uploads/default.jpg';
          ?>
          <div class="bg-white rounded-2xl shadow-lg p-4 hover:shadow-xl transition">
            <img src="<?= $gambar ?>" alt="<?= htmlspecialchars($produk['nama']) ?>" class="rounded-xl w-full h-40 object-cover mb-4" />
            <h3 class="text-lg font-bold text-pink-700"><?= htmlspecialchars($produk['nama']) ?></h3>
            <p class="text-sm text-gray-600"><strong>Kategori:</strong> <?= htmlspecialchars($produk['kategori']) ?></p>
            <p class="text-sm text-gray-600 mb-1"><?= htmlspecialchars($produk['deskripsi']) ?></p>
            <p class="text-sm text-pink-600 font-semibold mt-2">💸 Harga: Rp <?= number_format($produk['harga'], 0, ',', '.') ?></p>
            <p class="text-sm text-gray-600">Stok: <span class="text-pink-700 font-bold"><?= $produk['stok'] ?></span></p>

            <form action="keranjang.php" method="POST" class="mt-3 flex items-center gap-2">
              <input type="hidden" name="produk_id" value="<?= $produk['id'] ?>">
              <input type="number" name="jumlah" value="1" min="1" max="<?= $produk['stok'] ?>" class="w-16 px-3 py-2 border border-pink-300 rounded-md" />
              <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-md hover:bg-pink-600 transition">Tambah ke Keranjang</button>
            </form>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <!-- Produk tidak ditemukan -->
      <div class="flex flex-col items-center mt-20 animate-fade-in text-center">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="w-32 h-32 mb-4">
          <path fill="#FFD1DC" d="M32 4c7 0 12 6 12 13s-5 13-12 13S20 24 20 17s5-13 12-13z"/>
          <path fill="#F08080" d="M44 30H20l2 20c0 6 6 10 10 10s10-4 10-10l2-20z"/>
          <circle cx="24" cy="17" r="2" fill="#000"/>
          <circle cx="40" cy="17" r="2" fill="#000"/>
          <path d="M26 24c2 1 4 1 6 0" stroke="#000" stroke-linecap="round"/>
          <path d="M32 45c1 1 1 3 0 4s-3 1-4 0" fill="none" stroke="#000" stroke-linecap="round"/>
        </svg>
        <h2 class="text-xl font-semibold text-pink-600 mb-2">Mohon Maaf🙏 Produk tidak ditemukan 😢</h2>
        <p class="text-gray-600 mb-4">Mungkin nama es krimnya salah ketik atau lagi habis stok, Yuk coba cari lagi 🍦</p>
      </div>
    <?php endif; ?>
  </div>

</body>
</html>
