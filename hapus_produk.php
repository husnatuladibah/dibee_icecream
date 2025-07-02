<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);

// Hapus gambar terlebih dahulu (opsional)
$result = mysqli_query($koneksi, "SELECT gambar FROM produk WHERE id=$id");
$row = mysqli_fetch_assoc($result);
if ($row && file_exists('uploads/' . $row['gambar'])) {
    unlink('uploads/' . $row['gambar']);
}

// Hapus dari database
$hapus = mysqli_query($koneksi, "DELETE FROM produk WHERE id=$id");

if ($hapus) {
    header("Location: daftar_produk.php");
    exit;
} else {
    echo "Gagal hapus produk: " . mysqli_error($koneksi);
}
?>
