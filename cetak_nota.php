<?php
include 'koneksi.php';

$pesanan_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$pesanan_id) die("ID Pesanan tidak valid.");

// Ambil data pesanan
$q1 = mysqli_query($koneksi, "
    SELECT p.*, u.nama
    FROM pesanan AS p
    JOIN pengguna AS u ON p.pengguna_id = u.id
    WHERE p.id = $pesanan_id
");
$pesanan = mysqli_fetch_assoc($q1);
if (!$pesanan) die("Data pesanan tidak ditemukan.");

// Ambil detail produk
$q2 = mysqli_query($koneksi, "
    SELECT pr.nama, pr.harga, dp.jumlah
    FROM detail_pesanan AS dp
    JOIN produk AS pr ON dp.produk_id = pr.id
    WHERE dp.pesanan_id = $pesanan_id
");

$produk = [];
while ($row = mysqli_fetch_assoc($q2)) {
    $produk[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
  <meta charset="UTF-8">
  <title>Nota Pesanan #<?= $pesanan['id'] ?></title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .nota { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ccc; }
    h2 { text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
    .footer { margin-top: 30px; font-size: 12px; text-align: center; color: #555; }
    .print-btn { margin-top: 20px; text-align: center; }
    .print-btn button,
    .print-btn a {
      display: inline-block;
      margin: 5px;
      background-color: green;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      cursor: pointer;
      font-size: 16px;
    }
    .print-btn a.kembali {
      background-color: #e91e63; /* pink */
    }
  </style>
</head>
<body>

<div class="nota">
  <h2>Nota Pesanan Dibee Ice Cream 🍦🐝</h2>
  <p><strong>No. Pesanan:</strong> <?= $pesanan['id'] ?></p>
  <p><strong>Nama Customer:</strong> <?= htmlspecialchars($pesanan['nama']) ?></p>
  <p><strong>No. Meja:</strong> <?= htmlspecialchars($pesanan['alamat_pengiriman']) ?></p>
  <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($pesanan['metode_pembayaran']) ?></p>
  <p><strong>Tanggal:</strong> <?= date("d-m-Y H:i", strtotime($pesanan['waktu_pesan'] ?? 'now')) ?></p>

  <table>
    <thead>
      <tr>
        <th>Produk</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $total = 0;
      foreach ($produk as $item):
        $subtotal = $item['harga'] * $item['jumlah'];
        $total += $subtotal;
      ?>
      <tr>
        <td><?= htmlspecialchars($item['nama']) ?></td>
        <td><?= $item['jumlah'] ?></td>
        <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
      </tr>
      <?php endforeach; ?>
      <tr>
        <th colspan="2">Total</th>
        <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
      </tr>
    </tbody>
  </table>

  <div class="print-btn">
    <button onclick="window.print()">🧾 Simpan / Cetak PDF</button>
    <a href="customer_dashboard.php" class="kembali">← Kembali ke Dashboard</a>
  </div>

  <div class="footer">
    Dibee Ice Cream &copy; <?= date('Y') ?> | Terima kasih telah berbelanja 💖
  </div>
</div>

</body>
</html>
