<?php
session_start();
include "koneksi.php";
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// Ambil data barang berdasarkan session cart
$items = [];
$total_keseluruhan = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $ids_str = implode(',', $ids);
    $q = mysqli_query($koneksi, "SELECT * FROM tmbbrg WHERE id_barang IN ($ids_str)");
    while ($row = mysqli_fetch_assoc($q)) {
        $qty = $_SESSION['cart'][$row['id_barang']]['qty'];
        $subtotal = $row['harga'] * $qty;
        $total_keseluruhan += $subtotal;
        $items[] = [
            'data' => $row,
            'qty' => $qty,
            'subtotal' => $subtotal
        ];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Belanja - Boneka Store</title>
    <style>
        /* (Gunakan CSS tema ungu sama seperti halaman lain) */
        body { background: linear-gradient(135deg, #1a0a2e, #2d1b4e); color: #f0e6ff; font-family: Arial; padding: 20px; }
        .cart-box { max-width: 800px; margin: auto; background: #2c1a4d; padding: 20px; border-radius: 15px; border: 1px solid #9b59b6; }
        .cart-item { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #4a2b6b; padding: 15px 0; }
        .cart-item img { width: 60px; height: 60px; border-radius: 8px; object-fit: cover; }
        .cart-item .info { flex: 1; padding: 0 15px; }
        .cart-item .qty { color: #d7bde2; margin: 0 10px; }
        .btn-hapus { background: #e74c3c; color: white; border: none; padding: 5px 12px; border-radius: 20px; cursor: pointer; }
        .checkout-btn { background: #9b59b6; color: white; padding: 15px 30px; border: none; border-radius: 25px; font-size: 18px; cursor: pointer; margin-top: 20px; width: 100%; }
        .checkout-btn:hover { background: #d7bde2; color: #1a0a2e; }
        .empty-cart { text-align: center; color: #d2b4de; }
        .total-price { font-size: 20px; font-weight: bold; color: #f1c40f; text-align: right; margin-top: 20px; padding-top: 20px; border-top: 2px solid #4a2b6b; }
    </style>
</head>
<body>
    <div class="cart-box">
        <h2 style="color: #d7bde2;">🛒 Keranjang Belanja</h2>
        
        <?php if (empty($items)): ?>
            <div class="empty-cart">Keranjang Anda masih kosong. <a href="index.php" style="color: #9b59b6;">Belanja sekarang!</a></div>
        <?php else: ?>
            <?php foreach ($items as $item): ?>
                <div class="cart-item">
                    <img src="uploads/<?= $item['data']['foto']; ?>">
                    <div class="info">
                        <strong><?= $item['data']['nama_barang']; ?></strong><br>
                        Rp <?= number_format($item['data']['harga'], 0, ',', '.'); ?>
                    </div>
                    <div class="qty">Jumlah: <?= $item['qty']; ?></div>
                    <div style="color: #d2b4de;">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></div>
                    <a href="hapus_keranjang.php?id=<?= $item['data']['id_barang']; ?>"><button class="btn-hapus">✖</button></a>
                </div>
            <?php endforeach; ?>
            
            <div class="total-price">Total Belanja: Rp <?= number_format($total_keseluruhan, 0, ',', '.'); ?></div>
            <a href="checkout.php"><button class="checkout-btn">Lanjut ke Checkout</button></a>
        <?php endif; ?>
    </div>
</body>
</html>