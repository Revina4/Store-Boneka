<?php
session_start();
include "koneksi.php";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // 1. CEK ADMIN STATIS
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['user'] = 'admin';
        $_SESSION['role'] = 'admin';
        header("Location: index.php");
        exit;
    }

    // 2. CEK USER BIASA DI DATABASE
    $pass_md5 = md5($password);
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$pass_md5'");
    
    if (mysqli_num_rows($query) == 1) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['user'] = $data['username'];
        $_SESSION['user_id'] = $data['id']; // <--- Simpan ID User untuk Query Pesanan
        $_SESSION['role'] = $data['role'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Boneka Store</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }
        body { background: #2b4a8a; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-card { background: #fff; width: 100%; max-width: 400px; padding: 40px 35px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); text-align: center; }
        .login-card img { width: 70px; height: 70px; object-fit: contain; margin-bottom: 10px; border-radius: 50%; }
        .login-card h1 { color: #1976d2; font-size: 28px; margin-bottom: 5px; font-weight: 700; }
        .login-card p.subtitle { color: #666; font-size: 14px; margin-bottom: 20px; }
        .form-group { text-align: left; margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; font-size: 14px; color: #333; margin-bottom: 8px; }
        .form-group input { width: 100%; padding: 12px 15px; background: #f1f5f9; border: 1px solid transparent; border-radius: 10px; font-size: 15px; transition: 0.3s; }
        .form-group input:focus { outline: none; background: #fff; border: 1px solid #1976d2; box-shadow: 0 0 0 3px rgba(25,118,210,0.1); }
        .btn-login { width: 100%; padding: 14px; background: #1976d2; color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 700; cursor: pointer; transition: 0.3s; }
        .btn-login:hover { background: #1565c0; }
        .error { color: #d32f2f; background: #ffebee; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; }
        .register-link { margin-top: 20px; font-size: 14px; color: #666; }
        .register-link a { color: #1976d2; text-decoration: none; font-weight: 600; }
        .register-link a:hover { text-decoration: underline; }
        .footer-copyright { margin-top: 20px; font-size: 12px; color: rgba(255,255,255,0.7); text-align: center; }
    </style>
</head>
<body>
    <div style="width:100%; display:flex; flex-direction:column; align-items:center;">
        <div class="login-card">
            <img src="uploads/logo2.jpg" alt="Logo Boneka Store">
            <h1>Boneka Store</h1>
            <p class="subtitle">Masuk untuk berbelanja boneka lucu</p>

            <?php if (isset($error)): ?>
                <div class="error"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Nama Pengguna</label>
                    <input type="text" name="username" placeholder="Masukkan username" required>
                </div>
                <div class="form-group">
                    <label>Kata Sandi</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" name="login" class="btn-login">MASUK BONEKA STORE</button>
            </form>

            <div class="register-link">
                Belum punya akun? <a href="daftar.php">Daftar Sekarang!</a>
            </div>
        </div>
        <div class="footer-copyright">
            &copy; 2026 Boneka Store - Teman Bermain Anak
        </div>
    </div>
</body>
</html>