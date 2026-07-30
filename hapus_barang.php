<?php
session_start();
include "koneksi.php";

// Proteksi: Hanya Admin
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM tmbbrg WHERE id_barang='$id'");
echo "<script>alert('Data boneka berhasil dihapus!'); window.location='stok_barang.php';</script>";
exit;
?>