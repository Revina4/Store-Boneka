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
        $_SESSION['user_id'] = $data['id'];
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Boneka Store</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Background Ungu Gradasi */
        body { 
            background: linear-gradient(135deg, #1a0a2e, #2d1b4e); 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0; 
        }
        
        /* Kartu Login Ungu Gelap */
        .login-card { 
            background: #2c1a4d; 
            width: 100%; 
            max-width: 400px; 
            padding: 40px 35px; 
            border-radius: 15px; 
            border: 1px solid #9b59b6; 
            box-shadow: 0 0 20px rgba(155, 89, 182, 0.3); 
            text-align: center; 
        }
        
        /* Logo & Judul */
        .login-card img { 
            width: 70px; 
            height: 70px; 
            object-fit: contain; 
            margin-bottom: 10px; 
            border-radius: 50%; 
            border: 2px solid #d7bde2;
        }
        .login-card h1 { 
            color: #d7bde2; 
            font-size: 28px; 
            margin-bottom: 5px; 
            font-weight: 700; 
        }
        .login-card p.subtitle { 
            color: #d2b4de; 
            font-size: 14px; 
            margin-bottom: 25px; 
        }
        
        /* Form Input */
        .form-group { 
            text-align: left; 
            margin-bottom: 20px; 
        }
        .form-group label { 
            display: block; 
            font-weight: 600; 
            font-size: 14px; 
            color: #d7bde2; 
            margin-bottom: 8px; 
        }
        .form-group input { 
            width: 100%; 
            padding: 12px 15px; 
            background: #1f0f3a; 
            border: 1px solid #9b59b6; 
            border-radius: 8px; 
            font-size: 15px; 
            color: #f0e6ff; 
            transition: 0.3s; 
            outline: none;
        }
        .form-group input::placeholder { color: #7a5a8a; }
        .form-group input:focus { 
            border-color: #d7bde2; 
            box-shadow: 0 0 0 3px rgba(215, 189, 226, 0.1); 
        }
        
        /* Tombol Login Ungu */
        .btn-login { 
            width: 100%; 
            padding: 14px; 
            background: #9b59b6; 
            color: white; 
            border: none; 
            border-radius: 25px; 
            font-size: 16px; 
            font-weight: 700; 
            cursor: pointer; 
            transition: 0.3s; 
            margin-top: 10px;
        }
        .btn-login:hover { 
            background: #d7bde2; 
            color: #1a0a2e; 
        }
        
        /* Pesan Error */
        .error { 
            color: #e74c3c; 
            background: #1f0f3a; 
            padding: 10px; 
            border-radius: 8px; 
            margin-bottom: 15px; 
            font-size: 14px; 
            border: 1px solid #e74c3c;
        }
        
        /* Link Daftar */
        .register-link { 
            margin-top: 20px; 
            font-size: 14px; 
            color: #d2b4de; 
        }
        .register-link a { 
            color: #d7bde2; 
            text-decoration: none; 
            font-weight: 600; 
        }
        .register-link a:hover { 
            color: #9b59b6; 
            text-decoration: underline; 
        }
        
        /* Footer Copyright */
        .footer-copyright { 
            margin-top: 20px; 
            font-size: 12px; 
            color: rgba(255, 255, 255, 0.4); 
            text-align: center; 
        }
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
