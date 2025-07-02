<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login dan role-nya customer
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

// Ambil data pengguna dari database berdasarkan user_id
$user_id = $_SESSION['user_id'];
$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id = '$user_id'");
$data = mysqli_fetch_assoc($query);

$nama = $data['nama'];
$email = $data['email'];
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
  <title>Profil Pengguna</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fff0f5;
      padding: 40px;
      text-align: center;
    }

    .card {
      background: #fff;
      padding: 30px;
      margin: auto;
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      max-width: 400px;
    }

    .card h2 {
      color: #e91e63;
      margin-bottom: 20px;
    }

    .card p {
      font-size: 16px;
      color: #333;
    }

    .btn {
      display: inline-block;
      padding: 10px 20px;
      margin: 10px 5px;
      border: none;
      border-radius: 8px;
      background-color: #e91e63;
      color: white;
      text-decoration: none;
      font-weight: bold;
    }

    .btn:hover {
      background-color: #d81b60;
    }
  </style>
</head>
<body>

<div class="card">
  <h2>Profil Pengguna</h2>
  <p><strong>Nama:</strong> <?= htmlspecialchars($nama) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>

  <a href="customer_dashboard.php" class="btn">🏠 Kembali ke Beranda</a>
  <a href="logout.php" class="btn">🔐 Logout</a>
</div>

</body>
</html>
