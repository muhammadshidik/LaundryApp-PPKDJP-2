-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Jul 2025 pada 10.32
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
-- Database: `db_laundri`
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
(1, 'Muhammad Siddiq', '089684758768', 'Jakarta Timur', '2024-11-29 17:37:19', '2025-07-14 02:51:37', 0),
(23, 'Abdullah Faqih', '085810382828', 'Jakarta Barat', '2025-07-14 02:41:02', '2025-07-14 02:41:02', 0),
(24, 'Agra Saputra', '087887695252', 'Sawangan, Depok', '2025-07-14 02:41:51', '2025-07-14 02:41:51', 0),
(25, 'Aldo Rio Prayoga', '081317487094', 'Bekasi Kota', '2025-07-14 02:42:55', '2025-07-14 02:42:55', 0),
(26, 'Ananda Nabilla Luthfiyyah', '0895341021748', 'Jakarta Selatan', '2025-07-14 02:43:52', '2025-07-14 02:43:52', 0),
(27, 'Angela', '085776355608', 'Jakarta', '2025-07-14 02:45:02', '2025-07-14 02:45:02', 0),
(28, 'Aryo putranto', '08987002882', 'Jakarta Timur', '2025-07-14 02:45:42', '2025-07-14 02:45:51', 0),
(29, 'Diriansyah', '082297789349', 'Jakarta Pusat', '2025-07-14 02:46:35', '2025-07-14 02:46:35', 0),
(30, 'Erssa Istrary Yusuf', '0895331020847', 'Jakarta Selatan', '2025-07-14 02:48:14', '2025-07-14 02:48:14', 0),
(31, 'Hardianti', '0857 2224 0065', 'Jakarta Selatan', '2025-07-14 02:48:47', '2025-07-14 02:48:47', 0),
(32, 'Intan Dwi Yasarah', '087788541945', 'Jakarta Selatan', '2025-07-14 02:49:38', '2025-07-14 02:49:38', 0),
(33, 'Muhammad Raihan', '085710690044', 'Jakarta Utara', '2025-07-14 02:50:26', '2025-07-14 02:50:26', 0),
(34, 'Raihan Adliansyah', '0895403953788', 'Jakarta Selatan', '2025-07-14 02:51:23', '2025-07-14 02:51:23', 0),
(35, 'Raymond Agung Nugroho', '09988877666', 'Jakarta', '2025-07-14 02:52:06', '2025-07-14 02:52:06', 0),
(36, 'Salsabila Suci Gustiani', '082114816010', 'Jakarta Selatan', '2025-07-14 03:40:49', '2025-07-14 03:40:49', 0),
(37, 'Sayyid Hamzah Azzami', ' 085715303916', 'Jakarta Selatan', '2025-07-14 03:42:17', '2025-07-14 03:42:17', 0),
(38, 'Sayyid Umar ', ' 085772169466', 'Jakarta Timur', '2025-07-14 03:43:27', '2025-07-14 03:43:27', 0),
(39, 'Soffian Al Fikri', ' 089687960758', 'Jakarta Utara', '2025-07-14 03:44:13', '2025-07-14 03:44:13', 0),
(40, 'William Setiady', '081384606268', 'Jakarta Selatan', '2025-07-14 03:44:59', '2025-07-14 03:44:59', 0),
(41, 'Joko Tingkir', '085710590044', 'Jl.Warakas II GGIIB NO5B RT005 RW02 KEL.WARAKAS KEC.TANJUNG PRIOK', '2025-07-14 04:13:13', '2025-07-14 04:13:13', 0);

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
(2, 'Operator', '2024-11-11 02:11:05', '2025-07-14 02:07:03', 0),
(3, 'Leader', '2025-07-14 02:06:49', '2025-07-14 02:08:15', 0);

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
(5, 40, 6, '2024-12-03', NULL, '2024-12-02 01:56:48', '2024-12-02 01:56:48', 0),
(8, 76, 41, '2025-07-17', NULL, '2025-07-15 04:40:09', '2025-07-15 04:40:09', 0),
(9, 77, 37, '2025-07-16', NULL, '2025-07-15 04:45:38', '2025-07-15 04:45:38', 0),
(11, 79, 34, '2025-07-16', NULL, '2025-07-15 05:41:12', '2025-07-15 05:41:12', 0),
(12, 80, 39, '2025-07-16', NULL, '2025-07-15 06:18:15', '2025-07-15 06:18:15', 0),
(13, 84, 28, '2025-07-17', NULL, '2025-07-15 06:36:21', '2025-07-15 06:36:21', 0),
(14, 85, 24, '2025-07-15', NULL, '2025-07-15 07:18:18', '2025-07-15 07:18:18', 0),
(17, 88, 23, '2025-07-17', NULL, '2025-07-15 07:28:40', '2025-07-15 07:28:40', 0),
(19, 99, 34, '2025-07-16', NULL, '2025-07-15 07:46:04', '2025-07-15 07:46:04', 0),
(21, 111, 24, '2025-07-17', NULL, '2025-07-15 08:05:24', '2025-07-15 08:05:24', 0),
(22, 111, 24, '2025-07-16', NULL, '2025-07-15 08:08:24', '2025-07-15 08:08:24', 0),
(23, 114, 30, '2025-07-17', NULL, '2025-07-15 08:31:17', '2025-07-15 08:31:17', 0);

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
(111, 24, 'TRX-250715-150502-WU6M', '2025-07-15 08:05:02', '2025-07-17', 1, 10000, 12000, 2000, '2025-07-15 08:05:02', '2025-07-15 08:11:39', 0),
(112, 31, 'TRX-250715-151229-K68Q', '2025-07-15 08:12:29', '2025-07-17', 1, 10000, NULL, NULL, '2025-07-15 08:12:29', '2025-07-15 08:17:42', 0),
(113, 39, 'TRX-250715-151949-MID0', '2025-07-15 08:19:49', '2025-07-17', 1, 10000, NULL, NULL, '2025-07-15 08:19:49', '2025-07-15 08:27:23', 0),
(114, 30, 'TRX-250715-153042-JKOX', '2025-07-15 08:30:42', '2025-07-17', 1, 10000, 23000, 13000, '2025-07-15 08:30:42', '2025-07-15 08:31:17', 0);

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
(90, 84, 9, 1, 5000, NULL, '2025-07-15 06:35:52', '2025-07-15 06:35:52', 0),
(91, 85, 9, 12, 60000, NULL, '2025-07-15 07:17:26', '2025-07-15 07:17:26', 0),
(93, 87, 9, 12, 60000, NULL, '2025-07-15 07:27:30', '2025-07-15 07:27:30', 0),
(94, 88, 9, 12, 60000, NULL, '2025-07-15 07:27:30', '2025-07-15 07:27:30', 0),
(95, 89, 13, 2, 30000, NULL, '2025-07-15 07:30:06', '2025-07-15 07:30:06', 0),
(96, 89, 10, 2, 6000, NULL, '2025-07-15 07:30:06', '2025-07-15 07:30:06', 0),
(97, 89, 14, 2, 40000, NULL, '2025-07-15 07:30:06', '2025-07-15 07:30:06', 0),
(98, 90, 13, 2, 30000, NULL, '2025-07-15 07:30:06', '2025-07-15 07:30:06', 0),
(99, 90, 10, 2, 6000, NULL, '2025-07-15 07:30:06', '2025-07-15 07:30:06', 0),
(100, 90, 14, 2, 40000, NULL, '2025-07-15 07:30:06', '2025-07-15 07:30:06', 0),
(101, 91, 9, 2, 10000, NULL, '2025-07-15 07:31:53', '2025-07-15 07:31:53', 0),
(102, 92, 9, 2, 10000, NULL, '2025-07-15 07:31:53', '2025-07-15 07:31:53', 0),
(103, 93, 11, 2, 50000, NULL, '2025-07-15 07:34:37', '2025-07-15 07:34:37', 0),
(104, 94, 11, 2, 50000, NULL, '2025-07-15 07:34:37', '2025-07-15 07:34:37', 0),
(105, 95, 12, 2, 14000, NULL, '2025-07-15 07:35:33', '2025-07-15 07:35:33', 0),
(106, 96, 12, 2, 14000, NULL, '2025-07-15 07:35:33', '2025-07-15 07:35:33', 0),
(107, 97, 9, 2, 10000, NULL, '2025-07-15 07:40:57', '2025-07-15 07:40:57', 0),
(109, 99, 12, 12, 84000, NULL, '2025-07-15 07:45:03', '2025-07-15 07:45:03', 0),
(110, 100, 12, 12, 84000, NULL, '2025-07-15 07:45:03', '2025-07-15 07:45:03', 0),
(111, 101, 9, 2222, 11110000, NULL, '2025-07-15 07:47:06', '2025-07-15 07:47:06', 0),
(112, 102, 9, 2222, 11110000, NULL, '2025-07-15 07:47:06', '2025-07-15 07:47:06', 0),
(113, 103, 9, 4, 20000, NULL, '2025-07-15 07:51:34', '2025-07-15 07:51:34', 0),
(114, 104, 9, 4, 20000, NULL, '2025-07-15 07:51:34', '2025-07-15 07:51:34', 0),
(115, 105, 11, 3, 75000, NULL, '2025-07-15 07:56:40', '2025-07-15 07:56:40', 0),
(119, 109, 9, 2, 10000, NULL, '2025-07-15 08:01:54', '2025-07-15 08:01:54', 0),
(120, 110, 9, 2, 10000, NULL, '2025-07-15 08:01:54', '2025-07-15 08:01:54', 0),
(121, 111, 9, 2, 10000, NULL, '2025-07-15 08:05:02', '2025-07-15 08:05:02', 0),
(122, 112, 9, 2, 10000, NULL, '2025-07-15 08:12:29', '2025-07-15 08:12:29', 0),
(123, 113, 9, 2, 10000, NULL, '2025-07-15 08:19:49', '2025-07-15 08:19:49', 0),
(124, 114, 9, 2, 10000, NULL, '2025-07-15 08:30:42', '2025-07-15 08:30:42', 0);

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
(9, 'Cuci Kering', 5000, 'Cuci Kering Rp.5000/Kg', '2025-07-14 07:40:11', '2025-07-14 07:40:11', 0),
(10, 'Setrika saja ', 3000, 'Setrika saja Rp.3.000/kg', '2025-07-14 07:41:07', '2025-07-14 07:41:07', 0),
(11, 'Cuci Sepatu', 25000, 'Cuci Sepatu Rp.25.000/Pasang', '2025-07-14 07:42:12', '2025-07-14 07:42:12', 0),
(12, 'Cuci Setrika', 7000, 'Cuci Setrika Rp.7000/Kg', '2025-07-14 07:42:47', '2025-07-14 07:42:47', 0),
(13, 'Dry Clean', 15000, 'Dry Clean Rp.15.000/Kg ', '2025-07-14 07:45:36', '2025-07-14 07:45:36', 0),
(14, 'Cuci Carpet', 20000, 'Cuci Carpet Rp.20000/m2', '2025-07-14 07:46:19', '2025-07-14 07:46:19', 0);

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
(1, 1, 'admin', 'admin@gmail.com', '123', 'profile_picture1.jpg', '2024-11-11 02:30:45', '2025-06-17 07:21:51', 0),
(14, 2, 'Operator', 'operator@gmail.com', '123', 'profile_picture14.jpg', '2024-11-28 12:16:42', '2025-07-14 02:08:49', 0),
(17, 3, 'Leader', 'leader@gmail.com', '123', '', '2025-07-14 02:09:24', '2025-07-14 02:09:59', 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT untuk tabel `level`
--
ALTER TABLE `level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `trans_laundry_pickup`
--
ALTER TABLE `trans_laundry_pickup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `trans_order`
--
ALTER TABLE `trans_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT untuk tabel `trans_order_detail`
--
ALTER TABLE `trans_order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT untuk tabel `type_of_service`
--
ALTER TABLE `type_of_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
