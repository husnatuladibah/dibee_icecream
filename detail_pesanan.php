<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);
$pesanan = mysqli_query($koneksi, "
    SELECT p.*, u.nama, u.email 
    FROM pesanan p 
    JOIN pengguna u ON p.pengguna_id = u.id 
    WHERE p.id = $id
");

$dataPesanan = mysqli_fetch_assoc($pesanan);

if (!$dataPesanan) {
    echo "Pesanan tidak ditemukan.";
    exit;
}

$detail = mysqli_query($koneksi, "
    SELECT d.jumlah, pr.nama, pr.harga 
    FROM detail_pesanan d 
    JOIN produk pr ON d.produk_id = pr.id 
    WHERE d.pesanan_id = $id
");

$flashMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $statusBaru = mysqli_real_escape_string($koneksi, $_POST['status']);
    mysqli_query($koneksi, "UPDATE pesanan SET status = '$statusBaru' WHERE id = $id");
    $flashMessage = "Status pesanan berhasil diupdate menjadi <strong>" . htmlspecialchars($statusBaru) . "</strong>.";
    
    // Refresh data pesanan supaya status terbaru tampil
    $pesanan = mysqli_query($koneksi, "
        SELECT p.*, u.nama, u.email 
        FROM pesanan p 
        JOIN pengguna u ON p.pengguna_id = u.id 
        WHERE p.id = $id
    ");
    $dataPesanan = mysqli_fetch_assoc($pesanan);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
  <meta charset="UTF-8" />
  <title>Detail Pesanan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 min-h-screen py-10 px-4 font-sans text-gray-800">

  <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg p-8">
    <h1 class="text-3xl font-bold text-pink-600 mb-6">🧾 Detail Pesanan</h1>

    <?php if ($flashMessage): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <?= $flashMessage ?>
      </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
      <div>
        <p><span class="font-semibold text-pink-700">👤 Nama Customer:</span><br><?= htmlspecialchars($dataPesanan['nama']) ?></p>
        <p class="mt-2"><span class="font-semibold text-pink-700">📧 Email:</span><br><?= htmlspecialchars($dataPesanan['email']) ?></p>
        <p class="mt-2"><span class="font-semibold text-pink-700">🕒 Tanggal:</span><br><?= htmlspecialchars($dataPesanan['tanggal_pesanan']) ?></p>
      </div>
      <div>
        <p><span class="font-semibold text-pink-700">📦 Status:</span><br><span class="inline-block mt-1 px-3 py-1 rounded-full bg-green-100 text-green-700 font-medium"><?= htmlspecialchars($dataPesanan['status']) ?></span></p>
        <p class="mt-4"><span class="font-semibold text-pink-700">💰 Total:</span><br><span class="text-lg font-bold text-gray-900">Rp<?= number_format($dataPesanan['total_harga'], 0, ',', '.') ?></span></p>
      </div>
    </div>

    <div class="mb-6">
      <h2 class="text-lg font-semibold text-pink-600 mb-2">🧊 Daftar Produk</h2>
      <ul class="space-y-2 list-disc list-inside">
        <?php while ($row = mysqli_fetch_assoc($detail)): ?>
          <li class="text-gray-700">
            <?= htmlspecialchars($row['nama']) ?> 
            (<span class="text-sm"><?= $row['jumlah'] ?> x Rp<?= number_format($row['harga'], 0, ',', '.') ?></span>) 
            = <span class="font-medium">Rp<?= number_format($row['jumlah'] * $row['harga'], 0, ',', '.') ?></span>
          </li>
        <?php endwhile; ?>
      </ul>
    </div>

    <form method="POST" action="ubah_status.php" class="mt-6">
      <input type="hidden" name="id" value="<?= $dataPesanan['id'] ?>">

      <label for="status" class="block mb-2 font-semibold text-pink-700">🔁 Ubah Status:</label>
      <div class="flex flex-wrap gap-2 items-center">
        <select name="status" id="status" class="border border-pink-300 px-4 py-2 rounded-md focus:ring-pink-400 focus:outline-none" required>
          <option value="menunggu" <?= $dataPesanan['status'] == 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
          <option value="selesai" <?= $dataPesanan['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
        </select>
        <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-4 py-2 rounded-md transition">
          Update
        </button>
      </div>
    </form>

    <div class="mt-6">
      <a href="lihat_pesanan.php" class="text-pink-500 hover:underline text-sm">← Kembali ke daftar pesanan</a>
    </div>
  </div>

</body>
</html>
