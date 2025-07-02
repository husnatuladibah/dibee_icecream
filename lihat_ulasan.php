<?php
session_start();
include 'koneksi.php';

// Cek session admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$query = "
    SELECT uls.id, p.nama AS nama_produk, pg.nama AS nama_customer, uls.rating, uls.komentar, uls.tanggal_ulasan
    FROM ulasan uls
    JOIN produk p ON uls.produk_id = p.id
    JOIN pengguna pg ON uls.pengguna_id = pg.id
    ORDER BY uls.tanggal_ulasan DESC
";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
    <title>Daftar Ulasan</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff0f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(255, 182, 193, 0.4);
        }

        h2 {
            text-align: center;
            color: #d63384;
        }

        a.back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            background-color: #ffc0cb;
            padding: 8px 15px;
            border-radius: 12px;
            color: #fff;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        a.back-link:hover {
            background-color: #ff69b4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            border: 1px solid #f5c2d7;
            padding: 12px;
            text-align: center;
        }

        table th {
            background-color: #f8d7da;
            color: #721c24;
        }

        table tr:nth-child(even) {
            background-color: #fff0f5;
        }

        p {
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Daftar Ulasan Customer</h2>
        <a class="back-link" href="admin_dashboard.php">← Kembali ke Dashboard</a>

        <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>ID Ulasan</th>
                <th>Produk</th>
                <th>Nama Customer</th>
                <th>Rating</th>
                <th>Komentar</th>
                <th>Tanggal</th>
            </tr>
            <?php while($ulasan = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($ulasan['id']) ?></td>
                <td><?= htmlspecialchars($ulasan['nama_produk']) ?></td>
                <td><?= htmlspecialchars($ulasan['nama_customer']) ?></td>
                <td><?= htmlspecialchars($ulasan['rating']) ?></td>
                <td><?= htmlspecialchars($ulasan['komentar']) ?></td>
                <td><?= htmlspecialchars($ulasan['tanggal_ulasan']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>Tidak ada ulasan.</p>
        <?php endif; ?>
    </div>
</body>
</html>

