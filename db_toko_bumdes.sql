CREATE DATABASE IF NOT EXISTS db_toko_bumdes;
USE db_toko_bumdes;

-- 1. Tabel Pengguna (Admin & Kasir)
CREATE TABLE data_pengguna (
    id_pengguna INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('Admin', 'Kasir') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabel Barang
CREATE TABLE data_barang (
    id_barang INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(50) NOT NULL UNIQUE,
    nama_barang VARCHAR(100) NOT NULL,
    stok INT DEFAULT 0,
    harga_beli DECIMAL(12,2) NOT NULL,
    harga_jual DECIMAL(12,2) NOT NULL,
    satuan VARCHAR(20) DEFAULT 'Pcs'
);

-- 3. Tabel Pembelian (Restock Barang)
CREATE TABLE data_pembelian (
    id_pembelian INT AUTO_INCREMENT PRIMARY KEY,
    no_faktur VARCHAR(50) NOT NULL,
    tanggal_beli DATE NOT NULL,
    id_barang INT,
    jumlah INT NOT NULL,
    total_harga DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (id_barang) REFERENCES data_barang(id_barang) ON DELETE SET NULL
);

-- 4. Tabel Penjualan (Transaksi Utama)
CREATE TABLE data_penjualan (
    id_penjualan INT AUTO_INCREMENT PRIMARY KEY,
    no_invoice VARCHAR(50) NOT NULL UNIQUE,
    tanggal_jual DATETIME NOT NULL,
    id_pengguna INT,
    total_bayar DECIMAL(12,2) NOT NULL,
    metode_bayar ENUM('Tunai', 'Tempo') NOT NULL,
    status_bayar ENUM('Lunas', 'Belum Lunas') NOT NULL,
    nama_pelanggan VARCHAR(100) DEFAULT 'Umum',
    jatuh_tempo DATE NULL,
    FOREIGN KEY (id_pengguna) REFERENCES data_pengguna(id_pengguna)
);

-- 5. Detail Penjualan (Item yang dibeli)
CREATE TABLE detail_penjualan (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    no_invoice VARCHAR(50),
    id_barang INT,
    jumlah INT NOT NULL,
    harga_satuan DECIMAL(12,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (no_invoice) REFERENCES data_penjualan(no_invoice) ON DELETE CASCADE,
    FOREIGN KEY (id_barang) REFERENCES data_barang(id_barang)
);

-- Insert User Default (Password: admin123)
INSERT INTO data_pengguna (username, password, nama_lengkap, role) VALUES 
('admin', '$2y$10$wK8rFhXvX1M1L4KBlzW5eOq6X6vP3Nl6.SreK2K9J6GzXb7N3rC2a', 'Administrator BUMDes', 'Admin');