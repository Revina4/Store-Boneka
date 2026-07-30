<?php
session_start();
include "koneksi.php";

$keyword = isset($_GET['q']) ? mysqli_real_escape_string($koneksi, $_GET['q']) : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hasil Pencarian - Boneka Store</title>
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
        
        h2 { text-align: center; color: #d7bde2; text-shadow: 0 0 10px #9b59b6; margin-top: 20px; }
        .card-container { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .card { background: #2c1a4d; border: 1px solid #9b59b6; border-radius: 15px; width: 300px; padding: 15px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.5); }
        .card img { width: 100%; height: 200px; object-fit: cover; border-radius: 10px; border: 2px solid #d7bde2; }
        .card h3 { color: #d7bde2; }
        button { margin-top: 10px; padding: 8px 20px; border: none; background: #9b59b6; color: white; border-radius: 25px; cursor: pointer; transition: 0.3s; }
        button:hover { background: #d7bde2; color: #1a0a2e; }
        .no-result { text-align: center; color: #e74c3c; font-size: 18px; background: #2c1a4d; padding: 20px; border-radius: 10px; border: 1px solid #9b59b6; }
        .back-btn { display: block; text-align: center; margin: 30px 0; }
        .back-btn a { color: #d7bde2; text-decoration: none; border: 1px solid #9b59b6; padding: 10px 25px; border-radius: 25px; transition: 0.3s; }
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
</table>

<h2>🔍 Hasil Pencarian untuk: "<?= htmlspecialchars($keyword); ?>"</h2>

<div class="card-container">
    <?php
    if (!empty($keyword)) {
        $query = "SELECT * FROM tmbbrg WHERE nama_barang LIKE '%$keyword%' OR seri LIKE '%$keyword%' OR jenis LIKE '%$keyword%'";
        $result = mysqli_query($koneksi, $query);
        
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
                    <a href="beli.php?id=<?= urlencode($row['id_barang']); ?>">
                        <button>BELI SEKARANG</button>
                    </a>
                </div>
    <?php
            }
        } else {
            echo '<div class="no-result">⚠️ Tidak ada boneka yang ditemukan untuk kata kunci tersebut.</div>';
        }
    } else {
        echo '<div class="no-result">⚠️ Silakan masukkan kata kunci pencarian.</div>';
    }
    ?>
</div>

<div class="back-btn">
    <a href="index.php">Kembali ke Home</a>
</div>

</body>
</html>