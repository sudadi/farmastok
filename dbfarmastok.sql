-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 03 Mei 2017 pada 14.37
-- Versi Server: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbfarmastok`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tobat`
--

CREATE TABLE `tobat` (
  `id` int(11) NOT NULL,
  `kdobat` varchar(15) NOT NULL,
  `nmobat` varchar(30) NOT NULL,
  `satuan` varchar(15) NOT NULL,
  `limitstok` int(3) NOT NULL,
  `hbeli` float NOT NULL,
  `hjual` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tobat`
--

INSERT INTO `tobat` (`id`, `kdobat`, `nmobat`, `satuan`, `limitstok`, `hbeli`, `hjual`) VALUES
(1, '0123001', 'Parasetamot', 'tablet', 10, 100, 200),
(9, '0123002', 'Konidin', 'kaplet', 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tobatin`
--

CREATE TABLE `tobatin` (
  `id` int(11) NOT NULL,
  `kdtrans` varchar(15) NOT NULL,
  `kdsupp` varchar(15) NOT NULL,
  `tgltrans` date NOT NULL,
  `kdobat` varchar(15) NOT NULL,
  `qty` int(11) NOT NULL,
  `stat` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tobatin`
--

INSERT INTO `tobatin` (`id`, `kdtrans`, `kdsupp`, `tgltrans`, `kdobat`, `qty`, `stat`) VALUES
(21, '20170502001', '020230', '2017-05-02', '0123001', 3, ''),
(22, '20170502001', '020230', '2017-05-02', '0123002', 6, ''),
(23, '20170502002', '020230', '2017-05-02', '0123002', 2, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tobatout`
--

CREATE TABLE `tobatout` (
  `id` int(11) NOT NULL,
  `kdtrans` varchar(15) NOT NULL,
  `kdsat` varchar(5) NOT NULL,
  `kdobat` varchar(15) NOT NULL,
  `qty` int(3) NOT NULL,
  `tgltrans` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `opt` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tobatout`
--

INSERT INTO `tobatout` (`id`, `kdtrans`, `kdsat`, `kdobat`, `qty`, `tgltrans`, `opt`) VALUES
(5, '20170503001', '0002', '0123001', 4, '2017-05-03 19:22:32', ''),
(6, '20170503001', '0002', '0123002', 3, '2017-05-03 19:22:57', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tsatelit`
--

CREATE TABLE `tsatelit` (
  `id` int(11) NOT NULL,
  `kdsat` varchar(5) NOT NULL,
  `nmsat` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tsatelit`
--

INSERT INTO `tsatelit` (`id`, `kdsat`, `nmsat`) VALUES
(2, '0001', 'Bangsal Parang Seling'),
(3, '0002', 'Bangsal Parang Kusumo'),
(4, '0003', 'Bangsal Ceplok Sriwedari');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tsupp`
--

CREATE TABLE `tsupp` (
  `id` int(11) NOT NULL,
  `kdsupp` varchar(15) NOT NULL,
  `nmsupp` varchar(50) NOT NULL,
  `almsupp` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tsupp`
--

INSERT INTO `tsupp` (`id`, `kdsupp`, `nmsupp`, `almsupp`) VALUES
(3, '020230', 'PT. Megah Medika Pratama', ''),
(4, '020302', 'PT. Surya Global', ''),
(5, '020231', 'PT. Merapi Utama Pharma', ''),
(6, '020001', 'Antarmitra Sembada, PT.', ''),
(7, '020002', 'Anugrah Argon Medika, PT.', ''),
(8, '020003', 'Dos Ni Roha, PT', ''),
(9, '020004', 'Anugerah Pharmindo Lestari, PT', ''),
(10, '020005', 'Bina Sanprima, PT', ''),
(11, '020006', 'Enseval, PT', ''),
(12, '020007', 'Parit Padang Global, PT', ''),
(13, '020008', 'Millenium Pharmacon Int. PT', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tuser`
--

CREATE TABLE `tuser` (
  `id` int(11) NOT NULL,
  `userid` varchar(15) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `email` varchar(25) NOT NULL,
  `passwd` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tuser`
--

INSERT INTO `tuser` (`id`, `userid`, `nama`, `email`, `passwd`) VALUES
(1, 'admin', 'administrator', '', 'kampret');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tobat`
--
ALTER TABLE `tobat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kdobat` (`kdobat`);

--
-- Indexes for table `tobatin`
--
ALTER TABLE `tobatin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kdtrans` (`kdtrans`,`kdsupp`,`kdobat`);

--
-- Indexes for table `tobatout`
--
ALTER TABLE `tobatout`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kdtrans` (`kdtrans`,`kdsat`,`kdobat`);

--
-- Indexes for table `tsatelit`
--
ALTER TABLE `tsatelit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kdsat` (`kdsat`);

--
-- Indexes for table `tsupp`
--
ALTER TABLE `tsupp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kdsupp` (`kdsupp`);

--
-- Indexes for table `tuser`
--
ALTER TABLE `tuser`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userid` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tobat`
--
ALTER TABLE `tobat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tobatin`
--
ALTER TABLE `tobatin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `tobatout`
--
ALTER TABLE `tobatout`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tsatelit`
--
ALTER TABLE `tsatelit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tsupp`
--
ALTER TABLE `tsupp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `tuser`
--
ALTER TABLE `tuser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
