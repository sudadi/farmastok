-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 10 Mei 2017 pada 10.54
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
-- Struktur dari tabel `tfifo`
--

DROP TABLE IF EXISTS `tfifo`;
CREATE TABLE `tfifo` (
  `id` int(11) NOT NULL,
  `masuk` varchar(15) NOT NULL,
  `keluar` varchar(15) NOT NULL,
  `kdobat` varchar(15) NOT NULL,
  `QTY` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tobat`
--

DROP TABLE IF EXISTS `tobat`;
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
(1, '0123001', 'Parasetamol', 'tablet', 10, 100, 200),
(2, '0123002', 'Konidin', 'kaplet', 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tobatin`
--

DROP TABLE IF EXISTS `tobatin`;
CREATE TABLE `tobatin` (
  `id` int(11) NOT NULL,
  `kdtrans` varchar(15) NOT NULL,
  `kdsupp` varchar(15) NOT NULL,
  `tgltrans` date NOT NULL,
  `kdobat` varchar(15) NOT NULL,
  `qty` int(11) NOT NULL,
  `hbeli` int(11) NOT NULL,
  `keluar` int(6) NOT NULL,
  `sisa` int(6) NOT NULL,
  `stat` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tobatin`
--

INSERT INTO `tobatin` (`id`, `kdtrans`, `kdsupp`, `tgltrans`, `kdobat`, `qty`, `hbeli`, `keluar`, `sisa`, `stat`) VALUES
(1, '20170502001', '020230', '2017-05-02', '0123001', 3, 0, 0, 3, ''),
(2, '20170502001', '020230', '2017-04-02', '0123002', 6, 0, 0, 6, ''),
(3, '20170502002', '020230', '2017-05-02', '0123002', 6, 0, 0, 6, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tobatout`
--

DROP TABLE IF EXISTS `tobatout`;
CREATE TABLE `tobatout` (
  `id` int(11) NOT NULL,
  `kdtrans` varchar(15) NOT NULL,
  `kdsat` varchar(5) NOT NULL,
  `kdobat` varchar(15) NOT NULL,
  `qty` int(3) NOT NULL,
  `hjual` int(11) NOT NULL,
  `tgltrans` date NOT NULL,
  `opt` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Trigger `tobatout`
--
DROP TRIGGER IF EXISTS `OBATOUT_AFDELETE`;
DELIMITER $$
CREATE TRIGGER `OBATOUT_AFDELETE` AFTER DELETE ON `tobatout` FOR EACH ROW BEGIN
SET @KDOBAT=OLD.KDOBAT;
SET @KELUAR=OLD.KDTRANS;
SET @X=(SELECT COUNT(id) FROM tfifo WHERE KELUAR=@KELUAR AND KDOBAT=@KDOBAT ORDER BY ID  LIMIT 1);
IF (@X > 0 ) THEN
	REPEAT
    	SET @ID=(SELECT ID FROM tfifo WHERE KELUAR=@KELUAR AND KDOBAT=@KDOBAT ORDER BY ID  LIMIT 1);
    	SET @QTY=(SELECT QTY FROM tfifo WHERE ID=@ID);
        SET @KDTRANS=(SELECT MASUK FROM TFIFO WHERE ID = @ID);
		UPDATE TOBATIN SET SISA=SISA+@QTY, KELUAR=KELUAR-@QTY WHERE KDTRANS=@KDTRANS AND KDOBAT=@KDOBAT;
        DELETE FROM TFIFO WHERE ID = @ID;
    SET @X = @X - 1;
	UNTIL@X = 0
	END REPEAT;
END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `OBATOUT_AFINSERT`;
DELIMITER $$
CREATE TRIGGER `OBATOUT_AFINSERT` AFTER INSERT ON `tobatout` FOR EACH ROW BEGIN
SET @KELUAR=NEW.KDTRANS;
SET @KDOBAT=NEW.KDOBAT;
SET @JKELUAR=NEW.QTY;
SET @ID=(SELECT id FROM tobatin WHERE kdobat=@KDOBAT AND sisa > 0 ORDER BY kdtrans  LIMIT 1);
SET @SISA=(SELECT SISA FROM TOBATIN WHERE ID=@ID);
REPEAT
IF(@SISA>@JKELUAR) THEN
UPDATE TOBATIN SET SISA=@SISA-@JKELUAR, KELUAR=KELUAR+@JKELUAR WHERE ID=@ID;
SET @MASUK=(SELECT kdtrans FROM tobatin WHERE id=@ID); 
INSERT INTO tfifo (MASUK, KELUAR, KDOBAT, QTY) VALUES (@MASUK, @KELUAR, @KDOBAT, @JKELUAR);
SET @JKELUAR=0;
END IF;
IF (@SISA<@JKELUAR) THEN
UPDATE TOBATIN SET SISA=0, KELUAR=KELUAR+@SISA WHERE ID=@ID;
SET @MASUK=(SELECT kdtrans FROM tobatin WHERE id=@ID); 
INSERT INTO tfifo (MASUK, KELUAR, KDOBAT, QTY) VALUES (@MASUK, @KELUAR, @KDOBAT, @SISA);
SET @JKELUAR=@JKELUAR-@SISA;
SET @ID=(SELECT ID FROM TOBATIN WHERE KDOBAT=@KDOBAT AND SISA>0 ORDER BY KDTRANS LIMIT 1);
SET @SISA=(SELECT SISA FROM TOBATIN WHERE ID=@ID);
END IF;
IF(@SISA=@JKELUAR) THEN
UPDATE TOBATIN SET SISA=0,KELUAR=KELUAR+@JKELUAR WHERE ID=@ID;
SET @MASUK=(SELECT kdtrans FROM tobatin WHERE id=@ID); 
INSERT INTO tfifo (MASUK, KELUAR, KDOBAT, QTY) VALUES (@MASUK, @KELUAR, @KDOBAT, @JKELUAR);
SET @JKELUAR=0;
END IF;
UNTIL @JKELUAR=0
END REPEAT;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `OBATOUT_AFUPDATE`;
DELIMITER $$
CREATE TRIGGER `OBATOUT_AFUPDATE` AFTER UPDATE ON `tobatout` FOR EACH ROW BEGIN
SET @NQTY = NEW.QTY;
SET @KDOBAT = NEW.KDOBAT;
SET @KELUAR = NEW.KDTRANS;
SET @OQTY = OLD.QTY;

IF (@NQTY < @OQTY) THEN
	SET @QTY = @OQTY - @NQTY;
	SET @ID=(SELECT ID FROM TOBATIN WHERE KDOBAT=@KDOBAT AND KELUAR > 0 ORDER BY KDTRANS  DESC LIMIT 1);
	SET @KELUAR=(SELECT KELUAR FROM TOBATIN WHERE ID=@ID);
	REPEAT
		IF(@KELUAR > @QTY) THEN
			UPDATE TOBATIN SET SISA=SISA+@QTY, KELUAR=KELUAR-@QTY WHERE ID=@ID;
			SET @QTY=0;
		END IF;
		IF (@KELUAR < @QTY) THEN
			UPDATE TOBATIN SET KELUAR=0, SISA=SISA+@KELUAR WHERE ID=@ID;
			SET @QTY=@QTY-@KELUAR;
			SET @ID=(SELECT ID FROM TOBATIN WHERE KDOBAT=@KDOBAT AND KELUAR > 0 ORDER BY KDTRANS  DESC LIMIT 1);
			SET @KELUAR=(SELECT KELUAR FROM TOBATIN WHERE ID=@ID);
		END IF;
		IF(@KELUAR = @QTY) THEN
			UPDATE TOBATIN SET KELUAR=0, SISA=SISA+@KELUAR WHERE ID=@ID;
			SET @QTY=0;
		END IF;
	UNTIL @QTY=0
	END REPEAT;
END IF;

IF (@NQTY > @OQTY) THEN
	SET @JKELUAR=@NQTY-@OQTY;
	SET @ID=(SELECT ID FROM TOBATIN WHERE KDOBAT=@KDOBAT AND SISA>0 ORDER BY KDTRANS  LIMIT 1);
	SET @SISA=(SELECT SISA FROM TOBATIN WHERE ID=@ID);
	REPEAT
		IF(@SISA>@JKELUAR) THEN
			UPDATE TOBATIN SET SISA=@SISA-@JKELUAR, KELUAR=KELUAR+@JKELUAR WHERE ID=@ID;
            SET @MASUK=(SELECT kdtrans FROM tobatin WHERE id=@ID);
            INSERT INTO tfifo (MASUK, KELUAR, KDOBAT, QTY) VALUES (@MASUK, @KELUAR, @KDOBAT, @JKELUAR) ON DUPLICATE KEY UPDATE QTY=QTY+@JKELUAR;
			SET @JKELUAR=0;
		END IF;
		IF (@SISA<@JKELUAR) THEN
			UPDATE TOBATIN SET SISA=0, KELUAR=KELUAR+@SISA WHERE ID=@ID;
			SET @MASUK=(SELECT kdtrans FROM tobatin WHERE id=@ID); 
			INSERT INTO tfifo (MASUK, KELUAR, KDOBAT, QTY) VALUES (@MASUK, @KELUAR, @KDOBAT, @SISA) ON DUPLICATE KEY UPDATE QTY=QTY+@JKELUAR;
			SET @JKELUAR=@JKELUAR-@SISA;
			SET @ID=(SELECT ID FROM TOBATIN WHERE KDOBAT=@KDOBAT AND SISA>0 ORDER BY KDTRANS LIMIT 1);
			SET @SISA=(SELECT SISA FROM TOBATIN WHERE ID=@ID);
		END IF;
		IF(@SISA=@JKELUAR) THEN
			UPDATE TOBATIN SET SISA=0,KELUAR=KELUAR+@JKELUAR WHERE ID=@ID;
			SET @MASUK=(SELECT kdtrans FROM tobatin WHERE id=@ID); 
			INSERT INTO tfifo (MASUK, KELUAR, KDOBAT, QTY) VALUES (@MASUK, @KELUAR, @KDOBAT, @JKELUAR) ON DUPLICATE KEY UPDATE QTY=QTY+@JKELUAR;
			SET @JKELUAR=0;
		END IF;
	UNTIL @JKELUAR=0
	END REPEAT;
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tsatelit`
--

DROP TABLE IF EXISTS `tsatelit`;
CREATE TABLE `tsatelit` (
  `id` int(11) NOT NULL,
  `kdsat` varchar(5) NOT NULL,
  `nmsat` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tsatelit`
--

INSERT INTO `tsatelit` (`id`, `kdsat`, `nmsat`) VALUES
(1, '0001', 'Bangsal Parang Seling'),
(2, '0002', 'Bangsal Parang Kusumo'),
(3, '0003', 'Bangsal Ceplok Sriwedari');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tsupp`
--

DROP TABLE IF EXISTS `tsupp`;
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
(1, '020230', 'PT. Megah Medika Pratama', ''),
(2, '020302', 'PT. Surya Global', ''),
(3, '020231', 'PT. Merapi Utama Pharma', ''),
(4, '020001', 'Antarmitra Sembada, PT.', ''),
(5, '020002', 'Anugrah Argon Medika, PT.', ''),
(6, '020003', 'Dos Ni Roha, PT', ''),
(7, '020004', 'Anugerah Pharmindo Lestari, PT', ''),
(8, '020005', 'Bina Sanprima, PT', ''),
(9, '020006', 'Enseval, PT', ''),
(10, '020007', 'Parit Padang Global, PT', ''),
(11, '020008', 'Millenium Pharmacon Int. PT', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tuser`
--

DROP TABLE IF EXISTS `tuser`;
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
-- Indexes for table `tfifo`
--
ALTER TABLE `tfifo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `keluar` (`keluar`,`masuk`,`kdobat`) USING BTREE;

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
-- AUTO_INCREMENT for table `tfifo`
--
ALTER TABLE `tfifo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
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
