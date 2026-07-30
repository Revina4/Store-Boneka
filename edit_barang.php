<?php
session_start();
include "koneksi.php";

// Proteksi: Hanya Admin yang boleh membuka halaman ini!
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$id = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : 0;
$query = mysqli_query($koneksi, "SELECT * FROM tmbbrg WHERE id_barang='$id'");
$data = mysqli_fetch_assoc($query);
if (!$data) { header("Location: stok_barang.php"); exit; }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Boneka - Admin</title>
    <style>
        body { background: linear-gradient(135deg, #1a0a2e, #2d1b4e); color: #f0e6ff; font-family: Arial; padding: 20px; }
        form { width: 50%; margin: auto; background: #2c1a4d; padding: 30px; border-radius: 15px; border: 1px solid #9b59b6; }
        h2 { text-align:center; color: #d7bde2; }
        input, textarea { width: 100%; padding: 10px; margin: 8px 0 20px; background: #1f0f3a; color: #f0e6ff; border: 1px solid #9b59b6; border-radius: 8px; box-sizing: border-box; }
        button { background: #f1c40f; color: black; padding: 12px 30px; border: none; border-radius: 25px; cursor: pointer; font-size: 16px; }
        button:hover { background: #d7bde2; }
        a { color: #d7bde2; text-decoration: none; border: 1px solid #9b59b6; padding: 8px 20px; border-radius: 25px; display: inline-block; margin-top: 15px; }
    </style>
</head>
<body>
    <h2>✏️ Edit Boneka</h2>
    <form action="proses_edit.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_barang" value="<?= $data['id_barang']; ?>">
        Seri: <input type="text" name="seri" value="<?= htmlspecialchars($data['seri']); ?>" required><br>
        Nama Boneka: <input type="text" name="nama_barang" value="<?= htmlspecialchars($data['nama_barang']); ?>" required><br>
        Jenis: <input type="text" name="jenis" value="<?= htmlspecialchars($data['jenis']); ?>"><br>
        Harga: <input type="number" name="sharga" value="<?= $data['harga']; ?>" required><br>
        Deskripsi: <textarea name="deskripsi" rows="4"><?= htmlspecialchars($data['deskripsi']); ?></textarea><br>
        <small>Foto saat ini:</small> <img src="uploads/<?= $data['foto']; ?>" width="100" style="border-radius:8px; display:block; margin:10px 0;"><br>
        Ganti Foto (Kosongkan jika tidak ingin ganti): <input type="file" name="foto" accept="image/*"><br>
        <button type="submit">UPDATE DATA</button>
        <a href="stok_barang.php">Batal</a>
    </form>
</body>
</html>