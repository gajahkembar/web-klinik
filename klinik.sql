-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2024 at 08:55 PM
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
-- Database: `klinik`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama`) VALUES
(4, '123', '$2y$10$v9cRoaM/7pxd8QZ9YO/29O9Y/WKdrGcHX15/Z0iQgPPLlV1V8ujvi', '123'),
(5, 'admin', '$2y$10$6kNNpj1pEmbg7hUD/nNBVOLty8kVgCtPf4q9bIGMITJY.l4.xQP/m', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `daftar_poli`
--

CREATE TABLE `daftar_poli` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_pasien` int(10) UNSIGNED NOT NULL,
  `id_jadwal` int(10) UNSIGNED NOT NULL,
  `keluhan` text NOT NULL,
  `no_antrian` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daftar_poli`
--

INSERT INTO `daftar_poli` (`id`, `id_pasien`, `id_jadwal`, `keluhan`, `no_antrian`) VALUES
(7, 7, 2, 'sgbgbdvsdg', 2),
(8, 7, 3, 'aefefasef', 2),
(9, 7, 2, 'affwfw', 3),
(10, 7, 3, 'sggege', 4),
(12, 14, 2, 'fwfwawa', 5);

-- --------------------------------------------------------

--
-- Table structure for table `detail_periksa`
--

CREATE TABLE `detail_periksa` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_periksa` int(10) UNSIGNED NOT NULL,
  `id_obat` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_periksa`
--

INSERT INTO `detail_periksa` (`id`, `id_periksa`, `id_obat`) VALUES
(1, 19, 9),
(2, 19, 10),
(3, 20, 7),
(4, 21, 9),
(5, 21, 10),
(6, 21, 16),
(7, 21, 7);

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE `dokter` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_hp` varchar(50) NOT NULL,
  `id_poli` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokter`
--

INSERT INTO `dokter` (`id`, `nama`, `alamat`, `no_hp`, `id_poli`) VALUES
(3, 'Andi', 'Jalan Mawar', '0812345678901', 8),
(4, 'Budi', 'Jalan Melati', '082345678901', 8),
(5, 'Cici', 'Jalan Gajah', '083456789012', 9),
(6, 'Dedi', 'Jalan Kanguru', '084567890123', 9),
(7, 'Erna', 'Jalan Mawar', '085678901234', 10),
(8, 'Fajar', 'Jalan Melati', '086789012345', 10),
(9, 'Gina', 'Jalan Gajah', '087890123456', 11),
(10, 'Hana', 'Jalan Kanguru', '088901234567', 11),
(11, 'Indra', 'Jalan Mawar', '089012345678', 12),
(12, 'Joko', 'Jalan Melati', '090123456789', 12),
(13, 'Farida', 'Jalan Melati', '086789012345', 13),
(14, 'Gita', 'Jalan Gajah', '087890123456', 13),
(15, 'Hadi', 'Jalan Kanguru', '088901234567', 14),
(16, 'Ira', 'Jalan Mawar', '089012345678', 14),
(17, 'Joko', 'Jalan Melati', '090123456789', 15),
(18, 'Laila', 'Jalan Gajah', '091234567890', 15),
(19, 'Miko', 'Jalan Kanguru', '092345678901', 16),
(20, 'Nadia', 'Jalan Mawar', '093456789012', 16),
(21, 'Oki', 'Jalan Melati', '094567890123', 17),
(22, 'Putri', 'Jalan Gajah', '095678901234', 17);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_periksa`
--

CREATE TABLE `jadwal_periksa` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_dokter` int(10) UNSIGNED NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_periksa`
--

INSERT INTO `jadwal_periksa` (`id`, `id_dokter`, `hari`, `jam_mulai`, `jam_selesai`) VALUES
(2, 3, 'Senin', '00:00:00', '11:11:00'),
(3, 4, 'Senin', '00:00:00', '00:00:00'),
(13, 5, 'Selasa', '00:00:00', '00:00:00'),
(16, 6, 'Jumat', '11:11:00', '22:22:00'),
(17, 7, 'Rabu', '00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_obat` varbinary(50) NOT NULL,
  `kemasan` varchar(35) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id`, `nama_obat`, `kemasan`, `harga`) VALUES
(3, 0x50617261636574616d6f6c205369727570, 'Botol', 10000),
(4, 0x416d6f786963696c6c696e205369727570, 'Botol', 15000),
(5, 0x566974616d696e2043205369727570, 'Botol', 12000),
(6, 0x49627570726f66656e205461626c6574, 'Strip', 8000),
(7, 0x4365746972697a696e65205461626c6574, 'Strip', 7500),
(8, 0x50617261636574616d6f6c205461626c6574, 'Strip', 5000),
(9, 0x416d6c6f646970696e65205461626c6574, 'Dus', 30000),
(10, 0x416d6f786963696c6c696e204b617073756c, 'Dus', 20000),
(11, 0x4d6566656e616d69632041636964204b617073756c, 'Dus', 25000),
(12, 0x466c75696d7563696c20536163686574, 'Sachet', 5500),
(13, 0x50617261636574616d6f6c2053657262756b, 'Sachet', 3000),
(14, 0x4f736b61646f6e20284f6261742053616b6974204b6570616c6129, 'Sachet', 4000),
(15, 0x53616c657020416d6f786963696c6c696e, 'Tube', 12000),
(16, 0x4265746164696e652053616c6570, 'Tube', 15000),
(17, 0x44616b746172696e202853616c657020416e74696a616d757229, 'Tube', 18000);

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_ktp` varchar(255) NOT NULL,
  `no_hp` varchar(50) NOT NULL,
  `no_rm` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`id`, `nama`, `alamat`, `no_ktp`, `no_hp`, `no_rm`) VALUES
