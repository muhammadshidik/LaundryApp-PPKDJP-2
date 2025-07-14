-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Jul 2025 pada 16.59
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_laundry`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `customer`
--

INSERT INTO `customer` (`id`, `customer_name`, `phone`, `address`, `created_at`, `updated_at`, `deleted_at`) VALUES
(24, 'Hardianti', '0899828282827', 'Jakarta Selatan', '2025-06-22 07:12:34', '2025-07-14 12:13:38', 0),
(25, 'Muhammad Siddiq', '089684758768', 'Jakarta Timur', '2025-06-26 02:38:16', '2025-07-14 11:43:22', 0),
(26, 'Abdullah Faqih', '085810382828', 'Jakarta Barat', '2025-07-14 12:16:20', '2025-07-14 12:16:20', 0),
(27, 'Agra Saputra', '080887379338', 'Sawangan, Depok', '2025-07-14 12:17:01', '2025-07-14 12:17:01', 0),
(28, 'Aldo Rio Prayoga', '08373373773', 'Bekasi Kota, Jawa Barat', '2025-07-14 12:17:33', '2025-07-14 12:17:33', 0),
(29, 'Ananda Nabila Luthfiyyah', '080887379338', 'Jakarta Selatan', '2025-07-14 12:17:54', '2025-07-14 12:17:54', 0),
(30, 'Angela', '085810382828', 'Jakarta Barat', '2025-07-14 12:18:09', '2025-07-14 12:18:09', 0),
(31, 'Aryo Putranto', '080887379338', 'Jakarta Timur', '2025-07-14 12:18:37', '2025-07-14 12:18:37', 0),
(32, 'Diriansyah', '08373373773', 'Jakarta Pusat', '2025-07-14 12:18:53', '2025-07-14 12:18:53', 0),
(33, 'Erssa Istary Yusuf', '085810382828', 'Jakarta Pusat', '2025-07-14 12:19:20', '2025-07-14 12:19:20', 0),
(34, 'Intan Dwi Yasarah', '080887379338', 'Jakarta Pusat', '2025-07-14 12:19:43', '2025-07-14 12:19:43', 0),
(35, 'Muhammad Reihan', '085810382828', 'Warakas, Jakarta Pusat', '2025-07-14 12:20:08', '2025-07-14 12:20:08', 0),
(36, 'Raihan Adliansyah', '08373373773', 'Jakarta Pusat', '2025-07-14 12:20:41', '2025-07-14 12:20:41', 0),
(37, 'Raymond Agung Nugroho', '085810382828', 'Tanggerang, Banten', '2025-07-14 12:21:11', '2025-07-14 12:21:11', 0),
(38, 'Salsabila Suci Gustiani', '085810382828', 'Jakarta Selatan', '2025-07-14 12:21:43', '2025-07-14 12:21:43', 0),
(39, 'Sayyid Hamzah Azzami', '085810382828', 'Jakarta Pusat', '2025-07-14 12:22:03', '2025-07-14 12:22:03', 0),
(40, 'Sayyid Umar Hasani', '085810382828', 'Jakarta Timur', '2025-07-14 12:22:30', '2025-07-14 12:22:30', 0),
(41, 'Shofian Al FIkri Subagio', '08373373773', 'Jakarta Utara', '2025-07-14 12:22:59', '2025-07-14 12:22:59', 0),
(42, 'William Setiandy', '080887379338', 'Jakarta Selatan', '2025-07-14 12:23:18', '2025-07-14 12:23:18', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `level`
--

CREATE TABLE `level` (
  `id` int(11) NOT NULL,
  `level_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `level`
--

