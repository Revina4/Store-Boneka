<?php
session_start();
include "koneksi.php";

if (isset($_POST['daftar'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = md5($_POST['password']);

    // Cek username
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    if (!$cek) {
        $error = "Terjadi kesalahan database.";
    } elseif (mysqli_num_rows($cek) > 0) {
        $error = "Username '$username' sudah dipakai!";
    } else {
        // Simpan dengan data lengkap
        $simpan = mysqli_query($koneksi, "INSERT INTO users (username, nama_lengkap, email, password, role) VALUES ('$username', '$nama_lengkap', '$email', '$password', 'user')");
        if ($simpan) {
            echo "<script>alert('Akun berhasil dibuat! Silakan login.'); window.location='login.php';</script>";
            exit;
        } else {
            $error = "Gagal menyimpan akun: " . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - Boneka Store</title>
    <style>
        html, body { margin: 0; padding: 0; height: 100%; background: linear-gradient(135deg, #1a0a2e, #2d1b4e); color: #f0e6ff; font-family: Arial; }
        table { width: 100%; height: 100%; border-collapse: collapse; }
        td { border: 1px solid #4a2b6b; vertical-align: top; }
        .baris1 td { height: 8%; background: #1f0f3a; }
        .baris2 td { height: 12%; background: #2c1a4d; }
        .baris3 td { height: 80%; }
        .cell-flex { display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
        .menu a { color: #d7bde2; text-decoration: none; margin: 0 10px; font-weight: bold; transition: 0.3s; }
        .menu a:hover { color: #9b59b6; }
        .search input { padding: 8px; border-radius: 20px 0 0 20px; border: 1px solid #9b59b6; background: #1f0f3a; color: white; outline: none; }
        .search input[type="submit"] { border-radius: 0 20px 20px 0; background: #9b59b6; border: 1px solid #9b59b6; color: white; cursor: pointer; padding: 8px 15px; }
        .logo-text { font-weight: bold; font-size: 18px; color: #d7bde2; }
        
        .register-card { width: 50%; margin: 40px auto; background: #2c1a4d; padding: 30px; border-radius: 15px; border: 1px solid #9b59b6; box-shadow: 0 0 20px rgba(155,89,182,0.2); }
        .register-card h2 { text-align:center; color: #d7bde2; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; color: #d7bde2; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px; background: #1f0f3a; color: #f0e6ff; border: 1px solid #9b59b6; border-radius: 8px; box-sizing: border-box; }
        .form-group input:focus { border-color: #d7bde2; outline: none; }
        .btn-daftar { background: #9b59b6; color: white; padding: 12px 30px; border: none; border-radius: 25px; cursor: pointer; font-weight: bold; transition: 0.3s; display: block; margin: 20px auto 0; }
        .btn-daftar:hover { background: #d7bde2; color: #1a0a2e; }
        .error-box { color: #e74c3c; background: #2c1a4d; padding: 10px; border-radius: 8px; border: 1px solid #e74c3c; margin-bottom: 15px; text-align: center; }
        .login-link { text-align: center; margin-top: 15px; font-size: 14px; }
        .login-link a { color: #9b59b6; text-decoration: none; font-weight: bold; }
        .login-link a:hover { text-decoration: underline; }
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
                    <a href="stok_barang.php">Stok Boneka</a> |
                    <a href="tambah.php">Tambah Boneka</a> |
                    <a href="logout.php">Logout</a> |
                    <a href="kontak.php">Kontak</a>
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
            <div class="register-card">
                <h2>🧸 Buat Akun Baru</h2>
                
                <?php if (isset($error)): ?>
                    <div class="error-box"><?= $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Nama Panggilan (Username)</label>
                        <input type="text" name="username" placeholder="Contoh: JokoBoneka" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" placeholder="Masukkan nama asli Anda" required>
                    </div>
                    <div class="form-group">
                        <label>Email Aktif</label>
                        <input type="email" name="email" placeholder="contoh@email.com" required>
                    </div>
                    <div class="form-group">
                        <label>Kata Sandi</label>
                        <input type="password" name="password" placeholder="Buat kata sandi" required>
                    </div>
                    <button type="submit" name="daftar" class="btn-daftar">DAFTAR SEKARANG</button>
                </form>
                <div class="login-link">
                    Sudah punya akun? <a href="login.php">Masuk di sini</a>
                </div>
            </div>
        </td>
    </tr>
</table>

</body>
</html>