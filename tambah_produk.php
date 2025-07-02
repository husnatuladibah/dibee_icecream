<?php
session_start();
include 'koneksi.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: daftar_produk.php");
    exit;
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $stok = mysqli_real_escape_string($koneksi, $_POST['stok']);

    // Upload gambar
    $gambar = $_FILES['gambar']['name'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];
    $target_dir = "uploads/";

    // Buat folder jika belum ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!empty($gambar)) {
        $ekstensi = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
        $nama_file_baru = uniqid('produk_') . '.' . $ekstensi;
        $target_file = $target_dir . $nama_file_baru;

        $tipe_valid = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ekstensi, $tipe_valid)) {
            $error = "Tipe file tidak valid. Gunakan JPG, PNG, atau GIF.";
        } elseif (move_uploaded_file($gambar_tmp, $target_file)) {
            // Simpan ke database
            $query = "INSERT INTO produk (nama, harga, deskripsi, kategori, gambar, stok) 
                      VALUES ('$nama', '$harga', '$deskripsi', '$kategori', '$nama_file_baru', '$stok')";
            if (mysqli_query($koneksi, $query)) {
                $success = "Produk berhasil ditambahkan!";
            } else {
                $error = "Gagal menyimpan ke database: " . mysqli_error($koneksi);
            }
        } else {
            $error = "Gagal mengupload gambar.";
        }
    } else {
        $error = "Mohon pilih gambar produk.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tambah Produk - Adibah Ice Cream</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 min-h-screen flex items-center justify-center p-6 font-sans">

  <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-lg">
    <h1 class="text-2xl font-bold text-pink-600 mt-2 mb-6">🍨 Tambah Produk Baru</h1>

    <?php if (isset($success)) : ?>
      <div class="mb-4 text-green-700 font-semibold"><?= $success; ?></div>
    <?php elseif (isset($error)) : ?>
      <div class="mb-4 text-red-700 font-semibold"><?= $error; ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
      
      <div>
        <label for="nama" class="block font-medium text-pink-700">Nama Produk:</label>
        <input type="text" name="nama" id="nama" required
               class="w-full border border-pink-300 px-4 py-2 rounded-md focus:ring-pink-400 focus:outline-none"/>
      </div>

      <div>
        <label for="harga" class="block font-medium text-pink-700">Harga:</label>
        <input type="number" name="harga" id="harga" required
               class="w-full border border-pink-300 px-4 py-2 rounded-md focus:ring-pink-400 focus:outline-none"/>
      </div>

      <div>
        <label for="deskripsi" class="block font-medium text-pink-700">Deskripsi:</label>
        <textarea name="deskripsi" id="deskripsi" rows="3" required
                  class="w-full border border-pink-300 px-4 py-2 rounded-md focus:ring-pink-400 focus:outline-none"></textarea>
      </div>

      <div>
        <label for="kategori" class="block font-medium text-pink-700">Kategori:</label>
        <select name="kategori" id="kategori" required
                class="w-full border border-pink-300 px-4 py-2 rounded-md focus:ring-pink-400 focus:outline-none">
          <option value="">-- Pilih Kategori --</option>
          <option value="Ice Cream Premium">Ice Cream Premium</option>
          <option value="Ice Cream Reguler">Ice Cream Reguler</option>
        </select>
      </div>

      <div>
        <label for="stok" class="block font-medium text-pink-700">Stok:</label>
        <input type="number" name="stok" id="stok" required
               class="w-full border border-pink-300 px-4 py-2 rounded-md focus:ring-pink-400 focus:outline-none"/>
      </div>

      <div>
        <label for="gambar" class="block font-medium text-pink-700">Gambar Produk:</label>
        <input type="file" name="gambar" id="gambar" accept=".jpg,.jpeg,.png,.gif"
               class="w-full border border-pink-300 px-4 py-2 rounded-md bg-white"/>
      </div>

      <div class="flex justify-between items-center pt-4">
        <a href="admin_dashboard.php" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-4 py-2 rounded-md transition">
          &larr; Kembali ke Dashboard
        </a>
        <button type="submit"
                class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-4 py-2 rounded-md transition">
          Simpan Produk
        </button>
      </div>
    </form>
  </div>

</body>
</html>
