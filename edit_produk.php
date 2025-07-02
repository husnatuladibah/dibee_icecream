<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID produk tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);
$result = mysqli_query($koneksi, "SELECT * FROM produk WHERE id = $id LIMIT 1");
$produk = mysqli_fetch_assoc($result);

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga = intval($_POST['harga']);
    $stok = intval($_POST['stok']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    if ($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads/" . $gambar);
        $updateGambar = ", gambar='$gambar'";
    } else {
        $updateGambar = "";
    }

    $update = "UPDATE produk SET nama='$nama', kategori='$kategori', harga=$harga, stok=$stok, deskripsi='$deskripsi' $updateGambar WHERE id=$id";
    if (mysqli_query($koneksi, $update)) {
        header("Location: daftar_produk.php");
        exit;
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 min-h-screen flex items-center justify-center p-6 font-sans">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-lg">
        <h1 class="text-2xl font-bold text-pink-600 mb-6">✏️ Edit Produk</h1>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block font-medium text-pink-700">Nama Produk:</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($produk['nama']) ?>" required
                       class="w-full border border-pink-300 px-4 py-2 rounded-md focus:ring-pink-400 focus:outline-none">
            </div>

            <div>
                <label class="block font-medium text-pink-700">Kategori:</label>
                <select name="kategori" required
                        class="w-full border border-pink-300 px-4 py-2 rounded-md focus:ring-pink-400 focus:outline-none">
                    <option value="Ice Cream Premium" <?= $produk['kategori'] === 'Ice Cream Premium' ? 'selected' : '' ?>>Ice Cream Premium</option>
                    <option value="Ice Cream Reguler" <?= $produk['kategori'] === 'Ice Cream Reguler' ? 'selected' : '' ?>>Ice Cream Reguler</option>
                </select>
            </div>

            <div>
                <label class="block font-medium text-pink-700">Harga:</label>
                <input type="number" name="harga" value="<?= $produk['harga'] ?>" required
                       class="w-full border border-pink-300 px-4 py-2 rounded-md focus:ring-pink-400 focus:outline-none">
            </div>

            <div>
                <label class="block font-medium text-pink-700">Stok:</label>
                <input type="number" name="stok" value="<?= $produk['stok'] ?>" required
                       class="w-full border border-pink-300 px-4 py-2 rounded-md focus:ring-pink-400 focus:outline-none">
            </div>

            <div>
                <label class="block font-medium text-pink-700">Deskripsi:</label>
                <textarea name="deskripsi" rows="3" required
                          class="w-full border border-pink-300 px-4 py-2 rounded-md focus:ring-pink-400 focus:outline-none"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
            </div>

            <div>
                <label class="block font-medium text-pink-700">Gambar Produk (opsional):</label>
                <input type="file" name="gambar"
                       class="w-full border border-pink-300 px-4 py-2 rounded-md bg-white">
            </div>

            <div class="flex justify-between items-center pt-4">
                <a href="daftar_produk.php" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-4 py-2 rounded-md transition">
                    &larr; Kembali
                </a>
                <button type="submit"
                        class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-4 py-2 rounded-md transition">
                    Update Produk
                </button>
            </div>
        </form>
    </div>
</body>
</html>
