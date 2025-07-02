<?php
session_start();
include 'koneksi.php';

// Cek akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil data produk tanpa JOIN
$query = "SELECT * FROM produk ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin - Daftar Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 min-h-screen p-6 font-sans">

  <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-lg p-6">
    <h1 class="text-3xl font-bold text-pink-600 mb-6">🍦 Daftar Produk Ice Cream</h1>

    <div class="mb-4">
      <a href="tambah_produk.php" 
         class="inline-block bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 px-4 rounded transition">
         + Tambah Produk Baru
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full border border-pink-300 rounded-lg">
        <thead class="bg-pink-100 text-pink-700 font-semibold">
          <tr>
            <th class="border border-pink-300 px-4 py-2 text-left">Gambar</th>
            <th class="border border-pink-300 px-4 py-2 text-left">Nama</th>
            <th class="border border-pink-300 px-4 py-2 text-left">Kategori</th>
            <th class="border border-pink-300 px-4 py-2 text-left">Harga</th>
            <th class="border border-pink-300 px-4 py-2 text-left">Stok</th>
            <th class="border border-pink-300 px-4 py-2 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <tr class="even:bg-pink-50">
                <td class="border border-pink-300 px-4 py-2">
                  <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="" class="max-w-[80px] rounded" />
                </td>
                <td class="border border-pink-300 px-4 py-2"><?php echo htmlspecialchars($row['nama']); ?></td>
                <td class="border border-pink-300 px-4 py-2"><?php echo htmlspecialchars($row['kategori']); ?></td>
                <td class="border border-pink-300 px-4 py-2">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                <td class="border border-pink-300 px-4 py-2"><?php echo $row['stok']; ?></td>
                <td class="border border-pink-300 px-4 py-2 space-x-2">
                <a href="edit_produk.php?id=<?= $row['id']; ?>"
                  class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold py-1 px-3 rounded transition">
                  ✏️ Edit
                </a>
                <a href="hapus_produk.php?id=<?= $row['id']; ?>" 
                  onclick="return confirm('Yakin ingin menghapus produk ini?')" 
                  class="inline-block bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-1 px-3 rounded transition">
                  🗑️ Hapus
                </a>
              </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center py-4">Belum ada produk.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="mt-6">
      <a href="admin_dashboard.php" class="inline-block bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 px-4 rounded transition">
          &larr; Kembali ke Dashboard
      </a>
    </div>
  </div>

</body>
</html>

