<?php
session_start();

// Hapus produk dari keranjang
if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

// Ubah jumlah qty
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['qty'] as $id => $qty) {
        $_SESSION['cart'][$id]['qty'] = max(1, intval($qty));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Keranjang Belanja</h2>
    <a href="index.php" class="btn btn-secondary mb-3">Kembali ke Produk</a>

    <?php if (!empty($_SESSION['cart'])): ?>
    <form method="POST">
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $item):
                    $subtotal = $item['price'] * $item['qty'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                    <td><input type="number" name="qty[<?= $item['id'] ?>]" value="<?= $item['qty'] ?>" min="1" class="form-control" style="width:80px"></td>
                    <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                    <td><a href="cart.php?remove=<?= $item['id'] ?>" class="btn btn-danger btn-sm">Hapus</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th colspan="2">Rp <?= number_format($total, 0, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>
        <button type="submit" class="btn btn-primary">Update Keranjang</button>
        <a href="checkout.php" class="btn btn-success">Checkout</a>
    </form>
    <?php else: ?>
        <div class="alert alert-warning">Keranjang belanja kosong.</div>
    <?php endif; ?>
</div>
</body>
</html>
