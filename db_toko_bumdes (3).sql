-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Jun 2026 pada 05.59
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
-- Database: `db_toko_bumdes`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bayar_sewa`
--

CREATE TABLE `bayar_sewa` (
  `id_bayar` int(11) NOT NULL,
  `no_kwitansi` varchar(30) NOT NULL,
  `kode_sewa` varchar(30) NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `jumlah_bayar` decimal(15,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `petugas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bayar_sewa`
--

INSERT INTO `bayar_sewa` (`id_bayar`, `no_kwitansi`, `kode_sewa`, `tanggal_bayar`, `jumlah_bayar`, `keterangan`, `petugas`) VALUES
(1, 'KW-20260528-001', 'SEWA-20260528-001', '2026-01-06', 500000.00, 'Pembayaran sewa Lapak 1 bulan Januari 26', 'Staff BUMDes'),
(2, 'KW-20260528-002', 'SEWA-20260528-002', '2026-01-06', 500000.00, 'Pembayaran sewa Lapak 2 bulan Januari 26 ', 'Staff BUMDes'),
(3, 'KW-20260529-001', 'SEWA-20260528-001', '2026-02-04', 500000.00, 'Bln Feb 26', 'Staff BUMDes'),
(4, 'KW-20260529-002', 'SEWA-20260528-004', '2026-03-07', 500000.00, 'Pembayarn sewa bulan 03-26', 'Staff BUMDes'),
(5, 'KW-20260617-001', 'SEWA-20260528-001', '2026-06-16', 500000.00, 'Pembayaran Sewa Lapak Bulan Juni 26', 'Staff BUMDes'),
(6, 'KW-20260617-002', 'SEWA-20260528-002', '2026-06-16', 500000.00, 'Pembayaran sewa Lapak No. 2 Juni 2026', 'Staff BUMDes'),
(7, 'KW-20260617-003', 'SEWA-20260528-008', '2026-06-16', 500000.00, 'Pembayaran Sewa Kios 3 bulan Juni 26', 'Staff BUMDes'),
(8, 'KW-20260627-001', 'SEWA-20260528-002', '2026-06-12', 500000.00, 'Pembayaran Lapak No. 1', 'Staff BUMDes');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cicilan_hutang`
--

