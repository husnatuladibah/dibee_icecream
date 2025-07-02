<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$promo = mysqli_query($koneksi, "SELECT * FROM promo ORDER BY tanggal_upload DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
  <meta charset="UTF-8">
  <title>Promo Bulanan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 min-h-screen flex flex-col items-center justify-start py-10 px-4">

  <div class="max-w-3xl w-full bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-bold text-yellow-600 mb-6 text-center">📢 Promo Bulanan</h1>

    <?php if (mysqli_num_rows($promo) > 0): ?>
      <div class="space-y-10">
        <?php while($row = mysqli_fetch_assoc($promo)): ?>
          <div class="border-b pb-4">
            <p class="font-semibold text-lg mb-2"><?= htmlspecialchars($row['deskripsi']) ?></p>

            <!-- Embed preview PDF (ukuran sedang, responsif) -->
            <div class="w-full aspect-video max-h-80">
              <iframe 
                src="promo/<?= htmlspecialchars($row['nama_file']) ?>" 
                class="w-full h-full border rounded-md shadow-sm mb-2">
              </iframe>
            </div>

            <div class="flex items-center justify-between text-sm text-gray-500 mt-1">
              <span>Uploaded: <?= $row['tanggal_upload'] ?></span>
              <a href="promo/<?= htmlspecialchars($row['nama_file']) ?>" target="_blank" class="text-blue-600 underline">
                🔗 Buka di tab baru
              </a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p class="text-gray-600 text-center">Belum ada promo yang tersedia saat ini.</p>
    <?php endif; ?>

    <!-- Tombol Kembali -->
    <div class="mt-10 text-center">
      <a href="customer_dashboard.php" class="inline-block bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition">
        ⬅️ Kembali ke Beranda
      </a>
    </div>
  </div>

</body>
</html>
