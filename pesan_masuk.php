<?php
session_start();
include "koneksi.php";

// Proteksi: Hanya Admin
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

// Ambil semua pesan dari tabel kontak
$result = mysqli_query($koneksi, "SELECT * FROM kontak ORDER BY tanggal_kirim DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pesan Masuk - Admin Boneka Store</title>
    <style>
        body { background: linear-gradient(135deg, #1a0a2e, #2d1b4e); color: #f0e6ff; font-family: Arial; margin: 0; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        td { border: 1px solid #4a2b6b; vertical-align: top; }
        .baris1 td { height: 8%; background: #1f0f3a; padding: 10px; }
        .baris2 td { height: 12%; background: #2c1a4d; padding: 10px; }
        .cell-flex { display: flex; justify-content: space-between; align-items: center; padding: 0 10px; }
        .menu a { color: #d7bde2; text-decoration: none; margin: 0 10px; font-weight: bold; transition: 0.3s; }
        .menu a:hover { color: #9b59b6; }
        .search input { padding: 8px; border-radius: 20px 0 0 20px; border: 1px solid #9b59b6; background: #1f0f3a; color: white; outline: none; }
        .search input[type="submit"] { border-radius: 0 20px 20px 0; background: #9b59b6; border: 1px solid #9b59b6; color: white; cursor: pointer; padding: 8px 15px; }
        .logo-text { font-weight: bold; font-size: 18px; color: #d7bde2; }

        h2 { text-align:center; color: #d7bde2; }
        .tabel-pesan { width: 100%; background: #2c1a4d; border-radius: 15px; padding: 20px; border: 1px solid #9b59b6; }
        .tabel-pesan th { color: #d7bde2; text-align: left; padding: 10px; border-bottom: 1px solid #4a2b6b; }
        .tabel-pesan td { padding: 10px; border-bottom: 1px solid #4a2b6b; }
        .btn-balas { background: #f1c40f; color: black; padding: 5px 15px; border-radius: 20px; border: none; cursor: pointer; text-decoration: none; }
        .no-data { text-align: center; padding: 30px; color: #d2b4de; }
    </style>
</head>
<body>
<table>
    <tr class="baris1">
        <td><div class="cell-flex"><span>🧸 Boneka Store - Admin</span><span>📞 +62 857 6735 6534</span></div></td>
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
                    <a href="stok_barang.php">Kelola Stok</a> |
                    <a href="tambah.php">Tambah Boneka</a> |
                    <!-- LINK PESAN MASUK DIHAPUS DISINI (karena sedang ada di halaman ini) -->
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </td>
    </tr>
</table>

<h2>📨 Daftar Pesan Masuk dari Pelanggan</h2>
<div class="tabel-pesan">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <table width="100%">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Subjek</th>
                <th>Pesan</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
            <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= htmlspecialchars($row['nama']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['subjek']); ?></td>
                <td><?= htmlspecialchars(substr($row['pesan'], 0, 50)); ?>...</td>
                <td><?= date('d M Y, H:i', strtotime($row['tanggal_kirim'])); ?></td>
                <td>
                    <a href="mailto:<?= htmlspecialchars($row['email']); ?>?subject=Balasan: <?= htmlspecialchars($row['subjek']); ?>" class="btn-balas">📧 Balas</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <div class="no-data">Belum ada pesan masuk dari pelanggan.</div>
    <?php endif; ?>
</div>
</body>
</html>