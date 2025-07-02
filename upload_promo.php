<?php
session_start();
include 'koneksi.php';

// Cek login & role
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$uploadStatus = "";
$success = false;

// Upload jika ada kiriman POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaFile = basename($_FILES['promo_pdf']['name']);
    $lokasiTmp = $_FILES['promo_pdf']['tmp_name'];
    $ekstensi = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    // Sanitasi nama file
    $namaFileAman = time() . '_' . preg_replace("/[^a-zA-Z0-9-_\.]/", "_", $namaFile);
    $tujuan = 'promo/' . $namaFileAman;

    if ($ekstensi === 'pdf') {
        if (!is_dir('promo')) {
            mkdir('promo', 0777, true);
        }

        if (move_uploaded_file($lokasiTmp, $tujuan)) {
            // Simpan ke DB
            mysqli_query($koneksi, "INSERT INTO promo (nama_file, deskripsi, tanggal_upload) VALUES ('$namaFileAman', '$deskripsi', NOW())");
            $uploadStatus = "✅ Berhasil upload promo!";
            $success = true;
        } else {
            $uploadStatus = "❌ Gagal upload file.";
        }
    } else {
        $uploadStatus = "⚠️ Hanya file PDF yang diperbolehkan.";
    }
}

// Ambil semua promo
$dataPromo = mysqli_query($koneksi, "SELECT * FROM promo ORDER BY tanggal_upload DESC");

// Hapus jika diminta
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM promo WHERE id = $id"));

    if ($data && file_exists('promo/' . $data['nama_file'])) {
        unlink('promo/' . $data['nama_file']);
    }

    mysqli_query($koneksi, "DELETE FROM promo WHERE id = $id");
    header("Location: upload_promo.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
  <meta charset="UTF-8">
  <title>Upload Promo Bulanan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 p-6 min-h-screen">
  <div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow-md">

    <h1 class="text-2xl font-bold text-pink-600 mb-4">📤 Upload Promo Bulanan</h1>

    <?php if ($uploadStatus): ?>
      <div class="mb-5 px-4 py-3 rounded-md <?= $success ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
        <?= $uploadStatus ?>
      </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="mb-8 space-y-4">
      <input type="text" name="deskripsi" placeholder="Deskripsi promo (misal: Diskon Juni)" class="w-full border p-2 rounded" required>
      <input type="file" name="promo_pdf" accept="application/pdf" class="w-full border p-2 rounded" required>
      <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded shadow">Upload PDF</button>
    </form>

    <?php if (mysqli_num_rows($dataPromo) > 0): ?>
      <h2 class="text-xl font-semibold text-pink-700 mb-3">📂 Daftar Promo Terupload</h2>
      <table class="w-full text-sm text-left border border-gray-200 rounded overflow-hidden">
        <thead class="bg-pink-100 text-pink-700">
          <tr>
            <th class="p-2">#</th>
            <th class="p-2">Deskripsi</th>
            <th class="p-2">Tanggal</th>
            <th class="p-2">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white">
          <?php $no = 1; while($p = mysqli_fetch_assoc($dataPromo)): ?>
            <tr class="border-b">
              <td class="p-2"><?= $no++ ?></td>
              <td class="p-2"><?= htmlspecialchars($p['deskripsi'] ?? '-') ?></td>
              <td class="p-2"><?= $p['tanggal_upload'] ?></td>
              <td class="p-2 space-x-2">
                <a href="promo/<?= htmlspecialchars($p['nama_file']) ?>" target="_blank" class="text-blue-600 underline">lihat</a>
                <a href="?hapus=<?= $p['id'] ?>" onclick="return confirm('Yakin ingin menghapus promo ini?')" class="text-red-600 underline">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-gray-500 mt-6">Belum ada promo yang diupload.</p>
    <?php endif; ?>

    <div class="mt-6 text-center">
      <a href="admin_dashboard.php" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded shadow">
        ⬅️ Kembali ke Dashboard
      </a>
    </div>

  </div>
</body>
</html>
