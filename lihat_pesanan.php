<?php
session_start();
include 'koneksi.php';

// Cek session admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$query = "
    SELECT p.id, p.tanggal_pesanan, p.status, u.nama AS nama_customer, u.email
    FROM pesanan p
    JOIN pengguna u ON p.pengguna_id = u.id
    ORDER BY p.tanggal_pesanan DESC
";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
  <meta charset="UTF-8" />
  <title>Daftar Pesanan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans p-6">
  <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4 text-pink-600">📦 Daftar Pesanan</h1>
    <a href="admin_dashboard.php" class="inline-block bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 px-4 rounded transition mb-6">
      ← Kembali ke Dashboard</a>

    <div class="overflow-x-auto">
      <table class="w-full table-auto border border-gray-200">
        <thead class="bg-pink-100 text-pink-800">
          <tr>
            <th class="py-2 px-4 border">ID Pesanan</th>
            <th class="py-2 px-4 border">Nama Customer</th>
            <th class="py-2 px-4 border">Email</th>
            <th class="py-2 px-4 border">Tanggal Pesan</th>
            <th class="py-2 px-4 border">Status</th>
            <th class="py-2 px-4 border">Detail</th>
          </tr>
        </thead>
        <tbody>
        <?php 
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Tentukan warna status
                $warna_status = "text-gray-600";
                if ($row['status'] === 'menunggu') $warna_status = "text-yellow-600 font-semibold";
                else if ($row['status'] === 'diproses') $warna_status = "text-blue-600 font-semibold";
                else if ($row['status'] === 'selesai') $warna_status = "text-green-600 font-semibold";
                else if ($row['status'] === 'batal') $warna_status = "text-red-600 font-semibold";

                echo "<tr class='hover:bg-gray-50'>";
                echo "<td class='py-2 px-4 border text-center'>{$row['id']}</td>";
                echo "<td class='py-2 px-4 border'>{$row['nama_customer']}</td>";
                echo "<td class='py-2 px-4 border'>{$row['email']}</td>";
                echo "<td class='py-2 px-4 border'>{$row['tanggal_pesanan']}</td>";
                echo "<td class='py-2 px-4 border {$warna_status}'>{$row['status']}</td>";
                // Link ke halaman detail_pesanan.php dengan id pesanan sebagai parameter
                echo "<td class='py-2 px-4 border text-center'>
                        <a href='detail_pesanan.php?id={$row['id']}' class='text-blue-500 hover:underline'>Lihat Detail</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='text-center py-4'>Tidak ada pesanan.</td></tr>";
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>