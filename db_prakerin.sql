-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2021 at 05:29 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_prakerin`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bimbingan`
--

CREATE TABLE `tbl_bimbingan` (
  `kdbimbingan` int(11) NOT NULL,
  `nip` char(21) NOT NULL,
  `nim` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `judul` varchar(50) NOT NULL,
  `catatan` text NOT NULL,
  `file` text NOT NULL,
  `kdpenempatan` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_bimbingan`
--

INSERT INTO `tbl_bimbingan` (`kdbimbingan`, `nip`, `nim`, `tanggal`, `judul`, `catatan`, `file`, `kdpenempatan`) VALUES
(9, '2021173010552', 170180055, '2021-11-21', 'Sistem Informasi ATK Pada PT. Taspen (Persero)', 'mahasiswa sudah menyelesaikan bimbingan dan laporan ', 'lampiran/bimbingan/KARTU_KONSULTASI_KP2.doc', 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_file`
--

CREATE TABLE `tbl_file` (
  `kdfile` int(11) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `nama` text NOT NULL,
  `share` int(11) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_guru`
--

CREATE TABLE `tbl_guru` (
  `nip` char(21) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `mapel` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_industri`
--

CREATE TABLE `tbl_industri` (
  `kdind` int(11) NOT NULL,
  `nama_industri` varchar(50) NOT NULL,
  `bidang_kerja` varchar(50) NOT NULL,
  `deskripsi` text NOT NULL,
  `alamat_industri` text NOT NULL,
  `wilayah` varchar(50) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `website` text NOT NULL,
  `email` text NOT NULL,
  `syarat` text NOT NULL,
  `kuota` int(20) NOT NULL,
  `foto` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_industri`
--

INSERT INTO `tbl_industri` (`kdind`, `nama_industri`, `bidang_kerja`, `deskripsi`, `alamat_industri`, `wilayah`, `telepon`, `website`, `email`, `syarat`, `kuota`, `foto`) VALUES
(4, 'Bank Syariah Indonesia', 'Custumer Service', 'Bank Syariah Indonesia adalah lembaga perbankan syariah. Bank ini berdiri pada 01 Februari 2021 pukul 13.00 WIB atau bertepatan dengan tanggal 19 Jumadil Akhir 1442 H. Wikipedia', 'Jl. Medan - Banda Aceh No. 15, Kec. Lhoksukon, Kab. Aceh Utara, Aceh', 'Lhoksukon', '0836547526', 'https://www.bankbsi.co.id/jaringan/430', 'bsi@gmail.co.id', 'mahasiswa aktif', 4, 'bsi.jpg'),
(3, 'PT. Taspen (PERSERO) KC Lhokseumawe', 'Layanan', 'PT TASPEN (Persero) atau Dana Tabungan dan Asuransi Pegawai Negeri adalah Badan Usaha Milik Negara Indonesia yang bergerak di bidang asuransi tabungan hari tua dan dana pensiun bagi ASN dan Pejabat Negara.', 'Alamat: JL. Merdeka Timur, No. 198 A, Aceh Utara, Mon Geudong, Banda Sakti, Kota Lhokseumawe, Aceh', 'Lhokseumawe', '0873654647', 'https://www.taspen.co.id/', 'taspen@gmail.co.id', 'Mahasiswa aktif', 5, '2019-10-151.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_info`
--

CREATE TABLE `tbl_info` (
  `kdinfo` int(11) NOT NULL,
  `kdlabel` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `judul` text NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` text NOT NULL,
  `penulis` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_info`
--

INSERT INTO `tbl_info` (`kdinfo`, `kdlabel`, `tanggal`, `judul`, `deskripsi`, `gambar`, `penulis`) VALUES
(11, 2, '2021-11-17', 'dadad', '<p>kjlnhjklhjhk</p>', 'foto/info/Picturew1.png', 'SIM MAGANG'),
(12, 4, '2021-11-21', 'Gedung Prodi Sistem Informasi Di Universitas Malikussaleh', '<p>nahdgdfsytgeydvjyxgd<br></p>', 'foto/info/teknik.jpg', 'SIM MAGANG');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jurusan`
--

CREATE TABLE `tbl_jurusan` (
  `kdjurusan` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_jurusan`
--

INSERT INTO `tbl_jurusan` (`kdjurusan`, `nama`) VALUES
(4, 'Fakultas Teknik'),
(5, 'sistem informasi');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kelas`
--

CREATE TABLE `tbl_kelas` (
  `kdkelas` int(11) NOT NULL,
  `kdjurusan` char(5) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_kelas`
--

INSERT INTO `tbl_kelas` (`kdkelas`, `kdjurusan`, `nama`) VALUES
(7, '4', 'Jurusan Teknik Industri '),
(8, '4', 'Jurusan Teknik Kimia'),
(12, '5', 'A1 Sistem Informasi');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_label`
--

CREATE TABLE `tbl_label` (
  `kdlabel` int(11) NOT NULL,
  `nama_label` varchar(50) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_label`
--

INSERT INTO `tbl_label` (`kdlabel`, `nama_label`, `keterangan`) VALUES
(1, 'Pengumuman', '-'),
(2, 'Tips', '-'),
(3, 'Industri', '-'),
(4, 'Universitas', '-'),
(5, 'Lain-lain', '-');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mahasiswa`
--

CREATE TABLE `tbl_mahasiswa` (
  `nim` int(11) NOT NULL,
  `kdkelas` int(11) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `telp` varchar(14) NOT NULL,
  `foto` text NOT NULL,
  `password` text NOT NULL,
  `kdpemb` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_mahasiswa`
--

INSERT INTO `tbl_mahasiswa` (`nim`, `kdkelas`, `nama_lengkap`, `telp`, `foto`, `password`, `kdpemb`) VALUES
(170180055, 12, 'Mulianisaa', '082247138374', 'c461c62f8d36a2231565e3a511ed65f82.jpg', '43c2ff380bca60a6aed04417d9e3c004', 0),
(1657301055, 7, 'Jhon Doe', '6289502774065', 'Screenshot_8.png', 'd27be282ca9c4a8a92a0331ead7ffa08', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_nilai`
--

CREATE TABLE `tbl_nilai` (
  `kdnilai` int(11) NOT NULL,
  `kdpenempatan` int(11) NOT NULL,
  `keterangan` varchar(3) NOT NULL,
  `nilai` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_nilai`
--

INSERT INTO `tbl_nilai` (`kdnilai`, `kdpenempatan`, `keterangan`, `nilai`) VALUES
(1, 1, 'Tek', 90),
(3, 5, 'Lap', 98),
(9, 10, 'A', 90);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pemb`
--

CREATE TABLE `tbl_pemb` (
  `kdpemb` int(11) NOT NULL,
  `kdjurusan` char(5) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` text NOT NULL,
  `nip` char(21) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `wilayah` varchar(50) NOT NULL,
  `email` varchar(54) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_pemb`
--

INSERT INTO `tbl_pemb` (`kdpemb`, `kdjurusan`, `username`, `password`, `nip`, `nama_lengkap`, `wilayah`, `email`) VALUES
(12, '5', '1657s301055', '49030941543807763afd3a3f643285a8', '2021173010552', 'Jastin Iksanxl', 'Lhokseumawe', ''),
(14, '5', 'angga', '8479c86c7afcb56631104f5ce5d6de62', '1234567', 'Angga Pratama, S. kom. M. MSI', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_penempatan`
--

CREATE TABLE `tbl_penempatan` (
  `kdpenempatan` int(11) NOT NULL,
  `nim` int(11) NOT NULL,
  `kdpemb` int(11) NOT NULL,
  `kdind` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `wilayah` varchar(50) NOT NULL,
  `tahun` year(4) NOT NULL,
  `status` enum('-','proses','ditolak','diterima') NOT NULL,
  `surat` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_penempatan`
--

INSERT INTO `tbl_penempatan` (`kdpenempatan`, `nim`, `kdpemb`, `kdind`, `tanggal`, `wilayah`, `tahun`, `status`, `surat`) VALUES
(9, 1657301055, 0, 4, '2021-11-20', 'Ache Utara', 2021, 'diterima', 'i.pdf'),
(10, 170180055, 0, 3, '2021-11-21', 'Lhokseumawe', 2021, 'diterima', 'surat_permohonan_magan_di_upt_bkk.docx');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tolak_penempatan`
--

CREATE TABLE `tbl_tolak_penempatan` (
  `kdtolak` int(11) NOT NULL,
  `kdpenempatan` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `alasan` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `identitas` varchar(32) NOT NULL,
  `password` text NOT NULL,
  `status` varchar(11) NOT NULL,
  `foto` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `username`, `nama_lengkap`, `identitas`, `password`, `status`, `foto`) VALUES
(1, 'admin', 'SIM MAGANGSI', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'SIM1.png'),
(2, 'prodi', 'Jhon Doe', 'kaprodi', '21232f297a57a5a743894a0e4a801fc3', 'kaprodi', 'SIM1.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_bimbingan`
--
ALTER TABLE `tbl_bimbingan`
  ADD PRIMARY KEY (`kdbimbingan`),
  ADD KEY `nip` (`nip`,`nim`);

--
-- Indexes for table `tbl_file`
--
ALTER TABLE `tbl_file`
  ADD PRIMARY KEY (`kdfile`);

--
-- Indexes for table `tbl_guru`
--
ALTER TABLE `tbl_guru`
  ADD PRIMARY KEY (`nip`);

--
-- Indexes for table `tbl_industri`
--
ALTER TABLE `tbl_industri`
  ADD PRIMARY KEY (`kdind`);

--
-- Indexes for table `tbl_info`
--
ALTER TABLE `tbl_info`
  ADD PRIMARY KEY (`kdinfo`);

--
-- Indexes for table `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  ADD PRIMARY KEY (`kdjurusan`);

--
-- Indexes for table `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  ADD PRIMARY KEY (`kdkelas`);

--
-- Indexes for table `tbl_label`
--
ALTER TABLE `tbl_label`
  ADD PRIMARY KEY (`kdlabel`);

--
-- Indexes for table `tbl_mahasiswa`
--
ALTER TABLE `tbl_mahasiswa`
  ADD PRIMARY KEY (`nim`);

--
-- Indexes for table `tbl_nilai`
--
ALTER TABLE `tbl_nilai`
  ADD PRIMARY KEY (`kdnilai`);

--
-- Indexes for table `tbl_pemb`
--
ALTER TABLE `tbl_pemb`
  ADD PRIMARY KEY (`kdpemb`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indexes for table `tbl_penempatan`
--
ALTER TABLE `tbl_penempatan`
  ADD PRIMARY KEY (`kdpenempatan`),
  ADD KEY `nim` (`nim`);

--
-- Indexes for table `tbl_tolak_penempatan`
--
ALTER TABLE `tbl_tolak_penempatan`
  ADD PRIMARY KEY (`kdtolak`),
  ADD KEY `kdpenempatan` (`kdpenempatan`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_bimbingan`
--
ALTER TABLE `tbl_bimbingan`
  MODIFY `kdbimbingan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_file`
--
ALTER TABLE `tbl_file`
  MODIFY `kdfile` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_industri`
--
ALTER TABLE `tbl_industri`
  MODIFY `kdind` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_info`
--
ALTER TABLE `tbl_info`
  MODIFY `kdinfo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  MODIFY `kdjurusan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  MODIFY `kdkelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_label`
--
ALTER TABLE `tbl_label`
  MODIFY `kdlabel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_nilai`
--
ALTER TABLE `tbl_nilai`
  MODIFY `kdnilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_pemb`
--
ALTER TABLE `tbl_pemb`
  MODIFY `kdpemb` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_penempatan`
--
ALTER TABLE `tbl_penempatan`
  MODIFY `kdpenempatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_tolak_penempatan`
--
ALTER TABLE `tbl_tolak_penempatan`
  MODIFY `kdtolak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
