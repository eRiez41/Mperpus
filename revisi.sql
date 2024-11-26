-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Nov 2024 pada 04.22
-- Versi server: 10.4.18-MariaDB
-- Versi PHP: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `revisi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota`
--

CREATE TABLE `anggota` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `foto_siswa` varchar(255) DEFAULT NULL,
  `tahun_ajaran` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `anggota`
--

INSERT INTO `anggota` (`id`, `nama`, `nisn`, `alamat`, `telepon`, `kelas`, `foto_siswa`, `tahun_ajaran`) VALUES
(1, 'Anita', '1234567890', 'Jl. Merdeka No. 10', '081234567890', '9A', '', '2023/2024'),
(2, 'Budi', '0987654321', 'Jl. Pahlawan No. 5', '085678901234', '9B', '', '2023/2024'),
(3, 'Citra', '2345678901', 'Jl. Jenderal Sudirman No. 15', '087654321098', '9B', '', '2022/2023'),
(4, 'Dharma', '3456789012', 'Jl. Gajah Mada No. 20', '089876543210', '8B', '', '2023/2024'),
(5, 'Eka', '4567890123', 'Jl. Diponegoro No. 8', '081234567890', '7A', '', '2023/2024'),
(6, 'Fani', '5678901234', 'Jl. Asia Afrika No. 12', '085678901234', '7B', '', '2023/2024'),
(7, 'Gita', '6789012345', 'Jl. Gatot Subroto No. 25', '087654321098', '6A', '', '2023/2024'),
(8, 'Hadi', '7890123456', 'Jl. Veteran No. 6', '089876543210', '6B', '', '2023/2024'),
(9, 'Indra', '8901234567', 'Jl. Hayam Wuruk No. 18', '081234567890', '5A', '', '2023/2024'),
(10, 'Joko', '9012345678', 'Jl. Dipati Ukur No. 30', '085678901234', '5B', '', '2023/2024'),
(11, 'Kartika', '0123456789', 'Jl. A Yani No. 4', '087654321098', '9C', '', '2023/2024'),
(12, 'Lukman', '5432109876', 'Jl. Asia Tenggara No. 22', '089876543210', '9D', '', '2023/2024'),
(13, 'Maria', '6543210987', 'Jl. HOS Cokroaminoto No. 9', '081234567890', '8C', '', '2023/2024'),
(14, 'Nina', '7654321098', 'Jl. Diponegoro No. 11', '085678901234', '8D', '', '2023/2024'),
(15, 'Oscar', '8765432109', 'Jl. Diponegoro No. 13', '087654321098', '7C', '', '2023/2024'),
(31, 'ujang', '1212121200', 'tasik', '12121212', '9C', NULL, '2021/2022'),
(32, 'Deristiana Purtiwan', '12121213', 'Tasikmalaya', '085523763032', '7B', '', '2017/2018');

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `kode_buku` varchar(50) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `penerbit` varchar(255) NOT NULL,
  `tahun_terbit` year(4) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `jumlah_lembar` int(11) NOT NULL,
  `jumlah_buku` int(11) NOT NULL,
  `tahun_masuk` year(4) NOT NULL,
  `harga_buku` decimal(10,2) NOT NULL,
  `gambar_buku` varchar(255) DEFAULT NULL,
  `penulis` varchar(100) NOT NULL,
  `tahun_ajaran` varchar(10) NOT NULL,
  `kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`kode_buku`, `judul`, `penerbit`, `tahun_terbit`, `kategori`, `isbn`, `jumlah_lembar`, `jumlah_buku`, `tahun_masuk`, `harga_buku`, `gambar_buku`, `penulis`, `tahun_ajaran`, `kelas`) VALUES
('111', 'Ikan Terbang', 'Kemendikbud', 2020, 'Pelajaran', '97860251485190', 100, 56, 2023, '85000.00', '', 'Pak Adi', '2020/2021', 8),
('1143', 'Gening Aya', 'Kemendikbud', 2015, 'Pelajaran', '9786025148552', 120, 76, 2016, '12399.00', '', 'Abah De', '2017/2018', 7),
('190', 'Tak Terkira', 'sekolah', 2020, 'Pelajaran', '97860251454', 120, 12, 2020, '12000.00', '', 'Abah De', '2023/2024', 9),
('BK001', 'Matematika SMP 7', 'Penerbit Pendidikan', 2022, 'Pelajaran', '978-1234567890', 200, 5, 2023, '75000.00', 'matematika_smp.jpg', 'Dr. Purnomo', '2023/2024', 7),
('BK002', 'Bahasa Indonesia SMP 9', 'Penerbit Budi Pustaka', 2022, 'Pelajaran', '978-9876543210', 180, 3, 2023, '85000.00', 'bahasa_indonesia_smp.jpg', 'Drs. Ananda', '2018/2019', 9),
('BK003', 'Fiksi Remaja: Petualangan di Nusantara', 'Penerbit Remaja Ceria', 2023, 'Fiksi', '978-7654321098', 250, 4, 2023, '90000.00', 'fiksi_petualangan.jpg', 'Dian Citra', '2023/2024', 7),
('BK004', 'Buku Biologi SMP 9', 'Penerbit Biologi Maju', 2022, 'Ilmiah', '978-5678901234', 220, 6, 2023, '80000.00', 'biologi_smp.jpg', 'Dr. Siti Hartati', '2023/2024', 9),
('BK005', 'Sejarah Indonesia SMP', 'Penerbit Sejarah Abadi', 2022, 'Sejarah', '978-3456789012', 190, 2, 2023, '95000.00', 'sejarah_indonesia.jpg', 'Prof. Dwi Suryanto', '2023/2024', 8),
('BK006', 'Kisah Nyata Pahlawan Muda', 'Penerbit Inspirasi Pemuda', 2023, 'Non-Fiksi', '978-5432109876', 210, 4, 2023, '88000.00', 'kisah_pahlawan.jpg', 'Susi Purnama', '2023/2024', 7),
('BK007', 'Buku Kimia SMP 8', 'Penerbit Kimia Jaya', 2022, 'Pelajaran', '978-0987654321', 200, 3, 2023, '82000.00', 'kimia_smp.jpg', 'Prof. Ahmad Yani', '2023/2024', 8),
('BK008', 'Novel Fantasi: Dunia Ksatria', 'Penerbit Fantasi Dunia', 2023, 'Fiksi', '978-6789012345', 240, 5, 2023, '92000.00', 'novel_fantasi.jpg', 'Rani Novianti', '2023/2024', 9),
('BK009', 'Teknologi Informasi SMP 7', 'Penerbit Tekno Cerdas', 2022, 'Pelajaran', '978-3210987654', 230, 4, 2023, '86000.00', 'teknologi_informasi.jpg', 'Dewi Susanti', '2017/2018', 7),
('BK010', 'Buku Ekonomi SMP 9', 'Penerbit Ekonomi Mandiri', 2022, 'Pelajaran', '978-4567890123', 210, 3, 2023, '89000.00', 'ekonomi_smp.jpg', 'Dr. Haryanto', '2023/2024', 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku_tamu`
--

