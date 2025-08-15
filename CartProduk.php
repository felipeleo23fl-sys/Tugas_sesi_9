<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Hapus item
if (isset($_GET['remove'])) {
    $pid = intval($_GET['remove']);
    unset($_SESSION['cart'][$pid]);
    header("Location: CartProduk.php");
    exit;
}

// Update qty
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['qty'] as $pid => $qty) {
        $_SESSION['cart'][$pid] = max(1, intval($qty));
    }
}

// Ambil data produk
$cart_items = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(",", array_keys($_SESSION['cart']));
    $result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    while ($row = $result->fetch_assoc()) {
        $pid = $row['id'];
        $qty = $_SESSION['cart'][$pid];
        $subtotal = $row['price'] * $qty;
        $total += $subtotal;
        $cart_items[] = [
            'id' => $pid,
            'name' => $row['product_name'],
            'price' => $row['price'],
            'qty' => $qty,
            'subtotal' => $subtotal
        ];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Shopping Cart</h2>
    <a href="IndexProduk.php" class="btn btn-secondary mb-3">Melanjutkan belanja</a>

    <?php if (!empty($cart_items)): ?>
    <form method="POST">
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                    <td><input type="number" name="qty[<?= $item['id'] ?>]" value="<?= $item['qty'] ?>" min="1" class="form-control" style="width:80px"></td>
                    <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                    <td><a href="CartProduk.php?remove=<?= $item['id'] ?>" class="btn btn-danger btn-sm">Hapus</a></td>
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
        <button type="submit" class="btn btn-primary">Update Cart</button>
        <a href="checkout.php" class="btn btn-success">Checkout</a>
    </form>
    <?php else: ?>
        <div class="alert alert-warning">Cart kosong.</div>
    <?php endif; ?>
</div>
</body>
</html>
