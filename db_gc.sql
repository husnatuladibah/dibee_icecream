-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2025 at 12:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_gc`
--

-- --------------------------------------------------------

--
-- Table structure for table `balasan_ulasan`
--

CREATE TABLE `balasan_ulasan` (
  `id` int(11) NOT NULL,
  `ulasan_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `isi_balasan` text NOT NULL,
  `tanggal_balasan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id` int(11) NOT NULL,
  `pesanan_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id`, `pesanan_id`, `produk_id`, `jumlah`) VALUES
(5, 19, 22, 1),
(6, 20, 22, 1),
(7, 21, 22, 2),
(8, 22, 22, 1),
(9, 23, 22, 1),
(10, 24, 22, 1),
(11, 25, 22, 1),
(12, 26, 22, 1),
(13, 27, 22, 3),
(14, 28, 22, 1),
(15, 29, 22, 1),
(16, 30, 22, 1),
(17, 31, 22, 1),
(18, 32, 22, 1),
(19, 33, 22, 1),
(20, 34, 22, 3);

-- --------------------------------------------------------

--
-- Table structure for table `metode_pembayaran`
--

CREATE TABLE `metode_pembayaran` (
  `id` int(11) NOT NULL,
  `nama_metode` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `email`, `password`, `role`, `tanggal_dibuat`) VALUES
