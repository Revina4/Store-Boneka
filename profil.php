<?php
session_start();
include "koneksi.php";

// Jika belum login, lempar ke login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['user'];
$role = $_SESSION['role'] ?? 'user';

// Ambil data lengkap user dari database (kecuali admin statis)
if ($role == 'admin') {
    $nama_lengkap = "Administrator";
    $email = "admin@bonekastore.com";
    $created_at = date("d F Y");
} else {
    $id_user = $_SESSION['user_id'];
    $qUser = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id_user'");
    $dataUser = mysqli_fetch_assoc($qUser);
    $nama_lengkap = $dataUser['nama_lengkap'];
    $email = $dataUser['email'];
    $created_at = date("d F Y", strtotime($dataUser['created_at']));
}

// Ambil daftar pesanan user
$listPesanan = [];
if ($role != 'admin' && isset($_SESSION['user_id'])) {
    $id_user = $_SESSION['user_id'];
    $qPesanan = mysqli_query($koneksi, "
        SELECT p.*, b.nama_barang, b.foto 
        FROM pesanan p 
        JOIN tmbbrg b ON p.id_barang = b.id_barang 
        WHERE p.id_user = '$id_user' 
        ORDER BY p.tanggal_pesan DESC
    ");
    while ($row = mysqli_fetch_assoc($qPesanan)) {
        $listPesanan[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profil - Boneka Store</title>
    <style>
        html, body { margin: 0; padding: 0; height: 100%; background: linear-gradient(135deg, #1a0a2e, #2d1b4e); color: #f0e6ff; font-family: Arial; }
        table { width: 100%; height: 100%; border-collapse: collapse; }
        td { border: 1px solid #4a2b6b; vertical-align: top; }
        .baris1 td { height: 8%; background: #1f0f3a; }
        .baris2 td { height: 12%; background: #2c1a4d; }
        .baris3 td { height: 80%; padding: 20px; }
        .cell-flex { display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
        .menu a { color: #d7bde2; text-decoration: none; margin: 0 10px; font-weight: bold; transition: 0.3s; }
        .menu a:hover { color: #9b59b6; }
        .search input { padding: 8px; border-radius: 20px 0 0 20px; border: 1px solid #9b59b6; background: #1f0f3a; color: white; outline: none; }
        .search input[type="submit"] { border-radius: 0 20px 20px 0; background: #9b59b6; border: 1px solid #9b59b6; color: white; cursor: pointer; padding: 8px 15px; }
        .logo-text { font-weight: bold; font-size: 18px; color: #d7bde2; }
        
        .profile-box { max-width: 700px; margin: 20px auto; background: #2c1a4d; padding: 30px; border-radius: 20px; border: 1px solid #9b59b6; box-shadow: 0 0 20px rgba(155, 89, 182, 0.3); }
        .profile-header { text-align: center; border-bottom: 1px solid #4a2b6b; padding-bottom: 20px; margin-bottom: 20px; }
        .profile-box img { width: 120px; height: 120px; border-radius: 50%; border: 3px solid #9b59b6; object-fit: cover; }
        .profile-detail { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .detail-item { background: #1f0f3a; padding: 15px; border-radius: 10px; }
        .detail-item label { display: block; color: #9b59b6; font-size: 12px; margin-bottom: 3px; }
        .detail-item span { font-size: 16px; color: #d7bde2; }
        .admin-badge { color: #f1c40f; font-weight: bold; }
        
        /* Pesanan */
        .order-section { margin-top: 30px; border-top: 1px solid #4a2b6b; padding-top: 20px; }
        .order-card { display: flex; align-items: center; gap: 15px; background: #1f0f3a; padding: 15px; border-radius: 10px; margin-bottom: 10px; border-left: 4px solid #9b59b6; }
        .order-card img { width: 60px; height: 60px; border-radius: 8px; object-fit: cover; }
        .order-info { flex: 1; }
        .order-info h4 { margin: 0 0 5px; color: #d7bde2; }
        .order-info p { margin: 0; font-size: 13px; color: #d2b4de; }
        .empty-orders { text-align: center; color: #d2b4de; padding: 20px; background: #1f0f3a; border-radius: 10px; }
        .back-btn { text-align: center; margin-top: 20px; }
        .back-btn a { color: #d7bde2; text-decoration: none; border: 1px solid #9b59b6; padding: 8px 20px; border-radius: 25px; transition: 0.3s; }
        .back-btn a:hover { background: #9b59b6; color: white; }
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
                <div class="search">
                    <form action="cari.php" method="get">
                        <input type="text" name="q" placeholder="Cari boneka...">
                        <input type="submit" value="Cari">
                    </form>
                </div>
            </div>
        </td>
    </tr>

    <tr class="baris3">
        <td>
            <div class="profile-box">
                <!-- Header -->
                <div class="profile-header">
                    <img src="uploads/logo2.jpg" alt="Avatar">
                    <?php if ($role == 'admin'): ?>
                        <h2 style="color:#d7bde2; margin-top:10px;">👤 <?= htmlspecialchars($username); ?></h2>
                        <p class="admin-badge">⭐ Admin Boneka Store</p>
                    <?php endif; ?>
                </div>

                <!-- Detail Data Diri -->
                <div class="profile-detail">
                    <div class="detail-item">
                        <label>Nama Panggilan</label>
                        <span><?= htmlspecialchars($username); ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Nama Lengkap</label>
                        <span><?= htmlspecialchars($nama_lengkap); ?></span>
                    </div>
                    <div class="detail-item" style="grid-column: span 2;">
                        <label>Email</label>
                        <span><?= htmlspecialchars($email); ?></span>
                    </div>
                    <div class="detail-item" style="grid-column: span 2;">
                        <label>Bergabung Sejak</label>
                        <span><?= htmlspecialchars($created_at); ?></span>
                    </div>
                </div>

                <!-- Riwayat Pesanan -->
                <div class="order-section">
                    <h3 style="color: #d7bde2; margin-bottom: 15px;">📦 Pesanan Saya</h3>
                    
                    <?php if (empty($listPesanan)): ?>
                        <div class="empty-orders">Belum ada pesanan</div>
                    <?php else: ?>
                        <?php foreach ($listPesanan as $psn): ?>
                            <div class="order-card">
                                <img src="uploads/<?= htmlspecialchars($psn['foto']); ?>" alt="Gambar">
                                <div class="order-info">
                                    <h4><?= htmlspecialchars($psn['nama_barang']); ?></h4>
                                    <p><strong>Status:</strong> <span style="color: #f1c40f;"><?= htmlspecialchars($psn['status']); ?></span></p>
                                    <p>Total: Rp <?= number_format($psn['total_harga'], 0, ',', '.'); ?></p>
                                    <p style="font-size: 11px; color:#9b59b6;">Dipesan: <?= date('d M Y, H:i', strtotime($psn['tanggal_pesan'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="back-btn">
                    <a href="index.php">Kembali ke Home</a>
                </div>
            </div>
        </td>
    </tr>
</table>
</body>
</html>