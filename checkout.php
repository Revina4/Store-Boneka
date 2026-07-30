<?php
session_start();
include "koneksi.php";
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if (empty($_SESSION['cart'])) { header("Location: keranjang.php"); exit; }

if (isset($_POST['checkout'])) {
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $metode = "COD (Bayar di Tempat)";
    $id_user = $_SESSION['user_id'];
    
    // 1. Hitung total
    $total = 0;
    $items_data = [];
    $ids = array_keys($_SESSION['cart']);
    $ids_str = implode(',', $ids);
    $q = mysqli_query($koneksi, "SELECT * FROM tmbbrg WHERE id_barang IN ($ids_str)");
    while ($row = mysqli_fetch_assoc($q)) {
        $qty = $_SESSION['cart'][$row['id_barang']]['qty'];
        $subtotal = $row['harga'] * $qty;
        $total += $subtotal;
        $items_data[] = ['data' => $row, 'qty' => $qty, 'subtotal' => $subtotal];
    }

    // 2. Simpan HEADER ke tabel orders
    mysqli_query($koneksi, "INSERT INTO orders (id_user, alamat_pengiriman, metode_pembayaran, total_keseluruhan) 
                            VALUES ('$id_user', '$alamat', '$metode', '$total')");
    $id_order = mysqli_insert_id($koneksi);

    // 3. Simpan DETAIL barang ke tabel order_items
    foreach ($items_data as $item) {
        $id_barang = $item['data']['id_barang'];
        $harga = $item['data']['harga'];
        $qty = $item['qty'];
        $subtotal = $item['subtotal'];
        mysqli_query($koneksi, "INSERT INTO order_items (id_order, id_barang, harga_satuan, jumlah, subtotal) 
                                VALUES ('$id_order', '$id_barang', '$harga', '$qty', '$subtotal')");
    }

    // 4. Kosongkan Session Keranjang
    unset($_SESSION['cart']);

    // 5. Redirect ke Struk
    header("Location: struk.php?id=" . $id_order);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Boneka Store</title>
    <style>
        body { background: linear-gradient(135deg, #1a0a2e, #2d1b4e); color: #f0e6ff; font-family: Arial; padding: 20px; }
        .checkout-box { max-width: 600px; margin: auto; background: #2c1a4d; padding: 30px; border-radius: 15px; border: 1px solid #9b59b6; }
        label { display: block; color: #d7bde2; margin-top: 15px; margin-bottom: 5px; }
        input, textarea { width: 100%; padding: 10px; background: #1f0f3a; color: #f0e6ff; border: 1px solid #9b59b6; border-radius: 8px; box-sizing: border-box; }
        .btn-order { background: #9b59b6; color: white; padding: 12px 25px; border: none; border-radius: 25px; cursor: pointer; margin-top: 20px; width: 100%; font-size: 16px; }
        .btn-order:hover { background: #d7bde2; color: #1a0a2e; }
    </style>
</head>
<body>
    <div class="checkout-box">
        <h2 style="color: #d7bde2; text-align:center;">📦 Detail Pembelian</h2>
        <form method="POST">
            <label>Alamat Pengiriman Lengkap</label>
            <textarea name="alamat" rows="3" placeholder="Masukkan nama jalan, RT/RW, kecamatan, kota, dan kode pos" required></textarea>
            
            <label>Metode Pembayaran</label>
            <input type="text" value="COD (Bayar di Tempat)" disabled style="background:#2c1a4d; color:#d7bde2;">
            
            <button type="submit" name="checkout" class="btn-order">PROSES PEMESANAN</button>
        </form>
    </div>
</body>
</html>