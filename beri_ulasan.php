<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['pesanan_id'])) {
    echo "Pesanan ID tidak ditemukan.";
    exit;
}

$pesanan_id = $_GET['pesanan_id'];
$user_id = $_SESSION['user_id'];

// Ambil produk_id berdasarkan pesanan_id dari tabel detail_pesanan
$ambilProduk = mysqli_query($koneksi, "SELECT produk_id FROM detail_pesanan WHERE pesanan_id = '$pesanan_id' LIMIT 1");
$dataProduk = mysqli_fetch_assoc($ambilProduk);

if (!$dataProduk || !isset($dataProduk['produk_id'])) {
    echo "Produk tidak ditemukan untuk pesanan ini.";
    exit;
}

$produk_id = $dataProduk['produk_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'];
    $komentar = mysqli_real_escape_string($koneksi, $_POST['komentar']);

    if (!$produk_id || !$user_id || !$rating) {
        echo "Data tidak lengkap.";
        exit;
    }

    // Pastikan produk_id benar-benar ada di tabel produk
    $cekProduk = mysqli_query($koneksi, "SELECT id FROM produk WHERE id = '$produk_id' LIMIT 1");
    if (mysqli_num_rows($cekProduk) === 0) {
        echo "Produk tidak valid.";
        exit;
    }

    // Masukkan ulasan ke tabel ulasan
    $query = "INSERT INTO ulasan (pengguna_id, produk_id, rating, komentar) 
              VALUES ('$user_id', '$produk_id', '$rating', '$komentar')";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: riwayat_pesanan.php");
        exit;
    } else {
        echo "Gagal menyimpan ulasan: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beri Ulasan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
</head>
<body class="bg-pink-50">
    <div class="max-w-xl mx-auto mt-20 bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold text-pink-600 mb-4">📝 Tulis Ulasan Anda</h2>
        <form method="POST">
            <label class="block mb-2 font-medium text-gray-700">Rating (1-5)</label>
            <input type="number" name="rating" min="1" max="5" required class="w-full border p-2 rounded mb-4">

            <label class="block mb-2 font-medium text-gray-700">Komentar</label>
            <textarea name="komentar" rows="5" class="w-full border p-2 rounded mb-4" placeholder="Ceritakan pengalaman Anda..."></textarea>

            <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded">Kirim Ulasan</button>
            <a href="riwayat_pesanan.php" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded ml-2">← Kembali</a>
        </form>
    </div>
</body>
</html>

