<?php
session_start();
include 'koneksi.php';

// Cek login dan role admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil data ulasan dengan JOIN produk dan data_user
$query = "
    SELECT ulasan.*, 
           produk.nama AS nama_produk, 
           data_user.fullname AS nama_customer
    FROM ulasan
    JOIN produk ON ulasan.produk_id = produk.id
    JOIN data_user ON ulasan.pengguna_id = data_user.id
    ORDER BY ulasan.tanggal_ulasan DESC
";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
  <meta charset="UTF-8">
  <title>Ulasan Customer</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 p-6 font-sans">
  <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-md p-6">
    <h1 class="text-2xl font-bold text-pink-600 mb-4">📝 Ulasan dari Customer</h1>
    <a href="admin_dashboard.php" class="text-sm text-blue-600 hover:underline">← Kembali ke Dashboard</a>

    <table class="mt-4 w-full border border-gray-300">
      <thead class="bg-pink-100 text-pink-700">
        <tr>
          <th class="p-2 border">Customer</th>
          <th class="p-2 border">Produk</th>
          <th class="p-2 border">Rating</th>
          <th class="p-2 border">Komentar</th>
          <th class="p-2 border">Gambar</th>
          <th class="p-2 border">Tanggal</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr class="text-center border-t">
          <td class="p-2 border"><?= htmlspecialchars($row['nama_customer']) ?></td>
          <td class="p-2 border"><?= htmlspecialchars($row['nama_produk']) ?></td>
          <td class="p-2 border"><?= htmlspecialchars($row['rating']) ?> ⭐</td>
          <td class="p-2 border"><?= htmlspecialchars($row['komentar']) ?></td>
          <td class="p-2 border">
            <?php if (!empty($row['gambar'])): ?>
              <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" class="w-16 h-16 object-cover mx-auto rounded" alt="Gambar Ulasan">
            <?php else: ?>
              -
            <?php endif; ?>
          </td>
          <td class="p-2 border"><?= date("d-m-Y H:i", strtotime($row['tanggal_ulasan'])) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
