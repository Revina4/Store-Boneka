<?php
$host = "localhost";
$user = "root";      // sesuaikan dengan user MySQL Anda
$pass = "";          // sesuaikan dengan password MySQL Anda
$db   = "db_penjualan";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal : " . mysqli_connect_error());
}
?>