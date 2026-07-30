<?php
session_start();
include "koneksi.php";

// Jika bukan admin, lempar balik
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$seri        = $_POST['seri'];
$nama_barang = $_POST['nama_barang'];
$jenis       = $_POST['jenis'];
$sharga      = $_POST['sharga'];
$deskripsi   = $_POST['deskripsi'];

// Upload foto
$foto = $_FILES['foto']['name'];
$tmp  = $_FILES['foto']['tmp_name'];
$folder = "uploads/";

// Buat folder uploads jika belum ada
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

if ($foto != "") {
    $target_file = $folder . basename($foto);
    if (move_uploaded_file($tmp, $target_file)) {
        $foto_db = $foto;
    } else {
        echo "<script>alert('Upload foto gagal'); window.location='tambah.php';</script>";
        exit;
    }
} else {
    $foto_db = "default.jpg"; 
}

// Simpan ke database menggunakan mysqli
$sql = "INSERT INTO tmbbrg (seri, nama_barang, jenis, harga, deskripsi, foto) 
        VALUES ('$seri', '$nama_barang', '$jenis', '$sharga', '$deskripsi', '$foto_db')";

$query = mysqli_query($koneksi, $sql);

if ($query) {
    echo "<script>alert('Data berhasil ditambahkan!'); window.location='stok_barang.php';</script>";
} else {
    echo "Gagal menyimpan data: " . mysqli_error($koneksi);
}
?>