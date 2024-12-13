-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 29 Okt 2024 pada 13.14
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tif23subang_native`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `agenda`
--

CREATE TABLE `agenda` (
  `id_agenda` int NOT NULL,
  `nama_agenda` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_kegiatan` date NOT NULL,
  `lokasi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `aspirasi`
--

CREATE TABLE `aspirasi` (
  `id_aspirasi` int NOT NULL,
  `invoice` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_aspirasi` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `pesan_aspirasi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_pengajuan` date DEFAULT NULL,
  `status` varchar(15) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aspirasi`
--

INSERT INTO `aspirasi` (`id_aspirasi`, `invoice`, `nama_lengkap`, `jenis_aspirasi`, `pesan_aspirasi`, `tanggal_pengajuan`, `status`) VALUES
(1, 'tif-sb-0001', 'Test', 'Aspirasi Kelas', 'Test', '2024-10-02', 'Menunggu');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bulan_pembayaran`
--

CREATE TABLE `bulan_pembayaran` (
  `id_bulan_pembayaran` int NOT NULL,
  `nama_bulan` enum('januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember') COLLATE utf8mb4_general_ci NOT NULL,
  `tahun` int NOT NULL,
  `pembayaran_perminggu` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int NOT NULL,
  `nama_jabatan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`) VALUES
