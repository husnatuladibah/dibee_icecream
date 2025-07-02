<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM pengguna WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nama'] = $user['nama'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: customer_dashboard.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Dibee Ice Cream</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-5OaDdZDZ+..." crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">

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
      width: 64px;
      animation: fall 6s linear infinite;
    }

    .fall-delay-1 { left: 10%; animation-delay: 0s; }
    .fall-delay-2 { left: 30%; animation-delay: 2s; }
    .fall-delay-3 { left: 60%; animation-delay: 1s; }
    .fall-delay-4 { left: 80%; animation-delay: 3s; }
  </style>
</head>
<link rel="icon" type="image/png" sizes="32x32" href="img/favicon.png">
<body class="bg-gradient-to-b from-pink-100 via-pink-50 to-pink-100 flex items-center justify-center min-h-screen relative overflow-hidden">

  <!-- Tombol Sound -->
  <div class="absolute top-5 right-5 z-50 cursor-pointer" onclick="toggleSound()">
    <img id="soundIcon" src="icon/sound-on.png" class="w-8 h-8" />
  </div>
  <audio id="bgSound" src="icon/ice-cream-bell.mp3" autoplay loop></audio>

  <!-- Ice Cream Jatuh -->
  <img src="icon/ice-cream1.png" class="falling fall-delay-1">
  <img src="icon/ice-cream2.png" class="falling fall-delay-2">
  <img src="icon/ice-cream3.png" class="falling fall-delay-3">
  <img src="icon/ice-cream4.png" class="falling fall-delay-4">

  <!-- Form Login -->
  <div class="bg-white/90 p-8 rounded-3xl shadow-xl w-full max-w-sm text-center backdrop-blur-md z-10">
    <img src="img/logo-dibee.png" alt="Logo Dibee" class="mx-auto w-32 mb-4 drop-shadow" />
    <h2 class="text-2xl font-bold text-pink-600 mb-6">Login ke Akunmu 🍦</h2>

    <form action="login.php" method="POST" class="space-y-4 text-left">
      <!-- Email -->
      <div class="relative">
        <i class="fa-solid fa-envelope absolute left-3 top-3.5 text-pink-400"></i>
        <input type="email" name="email" placeholder="Email"
          class="pl-10 w-full p-3 border border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 transition" required />
      </div>
      <!-- Password -->
      <div class="relative">
        <i class="fa-solid fa-lock absolute left-3 top-3.5 text-pink-400"></i>
        <input type="password" name="password" placeholder="Password"
          class="pl-10 w-full p-3 border border-pink-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 transition" required />
      </div>
      <!-- Tombol Login -->
      <button type="submit"
        class="w-full bg-gradient-to-r from-pink-400 to-pink-500 hover:from-pink-500 hover:to-pink-600 text-white font-semibold py-3 rounded-xl shadow-lg transition-all duration-200">
        <i class="fa-solid fa-right-to-bracket mr-2"></i>Login
      </button>
    </form>

    <p class="mt-4 text-sm text-gray-600 text-center">Belum punya akun?
      <a href="register.php" class="text-pink-500 font-semibold hover:underline">Daftar di sini</a>
    </p>
    <p class="text-pink-500 mt-6 italic">"Masuk dulu yuk, biar rasa manisnya makin terasa 🍨"</p>
  </div>

  <script>
    const audio = document.getElementById("bgSound");
    const icon = document.getElementById("soundIcon");

    function toggleSound() {
      if (audio.paused) {
        audio.play();
        icon.src = "icon/sound-on.png";
      } else {
        audio.pause();
        icon.src = "icon/sound-off.png";
      }
    }
  </script>
</body>
</html>
