<?php
session_start();
include 'koneksi.php';

// Cek apakah sudah login dan role admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // Cek email sudah ada atau belum
    $cekEmail = "SELECT id FROM pengguna WHERE email='$email' LIMIT 1";
    $resultCek = mysqli_query($koneksi, $cekEmail);

    if (mysqli_num_rows($resultCek) > 0) {
        $error = "Email sudah terdaftar.";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'admin'; // tetap admin

        $query = "INSERT INTO pengguna (nama, email, password, role) VALUES ('$nama', '$email', '$passwordHash', '$role')";
        if (mysqli_query($koneksi, $query)) {
            $success = "Admin berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan admin: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
    <title>Tambah Admin</title>
</head>
<body>
<h2>Tambah Admin Baru</h2>
<?php 
if ($error) echo "<p style='color:red;'>$error</p>";
if ($success) echo "<p style='color:green;'>$success</p>";
?>
<form method="POST" action="tambah_admin.php">
    <input type="text" name="nama" placeholder="Nama Admin" required>
    <input type="email" name="email" placeholder="Email Admin" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Tambah Admin</button>
</form>
<p><a href="admin_dashboard.php">Kembali ke Dashboard Admin</a></p>
</body>
</html>
