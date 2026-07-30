<?php
session_start();
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $subjek = mysqli_real_escape_string($koneksi, $_POST['subjek']);
    $pesan = mysqli_real_escape_string($koneksi, $_POST['pesan']);
    
    // Simpan ke tabel kontak di database
    mysqli_query($koneksi, "INSERT INTO kontak (nama, email, subjek, pesan) VALUES ('$nama', '$email', '$subjek', '$pesan')");
    
    // Tampilkan alert berhasil dan redirect ke halaman kontak
    echo "<script>alert('Pesan Anda berhasil dikirim ke Admin!'); window.location='kontak.php';</script>";
    exit;
} else {
    header("Location: kontak.php");
    exit;
}
?>