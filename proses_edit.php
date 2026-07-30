<?php
session_start();
include "koneksi.php";

// Proteksi: Hanya Admin
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$id = $_POST['id_barang'];
$seri = $_POST['seri'];
$nama = $_POST['nama_barang'];
$jenis = $_POST['jenis'];
$harga = $_POST['sharga'];
$deskripsi = $_POST['deskripsi'];

// Cek apakah user mengganti foto
if ($_FILES['foto']['name'] != "") {
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $folder = "uploads/";
    move_uploaded_file($tmp, $folder . basename($foto));
    $sql = "UPDATE tmbbrg SET seri='$seri', nama_barang='$nama', jenis='$jenis', harga='$harga', deskripsi='$deskripsi', foto='$foto' WHERE id_barang='$id'";
} else {
    // Jika tidak ganti foto
    $sql = "UPDATE tmbbrg SET seri='$seri', nama_barang='$nama', jenis='$jenis', harga='$harga', deskripsi='$deskripsi' WHERE id_barang='$id'";
}

mysqli_query($koneksi, $sql);
echo "<script>alert('Data boneka berhasil diperbarui!'); window.location='stok_barang.php';</script>";
?>