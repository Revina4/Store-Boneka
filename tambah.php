<?php
session_start();
include "koneksi.php";

// Jika bukan admin, tidak bisa akses halaman tambah
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Boneka Baru - Admin</title>
    <style>
        body { background: linear-gradient(135deg, #1a0a2e, #2d1b4e); color: #f0e6ff; font-family: Arial; padding: 20px; }
        form { width: 50%; margin: auto; background: #2c1a4d; padding: 30px; border-radius: 15px; border: 1px solid #9b59b6; box-shadow: 0 0 20px rgba(155, 89, 182, 0.2); }
        h2 { text-align:center; color: #d7bde2; text-shadow: 0 0 10px #9b59b6; }
        input, textarea, select { width: 100%; padding: 10px; margin: 8px 0 20px; background: #1f0f3a; color: #f0e6ff; border: 1px solid #9b59b6; border-radius: 8px; box-sizing: border-box; }
        input:focus, textarea:focus { border-color: #d7bde2; outline: none; }
        button { background: #9b59b6; color: white; padding: 12px 30px; border: none; border-radius: 25px; cursor: pointer; font-size: 16px; display: block; margin: 0 auto; transition: 0.3s; }
        button:hover { background: #d7bde2; color: #1a0a2e; }
    </style>
</head>
<body>
    <h2>🧸 Tambah Boneka Baru</h2>
    <form action="proses_tambah.php" method="post" enctype="multipart/form-data">
        Seri: <input type="text" name="seri" required placeholder="Contoh: DINO-001"><br>
        Nama Boneka: <input type="text" name="nama_barang" required placeholder="Contoh: Boneka Dinosaurus"><br>
        Jenis: <input type="text" name="jenis" placeholder="Contoh: Hewan / Kartun"><br>
        Harga: <input type="number" name="sharga" required placeholder="Masukkan angka saja"><br>
        Deskripsi: <textarea name="deskripsi" rows="4" placeholder="Ceritakan tentang boneka ini..."></textarea><br>
        Foto: <input type="file" name="foto" accept="image/*" style="border: none;"><br>
        <button type="submit">SIMPAN</button>
    </form>
</body>
</html>