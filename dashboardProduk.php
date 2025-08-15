<?php
session_start();
include 'koneksi.php';

// Tambah produk ke keranjang
if (isset($_GET['add_to_cart'])) {
    $id = intval($_GET['add_to_cart']);
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    $product = $result->fetch_assoc();

    if ($product) {
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = [
                'id' => $product['id'],
                'product_name' => $product['product_name'],
                'price' => $product['price'],
                'qty' => 1
            ];
        } else {
            $_SESSION['cart'][$id]['qty'] += 1;
        }
    }
    header("Location: dashboardProduk.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2>Dashboard Produk</h2>

    <a href="Add.php" class="btn btn-success mb-3">Tambah Produk</a>
    <a href="CartProduk.php" class="btn btn-primary mb-3">Lihat Keranjang 
        (<?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0 ?>)
    </a>

    <table class="table table-bordered table-striped bg-white">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Produk</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stock</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM products");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>
                    <?php
                    $gambar = $row['gambar'];
                    if (filter_var($gambar, FILTER_VALIDATE_URL)) {
                        echo "<img src='".htmlspecialchars($gambar)."' width='60'>";
                    } elseif (!empty($gambar) && file_exists("uploads/".$gambar)) {
                        echo "<img src='uploads/".htmlspecialchars($gambar)."' width='60'>";
                    } else {
                        echo "<span class='text-muted'>Tidak ada</span>";
                    }
                    ?>
                </td>
                <td><?= htmlspecialchars($row['kategori']) ?></td>
                <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                <td><?= $row['stock'] ?></td>
                <td>
                    <a href="Form_Update.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="DeleteProduk.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?')">Hapus</a>
                    <a href="CartProduk.php?add_to_cart=<?= $row['id'] ?>" class="btn btn-primary btn-sm">+ Keranjang</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
