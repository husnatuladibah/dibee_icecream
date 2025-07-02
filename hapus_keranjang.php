<?php
session_start();

// Cek apakah ID produk dikirim lewat POST
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Hapus item dari session keranjang
    if (isset($_SESSION['keranjang'][$id])) {
        unset($_SESSION['keranjang'][$id]);
    }
}

// Redirect kembali ke halaman keranjang
header("Location: keranjang.php");
exit;