CREATE TABLE `cicilan_hutang` (
  `id_cicilan` int(11) NOT NULL,
  `id_penjualan` int(11) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `jumlah_bayar` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_barang`
--

CREATE TABLE `data_barang` (
  `id_barang` int(11) NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kategori_barang` varchar(50) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `harga_beli` decimal(12,2) NOT NULL,
  `harga_jual` decimal(12,2) NOT NULL,
  `satuan` varchar(20) DEFAULT 'Pcs'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_barang`
--

INSERT INTO `data_barang` (`id_barang`, `kode_barang`, `nama_barang`, `kategori_barang`, `stok`, `harga_beli`, `harga_jual`, `satuan`) VALUES
(1, 'BRG-0001', 'Beras Premium ', NULL, -1, 14400.00, 14800.00, 'Kg'),
(2, 'BRG-0002', 'Telur Ayam Negeri', NULL, 0, 26500.00, 28000.00, 'Kg'),
(3, 'BRG-0003', 'Minyak sayur', NULL, -10, 18000.00, 264000.00, 'Doz'),
(4, 'BRG-0004', 'Ayam Fillet Dada', NULL, 90, 48000.00, 56000.00, 'Kg'),
(5, 'BRG-0005', 'Garam ', NULL, 0, 7000.00, 13500.00, 'Kg'),
(6, 'BRG-0006', 'Ayam Fillet Dada ', NULL, -90, 38500.00, 48000.00, 'Kg'),
(7, 'BRG-0007', 'Keju Chedar promiz', NULL, 7, 87500.00, 110000.00, 'Pack'),
(8, 'BRG-0008', 'Garam', NULL, 0, 8000.00, 13500.00, 'Kg'),
(9, 'BRG-0009', 'Royco Ayam', NULL, 8, 43400.00, 50000.00, 'Kg'),
(10, 'BRG-0010', 'Gula Pasir', NULL, 0, 17300.00, 19000.00, 'Kg'),
(11, 'BRG-0011', 'Minyak Goreng Sania', NULL, 1, 220000.00, 264000.00, 'Doz'),
(12, 'BRG-0012', 'Tepung Tapioka', NULL, 0, 6700.00, 12500.00, 'Kg'),
(13, 'BRG-0013', 'Tepung Terigu', NULL, 60, 9000.00, 12000.00, 'Kg'),
(14, 'BRG-0014', 'Kecap Manis 700 ML', NULL, 0, 24900.00, 30000.00, 'Pouch'),
(15, 'BRG-0015', 'Saus Tiram Saori Botol 1 L', NULL, 0, 59500.00, 60800.00, 'Botol'),
(16, 'BRG-0016', 'Sambal Saos Mahkota 1 L', NULL, 0, 13900.00, 14000.00, 'Pouch'),
(17, 'BRG-0017', 'Saus Tomat Delmonte 1 Kg', NULL, 0, 15700.00, 16000.00, 'Pouch'),
(18, 'BRG-0018', 'ABC Kecap Manis 520ML/685gr', NULL, 0, 20000.00, 22000.00, 'Pouch');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_keuangan`
--

CREATE TABLE `data_keuangan` (
  `id_keuangan` int(11) NOT NULL,
  `no_ref` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `jumlah_dana` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_keuangan`
--

INSERT INTO `data_keuangan` (`id_keuangan`, `no_ref`, `tanggal`, `kategori`, `keterangan`, `jumlah_dana`) VALUES
(8, 'VCR-20260527-855', '2026-05-27', 'Operasional', 'Pembelian Bensin untuk transport pembelian barang', 150000),
(10, 'TRX-20260527-436', '2026-05-27', 'Pemasukan Ritel', 'Pelunasan Piutang Toko atas Invoice: INV-2026-20260527065523', 19000),
(11, 'TRX-20260527-761', '2026-05-27', 'Pemasukan Ritel', 'Pelunasan Piutang Toko atas Invoice: INV-2026-5274', 190000),
(12, 'TRX-HUT-20260527-525', '2026-05-27', 'Pengeluaran Stok', 'Pelunasan Hutang Tempo Toko ke Supplier (Akay) Faktur: INV-0005/5/26', 18750),
(13, 'TRX-BELI-20260601-341', '2026-05-31', 'Pengeluaran Stok', 'Belanja Stok Kontan Pokok No Faktur: FAK-0001/31/5/26 (Supplier: Lotte)', 414000),
(14, 'TRX-BELI-20260601-294', '2026-05-31', 'Pengeluaran Stok', 'Belanja Stok Kontan Pokok No Faktur: FAK-0002/31/5/26 (Supplier: Lotte)', 347200),
(15, 'TRX-BELI-20260601-509', '2026-06-01', 'Pengeluaran Stok', 'Belanja Stok Kontan Pokok No Faktur: FAK-0001/1/6/26 (Supplier: Family Boneles)', 3465000),
(16, 'TRX-BELI-20260601-897', '2026-06-01', 'Pengeluaran Stok', 'Belanja Stok Kontan Pokok No Faktur: FAK-0002/1/6/26 (Supplier: Toko Plastik)', 700000),
(17, 'TRX-BELI-20260601-926', '2026-06-01', 'Pengeluaran Stok', 'Belanja Stok Kontan Pokok No Faktur: FAK-0003/1/6/26 (Supplier: Toko gen)', 220000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_pembelian`
--

CREATE TABLE `data_pembelian` (
  `id_pembelian` int(11) NOT NULL,
  `no_faktur` varchar(50) NOT NULL,
  `tanggal_beli` date NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `nama_supplier` varchar(100) DEFAULT 'Umum',
  `metode_bayar` enum('Tunai','Tempo') NOT NULL DEFAULT 'Tunai',
  `status_bayar` enum('Lunas','Belum Lunas') NOT NULL DEFAULT 'Lunas',
  `jatuh_tempo` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_pembelian`
--

INSERT INTO `data_pembelian` (`id_pembelian`, `no_faktur`, `tanggal_beli`, `id_barang`, `jumlah`, `total_harga`, `nama_supplier`, `metode_bayar`, `status_bayar`, `jatuh_tempo`) VALUES
(5, 'FAK-0001/31/5/26', '2026-05-31', 13, 60, 414000.00, 'Lotte', 'Tunai', 'Lunas', NULL),
(6, 'FAK-0002/31/5/26', '2026-05-31', 9, 8, 347200.00, 'Lotte', 'Tunai', 'Lunas', NULL),
(10, 'FAK-0001/1/6/26', '2026-06-01', 4, 90, 3465000.00, 'Family Boneles', 'Tempo', 'Belum Lunas', '2026-06-03'),
(11, 'FAK-0002/31/5/26', '2026-06-03', 11, 1, 220000.00, 'Family Agen', 'Tempo', 'Belum Lunas', '2026-06-06'),
(12, 'FAK-0003/1/6/26', '2026-06-03', 7, 8, 700000.00, 'Toko Plastik', 'Tempo', 'Belum Lunas', '2026-06-06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_pengguna`
--

CREATE TABLE `data_pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('Admin','Kasir') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_pengguna`
--

INSERT INTO `data_pengguna` (`id_pengguna`, `username`, `password`, `nama_lengkap`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$wK8rFhXvX1M1L4KBlzW5eOq6X6vP3Nl6.SreK2K9J6GzXb7N3rC2a', 'Administrator BUMDes', 'Admin', '2026-05-24 14:54:28'),
(2, 'direktur', '$2y$10$QnfSYfmtB0hyroK7eaZ.iOGo.OC3UuODFXHH72k7dxm1bu/Ds8Vri', 'Minta', 'Admin', '2026-05-27 15:43:16'),
(3, 'admin2', '$2y$10$X.sYDmjsUpDa0B2riQgC3.PiJfpci85kVwDPpESNCrAPuqfBl6kky', 'Arti', 'Admin', '2026-05-27 15:59:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_penjualan`
--

CREATE TABLE `data_penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `no_invoice` varchar(50) NOT NULL,
  `tanggal_jual` datetime NOT NULL,
  `id_pengguna` int(11) DEFAULT NULL,
  `total_bayar` decimal(12,2) NOT NULL,
  `metode_bayar` enum('Tunai','Tempo') NOT NULL,
  `status_bayar` enum('Lunas','Belum Lunas') NOT NULL,
  `nama_pelanggan` varchar(100) DEFAULT 'Umum',
  `jatuh_tempo` date DEFAULT NULL,
  `sisa_hutang` int(11) DEFAULT 0,
  `petugas` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_penjualan`
--

INSERT INTO `data_penjualan` (`id_penjualan`, `no_invoice`, `tanggal_jual`, `id_pengguna`, `total_bayar`, `metode_bayar`, `status_bayar`, `nama_pelanggan`, `jatuh_tempo`, `sisa_hutang`, `petugas`) VALUES
(24, 'INV-2026-5275', '2026-06-03 12:26:41', NULL, 4320000.00, 'Tempo', 'Belum Lunas', 'SPPG Cikahuripan 03', '2026-07-03', 0, NULL),
(25, 'INV-2026-5276', '2026-06-27 05:49:10', NULL, 110000.00, 'Tunai', 'Lunas', 'Umum', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_po`
--

CREATE TABLE `data_po` (
  `id_po` int(11) NOT NULL,
  `no_po` varchar(30) NOT NULL,
  `tanggal_po` date NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `alamat_supplier` text DEFAULT NULL,
  `total_estimasi` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('Pending','Dikirim','Selesai','Dibatalkan') NOT NULL DEFAULT 'Pending',
  `dibuat_oleh` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_sewa`
--

CREATE TABLE `data_sewa` (
  `id_sewa` int(11) NOT NULL,
  `kode_sewa` varchar(30) NOT NULL,
  `nama_penyewa` varchar(100) NOT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `no_hp` varchar(20) NOT NULL,
  `jenis_aset` enum('Kios','Lapak','Lapangan Bola') NOT NULL,
  `nama_aset` varchar(100) NOT NULL,
  `tarif_sewa` decimal(15,2) NOT NULL,
  `tipe_sewa` enum('Jam','Hari','Bulan','Tahun') NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `status_aktif` enum('Aktif','Selesai') NOT NULL DEFAULT 'Aktif',
  `status` varchar(20) DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_sewa`
--

INSERT INTO `data_sewa` (`id_sewa`, `kode_sewa`, `nama_penyewa`, `nik`, `no_hp`, `jenis_aset`, `nama_aset`, `tarif_sewa`, `tipe_sewa`, `tanggal_mulai`, `status_aktif`, `status`) VALUES
(1, 'SEWA-20260528-001', 'Bpk. Nahrudin', NULL, '082109765432', 'Kios', 'Lapak Nomor 1', 500000.00, 'Bulan', '2026-01-01', 'Aktif', 'Aktif'),
(2, 'SEWA-20260528-002', 'Bpk. Nahrudin', NULL, '082109765432', 'Kios', 'Lapak Nomor 2', 500000.00, 'Bulan', '2026-01-01', 'Aktif', 'Aktif'),
(3, 'SEWA-20260528-003', 'Mi Ovi', NULL, '081290786543', 'Kios', 'Lapak Nomor 3', 500000.00, 'Bulan', '2026-01-01', 'Aktif', 'Aktif'),
(4, 'SEWA-20260528-004', 'Bpk. Aldo', NULL, '082209785643', 'Lapak', 'Lapak Nomor 4', 500000.00, 'Bulan', '2026-01-01', 'Aktif', 'Aktif'),
(5, 'SEWA-20260528-005', 'Bpk. Minen', NULL, '085719674720', 'Lapak', 'Lapak Nomor 5', 500000.00, 'Bulan', '2026-05-28', 'Aktif', 'Aktif'),
(6, 'SEWA-20260528-006', 'Bpk. Hartoyo', NULL, '082298076453', 'Kios', 'Kios Nomor 1', 600000.00, 'Bulan', '2026-01-01', 'Aktif', 'Aktif'),
(7, 'SEWA-20260528-007', 'Bpk. Ismail', NULL, '083165987654', 'Kios', 'Kios Nomor 2', 600000.00, 'Bulan', '2026-01-01', 'Aktif', 'Aktif'),
(8, 'SEWA-20260528-008', 'Bpk. Aleng', NULL, '081209876453', 'Kios', 'Kios Nomor 3', 500000.00, 'Bulan', '2026-01-01', 'Aktif', 'Aktif'),
(9, 'SEWA-20260528-009', 'Ma Odong', NULL, '087709098765', 'Kios', 'Kios Nomor 4', 600000.00, 'Bulan', '2026-01-01', 'Aktif', 'Aktif'),
(10, 'SEWA-20260528-010', 'Bpk . Suta wijaya', NULL, '082109765433', 'Kios', 'Kios Nomor 6', 600000.00, 'Bulan', '2026-01-01', 'Aktif', 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `id_detail` int(11) NOT NULL,
  `no_invoice` varchar(50) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_penjualan`
--

INSERT INTO `detail_penjualan` (`id_detail`, `no_invoice`, `id_barang`, `jumlah`, `harga_satuan`, `subtotal`) VALUES
(13, 'INV-2026-5275', 6, 90, 0.00, 4320000.00),
(14, 'INV-2026-5276', 7, 1, 0.00, 110000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_po`
--

CREATE TABLE `detail_po` (
  `id_detail` int(11) NOT NULL,
  `no_po` varchar(30) NOT NULL,
  `nama_barang` varchar(150) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `satuan` varchar(30) NOT NULL DEFAULT 'Pcs',
  `harga_estimasi` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayarans_sewa`
--

CREATE TABLE `pembayarans_sewa` (
  `id_pembayaran` int(11) NOT NULL,
  `kode_sewa` varchar(50) NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `jumlah_bayar` int(11) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembayarans_sewa`
--

INSERT INTO `pembayarans_sewa` (`id_pembayaran`, `kode_sewa`, `tanggal_bayar`, `jumlah_bayar`, `keterangan`) VALUES
(8, 'SEWA-20260528-005', '2026-03-18', 500000, 'Pembayaran sewa Lapak Nomor 5 an. Bpk. Minen');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran_kas`
--

CREATE TABLE `pengeluaran_kas` (
  `id_pengeluaran` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text NOT NULL,
  `debet` double NOT NULL DEFAULT 0,
  `kredit` double NOT NULL DEFAULT 0,
  `nama_akun` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengeluaran_kas`
--

INSERT INTO `pengeluaran_kas` (`id_pengeluaran`, `tanggal`, `keterangan`, `debet`, `kredit`, `nama_akun`, `created_at`) VALUES
(1, '2026-05-27', 'Pembelian Bensin untuk transport pembelian barang', 0, 150000, 'Operasional', '2026-05-27 08:22:05'),
(2, '2026-05-19', 'Uang Makan harian Admin (Metode: Cash)', 0, 15000, 'Uang Makan', '2026-05-27 15:28:04'),
(3, '2026-05-17', 'Beli Bensin Mobil operasional (Metode: Cash)', 0, 99993, 'Uang Bensin', '2026-05-28 15:41:59'),
(4, '2026-01-07', '[Unit Sewa] Pembelian token listrik - Umum/Fasilitas', 0, 100000, 'Biaya Operasional Sewa (Umum/Fasilitas)', '2026-05-28 15:58:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran_sewa`
--

CREATE TABLE `pengeluaran_sewa` (
  `id_pengeluaran` int(11) NOT NULL,
  `kode_pengeluaran` varchar(50) NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `kategori_biaya` varchar(100) NOT NULL,
  `jumlah_keluar` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `petugas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `level` enum('admin','kasir','direktur','bendahara') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `level`) VALUES
(1, 'Administrator', 'admin', '0192023a7bbd73250516f069df18b500', 'admin'),
(2, 'Yudi Santoso', 'bendcmmsc', 'bendahara26', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bayar_sewa`
--
ALTER TABLE `bayar_sewa`
  ADD PRIMARY KEY (`id_bayar`),
  ADD UNIQUE KEY `no_kwitansi` (`no_kwitansi`);

--
-- Indeks untuk tabel `cicilan_hutang`
--
ALTER TABLE `cicilan_hutang`
  ADD PRIMARY KEY (`id_cicilan`);

--
-- Indeks untuk tabel `data_barang`
--
ALTER TABLE `data_barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`);

--
-- Indeks untuk tabel `data_keuangan`
--
ALTER TABLE `data_keuangan`
  ADD PRIMARY KEY (`id_keuangan`);

--
-- Indeks untuk tabel `data_pembelian`
--
ALTER TABLE `data_pembelian`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `data_pengguna`
--
ALTER TABLE `data_pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `data_penjualan`
--
ALTER TABLE `data_penjualan`
  ADD PRIMARY KEY (`id_penjualan`),
  ADD UNIQUE KEY `no_invoice` (`no_invoice`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indeks untuk tabel `data_po`
--
ALTER TABLE `data_po`
  ADD PRIMARY KEY (`id_po`),
  ADD UNIQUE KEY `no_po` (`no_po`);

--
-- Indeks untuk tabel `data_sewa`
--
ALTER TABLE `data_sewa`
  ADD PRIMARY KEY (`id_sewa`),
  ADD UNIQUE KEY `kode_sewa` (`kode_sewa`);

--
-- Indeks untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `no_invoice` (`no_invoice`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `detail_po`
--
ALTER TABLE `detail_po`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `no_po` (`no_po`);

--
-- Indeks untuk tabel `pembayarans_sewa`
--
ALTER TABLE `pembayarans_sewa`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `kode_sewa` (`kode_sewa`);

--
-- Indeks untuk tabel `pengeluaran_kas`
--
ALTER TABLE `pengeluaran_kas`
  ADD PRIMARY KEY (`id_pengeluaran`);

--
-- Indeks untuk tabel `pengeluaran_sewa`
--
ALTER TABLE `pengeluaran_sewa`
  ADD PRIMARY KEY (`id_pengeluaran`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bayar_sewa`
--
ALTER TABLE `bayar_sewa`
  MODIFY `id_bayar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `cicilan_hutang`
--
ALTER TABLE `cicilan_hutang`
  MODIFY `id_cicilan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_barang`
--
ALTER TABLE `data_barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `data_keuangan`
--
ALTER TABLE `data_keuangan`
  MODIFY `id_keuangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `data_pembelian`
--
ALTER TABLE `data_pembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `data_pengguna`
--
ALTER TABLE `data_pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `data_penjualan`
--
ALTER TABLE `data_penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `data_po`
--
ALTER TABLE `data_po`
  MODIFY `id_po` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_sewa`
--
ALTER TABLE `data_sewa`
  MODIFY `id_sewa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `detail_po`
--
ALTER TABLE `detail_po`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pembayarans_sewa`
--
ALTER TABLE `pembayarans_sewa`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran_kas`
--
ALTER TABLE `pengeluaran_kas`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran_sewa`
--
ALTER TABLE `pengeluaran_sewa`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `data_pembelian`
--
ALTER TABLE `data_pembelian`
  ADD CONSTRAINT `data_pembelian_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `data_barang` (`id_barang`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `data_penjualan`
--
ALTER TABLE `data_penjualan`
  ADD CONSTRAINT `data_penjualan_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `data_pengguna` (`id_pengguna`);

--
-- Ketidakleluasaan untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `detail_penjualan_ibfk_1` FOREIGN KEY (`no_invoice`) REFERENCES `data_penjualan` (`no_invoice`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_penjualan_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `data_barang` (`id_barang`);

--
-- Ketidakleluasaan untuk tabel `pembayarans_sewa`
--
ALTER TABLE `pembayarans_sewa`
  ADD CONSTRAINT `pembayarans_sewa_ibfk_1` FOREIGN KEY (`kode_sewa`) REFERENCES `data_sewa` (`kode_sewa`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
