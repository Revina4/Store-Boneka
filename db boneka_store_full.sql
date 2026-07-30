-- 1. Membuat Database
CREATE DATABASE IF NOT EXISTS db_penjualan;
USE db_penjualan;

-- ==========================================
-- 2. Tabel Produk (Boneka)
-- ==========================================
CREATE TABLE IF NOT EXISTS tmbbrg (
    id_barang INT AUTO_INCREMENT PRIMARY KEY,
    seri VARCHAR(50) NOT NULL,
    nama_barang VARCHAR(100) NOT NULL,
    jenis VARCHAR(50),
    harga DECIMAL(15,2) NOT NULL,
    deskripsi TEXT,
    foto VARCHAR(255)
);

-- Data Isi Awal (3 Boneka)
INSERT INTO tmbbrg (id_barang, seri, nama_barang, jenis, harga, deskripsi, foto) VALUES
(1, 'DINO-001', 'Boneka Dinosaurus', 'Boneka Hewan', 48000, 'Bahan Yelvo, Lembut, sehingga tidak membuat iritasi pada kulit ketika memegang kain.', 'boneka1.JPEG'),
(2, 'KEL-002', 'Boneka Kelinci Biru', 'Boneka Hewan', 60000, 'Bahan Yelvo, Lembut, sangat cocok untuk hadiah ulang tahun anak.', 'boneka2.WEBP'),
(3, 'BRG-003', 'Boneka Beruang Pink', 'Boneka Hewan', 50000, 'Bahan Yelvo, Lembut, aman untuk bayi dan anak-anak.', 'boneka3.WEBP');


-- ==========================================
-- 3. Tabel Pengguna (Login & Registrasi)
-- ==========================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- (Opsional) Data user biasa contoh, bisa diisi lewat registrasi website.


-- ==========================================
-- 4. Tabel Pesan Kontak
-- ==========================================
CREATE TABLE IF NOT EXISTS kontak (
    id_kontak INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subjek VARCHAR(255),
    pesan TEXT NOT NULL,
    tanggal_kirim TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('baru', 'dibaca', 'dibalas') DEFAULT 'baru'
);


-- ==========================================
-- 5. Tabel Transaksi (Header Pesanan)
-- ==========================================
CREATE TABLE IF NOT EXISTS orders (
    id_order INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    alamat_pengiriman TEXT NOT NULL,
    metode_pembayaran VARCHAR(50) NOT NULL,
    total_keseluruhan DECIMAL(15,2) NOT NULL,
    status ENUM('menunggu_pembayaran', 'diproses', 'selesai') DEFAULT 'menunggu_pembayaran',
    tanggal_order TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE
);


-- ==========================================
-- 6. Tabel Detail Barang dalam 1 Pesanan
-- ==========================================
CREATE TABLE IF NOT EXISTS order_items (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    id_order INT NOT NULL,
    id_barang INT NOT NULL,
    harga_satuan DECIMAL(15,2) NOT NULL,
    jumlah INT NOT NULL,
    subtotal DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (id_order) REFERENCES orders(id_order) ON DELETE CASCADE,
    FOREIGN KEY (id_barang) REFERENCES tmbbrg(id_barang) ON DELETE CASCADE
);