(3, 'M Tawfikur Rohman', 'Tawfik@gmail.com', 'fikbah123', 'customer', '2025-05-24 12:33:16'),
(4, 'admin', 'admin@gmail.com', 'admin123', 'admin', '2025-05-24 12:36:41'),
(5, 'Husnatul Adibah', 'husnatul.adibah783@gmail.com', 'adibah123', 'customer', '2025-05-25 07:55:45'),
(6, 'LISA NAILUL AMANI', 'nailul@gmail.com', 'nailul123', 'customer', '2025-05-28 07:35:37'),
(7, 'yuni sunarmi ningsih', 'yuni@gmail.com', 'uni123', 'customer', '2025-05-28 07:37:36'),
(10, 'eka nanda', 'eka@gmail.com', '$2y$10$mlRqKFDksGSWshdcttLfpeXy.78DEbn3VCwJ7.loQ0adg2lhrFQTG', 'customer', '2025-06-20 23:03:11'),
(11, 'hafidz', 'haf@gmail.com', '$2y$10$vruZlMmkpjh1cmqGra/2mOHVtvrYKPwvP0D2wgYUEUvyEHB1YI7bq', 'customer', '2025-06-30 06:27:22');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `pengguna_id` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `alamat_pengiriman` text NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `status` enum('menunggu','diproses','dikirim','selesai','batal') DEFAULT 'menunggu',
  `tanggal_pesanan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `pengguna_id`, `total_harga`, `alamat_pengiriman`, `metode_pembayaran`, `status`, `tanggal_pesanan`) VALUES
(2, 3, 25.00, 'krajan', 'COD', 'selesai', '2025-05-24 17:00:00'),
(3, 5, 175.00, 'krajan', 'COD', 'selesai', '2025-05-29 17:00:00'),
(4, 3, 13.00, 'rembang', 'COD', 'selesai', '2025-05-29 17:00:00'),
(5, 7, 50.00, 'plososari', 'Transfer Bank', 'selesai', '2025-05-30 17:00:00'),
(6, 7, 50.00, 'plososari', 'COD', 'selesai', '2025-05-30 17:00:00'),
(7, 6, 13.00, 'pasuruan', 'COD', 'selesai', '2025-05-30 17:00:00'),
(8, 6, 13.00, 'pasuruan', 'COD', 'selesai', '2025-05-31 12:45:10'),
(9, 5, 13.00, 'jakarta', 'COD', 'selesai', '2025-06-01 00:03:40'),
(10, 3, 13.00, 'nguling', 'Transfer Bank', 'selesai', '2025-06-01 02:49:34'),
(11, 5, 13.00, 'plososari', 'COD', 'selesai', '2025-06-01 08:02:31'),
(12, 5, 26.00, 'rembang', 'COD', 'selesai', '2025-06-01 08:15:23'),
(13, 5, 13.00, 'bismi', 'COD', 'selesai', '2025-06-01 08:19:56'),
(14, 5, 13.00, 'bismi', 'COD', 'selesai', '2025-06-01 08:22:02'),
(15, 3, 13.00, 'plososari', 'Transfer Bank', 'selesai', '2025-06-01 12:08:55'),
(16, 3, 13.00, 'poloso', 'Transfer Bank', 'selesai', '2025-06-01 12:14:10'),
(17, 5, 13000.00, 'krajan', 'Transfer Bank', 'selesai', '2025-06-01 14:03:02'),
(18, 5, 26000.00, 'plososari', 'COD', 'selesai', '2025-06-03 11:29:33'),
(19, 5, 20000.00, 'plososari', 'COD', 'selesai', '2025-06-29 07:09:13'),
(20, 5, 20000.00, 'meja 12', 'Cash', 'selesai', '2025-06-30 04:20:16'),
(21, 5, 40000.00, 'meja 9', 'Cash', 'selesai', '2025-06-30 04:22:57'),
(22, 5, 20000.00, '7', 'Transfer Bank', 'selesai', '2025-06-30 04:25:38'),
(23, 5, 20000.00, '21', 'Transfer Bank', 'selesai', '2025-06-30 04:27:34'),
(24, 5, 20000.00, '3', 'Cash', 'selesai', '2025-06-30 04:31:01'),
(25, 5, 20000.00, '1', 'Transfer Bank', 'selesai', '2025-06-30 05:46:32'),
(26, 5, 20000.00, '2', 'Transfer Bank', 'selesai', '2025-06-30 06:00:01'),
(27, 7, 60000.00, '13', 'Transfer Bank', 'selesai', '2025-06-30 06:28:53'),
(28, 7, 20000.00, '12', 'Transfer Bank', 'selesai', '2025-06-30 06:36:09'),
(29, 5, 20000.00, '4', 'Transfer Bank', 'menunggu', '2025-07-02 08:58:55'),
(30, 5, 20000.00, '4', 'Transfer Bank', 'menunggu', '2025-07-02 09:12:33'),
(31, 5, 20000.00, '1', 'Transfer Bank', 'menunggu', '2025-07-02 09:34:03'),
(32, 7, 20000.00, '3', 'Transfer Bank', 'menunggu', '2025-07-02 09:38:19'),
(33, 3, 20000.00, '5', 'Transfer Bank', 'menunggu', '2025-07-02 09:39:38'),
(34, 5, 60000.00, '3', 'Transfer Bank', 'menunggu', '2025-07-02 09:51:01');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama`, `deskripsi`, `harga`, `stok`, `gambar`, `kategori`, `status`, `tanggal_dibuat`) VALUES
(22, 'ice cream coklat', 'coklat enak', 20000.00, 9, 'produk_6860e5d03ebd5.jpg', 'Ice Cream Reguler', 'aktif', '2025-06-29 07:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `promo`
--

CREATE TABLE `promo` (
  `id` int(11) NOT NULL,
  `nama_file` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_upload` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promo`
--

INSERT INTO `promo` (`id`, `nama_file`, `deskripsi`, `tanggal_upload`) VALUES
(5, '1751283712_promo_july.pdf', 'Promo bulan Juli', '2025-06-30 18:41:52'),
(6, '1751450486_promo_july.pdf', 'promo enak', '2025-07-02 17:01:26');

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `id` int(11) NOT NULL,
  `pengguna_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `komentar` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggal_ulasan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ulasan`
--

INSERT INTO `ulasan` (`id`, `pengguna_id`, `produk_id`, `rating`, `komentar`, `gambar`, `tanggal_ulasan`) VALUES
(6, 5, 22, 3, 'enak banget', NULL, '2025-06-29 07:10:40'),
(7, 5, 22, 4, 'enak murah', NULL, '2025-06-30 11:54:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `balasan_ulasan`
--
ALTER TABLE `balasan_ulasan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ulasan_id` (`ulasan_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesanan_id` (`pesanan_id`),
  ADD KEY `detail_pesanan_ibfk_2` (`produk_id`);

--
-- Indexes for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_metode` (`nama_metode`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengguna_id` (`pengguna_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori`);

--
-- Indexes for table `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengguna_id` (`pengguna_id`),
  ADD KEY `ulasan_ibfk_2` (`produk_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `balasan_ulasan`
--
ALTER TABLE `balasan_ulasan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `promo`
--
ALTER TABLE `promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `balasan_ulasan`
--
ALTER TABLE `balasan_ulasan`
  ADD CONSTRAINT `balasan_ulasan_ibfk_1` FOREIGN KEY (`ulasan_id`) REFERENCES `ulasan` (`id`),
  ADD CONSTRAINT `balasan_ulasan_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `pengguna` (`id`);

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`),
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`);

--
-- Constraints for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `ulasan_ibfk_1` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`),
  ADD CONSTRAINT `ulasan_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
