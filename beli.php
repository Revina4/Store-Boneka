<?php
session_start();
include "koneksi.php";

// Proteksi: Hanya user yang sudah login yang bisa beli
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : 0;
$query = mysqli_query($koneksi, "SELECT * FROM tmbbrg WHERE id_barang='$id'");
$data = mysqli_fetch_assoc($query);

// Jika ID tidak ditemukan, lempar kembali ke halaman utama
if (!$data) {
    header("Location: index.php");
    exit;
}

// PROSES SIMPAN PESANAN KE DATABASE
$id_user = $_SESSION['user_id'];
$id_barang = $data['id_barang'];
$harga = $data['harga'];

// Simpan ke tabel pesanan (Admin dibuatkan di SQL sebelumnya)
mysqli_query($koneksi, "INSERT INTO pesanan (id_user, id_barang, total_harga) VALUES ('$id_user', '$id_barang', '$harga')");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Beli Boneka</title>
    <style>
        body { background: linear-gradient(135deg, #1a0a2e, #2d1b4e); color: #f0e6ff; text-align: center; padding: 50px; font-family: Arial; }
        .box { background: #2c1a4d; padding: 30px; display: inline-block; border-radius: 15px; border: 1px solid #9b59b6; box-shadow: 0 0 20px rgba(155, 89, 182, 0.3); }
        img { width: 250px; border-radius: 10px; border: 2px solid #d7bde2; }
        h3 { color: #d7bde2; }
        p { color: #d2b4de; }
        .success-msg { color: #2ecc71; font-weight: bold; }
        button { background: #9b59b6; color: white; border: none; padding: 10px 25px; border-radius: 25px; cursor: pointer; font-size: 16px; transition: 0.3s; margin-top: 15px; }
        button:hover { background: #d7bde2; color: #1a0a2e; }
        a { text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <img src="uploads/<?= htmlspecialchars($data['foto']); ?>"><br>
        <h3><?= htmlspecialchars($data['nama_barang']); ?></h3>
        <p>Harga: Rp <?= number_format($data['harga'],0,',','.'); ?></p>
        <p class="success-msg">✅ Pesanan berhasil dibuat! Silakan cek menu Profil untuk status.</p>
        <p>Terima kasih telah memesan. Silakan hubungi admin untuk pembayaran.</p>
        <a href="stok_barang.php"><button>Kembali Belanja</button></a>
    </div>
</body>
</html>