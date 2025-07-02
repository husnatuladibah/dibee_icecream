<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login dan role admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $statusBaru = mysqli_real_escape_string($koneksi, $_POST['status']);

    // Update status pesanan
    $query = "UPDATE pesanan SET status = '$statusBaru' WHERE id = $id";
    if (mysqli_query($koneksi, $query)) {
        // Redirect ke halaman detail pesanan agar tampilan terupdate
        header("Location: detail_pesanan.php?id=$id&update=success");
        exit;
    } else {
        echo "Gagal mengubah status: " . mysqli_error($koneksi);
    }
} else {
    // Jika akses bukan POST, redirect ke dashboard admin
    header("Location: admin_dashboard.php");
    exit;
}
