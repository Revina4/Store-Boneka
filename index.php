<?php
session_start();
include "koneksi.php";

// Jika belum login, tendang ke login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Boneka Store - Toko Boneka Lucu</title>
    <style>
        html, body { margin: 0; padding: 0; height: 100%; background: linear-gradient(135deg, #1a0a2e, #2d1b4e); color: #f0e6ff; font-family: Arial, sans-serif; }
        table { width: 100%; height: 100%; border-collapse: collapse; }
        td { border: 1px solid #4a2b6b; vertical-align: top; }
        .baris1 td { height: 8%; background: #1f0f3a; }
        .baris2 td { height: 12%; background: #2c1a4d; }
        .baris3 td { height: 80%; padding: 20px; }
        .cell-flex { display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
        .menu a { color: #d7bde2; text-decoration: none; margin: 0 10px; font-weight: bold; transition: 0.3s; }
        .menu a:hover { color: #9b59b6; text-shadow: 0 0 10px #9b59b6; }
        .card-container { display: flex; flex-wrap: wrap; gap: 30px; justify-content: center; padding: 20px; }
        .card { background: #2c1a4d; border: 1px solid #9b59b6; border-radius: 15px; width: 300px; padding: 20px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.5); transition: transform 0.3s, box-shadow 0.3s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 0 20px rgba(155, 89, 182, 0.5); }
        .card img { width: 100%; height: 250px; object-fit: cover; border-radius: 10px; }
        .card h3 { color: #d7bde2; margin: 15px 0 5px; }
        .card h2 { color: #f1c40f; margin: 5px 0; }
        .card small { color: #bdc3c7; }
        button { margin-top: 15px; padding: 10px 25px; border: none; background: #9b59b6; color: white; border-radius: 25px; cursor: pointer; font-size: 16px; transition: 0.3s; }
        button:hover { background: #d7bde2; color: #1a0a2e; }
        .search input { padding: 8px; border-radius: 20px 0 0 20px; border: 1px solid #9b59b6; background: #1f0f3a; color: white; outline: none; }
        .search input[type="submit"] { border-radius: 0 20px 20px 0; background: #9b59b6; border: 1px solid #9b59b6; color: white; cursor: pointer; padding: 8px 15px; }
        .logo-text { font-weight: bold; font-size: 18px; color: #d7bde2; }
        .empty-msg { text-align: center; color: #d2b4de; padding: 30px; background: #2c1a4d; border-radius: 15px; border: 1px solid #9b59b6; }
    </style>
</head>
<body>

<table>
    <!-- BARIS 1 -->
    <tr class="baris1">
        <td>
            <div class="cell-flex">
                <span>🧸 Boneka Store - Teman Bermain Anak</span>
                <span>📞 +62 857 6735 6534 | ✉️ boneka@lucu.com</span>
            </div>
        </td>
    </tr>

    <!-- BARIS 2: Menu Dinamis -->
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

    <!-- BARIS 3: Produk -->
    <tr class="baris3">
        <td>
            <h2 style="text-align:center; color: #d7bde2; text-shadow: 0 0 10px #9b59b6;">Boneka Paling Laris</h2>
            <div class="card-container">
                <?php
                $result = mysqli_query($koneksi, "SELECT * FROM tmbbrg ORDER BY id_barang DESC LIMIT 6");
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
                            <h2>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></h2>
                            <p><?= htmlspecialchars(substr($row['deskripsi'], 0, 80)); ?>...</p>
                            <a href="beli.php?id=<?= urlencode($row['id_barang']); ?>">
                                <button>BELI</button>
                            </a>
                        </div>
                <?php
                    }
                } else {
                    echo '<div class="empty-msg">⚠️ Belum ada boneka di database.</div>';
                }
                ?>
            </div>
        </td>
    </tr>
</table>

</body>
</html>