INSERT INTO `level` (`id`, `level_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrator', '2024-11-11 02:11:05', '2024-12-02 02:07:59', 0),
(2, 'Operator', '2024-11-11 02:11:05', '2024-11-15 01:36:53', 0),
(3, 'Pimpinan', '2024-11-11 02:11:24', '2024-12-02 02:08:28', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `pengeluaran` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengeluaran`
--

INSERT INTO `pengeluaran` (`id`, `tanggal`, `deskripsi`, `pengeluaran`, `created_at`) VALUES
(1, '2025-07-13', 'qqqq', 2000000, '2025-07-13 05:10:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_laundry_pickup`
--

CREATE TABLE `trans_laundry_pickup` (
  `id` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `pickup_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `trans_laundry_pickup`
--

INSERT INTO `trans_laundry_pickup` (`id`, `id_order`, `id_customer`, `pickup_date`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(8, 65, 24, '2025-06-22', NULL, '2025-06-24 13:30:11', '2025-06-24 13:30:11', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_order`
--

CREATE TABLE `trans_order` (
  `id` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `order_date` varchar(50) NOT NULL,
  `order_end_date` date DEFAULT NULL,
  `order_status` tinyint(11) NOT NULL DEFAULT 0,
  `total_price` int(11) NOT NULL,
  `order_pay` int(11) DEFAULT NULL,
  `order_change` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `trans_order`
--

INSERT INTO `trans_order` (`id`, `id_customer`, `order_code`, `order_date`, `order_end_date`, `order_status`, `total_price`, `order_pay`, `order_change`, `created_at`, `updated_at`, `deleted_at`) VALUES
(65, 24, 'LNDRY-202506241342281', '2025-06-20', '2025-06-13', 1, 30, 4500, 4470, '2025-06-24 11:42:42', '2025-06-24 13:30:11', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_order_detail`
--

CREATE TABLE `trans_order_detail` (
  `id` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `trans_order_detail`
--

INSERT INTO `trans_order_detail` (`id`, `id_order`, `id_service`, `qty`, `subtotal`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(65, 63, 7, 3, 14, NULL, '2025-06-24 10:57:25', '2025-06-24 10:57:25', 0),
(66, 64, 6, 5, 25, NULL, '2025-06-24 11:07:51', '2025-06-24 11:07:51', 0),
(67, 65, 8, 6, 30, NULL, '2025-06-24 11:42:43', '2025-06-24 11:42:43', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `type_of_service`
--

CREATE TABLE `type_of_service` (
  `id` int(11) NOT NULL,
  `service_name` varchar(50) NOT NULL,
  `price` float NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `type_of_service`
--

INSERT INTO `type_of_service` (`id`, `service_name`, `price`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(6, '🔥 Setrika Saja', 3000, 'Setrika Saja Rp.3000/kg', '2025-06-22 07:15:47', '2025-07-14 12:50:07', 0),
(7, '👟 Cuci Sepatu', 25000, 'Cuci Sepatu Rp25.000/Pasang', '2025-06-22 07:16:18', '2025-07-14 12:49:49', 0),
(8, '👔 Cuci Setrika', 7000, 'Cuci Setrika Rp.7000/kg', '2025-06-22 07:16:41', '2025-07-14 12:47:32', 0),
(10, '🌪️ Cuci Kering', 5000, 'Cuci Kering Rp.5000/kg', '2025-06-26 02:58:30', '2025-07-14 12:47:04', 0),
(11, '✨ Dry Clean', 15000, 'Dry Clean Rp.15000/kg', '2025-07-14 11:29:01', '2025-07-14 12:49:25', 0),
(12, '🏠  Cuci Karpet', 20000, 'Cuci Karpet Rp.20000/m2', '2025-07-14 11:30:09', '2025-07-14 12:49:04', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `id_level` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `profile_picture` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `id_level`, `username`, `email`, `password`, `profile_picture`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Muhammad Siddiq', 'admin@gmail.com', '123', 'profile_picture1.jpeg', '2024-11-11 02:30:45', '2025-07-13 11:31:15', 0),
(14, 2, 'Pak Reza Ibrahim', 'operator@gmail.com', '123', 'profile_picture14.jpg', '2024-11-28 12:16:42', '2025-07-14 11:51:29', 0),
(17, 3, 'Pak Tri', 'leader@gmail.com', '123', '', '2025-07-13 09:16:39', '2025-07-14 11:51:41', 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `trans_laundry_pickup`
--
ALTER TABLE `trans_laundry_pickup`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `trans_order`
--
ALTER TABLE `trans_order`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `trans_order_detail`
--
ALTER TABLE `trans_order_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `type_of_service`
--
ALTER TABLE `type_of_service`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `level`
--
ALTER TABLE `level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `trans_laundry_pickup`
--
ALTER TABLE `trans_laundry_pickup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `trans_order`
--
ALTER TABLE `trans_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT untuk tabel `trans_order_detail`
--
ALTER TABLE `trans_order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT untuk tabel `type_of_service`
--
ALTER TABLE `type_of_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
