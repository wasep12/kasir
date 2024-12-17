-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2024 at 12:00 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kasir1`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori_produk`
--

CREATE TABLE `kategori_produk` (
  `id` int(11) NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_produk`
--

INSERT INTO `kategori_produk` (`id`, `kategori`) VALUES
(1, 'SABLON'),
(2, 'POLOS'),
(3, 'CUSTOM');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` set('Pria','Wanita','Lainya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `jenis_kelamin`, `alamat`, `telepon`) VALUES
(2, 'Rahma', 'Wanita', 'Banjarnegara', '085463728374'),
(3, 'mumu', 'Pria', 'kutruk', '0822132781718'),
(7, 'YUDIN', 'Pria', 'KP Peuteuy rt 001 rw 003', '083890887676');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` char(1) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `username`, `password`, `nama`, `role`) VALUES
(1, 'admin', '$2y$10$/I7laWi1mlNFxYSv54EUPOH8MuZhmRWxhE.LaddTK9TSmVe.IHP2C', 'Admin', '1'),
(2, 'ibrahimalanshor', '9b759040321a408a5c7768b4511287a6', 'Ibrahim Al Anshor', '2'),
(3, 'isnaryudin', '$2y$10$F/Q.I8kOmTPwuoeaqvVQxOGInKDTIVH4BlLjMKfve9nDz7.9VTmru', 'pegawai', '2');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` int(11) NOT NULL,
  `satuan` int(11) NOT NULL,
  `harga` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int(11) NOT NULL,
  `terjual` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `barcode`, `nama_produk`, `kategori`, `satuan`, `harga`, `stok`, `terjual`) VALUES
(1, 'KAOS-PNDK-002', 'Kaos warna biru pendek 30s', 2, 2, '55000', 0, '45'),
(2, 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', 1, 2, '18000', 10, '45'),
(3, 'D-000878', 'TAS', 2, 3, '200000', 0, '12'),
(4, 'SB-0003', 'SABLON DTF', 3, 2, '25000', 48, '22');

-- --------------------------------------------------------

--
-- Table structure for table `satuan_produk`
--

CREATE TABLE `satuan_produk` (
  `id` int(11) NOT NULL,
  `satuan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `satuan_produk`
--

INSERT INTO `satuan_produk` (`id`, `satuan`) VALUES
(1, 'PCS'),
(2, 'LUSIN'),
(3, 'KODI');

-- --------------------------------------------------------

--
-- Table structure for table `stok_keluar`
--

CREATE TABLE `stok_keluar` (
  `id` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `barcode` int(11) NOT NULL,
  `jumlah` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Keterangan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stok_keluar`
--

INSERT INTO `stok_keluar` (`id`, `tanggal`, `barcode`, `jumlah`, `Keterangan`) VALUES
(1, '2020-02-21 13:42:01', 1, '10', 'rusak');

-- --------------------------------------------------------

--
-- Table structure for table `stok_masuk`
--

CREATE TABLE `stok_masuk` (
  `id` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `barcode` int(11) NOT NULL,
  `jumlah` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stok_masuk`
--

INSERT INTO `stok_masuk` (`id`, `tanggal`, `barcode`, `jumlah`, `keterangan`, `supplier`) VALUES
(1, '2020-02-21 13:41:25', 1, '10', 'penambahan', NULL),
(2, '2020-02-21 13:41:40', 2, '20', 'penambahan', 1),
(3, '2020-02-21 13:42:23', 1, '10', 'penambahan', 2),
(4, '2024-12-09 20:02:08', 1, '12', 'penambahan', 2),
(5, '2024-12-10 02:01:15', 2, '20', 'penambahan', 2),
(6, '2024-12-10 07:01:11', 1, '21', 'penambahan', 1),
(7, '2024-12-10 12:52:50', 1, '12', 'penambahan', 2),
(8, '2024-12-12 20:18:28', 2, '12', 'penambahan', NULL),
(9, '2024-12-14 16:42:05', 3, '12', 'penambahan', 1),
(10, '2024-12-14 16:47:11', 4, '20', 'penambahan', 2),
(11, '2024-12-16 17:17:30', 2, '20', 'penambahan', 1),
(12, '2024-12-16 17:55:27', 4, '50', 'penambahan', 2);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `nama`, `alamat`, `telepon`, `keterangan`) VALUES
(1, 'Tulus', 'Banjarnegara', '083321128832', 'Aktif'),
(2, 'Nur', 'Cilacap', '082235542637', 'Baru');

-- --------------------------------------------------------

--
-- Table structure for table `toko`
--

CREATE TABLE `toko` (
  `id` int(11) NOT NULL,
  `nama` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `toko`
--

INSERT INTO `toko` (`id`, `nama`, `alamat`) VALUES
(1, 'Toko Bhovonkstore', 'Tangerang');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_bayar` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_uang` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diskon` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pelanggan` int(11) DEFAULT NULL,
  `nota` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kasir` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `tanggal`, `barcode`, `nama_produk`, `qty`, `total_bayar`, `jumlah_uang`, `diskon`, `pelanggan`, `nota`, `kasir`) VALUES
(52, '2024-12-10 01:21:54', 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', '1', '18000', '19000', '', 2, 'GOM3DNDM44Z1V0Q', 1),
(82, '2021-11-25 00:00:00', 'KAOS-PNDK-002', 'Kaos warna biru panjang 30s', '3', '57000', '60000', '2000', 2, 'TWUC26NMYNMQKP8', 2),
(85, '2021-01-12 00:00:00', 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', '4', '72000', '74000', '1000', 2, 'JAKPQW123NZKTY7', 5),
(88, '2024-02-18 00:00:00', 'KAOS-PNDK-002', 'Kaos warna biru panjang 30s', '7', '133000', '140000', '3500', 2, 'VFXJY234QWNRLP4', 8),
(97, '2023-10-01 00:00:00', 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', '7', '126000', '130000', '3500', 2, 'LZQPXM49WYNTKR1', 7),
(98, '2021-10-16 00:00:00', 'KAOS-PNDK-002', 'Kaos warna biru panjang 30s', '8', '152000', '160000', '4000', 3, 'TRZPLX85MQNYWF1', 8),
(101, '2024-12-10 03:37:49', 'KAOS-PNDK-002', 'Kaos warna biru pendek 30s', '1', '55000', '60000', '', 2, 'WWFS5L1T29EC8CT', 1),
(102, '2024-12-10 03:38:39', 'KAOS-PNDK-002', 'Kaos warna biru pendek 30s', '5', '275000', '300000', '', 2, 'BN6MG90ENMLBB6V', 1),
(106, '2024-12-12 22:42:10', 'KAOS-PNDK-002', 'Kaos warna biru pendek 30s', '20', '1100000', '1100000', '', 2, 'PZ49HTHY5F7T2M1', 1),
(107, '2024-12-12 22:44:03', 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', '3', '54000', '55000', '', 2, 'JVWC22RB9HY166E', 1),
(108, '2024-12-12 22:49:58', 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', '1', '18000', '18000', '1000', 2, 'WXFJE98VV1VDWSQ', 1),
(109, '2024-12-12 22:56:38', 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', '1', '18000', '18000', '1000', 2, 'SGXNWBKM7VQ21LL', 1),
(110, '2024-12-12 22:57:56', 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', '1', '18000', '18000', '1000', 2, 'N8Z5GKU5UWA3V5U', 1),
(111, '2024-12-13 12:43:55', 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', '1', '18000', '18000', '', 2, '3GUQN4GHXIBI975', 1),
(112, '2024-12-14 16:42:35', 'D-000878', 'TAS', '1', '200000', '200000', '', 2, 'IAZPKMVHTDBGFMM', 1),
(113, '2024-12-14 16:47:40', 'SB-0003', 'SABLON DTF', '2', '50000', '100000', '', 3, '61KHE8LHJTDYCMR', 1),
(114, '2024-12-14 16:54:31', 'SB-0003', 'SABLON DTF', '1', '25000', '26000', '200', 7, '456BH0EDA4JNQOI', 1),
(115, '2024-12-15 21:47:04', 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', '2', '36000', '40000', '', 2, 'QF6UTNWMPZ24E8F', 1),
(122, '2024-12-16 00:23:10', 'D-000878,SB-0003', 'TAS,SABLON DTF', '1,1', '225000', '225000', '', 3, 'PAXZ608AHU1U8NA', 1),
(123, '2024-12-16 00:26:30', 'D-000878,SB-0003', 'TAS,SABLON DTF', '1,2', '250000', '250000', '', 2, '2VHT830NDN2277P', 1),
(124, '2020-12-16 16:10:20', 'D-000878', 'TAS', '1', '200000', '200000', '', 3, '4HO6A6MSOQ2YJGY', 1),
(125, '2024-12-16 16:15:47', 'SB-0003', 'SABLON DTF', '2', '50000', '50000', '', 3, '3OMSNYD0SPVRAV1', 1),
(126, '2022-07-20 17:50:07', 'SB-0003', 'SABLON DTF', '2', '50000', '50000', '', 7, 'VXKU1UJL7E98PN4', 1),
(127, '2024-12-16 16:25:40', 'KAOS-PNDK-002,SB-0003', 'Kaos warna biru pendek 30s,SABLON DTF', '1,1', '80000', '80000', '2000', 3, 'P697TR5OQZMREDN', 1),
(128, '2024-12-16 16:35:11', 'SB-0003', 'SABLON DTF', '1', '25000', '25000', '2000', 7, '1VF37PI8I3LH7SH', 1),
(129, '2024-12-16 16:39:15', 'SB-0003', 'SABLON DTF', '1', '25000', '25000', '2000', 7, 'SZNUPV9M5JLKECO', 1),
(130, '2024-12-16 16:43:16', 'SB-0003', 'SABLON DTF', '1', '25000', '27000', '3000', 2, 'XT8ENM8KLDX464V', 1),
(131, '2024-12-16 16:51:35', 'D-000878', 'TAS', '1', '200000', '200000', '2000', 2, 'GV8PIZF2E104QT5', 1),
(132, '2024-12-16 16:56:35', 'D-000878', 'TAS', '1', '200000', '206000', '2000', 3, '63CV5ZE5WI7TFRP', 1),
(133, '2024-12-16 17:04:49', 'SB-0003', 'SABLON DTF', '1', '25000', '25000', '2000', 3, 'UF043G8QQCPRTBT', 1),
(134, '2024-12-16 17:09:21', 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', '1', '18000', '20000', '2000', 2, 'O809MXBOZQL04ZI', 1),
(135, '2024-12-16 17:13:04', 'D-000878', 'TAS', '1', '200000', '200000', '20000', 3, 'OE97BOX79F2Q3TD', 1),
(136, '2024-12-16 17:20:33', 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', '1', '18000', '20000', '1000', 3, '8BVPA0Y1IJ5QZD4', 1),
(137, '2024-12-16 17:31:38', 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', '1', '18000', '20000', '2000', 3, 'OA32SC9CVJRI5I1', 1),
(138, '2024-12-16 17:36:57', 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', '2', '36000', '40000', '2000', 7, 'QLQAYHX30IXE2J0', 1),
(139, '2024-12-16 17:43:56', 'KAOS-PNDK-001', 'Kaos warna hitam pendek 30s', '1', '18000', '20000', '3000', 3, '0JYLTF8F94QFG1M', 1),
(140, '2024-12-16 17:54:03', 'KAOS-PNDK-001,SB-0003', 'Kaos warna hitam pendek 30s,SABLON DTF', '3,1', '79000', '80000', '5000', 2, '54CFCHR9SVOGO7B', 1),
(141, '2024-12-16 17:56:07', 'KAOS-PNDK-001,SB-0003', 'Kaos warna hitam pendek 30s,SABLON DTF', '2,2', '86000', '90000', '5000', 3, '7SQAXZ87T8UCGQH', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori_produk`
--
ALTER TABLE `kategori_produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `satuan_produk`
--
ALTER TABLE `satuan_produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stok_keluar`
--
ALTER TABLE `stok_keluar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `toko`
--
ALTER TABLE `toko`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori_produk`
--
ALTER TABLE `kategori_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `satuan_produk`
--
ALTER TABLE `satuan_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stok_keluar`
--
ALTER TABLE `stok_keluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `toko`
--
ALTER TABLE `toko`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
