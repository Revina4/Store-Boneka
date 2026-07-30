<?php
session_start();
include "koneksi.php";

// Proteksi login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Hitung jumlah item di keranjang (untuk badge merah)
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Ambil data dari database
$result = mysqli_query($koneksi, "SELECT * FROM tmbbrg");
if (!$result) { die("Query error: " . mysqli_error($koneksi)); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Stok Boneka - Boneka Store</title>
    <style>
        body { 
            background: linear-gradient(135deg, #1a0a2e, #2d1b4e); 
            color: #f0e6ff; 
            font-family: Arial; 
            margin: 0; 
            padding: 20px; 
        }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        td { border: 1px solid #4a2b6b; vertical-align: top; }
        .baris1 td { height: 8%; background: #1f0f3a; padding: 10px; }
        .baris2 td { height: 12%; background: #2c1a4d; padding: 10px; }
        .cell-flex { display: flex; justify-content: space-between; align-items: center; padding: 0 10px; }
        .menu a { color: #d7bde2; text-decoration: none; margin: 0 10px; font-weight: bold; transition: 0.3s; }
        .menu a:hover { color: #9b59b6; text-shadow: 0 0 10px #9b59b6; }
        .logo-text { font-weight: bold; font-size: 18px; color: #d7bde2; }
        
        /* Ikon Keranjang */
        .search { display: flex; align-items: center; gap: 10px; }
        .search input { padding: 8px; border-radius: 20px 0 0 20px; border: 1px solid #9b59b6; background: #1f0f3a; color: white; outline: none; }
        .search input[type="submit"] { border-radius: 0 20px 20px 0; background: #9b59b6; border: 1px solid #9b59b6; color: white; cursor: pointer; padding: 8px 15px; }
        .cart-icon-container { position: relative; display: inline-flex; align-items: center; margin-right: 10px; }
        .cart-icon-container a { color: #d7bde2; text-decoration: none; font-size: 22px; transition: 0.3s; }
        .cart-icon-container a:hover { color: #9b59b6; }
        .cart-badge { 
            position: absolute; top: -5px; right: -8px; 
            background: #e74c3c; color: white; 
            font-size: 10px; font-weight: bold; 
            border-radius: 50%; width: 18px; height: 18px; 
            display: flex; justify-content: center; align-items: center;
        }
        
        h2 { text-align: center; color: #d7bde2; text-shadow: 0 0 15px #9b59b6; margin-top: 20px; }
        .card-container { display: flex; flex-wrap: wrap; gap: 30px; justify-content: center; }
        .card {
            background: #2c1a4d; 
            border: 1px solid #9b59b6; 
            border-radius: 15px;
            width: 300px; 
            padding: 20px; 
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
        }
        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 0 25px rgba(155, 89, 182, 0.5);
        }
        .card img { 
            width: 100%; 
            height: 200px; 
            object-fit: cover; 
            border-radius: 10px; 
            border: 2px solid #d7bde2;
        }
        .card h3 { color: #d7bde2; }
        button { padding: 10px 20px; border: none; border-radius: 25px; cursor: pointer; font-size: 14px; transition: 0.3s; width: 100%; }
        .btn-beli { background: #f1c40f; color: black; }
        .btn-beli:hover { background: #d4ac0d; }
        .btn-cart { background: #9b59b6; color: white; }
        .btn-cart:hover { background: #d7bde2; color: #1a0a2e; }
        .btn-group { display: flex; flex-direction: column; gap: 10px; margin-top: 15px; }
        .btn-edit { background: #f1c40f; color: black !important; width: auto; padding: 5px 15px; }
        .btn-hapus { background: #e74c3c; color: white !important; width: auto; padding: 5px 15px; }
        .admin-tools { display: flex; justify-content: center; gap: 10px; margin-top: 10px; }
        .no-data { text-align: center; color: #e74c3c; font-size: 18px; background: #2c1a4d; padding: 20px; border-radius: 10px; border: 1px solid #9b59b6; }
    </style>
</head>
<body>

<table>
    <tr class="baris1">
        <td>
            <div class="cell-flex">
                <span>🧸 Boneka Store - Teman Bermain Anak</span>
                <span>📞 +62 857 6735 6534 | ✉️ boneka@lucu.com</span>
            </div>
        </td>
    </tr>

    <tr class="baris2">
        <td>
            <div class="cell-flex">
                <div style="display:flex; align-items:center; gap:10px;">
                    <img src="uploads/logo2.jpg" alt="Logo" width="60" style="border-radius:50%;">
                    <span class="logo-text">Boneka Store</span>
                </div>
                
                <div class="menu">
                    <a href="index.php">Home</a> |
                    <a href="profil.php">Profil</a> |
                    
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                        <a href="stok_barang.php">Kelola Stok</a> |
                        <a href="tambah.php">Tambah Boneka</a> |
                        <a href="pesan_masuk.php">Pesan Masuk</a> |
                    <?php else: ?>
                        <a href="stok_barang.php">Lihat Produk</a> |
                        <a href="kontak.php">Kontak</a> |
                    <?php endif; ?>
                    
                    <a href="logout.php">Logout</a>
                </div>

                <!-- Search & Keranjang -->
                <div class="search">
                    <div class="cart-icon-container">
                        <a href="keranjang.php">🛒</a>
                        <span class="cart-badge"><?= $cart_count; ?></span>
                    </div>
                    <form action="cari.php" method="get">
                        <input type="text" name="q" placeholder="Cari boneka...">
                        <input type="submit" value="Cari">
                    </form>
                </div>
            </div>
        </td>
    </tr>
</table>

<h2>🐻 Koleksi Boneka Kami</h2>
<div class="card-container">
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $fotoPath = "uploads/" . $row['foto'];
            if (!file_exists($fotoPath) || empty($row['foto'])) {
                $fotoPath = "uploads/default.jpg";
            }
    ?>
            <div class="card">
                <img src="<?= htmlspecialchars($fotoPath); ?>" alt="<?= htmlspecialchars($row['nama_barang']); ?>">
                <h3><?= htmlspecialchars($row['nama_barang']); ?></h3>
                <p><strong>Seri:</strong> <?= htmlspecialchars($row['seri']); ?></p>
                <p><strong>Harga:</strong> Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
                <p><?= htmlspecialchars(substr($row['deskripsi'], 0, 100)); ?>...</p>

                <!-- Tombol Beli & Keranjang -->
                <div class="btn-group">
                    <a href="beli.php?id=<?= urlencode($row['id_barang']); ?>">
                        <button class="btn-beli">⚡ Beli Sekarang</button>
                    </a>
                    <a href="tambah_keranjang.php?id=<?= urlencode($row['id_barang']); ?>">
                        <button class="btn-cart">🛒 Masukkan Keranjang</button>
                    </a>
                </div>

                <!-- TOMBOL EDIT & HAPUS HANYA UNTUK ADMIN -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <div class="admin-tools">
                        <a href="edit_barang.php?id=<?= urlencode($row['id_barang']); ?>">
                            <button class="btn-edit">✏️ Edit</button>
                        </a>
                        <a href="hapus_barang.php?id=<?= urlencode($row['id_barang']); ?>" onclick="return confirm('Yakin ingin menghapus boneka ini?')">
                            <button class="btn-hapus">🗑️ Hapus</button>
                        </a>
                    </div>
                <?php endif; ?>

            </div>
    <?php
        }
    } else {
        echo '<div class="no-data">⚠️ Belum ada boneka. Silakan tambah boneka terlebih dahulu.</div>';
    }
    ?>
</div>

</body>
</html>