CREATE TABLE `buku_tamu` (
  `id` int(11) NOT NULL,
  `id_anggota` int(11) DEFAULT NULL,
  `keperluan` enum('meminjam','mengunjungi') DEFAULT NULL,
  `tanggal_kunjungan` timestamp NOT NULL DEFAULT current_timestamp(),
  `tahun_ajaran` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `buku_tamu`
--

INSERT INTO `buku_tamu` (`id`, `id_anggota`, `keperluan`, `tanggal_kunjungan`, `tahun_ajaran`) VALUES
(13, 2, 'mengunjungi', '2024-06-28 23:54:13', '2023/2024'),
(14, 14, 'meminjam', '2024-06-29 00:02:16', '2023/2024'),
(15, 15, 'meminjam', '2024-06-29 00:02:56', '2023/2024'),
(17, 10, 'mengunjungi', '2024-06-29 00:11:22', '2023/2024'),
(18, 31, 'meminjam', '2024-06-30 10:10:56', '2021/2022');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `kd_peminjaman` int(11) NOT NULL,
  `id` int(11) DEFAULT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_pengembalian` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `kategori` varchar(20) DEFAULT NULL,
  `tahun_ajaran` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`kd_peminjaman`, `id`, `tanggal_pinjam`, `tanggal_pengembalian`, `status`, `kategori`, `tahun_ajaran`) VALUES
(74, 32, '2024-07-05', NULL, 'dipinjam', 'wajib', '2017/2018'),
(75, 31, '2024-07-05', NULL, 'dipinjam', 'opsional', ''),
(76, 32, '2024-07-10', NULL, 'dipinjam', 'wajib', '2017/2018'),
(77, 3, '2024-07-10', NULL, 'dipinjam', 'opsional', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_detail`
--

CREATE TABLE `peminjaman_detail` (
  `id` int(11) NOT NULL,
  `kd_peminjaman` int(11) DEFAULT NULL,
  `kode_buku` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `peminjaman_detail`
--

INSERT INTO `peminjaman_detail` (`id`, `kd_peminjaman`, `kode_buku`, `status`) VALUES
(263, 74, '1143', 'dipinjam'),
(264, 74, 'BK009', 'dipinjam'),
(265, 75, 'BK004', 'dipinjam'),
(266, 75, 'BK003', 'dipinjam'),
(271, 77, 'BK004', 'dipinjam'),
(272, 77, 'BK006', 'dipinjam'),
(273, 77, 'BK008', 'dipinjam'),
(274, 76, '1143', 'dikembalikan'),
(275, 76, 'BK009', 'dikembalikan'),
(276, 76, '1143', 'dipinjam'),
(277, 76, 'BK009', 'dipinjam');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('Admin','Pustakawan') DEFAULT 'Pustakawan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'deris', '123', 'Pustakawan'),
(2, 'admin', 'admin', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nisn` (`nisn`);

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`kode_buku`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indeks untuk tabel `buku_tamu`
--
ALTER TABLE `buku_tamu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buku_tamu_ibfk_1` (`id_anggota`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`kd_peminjaman`);

--
-- Indeks untuk tabel `peminjaman_detail`
--
ALTER TABLE `peminjaman_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kd_peminjaman` (`kd_peminjaman`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `buku_tamu`
--
ALTER TABLE `buku_tamu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `kd_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_detail`
--
ALTER TABLE `peminjaman_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=278;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `buku_tamu`
--
ALTER TABLE `buku_tamu`
  ADD CONSTRAINT `buku_tamu_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id`);

--
-- Ketidakleluasaan untuk tabel `peminjaman_detail`
--
ALTER TABLE `peminjaman_detail`
  ADD CONSTRAINT `peminjaman_detail_ibfk_1` FOREIGN KEY (`kd_peminjaman`) REFERENCES `peminjaman` (`kd_peminjaman`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
