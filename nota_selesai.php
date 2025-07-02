<?php
$nama = "Husnatul Adibah";
$nomor_meja = "1";
$total = "Rp 20.000";
$produk = [
    ["ice cream coklat", 1, "Rp 20.000"]
];
$tanggal = "02-07-2025 11:34";
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
  <meta charset="UTF-8">
  <title>Nota Pesanan Dibee</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 30px;
      background-color: #fff0f5;
    }

    .nota {
      max-width: 550px;
      margin: auto;
      padding: 30px;
      border-radius: 12px;
      background: #fff8fb;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      border: 2px solid #ffc0cb;
    }

    .maskot {
      display: block;
      margin: 0 auto 10px auto;
      width: 100px;
    }

    h2 {
      text-align: center;
      color: #d63384;
      margin-bottom: 15px;
    }

    p {
      font-size: 16px;
      color: #444;
      margin: 6px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th {
      background-color: #ffe6f0;
      color: #c2185b;
    }

    td, th {
      border: 1px solid #f3c3d9;
      padding: 10px;
      text-align: left;
    }

    .print-btn {
      margin-top: 25px;
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    button {
      background-color: #28a745;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 15px;
      transition: background 0.3s;
    }

    button:hover {
      background-color: #218838;
    }

    .dashboard-btn {
      background-color: #e83e8c;
    }

    .dashboard-btn:hover {
      background-color: #c2185b;
    }

    .footer {
      text-align: center;
      margin-top: 30px;
      font-size: 13px;
      color: #999;
    }
  </style>
</head>
<body>

<div class="nota">
  <img src="maskot.png" alt="Maskot Dibee" class="maskot">
  <h2>Nota Pesanan Dibee Ice Cream 🍦🐝</h2>
  <p><strong>No. Pesanan:</strong> 31</p>
  <p><strong>Nama Customer:</strong> <?= $nama ?></p>
  <p><strong>No. Meja:</strong> <?= $nomor_meja ?></p>
  <p><strong>Metode Pembayaran:</strong> Transfer Bank</p>
  <p><strong>Tanggal:</strong> <?= $tanggal ?></p>

  <table>
    <tr>
      <th>Produk</th>
      <th>Jumlah</th>
      <th>Subtotal</th>
    </tr>
    <?php foreach ($produk as $p): ?>
    <tr>
      <td><?= $p[0] ?></td>
      <td><?= $p[1] ?></td>
      <td><?= $p[2] ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
      <th colspan="2">Total</th>
      <th><?= $total ?></th>
    </tr>
  </table>

  <div class="print-btn">
    <button onclick="window.print()">🖨️ Simpan / Cetak PDF</button>
    <button class="dashboard-btn" onclick="window.location.href='dashboard.php'">← Kembali ke Dashboard</button>
  </div>
</div>

<div class="footer">
  Dibee Ice Cream © <?= date("Y") ?> | Terima kasih telah berbelanja 💖
</div>

</body>
</html>