(7, 'Rahmanda', 'Semarang', '1234567890123456', '01234567890', 'RM-476470'),
(14, 'tes', 'tes', '1234567890123456', '01234567890', 'RM-233238');

-- --------------------------------------------------------

--
-- Table structure for table `periksa`
--

CREATE TABLE `periksa` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_daftar_poli` int(10) UNSIGNED NOT NULL,
  `tgl_periksa` datetime NOT NULL,
  `catatan` text NOT NULL,
  `biaya_periksa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `periksa`
--

INSERT INTO `periksa` (`id`, `id_daftar_poli`, `tgl_periksa`, `catatan`, `biaya_periksa`) VALUES
(19, 9, '2024-12-08 19:39:00', 'tes', 100000),
(20, 7, '2024-12-09 00:43:00', 'qwe', 57500),
(21, 12, '2024-12-10 20:59:00', 'feawefawefa', 122500);

-- --------------------------------------------------------

--
-- Table structure for table `poli`
--

CREATE TABLE `poli` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_poli` varchar(25) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poli`
--

INSERT INTO `poli` (`id`, `nama_poli`, `keterangan`) VALUES
(8, 'Poli Umum', 'Layanan medis yang menyediakan pemeriksaan kesehatan secara umum untuk diagnosis penyakit ringan hingga sedang. Cocok untuk pemeriksaan rutin atau keluhan umum.'),
(9, 'Poli Gigi', 'Layanan perawatan kesehatan gigi dan mulut, termasuk pembersihan gigi, pencabutan, penambalan, dan pemeriksaan kesehatan gigi secara rutin.'),
(10, 'Poli Anak', 'Layanan medis yang fokus pada kesehatan dan perkembangan anak, termasuk imunisasi, pemeriksaan tumbuh kembang, serta penanganan penyakit yang umum terjadi pada anak-anak.'),
(11, 'Poli Kandungan', 'Layanan untuk ibu hamil, termasuk pemeriksaan kehamilan, USG, konsultasi tentang persalinan, dan masalah kesehatan yang terkait dengan kehamilan.'),
(12, 'Poli Jantung', 'Layanan medis yang fokus pada pencegahan, diagnosis, dan pengobatan penyakit jantung serta gangguan pembuluh darah. Pemeriksaan elektrokardiogram (EKG) dan echocardiogram tersedia di poli ini.'),
(13, 'Poli Penyakit Dalam', 'Layanan pemeriksaan dan pengobatan penyakit yang menyerang organ dalam tubuh seperti diabetes, hipertensi, dan penyakit metabolik lainnya.'),
(14, 'Poli Mata', 'Layanan khusus untuk pemeriksaan kesehatan mata, pengobatan gangguan penglihatan, serta tindakan medis seperti pemasangan kacamata, terapi, atau operasi mata.'),
(15, 'Poli Kulit dan Kelamin', 'Layanan untuk diagnosis dan pengobatan masalah kulit dan kelamin, termasuk perawatan jerawat, infeksi kulit, serta penyakit kelamin.'),
(16, 'Poli THT', 'Layanan medis untuk menangani masalah pada telinga, hidung, dan tenggorokan seperti gangguan pendengaran, infeksi telinga, pilek kronis, dan masalah pernapasan.'),
(17, 'Poli Rehabilitasi Medik', 'Layanan yang membantu pasien pulih dan berfungsi kembali setelah penyakit atau cedera, termasuk fisioterapi, terapi okupasi, dan pengobatan untuk gangguan gerakan.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daftar_poli`
--
ALTER TABLE `daftar_poli`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `id_pasien` (`id_pasien`);

--
-- Indexes for table `detail_periksa`
--
ALTER TABLE `detail_periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_obat` (`id_obat`),
  ADD KEY `id_periksa` (`id_periksa`);

--
-- Indexes for table `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_poli` (`id_poli`);

--
-- Indexes for table `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_dokter` (`id_dokter`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `periksa`
--
ALTER TABLE `periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_daftar_poli` (`id_daftar_poli`);

--
-- Indexes for table `poli`
--
ALTER TABLE `poli`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `daftar_poli`
--
ALTER TABLE `daftar_poli`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `detail_periksa`
--
ALTER TABLE `detail_periksa`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `periksa`
--
ALTER TABLE `periksa`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `poli`
--
ALTER TABLE `poli`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `daftar_poli`
--
ALTER TABLE `daftar_poli`
  ADD CONSTRAINT `daftar_poli_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_periksa` (`id`),
  ADD CONSTRAINT `daftar_poli_ibfk_2` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id`);

--
-- Constraints for table `detail_periksa`
--
ALTER TABLE `detail_periksa`
  ADD CONSTRAINT `detail_periksa_ibfk_1` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id`),
  ADD CONSTRAINT `detail_periksa_ibfk_2` FOREIGN KEY (`id_periksa`) REFERENCES `periksa` (`id`);

--
-- Constraints for table `dokter`
--
ALTER TABLE `dokter`
  ADD CONSTRAINT `dokter_ibfk_1` FOREIGN KEY (`id_poli`) REFERENCES `poli` (`id`);

--
-- Constraints for table `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  ADD CONSTRAINT `jadwal_periksa_ibfk_1` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id`);

--
-- Constraints for table `periksa`
--
ALTER TABLE `periksa`
  ADD CONSTRAINT `periksa_ibfk_1` FOREIGN KEY (`id_daftar_poli`) REFERENCES `daftar_poli` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
