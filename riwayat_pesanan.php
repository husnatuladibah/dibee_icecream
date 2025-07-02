<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM pesanan WHERE pengguna_id = $user_id ORDER BY tanggal_pesanan DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
    <meta charset="UTF-8">
    <title>Riwayat Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-100 font-sans">

<div class="max-w-4xl mx-auto py-10 px-4">
    
    <!-- Judul dan Tombol Kembali -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-pink-600 mb-2">📦 Riwayat Pesanan Anda</h1>
        <div class="text-right">
            <a href="customer_dashboard.php" 
               class="inline-block bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition">
               ← Kembali ke Dashboard
            </a>
        </div>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="space-y-6">
            <?php while ($pesanan = mysqli_fetch_assoc($result)): ?>
                <div class="bg-white shadow rounded-lg p-6">
                    
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">Tanggal Pesanan</span>
                        <span class="text-sm text-gray-700 font-medium">
                            <?= htmlspecialchars($pesanan['tanggal_pesanan']) ?>
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-2">
                        <div>
                            <p class="text-gray-600 text-sm">Total Harga</p>
                            <p class="font-semibold text-gray-800">Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Status</p>
                            <p class="font-semibold <?= $pesanan['status'] === 'selesai' ? 'text-green-600' : 'text-yellow-600' ?>">
                                <?= htmlspecialchars(ucfirst($pesanan['status'])) ?>
                            </p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-gray-600 text-sm">Alamat Pengiriman</p>
                            <p class="text-gray-800"><?= htmlspecialchars($pesanan['alamat_pengiriman']) ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Metode Pembayaran</p>
                            <p class="text-gray-800"><?= htmlspecialchars($pesanan['metode_pembayaran']) ?></p>
                        </div>
                    </div>

                    <?php if ($pesanan['status'] === 'selesai'): ?>
                        <div class="mt-4 text-right">
                            <a href="beri_ulasan.php?pesanan_id=<?= $pesanan['id'] ?>" 
                            class="inline-block bg-pink-500 hover:bg-pink-600 text-white font-semibold px-4 py-2 rounded-md transition">
                            Beri Ulasan
                            </a>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="bg-white p-6 rounded-lg shadow text-center text-gray-600">
            Belum ada pesanan yang dilakukan.
        </div>
    <?php endif; ?>
</div>
</body>
</html>