(1, 'Administrator'),
(2, 'Wakil KM'),
(3, 'Sekretaris'),
(4, 'Bendahara'),
(5, 'Koordinator'),
(6, 'Mahasiswa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendapatan`
--

CREATE TABLE `pendapatan` (
  `id_pendapatan` int NOT NULL,
  `nama_pendapatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah_pendapatan` int NOT NULL,
  `tanggal_pendapatan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` int NOT NULL,
  `jumlah_pengeluaran` int NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_pengeluaran` int NOT NULL,
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat`
--

CREATE TABLE `riwayat` (
  `id_riwayat` int NOT NULL,
  `id_user` int NOT NULL,
  `id_uang_kas` int NOT NULL,
  `aksi` text COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `riwayat`
--

INSERT INTO `riwayat` (`id_riwayat`, `id_user`, `id_uang_kas`, `aksi`, `tanggal`) VALUES
(44, 6, 25, 'telah mengubah pembayaran minggu ke-1 dari Rp. 0 menjadi Rp. 2,500', 1729361441),
(45, 6, 25, 'telah mengubah pembayaran minggu ke-4 dari Rp. 2,500 menjadi Rp. 0', 1729363588),
(46, 6, 25, 'telah mengubah pembayaran minggu ke-3 dari Rp. 2,500 menjadi Rp. 0', 1729363596),
(47, 6, 25, 'telah mengubah pembayaran minggu ke-2 dari Rp. 2,500 menjadi Rp. 0', 1729363602),
(48, 6, 25, 'telah mengubah pembayaran minggu ke-1 dari Rp. 2,500 menjadi Rp. 0', 1729363607),
(49, 6, 25, 'telah mengubah pembayaran minggu ke-1 dari Rp. 0 menjadi Rp. 1,000', 1729363617),
(50, 6, 25, 'telah mengubah pembayaran minggu ke-1 dari Rp. 1,000 menjadi Rp. 2,500', 1729363624),
(51, 6, 25, 'telah mengubah pembayaran minggu ke-2 dari Rp. 0 menjadi Rp. 2,500', 1729363631),
(52, 6, 25, 'telah mengubah pembayaran minggu ke-3 dari Rp. 0 menjadi Rp. 2,500', 1729363639),
(53, 6, 25, 'telah mengubah pembayaran minggu ke-4 dari Rp. 0 menjadi Rp. 2,500', 1729363646),
(54, 6, 26, 'telah mengubah pembayaran minggu ke-1 dari Rp. 0 menjadi Rp. 2,500', 1729364800),
(55, 6, 26, 'telah mengubah pembayaran minggu ke-2 dari Rp. 0 menjadi Rp. 2,500', 1729364807),
(56, 1, 26, 'telah mengubah pembayaran minggu ke-4 dari Rp. 2,500 menjadi Rp. 0', 1729475117),
(57, 1, 26, 'telah mengubah pembayaran minggu ke-3 dari Rp. 2,500 menjadi Rp. 0', 1729475122),
(58, 1, 26, 'telah mengubah pembayaran minggu ke-2 dari Rp. 2,500 menjadi Rp. 0', 1729475128),
(59, 1, 26, 'telah mengubah pembayaran minggu ke-1 dari Rp. 2,500 menjadi Rp. 0', 1729475133),
(60, 1, 27, 'telah mengubah pembayaran minggu ke-1 dari Rp. 0 menjadi Rp. 2,500', 1729475167),
(61, 1, 27, 'telah mengubah pembayaran minggu ke-2 dari Rp. 0 menjadi Rp. 2,500', 1729475174),
(62, 1, 27, 'telah mengubah pembayaran minggu ke-2 dari Rp. 2,500 menjadi Rp. 0', 1729475415),
(63, 1, 27, 'telah mengubah pembayaran minggu ke-1 dari Rp. 2,500 menjadi Rp. 2,500', 1729475420),
(64, 1, 28, 'telah mengubah pembayaran minggu ke-1 dari Rp. 0 menjadi Rp. 2,500', 1729475449),
(65, 1, 28, 'telah mengubah pembayaran minggu ke-2 dari Rp. 0 menjadi Rp. 2,500', 1729475459),
(66, 1, 28, 'telah mengubah pembayaran minggu ke-3 dari Rp. 0 menjadi Rp. 2,500', 1729476376),
(67, 1, 28, 'telah mengubah pembayaran minggu ke-4 dari Rp. 0 menjadi Rp. 2,500', 1729476479),
(68, 1, 28, 'telah mengubah pembayaran minggu ke-4 dari Rp. 2,500 menjadi Rp. 0', 1729476722),
(69, 1, 28, 'telah mengubah pembayaran minggu ke-3 dari Rp. 2,500 menjadi Rp. 0', 1729476728),
(70, 1, 28, 'telah mengubah pembayaran minggu ke-2 dari Rp. 2,500 menjadi Rp. 0', 1729476733),
(71, 1, 28, 'telah mengubah pembayaran minggu ke-1 dari Rp. 2,500 menjadi Rp. 0', 1729476738),
(72, 1, 29, 'telah mengubah pembayaran minggu ke-1 dari Rp. 0 menjadi Rp. 2,000', 1729476769),
(73, 1, 29, 'telah mengubah pembayaran minggu ke-1 dari Rp. 2,000 menjadi Rp. 0', 1729477178),
(74, 1, 31, 'telah mengubah pembayaran minggu ke-1 dari Rp. 0 menjadi Rp. 2,000', 1729477582),
(75, 1, 32, 'telah mengubah pembayaran minggu ke-1 dari Rp. 0 menjadi Rp. 2,000', 1729477851);

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_pengeluaran`
--

CREATE TABLE `riwayat_pengeluaran` (
  `id_riwayat_pengeluaran` int NOT NULL,
  `id_user` int NOT NULL,
  `aksi` text COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `riwayat_pengeluaran`
--

INSERT INTO `riwayat_pengeluaran` (`id_riwayat_pengeluaran`, `id_user`, `aksi`, `tanggal`) VALUES
(4, 1, 'telah menambahkan pengeluaran test dengan biaya Rp. 1,000', 1729157342),
(5, 6, 'telah menambahkan pengeluaran beli spidol dengan biaya Rp. 5,000', 1729170028),
(6, 1, 'telah menambahkan pengeluaran uhuy dengan biaya Rp. 5,000', 1729478187);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int NOT NULL,
  `nama_siswa` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nim` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_kelamin` enum('pria','wanita') COLLATE utf8mb4_general_ci NOT NULL,
  `no_telepon` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `uang_kas`
--

CREATE TABLE `uang_kas` (
  `id_uang_kas` int NOT NULL,
  `id_siswa` int NOT NULL,
  `id_bulan_pembayaran` int NOT NULL,
  `minggu_ke_1` int DEFAULT NULL,
  `minggu_ke_2` int DEFAULT NULL,
  `minggu_ke_3` int DEFAULT NULL,
  `minggu_ke_4` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_jabatan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama_lengkap`, `username`, `password`, `id_jabatan`) VALUES
(1, 'Doni Setiawan Wahyono', 'admin', '$2y$10$RtlG8gY2cp/2BYEeMBJ2C.tMli1qvWGCoT/jkKIZVNrRJ/4cGbbTm', 1),
(6, 'Restu Alfina Rahayu', 'restu', '$2y$10$aUjFs3GaOYjevD5nflXKOebDEGdYT7btcKHh/QXgpagk4OhchRkEe', 4),
(7, 'Aisah Gandari Rahmah', 'aisah', '$2y$10$RULVqUlILUFHt/gQZZE2K.4oIUMwiG3F66.mTTI1glfFpMQVO8lX6', 3),
(8, 'Rafli Ramadhan', 'rafli', '$2y$10$EhjIgYSbN7sei9G6U5rnsOw56WDnFDh6GIyv85PaljDiMrpQZk2x2', 2),
(9, 'Salma Rostika Nurajni', 'salma', '$2y$10$DE.hwqm3EXmnerp07F2PbuSm7nfm6IKAks3Y9VgkhT4KSyb6IujkS', 5),
(10, 'Revalina Putri Artamevia', 'revalina', '$2y$10$/qxE.jXHo4Dg.kP8fc2m3OuvdCPotXNDWKuvwBRqY9vErj7brATE6', 4),
(11, 'Muhammad Raiham Samih', 'raihan', '$2y$10$i2q8Hgb3XPH9EQlKrv4H0.sdM98ROGscvToT7WgMZKFLUur2fZ7Hq', 5),
(12, 'Muhammad Danial Fadli', 'danial', '$2y$10$EuNGO67uCIZdk0MUKBcYzOpk8cZEOZJBxM47oCCyaRyUsKMjWa3bq', 5);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id_agenda`);

--
-- Indeks untuk tabel `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD PRIMARY KEY (`id_aspirasi`);

--
-- Indeks untuk tabel `bulan_pembayaran`
--
ALTER TABLE `bulan_pembayaran`
  ADD PRIMARY KEY (`id_bulan_pembayaran`);

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indeks untuk tabel `pendapatan`
--
ALTER TABLE `pendapatan`
  ADD PRIMARY KEY (`id_pendapatan`);

--
-- Indeks untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `riwayat`
--
ALTER TABLE `riwayat`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_uang_kas` (`id_uang_kas`);

--
-- Indeks untuk tabel `riwayat_pengeluaran`
--
ALTER TABLE `riwayat_pengeluaran`
  ADD PRIMARY KEY (`id_riwayat_pengeluaran`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indeks untuk tabel `uang_kas`
--
ALTER TABLE `uang_kas`
  ADD PRIMARY KEY (`id_uang_kas`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_bulan_pembayaran` (`id_bulan_pembayaran`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_jabatan` (`id_jabatan`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id_agenda` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `aspirasi`
--
ALTER TABLE `aspirasi`
  MODIFY `id_aspirasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `bulan_pembayaran`
--
ALTER TABLE `bulan_pembayaran`
  MODIFY `id_bulan_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `pendapatan`
--
ALTER TABLE `pendapatan`
  MODIFY `id_pendapatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `riwayat`
--
ALTER TABLE `riwayat`
  MODIFY `id_riwayat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT untuk tabel `riwayat_pengeluaran`
--
ALTER TABLE `riwayat_pengeluaran`
  MODIFY `id_riwayat_pengeluaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `uang_kas`
--
ALTER TABLE `uang_kas`
  MODIFY `id_uang_kas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
