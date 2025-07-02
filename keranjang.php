<?php
session_start();
include 'koneksi.php';

// Fungsi tambah produk ke keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['produk_id'], $_POST['jumlah'])) {
    $produk_id = intval($_POST['produk_id']);
    $jumlah = intval($_POST['jumlah']);

    // Cek keranjang di session, buat jika belum ada
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // Jika produk sudah ada di keranjang, jumlahkan
    if (isset($_SESSION['keranjang'][$produk_id])) {
        $_SESSION['keranjang'][$produk_id] += $jumlah;
    } else {
        $_SESSION['keranjang'][$produk_id] = $jumlah;
    }

    header('Location: keranjang.php'); // Redirect supaya refresh aman
    exit;
}

// Tampilkan isi keranjang
$keranjang = $_SESSION['keranjang'] ?? [];

$produk_keranjang = [];
$total_harga = 0;

if (!empty($keranjang)) {
    $ids = implode(',', array_keys($keranjang));
    $query = "SELECT * FROM produk WHERE id IN ($ids)";
    $result = mysqli_query($koneksi, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $row['jumlah'] = $keranjang[$row['id']];
        $row['subtotal'] = $row['harga'] * $row['jumlah'];
        $total_harga += $row['subtotal'];
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
<body class="bg-pink-50 min-h-screen font-sans">

    <div class="max-w-4xl mx-auto p-6">
        <h2 class="text-3xl font-bold text-pink-700 mb-6 text-center">🛒 Keranjang Belanja Anda</h2>

        <?php if (empty($produk_keranjang)): ?>
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <p class="text-gray-600 mb-4">Keranjang Anda kosong.</p>
                <a href="customer_dashboard.php" class="inline-block px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600 transition">
                    Belanja Sekarang
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto bg-white p-6 rounded-lg shadow">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-pink-100 text-pink-700">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama Produk</th>
                            <th scope="col" class="px-6 py-3">Harga</th>
                            <th scope="col" class="px-6 py-3">Jumlah</th>
                            <th scope="col" class="px-6 py-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produk_keranjang as $item): ?>
                            <tr class="border-b">
                                <td class="px-6 py-4"><?php echo htmlspecialchars($item['nama']); ?></td>
                                <td class="px-6 py-4">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                                <td class="px-6 py-4"><?php echo $item['jumlah']; ?></td>
                                <td class="px-6 py-4">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="bg-pink-100 font-semibold">
                            <td colspan="3" class="px-6 py-4 text-right">Total Harga</td>
                            <td class="px-6 py-4">Rp <?php echo number_format($total_harga, 0, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-6 flex justify-between">
                    <a href="customer_dashboard.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">← Lanjutkan Belanja</a>
                    <a href="checkout.php" class="px-6 py-2 bg-pink-500 text-white font-semibold rounded hover:bg-pink-600 transition">Checkout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
