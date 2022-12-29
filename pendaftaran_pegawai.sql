-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2022 at 01:15 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pendaftaran_pegawai`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `a_id` int(11) NOT NULL,
  `a_username` varchar(20) DEFAULT NULL,
  `a_password` varchar(20) DEFAULT NULL,
  `u_nik` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`a_id`, `a_username`, `a_password`, `u_nik`) VALUES
(1, 'bima', 'passbima', NULL),
(2, 'fuad', 'passfuad', NULL),
(3, 'elbert', 'passelbert', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_ujian`
--

CREATE TABLE `jadwal_ujian` (
  `j_id` int(11) NOT NULL,
  `j_hari` date NOT NULL,
  `j_jam` time NOT NULL,
  `j_lokasi` varchar(100) NOT NULL,
  `j_ruangan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jadwal_ujian`
--

INSERT INTO `jadwal_ujian` (`j_id`, `j_hari`, `j_jam`, `j_lokasi`, `j_ruangan`) VALUES
(1, '2022-12-20', '08:00:00', 'Gedung Departemen Informatika Institut Teknologi Sepuluh Nopember', 'IF-105 B');

-- --------------------------------------------------------

--
-- Table structure for table `peserta`
--

CREATE TABLE `peserta` (
  `u_nik` varchar(16) NOT NULL,
  `u_password` varchar(20) NOT NULL,
  `u_nama` varchar(50) NOT NULL,
  `u_email` varchar(50) NOT NULL,
  `u_kelamin` varchar(10) DEFAULT NULL,
  `u_tanggal_lahir` date DEFAULT NULL,
  `u_domisili` varchar(20) DEFAULT NULL,
  `u_alamat` varchar(100) DEFAULT NULL,
  `u_hp` varchar(15) DEFAULT NULL,
  `u_instansi` varchar(50) DEFAULT NULL,
  `u_alamat_instansi` varchar(50) DEFAULT NULL,
  `u_jabatan` varchar(50) DEFAULT NULL,
  `u_pas_foto` varchar(200) DEFAULT NULL,
  `u_ktp` varchar(200) DEFAULT NULL,
  `u_ijazah` varchar(200) DEFAULT NULL,
  `u_status` int(11) DEFAULT NULL,
  `p_no_reg` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `peserta_ujian`
--

CREATE TABLE `peserta_ujian` (
  `p_no_reg` varchar(16) NOT NULL,
  `p_nik` varchar(16) DEFAULT NULL,
  `p_nilai` int(11) DEFAULT NULL,
  `j_id` int(11) DEFAULT NULL,
  `p_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `jadwal_ujian`
--
ALTER TABLE `jadwal_ujian`
  ADD PRIMARY KEY (`j_id`);

--
-- Indexes for table `peserta`
--
ALTER TABLE `peserta`
  ADD PRIMARY KEY (`u_nik`);

--
-- Indexes for table `peserta_ujian`
--
ALTER TABLE `peserta_ujian`
  ADD PRIMARY KEY (`p_id`) USING BTREE,
  ADD KEY `fk_nik` (`p_nik`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `peserta_ujian`
--
ALTER TABLE `peserta_ujian`
  ADD CONSTRAINT `fk_nik` FOREIGN KEY (`p_nik`) REFERENCES `peserta` (`u_nik`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
