<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$keranjang = $_SESSION['keranjang'] ?? [];
$pesanan_berhasil = false;
$pesanan_id = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pengguna_id = $_SESSION['user_id'];
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']); // nomor meja
    $metode = mysqli_real_escape_string($koneksi, $_POST['metode']);

    if (empty($keranjang)) {
        echo "<p>Keranjang kosong!</p>";
        exit;
    }

    $total_harga = 0;
    $produk_data = [];

    foreach ($keranjang as $produk_id => $jumlah) {
    $q = mysqli_query($koneksi, "SELECT harga FROM produk WHERE id = $produk_id");
    if ($d = mysqli_fetch_assoc($q)) {
        $harga = $d['harga'];
        $total_harga += $harga * $jumlah;

        // Kurangi stok langsung di sini
        mysqli_query($koneksi, "
            UPDATE produk SET stok = stok - $jumlah WHERE id = $produk_id
        ");

        $produk_data[] = [
            'produk_id' => $produk_id,
            'jumlah' => $jumlah
        ];
    }
}

    $insertPesanan = "
        INSERT INTO pesanan (pengguna_id, total_harga, alamat_pengiriman, metode_pembayaran, status)
        VALUES ($pengguna_id, $total_harga, '$alamat', '$metode', 'menunggu')
    ";

    if (mysqli_query($koneksi, $insertPesanan)) {
        $pesanan_id = mysqli_insert_id($koneksi);
        foreach ($produk_data as $item) {
            $produk_id = $item['produk_id'];
            $jumlah = $item['jumlah'];
            mysqli_query($koneksi, "
                INSERT INTO detail_pesanan (pesanan_id, produk_id, jumlah)
                VALUES ($pesanan_id, $produk_id, $jumlah)
            ");
        }

        unset($_SESSION['keranjang']);
        $pesanan_berhasil = true;
        $_SESSION['last_pesanan_id'] = $pesanan_id;
    } else {
        $error = "Gagal menyimpan pesanan: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
  <meta charset="UTF-8">
  <title>Checkout</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .animate-fade-in {
      animation: fadeIn 0.8s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
  </style>
</head>
<body class="bg-pink-50 p-6 text-gray-800">
  <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-4">Checkout</h1>

    <?php if (!empty($error)): ?>
      <p class="text-red-600 font-semibold"><?= $error ?></p>

    <?php elseif ($pesanan_berhasil): ?>
      <div class="text-center animate-fade-in">
        <svg class="mx-auto mb-4 w-16 h-16 text-green-500 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <h2 class="text-2xl font-bold text-green-600 mb-2">Yey! Pesanan Berhasil 🎉</h2>
        <p class="text-gray-700 mb-4">Terima kasih sudah berbelanja. Kami akan segera mengantarkan pesanan ke meja Anda.</p>
        <a href="cetak_nota.php?id=<?= $pesanan_id ?>" target="_blank" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 inline-block mb-2">
          🧾 Download Nota (PDF)
        </a><br>
        <a href="customer_dashboard.php" class="bg-pink-500 text-white px-4 py-2 rounded-md hover:bg-pink-600 inline-block">← Kembali ke Dashboard</a>
      </div>

    <?php elseif (empty($keranjang)): ?>
      <p class="text-gray-600">Keranjang kosong. <a href="customer_dashboard.php" class="text-pink-500 hover:underline">Kembali ke Dashboard</a></p>

    <?php else: ?>
      <form method="POST">
        <div class="mb-4">
          <label class="block mb-1 font-semibold">Nomor Meja</label>
          <input type="text" name="alamat" placeholder="Contoh: Meja 3" class="w-full border border-pink-300 rounded-md p-2" required>
        </div>

        <div class="mb-4">
          <label class="block mb-1 font-semibold">Metode Pembayaran</label>
          <select name="metode" class="w-full border border-pink-300 rounded-md p-2" required>
            <option value="">-- Pilih --</option>
            <option value="Transfer Bank">Transfer Bank</option>
            <option value="Cash">Cash</option>
          </select>
        </div>

        <div id="qr-section" class="mt-4 hidden bg-pink-50 border border-pink-300 p-3 rounded-md">
          <p class="font-semibold mb-2">Silakan transfer ke:</p>
          <img src="img/qriss.jpeg" alt="QRIS Toko" class="w-40 mb-2">
          <p>Rekening: <strong>1234567890 (Bank ABC - Dibee Ice Cream)</strong></p>
          <p class="text-sm text-gray-500">Setelah transfer, mohon konfirmasi saat pesanan di antarkan ke meja anda.</p>
        </div>

        <div class="flex gap-4 mt-6">
          <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-md hover:bg-pink-600">
            Konfirmasi Pesanan
          </button>
          <a href="customer_dashboard.php" class="bg-pink-500 text-white px-4 py-2 rounded-md hover:bg-pink-600">
            ← Kembali ke Dashboard
          </a>
        </div>
      </form>
    <?php endif; ?>
  </div>

  <script>
    const metodeSelect = document.querySelector('select[name="metode"]');
    const qrSection = document.getElementById('qr-section');

    metodeSelect.addEventListener('change', function () {
      if (this.value === 'Transfer Bank') {
        qrSection.classList.remove('hidden');
      } else {
        qrSection.classList.add('hidden');
      }
    });
  </script>
</body>
</html>
