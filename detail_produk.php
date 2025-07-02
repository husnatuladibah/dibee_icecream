<?php
include 'koneksi.php';

// Cek apakah ada parameter id di URL
if (!isset($_GET['id'])) {
    echo "ID produk tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']); // amankan input

// Query ambil data produk berdasarkan id
$query = "SELECT p.*, k.nama_kategori FROM produk p 
          LEFT JOIN kategori k ON p.kategori_id = k.id 
          WHERE p.id = $id LIMIT 1";
$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Produk tidak ditemukan.";
    exit;
}

$produk = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
    <title>Detail Produk - <?php echo htmlspecialchars($produk['nama']); ?></title>
</head>
<body>
    <h2><?php echo htmlspecialchars($produk['nama']); ?></h2>
    <img src="uploads/<?php echo htmlspecialchars($produk['gambar']); ?>" alt="<?php echo htmlspecialchars($produk['nama']); ?>" style="max-width:300px;">
    <p><strong>Kategori:</strong> <?php echo htmlspecialchars($produk['nama_kategori']); ?></p>
    <p><strong>Harga:</strong> Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></p>
    <p><strong>Deskripsi:</strong><br><?php echo nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>

    <form action="keranjang.php" method="POST">
    <input type="hidden" name="produk_id" value="<?php echo $produk['id']; ?>">
    <label for="jumlah">Jumlah:</label>
    <input type="number" id="jumlah" name="jumlah" value="1" min="1" required>
    <button type="submit">Tambah ke Keranjang</button>
    </form>

    <a href="customer_dashboard.php">Kembali ke Daftar Produk</a>
</body>
</html>
