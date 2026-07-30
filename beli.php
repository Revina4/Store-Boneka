<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : 0;
$query = mysqli_query($koneksi, "SELECT * FROM tmbbrg WHERE id_barang='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) { header("Location: index.php"); exit; }

// SIMPAN KE SESSION KERANJANG (Untuk di-checkout)
$_SESSION['cart'][$id] = ['id' => $id, 'qty' => 1]; 

// LANGSUNG LEMPAR KE HALAMAN CHECKOUT
header("Location: checkout.php");
exit;
?>
