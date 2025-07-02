<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

function tampilkanRingkasanHariIni($koneksi) {
    $today = date('Y-m-d');

    $totalProduk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM produk"))['total'];

    $pesananHariIni = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT COUNT(*) AS total 
        FROM pesanan 
        WHERE DATE(tanggal_pesanan) = '$today'
    "))['total'];

    $selesaiHariIni = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT COUNT(*) AS total 
        FROM pesanan 
        WHERE status = 'selesai' AND DATE(tanggal_pesanan) = '$today'
    "))['total'];

    $cekUlasan = mysqli_query($koneksi, "SHOW COLUMNS FROM ulasan LIKE 'tanggal_ulasan'");
    $ulasanBaru = (mysqli_num_rows($cekUlasan) > 0)
        ? mysqli_fetch_assoc(mysqli_query($koneksi, "
            SELECT COUNT(*) AS total 
            FROM ulasan 
            WHERE DATE(tanggal_ulasan) = '$today'
        "))['total']
        : 0;

    echo '<div class="bg-pink-100 rounded-md p-4 my-4 text-pink-700">';
    echo '<h3 class="text-lg font-semibold mb-2">📊 Ringkasan Hari Ini</h3>';
    echo '<ul class="list-disc pl-5 space-y-1">';
    echo "<li>Total Produk: $totalProduk</li>";
    echo "<li>Pesanan Hari Ini: $pesananHariIni</li>";
    echo "<li>Pesanan Selesai Hari Ini: $selesaiHariIni</li>";
    echo "<li>Ulasan Baru: $ulasanBaru</li>";
    echo '</ul>';
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Admin - Adibah Ice Cream</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-pink-50 to-rose-100 min-h-screen flex flex-col items-center justify-start py-10 px-4">

  <div class="max-w-3xl w-full bg-white shadow-lg rounded-2xl p-8 border border-rose-100">
    <h1 class="text-3xl font-semibold text-pink-600 text-center mb-6">
      🎉 Selamat Datang, Admin! Semangat Kerjanya 😋
    </h1>

    <!-- Ringkasan Hari Ini -->
    <?php tampilkanRingkasanHariIni($koneksi); ?>

    <h2 class="text-xl font-semibold text-rose-500 mt-6 mb-4 border-b pb-1">🛠️ Menu Admin</h2>

    <ul class="space-y-3">
      <li>
        <a href="tambah_produk.php" class="flex items-center gap-2 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl hover:bg-rose-100 text-rose-600 font-medium shadow-sm transition">
          🍦 Tambah Produk
        </a>
      </li>
      <li>
        <a href="daftar_produk.php" class="flex items-center gap-2 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl hover:bg-rose-100 text-rose-600 font-medium shadow-sm transition">
          📋 Lihat Daftar Produk
        </a>
      </li>
      <li>
        <a href="lihat_pesanan.php" class="flex items-center gap-2 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl hover:bg-rose-100 text-rose-600 font-medium shadow-sm transition">
          📦 Lihat Pesanan
        </a>
      </li>
      <li>
        <a href="lihat_ulasan.php" class="flex items-center gap-2 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl hover:bg-rose-100 text-rose-600 font-medium shadow-sm transition">
          📝 Lihat Ulasan Customer
        </a>
      </li>
      <li>
        <a href="upload_promo.php" class= "flex items-center gap-2 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl hover:bg-rose-100 text-rose-600 font-medium shadow-sm transition">
          📤 Upload Promo Bulanan
        </a>
      </li>
      <li>
        <a href="logout.php" class="flex items-center gap-2 px-4 py-3 bg-red-50 border border-red-100 rounded-xl hover:bg-red-100 text-red-600 font-semibold shadow-sm transition">
          🚪 Logout
        </a>
      </li>
    </ul>
  </div>

</body>
</html>
