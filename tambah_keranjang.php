<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : 0;

// Jika item sudah ada di keranjang, tambah jumlah (+1)
if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += 1;
} else {
    // Jika belum ada, masukkan dengan jumlah 1
    $_SESSION['cart'][$id] = ['id' => $id, 'qty' => 1];
}

header("Location: keranjang.php");
exit;
?>