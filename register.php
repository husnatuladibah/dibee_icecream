<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Amankan input
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $konfirmasi = mysqli_real_escape_string($koneksi, $_POST['konfirmasi_password']);
    
    $role = 'customer';

    // Validasi konfirmasi password
    if ($password !== $konfirmasi) {
        echo "<script>alert('Konfirmasi password tidak cocok!'); window.history.back();</script>";
        exit;
    }

    // Cek apakah email sudah terdaftar
    $cekEmail = "SELECT id FROM pengguna WHERE email='$email' LIMIT 1";
    $resultCek = mysqli_query($koneksi, $cekEmail);

    if (mysqli_num_rows($resultCek) > 0) {
        echo "<script>alert('Email sudah terdaftar. Silakan login atau gunakan email lain.'); window.history.back();</script>";
        exit;
    }

    // Enkripsi password sebelum simpan
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user baru
    $query = "INSERT INTO pengguna (nama, email, password, role) VALUES ('$nama', '$email', '$hashedPassword', '$role')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // Redirect ke login setelah berhasil
        header("Location: login.php?success=1");
        exit;
    } else {
        echo "<script>alert('Registrasi gagal: " . mysqli_error($koneksi) . "'); window.history.back();</script>";
    }
}
?>

<!-- register.html -->
<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Dibee Ice Cream</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    body {
      font-family: 'Nunito', sans-serif;
    }

    @keyframes fall {
      0% { top: -60px; opacity: 0; }
      10% { opacity: 1; }
      100% { top: 100%; opacity: 0; }
    }

    .falling {
      position: absolute;
      width: 48px;
      animation: fall 6s linear infinite;
    }

    .fall-delay-1 { left: 10%; animation-delay: 0s; }
    .fall-delay-2 { left: 30%; animation-delay: 2s; }
    .fall-delay-3 { left: 60%; animation-delay: 1s; }
    .fall-delay-4 { left: 80%; animation-delay: 3s; }

    @keyframes drop {
      0% { transform: translateY(-100px); opacity: 0; }
      30% { opacity: 1; }
      100% { transform: translateY(0); opacity: 1; }
    }

    .drop-once {
      animation: drop 1.5s ease-out forwards;
    }

    .delay-1 { animation-delay: 0.2s; }
    .delay-2 { animation-delay: 0.5s; }
    .delay-3 { animation-delay: 0.8s; }
    .delay-4 { animation-delay: 1s; }

    .sound-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      z-index: 50;
      width: 32px;
      cursor: pointer;
    }
  </style>
</head>
<body class="bg-pink-100 flex items-center justify-center min-h-screen relative overflow-hidden">

  <!-- Ice Cream jatuh -->
  <img src="icon/ice-cream1.png" class="falling fall-delay-1">
  <img src="icon/ice-cream2.png" class="falling fall-delay-2">
  <img src="icon/ice-cream3.png" class="falling fall-delay-3">
  <img src="icon/ice-cream4.png" class="falling fall-delay-4">

  <!-- Sound button -->
  <img id="soundIcon" src="icon/sound-on.png" class="sound-btn" onclick="toggleSound()" alt="Sound Control">
  <audio id="bgSound" src="icon/ice-cream-bell.mp3" autoplay loop></audio>

  <!-- Form Registrasi -->
  <div class="bg-white/90 p-8 rounded-3xl shadow-xl w-full max-w-md text-center backdrop-blur-md z-10 drop-once delay-1">
    <!-- Logo baru sesuai login -->
    <img src="img/logo-dibee.png" alt="Logo Dibee" class="mx-auto w-28 mb-1 drop-once delay-2" />
    <h2 class="text-2xl font-bold text-pink-600 mb-6 drop-once delay-3">Daftar Akun Baru 🍦</h2>

    <form action="register.php" method="POST" class="space-y-4 drop-once delay-4 text-left">
      <!-- Nama -->
      <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-pink-400"><i class="fas fa-user"></i></span>
        <input type="text" name="nama" placeholder="Nama Lengkap"
          class="w-full pl-10 p-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400" required />
      </div>

      <!-- Email -->
      <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-pink-400"><i class="fas fa-envelope"></i></span>
        <input type="email" name="email" placeholder="Email"
          class="w-full pl-10 p-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400" required />
      </div>

      <!-- Password -->
      <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-pink-400"><i class="fas fa-lock"></i></span>
        <input type="password" name="password" placeholder="Password"
          class="w-full pl-10 p-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400" required />
      </div>

      <!-- Konfirmasi Password -->
      <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-pink-400"><i class="fas fa-lock"></i></span>
        <input type="password" name="konfirmasi_password" placeholder="Konfirmasi Password"
          class="w-full pl-10 p-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400" required />
      </div>

      <!-- Tombol Daftar -->
      <button type="submit"
        class="w-full bg-pink-500 hover:bg-pink-600 text-white font-semibold py-3 rounded-lg shadow-md transition">
        Daftar
      </button>
    </form>

    <p class="mt-4 text-sm text-gray-600">Sudah punya akun?
      <a href="login.php" class="text-pink-500 font-semibold hover:underline">Login di sini</a>
    </p>
  </div>

  <script>
    const sound = document.getElementById('bgSound');
    const icon = document.getElementById('soundIcon');

    function toggleSound() {
      if (sound.paused) {
        sound.play();
        icon.src = 'icon/sound-on.png';
      } else {
        sound.pause();
        icon.src = 'icon/sound-off.png';
      }
    }
  </script>
</body>
</html>

