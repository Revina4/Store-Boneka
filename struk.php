<?php
session_start();
include "koneksi.php";
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }

$id_order = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : 0;

// Ambil header transaksi & data user
$q = mysqli_query($koneksi, "
    SELECT o.*, u.username, u.nama_lengkap, u.email 
    FROM orders o 
    JOIN users u ON o.id_user = u.id 
    WHERE o.id_order = '$id_order' AND o.id_user = '{$_SESSION['user_id']}'
");
$order = mysqli_fetch_assoc($q);
if (!$order) { echo "<script>alert('Data pesanan tidak ditemukan!'); window.location='index.php';</script>"; exit; }

// Ambil list barang yang dibeli
$items = mysqli_query($koneksi, "
    SELECT i.*, b.nama_barang, b.foto 
    FROM order_items i 
    JOIN tmbbrg b ON i.id_barang = b.id_barang 
    WHERE i.id_order = '$id_order'
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Struk Belanja - Boneka Store</title>
    <style>
        body { background: #1a0a2e; display: flex; justify-content: center; padding: 20px; }
        .struk-box { background: #fdfbf7; color: #222; max-width: 400px; width: 100%; padding: 25px; border-radius: 15px; }
        .header { text-align: center; border-bottom: 2px dashed #9b59b6; padding-bottom: 15px; margin-bottom: 15px; }
        .header h2 { color: #9b59b6; margin: 0; }
        .row { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px dotted #ddd; }
        .total { border-top: 2px solid #9b59b6; margin-top: 15px; padding-top: 15px; font-weight: bold; font-size: 18px; display: flex; justify-content: space-between; }
        .footer { text-align: center; border-top: 2px dashed #9b59b6; padding-top: 15px; margin-top: 15px; font-size: 12px; color: #666; }
        .btn-print { background: #9b59b6; color: white; padding: 10px 20px; border: none; border-radius: 25px; margin-top: 15px; width: 100%; cursor: pointer; }
        @media print { body { background: white; padding: 0; } .btn-print { display: none; } }
    </style>
</head>
<body>
    <div class="struk-box">
        <div class="header">
            <h2>🧸 Boneka Store</h2>
            <small>Jl. Boneka Lucu No. 123</small>
            <p><strong>STRUK PEMBELIAN</strong></p>
        </div>
        <div class="row"><span>No. Pesanan</span><span>#<?= str_pad($order['id_order'], 5, '0', STR_PAD_LEFT); ?></span></div>
        <div class="row"><span>Tanggal</span><span><?= date('d F Y, H:i', strtotime($order['tanggal_order'])); ?></span></div>
        <div class="row"><span>Pelanggan</span><span><?= htmlspecialchars($order['nama_lengkap']); ?></span></div>
        <div class="row"><span>Alamat</span><span style="text-align:right;"><?= htmlspecialchars(substr($order['alamat_pengiriman'], 0, 30)); ?>...</span></div>
        <div class="row"><span>Pembayaran</span><span><?= $order['metode_pembayaran']; ?></span></div>
        
        <div style="border-top:1px solid #ddd; margin: 15px 0; padding-top:10px; font-weight:bold;">Detail Barang:</div>
        <?php while($row = mysqli_fetch_assoc($items)): ?>
            <div class="row">
                <span><?= htmlspecialchars($row['nama_barang']); ?> x<?= $row['jumlah']; ?></span>
                <span>Rp <?= number_format($row['subtotal'], 0, ',', '.'); ?></span>
            </div>
        <?php endwhile; ?>

        <div class="total">
            <span>TOTAL</span>
            <span style="color:#9b59b6;">Rp <?= number_format($order['total_keseluruhan'], 0, ',', '.'); ?></span>
        </div>
        
        <div class="footer">
            <p>Terima kasih telah berbelanja!</p>
            <p>Pesanan akan segera kami proses.</p>
        </div>
        <button class="btn-print" onclick="window.print()">🖨️ Cetak Struk</button>
    </div>
</body>
</html>