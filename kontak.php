<?php
session_start();
include "koneksi.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kontak - Boneka Store</title>
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
        .contact-container { max-width: 900px; margin: 40px auto; display: flex; gap: 40px; flex-wrap: wrap; }
        .contact-info { flex: 1; min-width: 250px; }
        .contact-form { flex: 2; min-width: 300px; }
        .info-item { margin-bottom: 20px; padding: 15px; background: #2c1a4d; border-radius: 10px; border-left: 4px solid #9b59b6; }
        .info-item h3 { color: #d7bde2; margin: 0 0 5px 0; }
        .info-item p { margin: 5px 0; color: #d2b4de; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; color: #d7bde2; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; background: #1f0f3a; color: #f0e6ff; border: 1px solid #9b59b6; border-radius: 8px; box-sizing: border-box; }
        .form-group input:focus, .form-group textarea:focus { border-color: #d7bde2; outline: none; }
        .btn-kirim { background: #9b59b6; color: white; padding: 12px 30px; border: none; border-radius: 25px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .btn-kirim:hover { background: #d7bde2; color: #1a0a2e; }
        .social-icons a { color: #d7bde2; text-decoration: none; margin-right: 15px; font-size: 20px; }
        .social-icons a:hover { color: #9b59b6; }
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
                    <?php endif; ?>
                    
                    <a href="logout.php">Logout</a>
                    <!-- LINK KONTAK SAYA BUANG DI SINI KARENA LAGI DI HALAMAN INI -->
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
            <div class="contact-container">
                <!-- Informasi Kontak -->
                <div class="contact-info">
                    <h2 style="color: #d7bde2;">📞 Hubungi Kami</h2>
                    <p style="color: #d2b4de;">Kami siap membantu Anda 24/7</p>
                    
                    <div class="info-item">
                        <h3>📍 Alamat Toko</h3>
                        <p>Jl. Boneka Lucu No. 123<br>Jakarta Selatan, Indonesia</p>
                    </div>
                    <div class="info-item">
                        <h3>📞 Telepon</h3>
                        <p>+62 857 6735 6534</p>
                        <p>+62 812 3456 7890</p>
                    </div>
                    <div class="info-item">
                        <h3>✉️ Email</h3>
                        <p>boneka@lucu.com</p>
                        <p>support@bonekastore.com</p>
                    </div>
                    <div class="info-item">
                        <h3>🕐 Jam Operasional</h3>
                        <p>Senin - Jumat: 08:00 - 21:00</p>
                        <p>Sabtu - Minggu: 09:00 - 18:00</p>
                    </div>
                </div>
                
                <!-- Form Kirim Pesan -->
                <div class="contact-form">
                    <h2 style="color: #d7bde2;">💬 Kirim Pesan</h2>
                    <form action="proses_kontak.php" method="post">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
                        </div>
                        <div class="form-group">
                            <label for="subjek">Subjek</label>
                            <input type="text" id="subjek" name="subjek" placeholder="Subjek pesan" required>
                        </div>
                        <div class="form-group">
                            <label for="pesan">Pesan</label>
                            <textarea id="pesan" name="pesan" rows="5" placeholder="Tulis pesan Anda..." required></textarea>
                        </div>
                        <button type="submit" class="btn-kirim">📨 Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </td>
    </tr>
</table>

</body>
</html>