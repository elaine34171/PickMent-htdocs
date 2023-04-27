-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2023 at 10:49 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pickment`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievement`
--

CREATE TABLE `achievement` (
  `id` int(11) NOT NULL,
  `nama` varchar(128) NOT NULL,
  `keterangan` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `achievement`
--

INSERT INTO `achievement` (`id`, `nama`, `keterangan`) VALUES
(1, 'Langkah Pertama', 'Melengkapi data diri pengguna'),
(2, 'Pemula', 'Memperoleh peringkat 1 di papan peringkat set awal'),
(3, 'Pemenang Bertahan', 'Memperoleh peringkat 1 di papan peringkat set akhir'),
(4, 'Mitos', 'Memperoleh peringkat 5 ke atas di papan peringkat mingguan'),
(5, 'Legenda', 'Memperoleh peringkat 1 di papan peringkat mingguan'),
(6, 'Peramal', 'Memilih jawaban benar 10 kali berturut-turut'),
(7, 'Penjelajah Waktu', 'Memilih jawaban benar 20 kali berturut-turut');

-- --------------------------------------------------------

--
-- Table structure for table `bingkai`
--

CREATE TABLE `bingkai` (
  `id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `warna` char(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bingkai`
--

INSERT INTO `bingkai` (`id`, `level`, `warna`) VALUES
(1, 0, 'ffffffffffff'),
(2, 1, '990000990000'),
(3, 2, 'f28500f28500'),
(4, 3, 'ffc823ffc823'),
(5, 4, '98cb0098cb00'),
(6, 5, '00C0A300C0A3'),
(7, 6, '00bbff00bbff'),
(8, 7, '074075074075'),
(9, 8, '6f2da86f2da8'),
(10, 9, 'ff8888ff8888'),
(11, 10, 'ff888800bbff');

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE `data` (
  `id` int(11) NOT NULL,
  `teks` varchar(512) NOT NULL,
  `set_data` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ikon`
--

CREATE TABLE `ikon` (
  `id` int(11) NOT NULL,
  `gambar` varchar(512) NOT NULL,
  `achievement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ikon`
--

INSERT INTO `ikon` (`id`, `gambar`, `achievement`) VALUES
(1, 'Pemain.png', 0),
(2, 'Langkah_Pertama.png', 1),
(3, 'Pemula.png', 2),
(4, 'Mitos.png', 4),
(5, 'Peramal.png', 6);

-- --------------------------------------------------------

--
-- Table structure for table `kartu_nama`
--

CREATE TABLE `kartu_nama` (
  `id` int(11) NOT NULL,
  `gambar` varchar(512) NOT NULL,
  `achievement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kartu_nama`
--

INSERT INTO `kartu_nama` (`id`, `gambar`, `achievement`) VALUES
(1, 'Pemain.png', 0),
(2, 'Langkah_Pertama.png', 1),
(3, 'Pemenang_Bertahan.png', 3),
(4, 'Legenda.png', 5),
(5, 'Penjelajah_Waktu.png', 7);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_medali`
--

CREATE TABLE `kategori_medali` (
  `id` int(11) NOT NULL,
  `nama` varchar(128) NOT NULL,
  `gambar` varchar(512) NOT NULL,
  `keterangan` varchar(512) NOT NULL,
  `syarat` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kategori_medali`
--

INSERT INTO `kategori_medali` (`id`, `nama`, `gambar`, `keterangan`, `syarat`) VALUES
(1, 'Ketekunan', 'Ketekunan.png', 'Berpartisipasi dalam permainan', 'partisipasi'),
(2, 'Inteligensi', 'Inteligensi.png', 'Memilih jawaban benar', 'jawaban_benar'),
(3, 'Ambisius', 'Ambisius.png', 'Memperoleh peringkat 1 di papan peringkat set awal', 'peringkat_satu_awal');

-- --------------------------------------------------------

--
-- Table structure for table `medali`
--

CREATE TABLE `medali` (
  `id` int(11) NOT NULL,
  `nama` varchar(128) NOT NULL,
  `gambar` varchar(512) NOT NULL,
  `keterangan` varchar(512) NOT NULL,
  `kategori` int(11) NOT NULL,
  `syarat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medali`
--

INSERT INTO `medali` (`id`, `nama`, `gambar`, `keterangan`, `kategori`, `syarat`) VALUES
(1, 'Ketekunan I', 'Ketekunan I.png', 'Berpartisipasi dalam 1 permainan', 1, 1),
(2, 'Ketekunan II', 'Ketekunan II.png', 'Berpartisipasi dalam 5 permainan', 1, 5),
(3, 'Ketekunan III', 'Ketekunan III.png', 'Berpartisipasi dalam 15 permainan', 1, 15),
(4, 'Inteligensi I', 'Inteligensi I.png', 'Memilih jawaban benar sebanyak 10 kali', 2, 10),
(5, 'Inteligensi II', 'Inteligensi II.png', 'Memilih jawaban benar sebanyak 35 kali', 2, 35),
(6, 'Inteligensi III', 'Inteligensi III.png', 'Memilih jawaban benar sebanyak 100 kali', 2, 100),
(7, 'Ambisius I', 'Ambisius I.png', 'Memperoleh peringkat 1 di papan peringkat set awal sebanyak 1 kali', 3, 1),
(8, 'Ambisius II', 'Ambisius II.png', 'Memperoleh peringkat 1 di papan peringkat set awal sebanyak 5 kali', 3, 5),
(9, 'Ambisius III', 'Ambisius III.png', 'Memperoleh peringkat 1 di papan peringkat set awal sebanyak 15 kali', 3, 15);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `waktu` datetime NOT NULL,
  `judul` varchar(128) NOT NULL,
  `isi` varchar(512) NOT NULL,
  `status_dibaca` int(11) NOT NULL,
  `pengguna` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nama_pengguna` varchar(10) NOT NULL,
  `kata_sandi` varchar(128) NOT NULL,
  `status_admin` int(11) NOT NULL,
  `preferensi_bahasa` int(11) NOT NULL,
  `nama_lengkap` varchar(128) NOT NULL,
  `tahun_lahir` int(11) NOT NULL,
  `jenis_kelamin` char(1) NOT NULL,
  `no_telepon` varchar(32) NOT NULL,
  `email` varchar(128) NOT NULL,
  `domisili` varchar(128) NOT NULL,
  `pekerjaan` varchar(128) NOT NULL,
  `pendidikan` varchar(128) NOT NULL,
  `xp_mingguan` int(11) NOT NULL,
  `waktu_berlaku_xp_mingguan` datetime NOT NULL,
  `xp` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `partisipasi` int(11) NOT NULL,
  `jawaban_benar` int(11) NOT NULL,
  `peringkat_satu_awal` int(11) NOT NULL,
  `medali_1` int(11) NOT NULL,
  `medali_2` int(11) NOT NULL,
  `bingkai` int(11) NOT NULL,
  `kartu_nama` int(11) NOT NULL,
  `ikon` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama_pengguna`, `kata_sandi`, `status_admin`, `preferensi_bahasa`, `nama_lengkap`, `tahun_lahir`, `jenis_kelamin`, `no_telepon`, `email`, `domisili`, `pekerjaan`, `pendidikan`, `xp_mingguan`, `waktu_berlaku_xp_mingguan`, `xp`, `level`, `partisipasi`, `jawaban_benar`, `peringkat_satu_awal`, `medali_1`, `medali_2`, `bingkai`, `kartu_nama`, `ikon`) VALUES
(1, 'Admin', '$2y$10$3GRaXfnuxhHq6Opltk8wceVV68ZSGaB2R1kGv0lrk.gO9xhCnDToC', 1, 0, '', 0, '', '', '', '', '', '', 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 0, 0, 0, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna_melabeli_data`
--

CREATE TABLE `pengguna_melabeli_data` (
  `sentimen` int(11) NOT NULL,
  `pengguna` int(11) NOT NULL,
  `data` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna_memiliki_achievement`
--

CREATE TABLE `pengguna_memiliki_achievement` (
  `pengguna` int(11) NOT NULL,
  `achievement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna_memiliki_ikon`
--

CREATE TABLE `pengguna_memiliki_ikon` (
  `pengguna` int(11) NOT NULL,
  `ikon` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna_memiliki_kartu`
--

CREATE TABLE `pengguna_memiliki_kartu` (
  `pengguna` int(11) NOT NULL,
  `kartu_nama` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna_memiliki_medali`
--

CREATE TABLE `pengguna_memiliki_medali` (
  `pengguna` int(11) NOT NULL,
  `medali` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna_mengerjakan_set`
--

CREATE TABLE `pengguna_mengerjakan_set` (
  `pengguna` int(11) NOT NULL,
  `set_data` int(11) NOT NULL,
  `waktu` datetime NOT NULL,
  `skor` int(11) NOT NULL,
  `status_selesai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `set_data`
--

CREATE TABLE `set_data` (
  `id` int(11) NOT NULL,
  `judul` varchar(128) NOT NULL,
  `jumlah_item` int(11) NOT NULL,
  `kuota` int(11) NOT NULL,
  `bahasa` int(11) NOT NULL,
  `pengguna` int(11) NOT NULL,
  `waktu` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievement`
--
ALTER TABLE `achievement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bingkai`
--
ALTER TABLE `bingkai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ikon`
--
ALTER TABLE `ikon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kartu_nama`
--
ALTER TABLE `kartu_nama`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_medali`
--
ALTER TABLE `kategori_medali`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medali`
--
ALTER TABLE `medali`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `set_data`
--
ALTER TABLE `set_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievement`
--
ALTER TABLE `achievement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bingkai`
--
ALTER TABLE `bingkai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `data`
--
ALTER TABLE `data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ikon`
--
ALTER TABLE `ikon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kartu_nama`
--
ALTER TABLE `kartu_nama`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kategori_medali`
--
ALTER TABLE `kategori_medali`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `medali`
--
ALTER TABLE `medali`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `set_data`
--
ALTER TABLE `set_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
