<?php
session_start(); // WAJIB! untuk menggunakan $_SESSION

include 'koneksi.php';

// Inisialisasi cart jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tambah produk ke cart
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $qty = max(1, intval($_POST['qty']));

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $qty; // Tambah jumlah
    } else {
        $_SESSION['cart'][$product_id] = $qty; // Produk baru
    }

    header("Location: CartProduk.php"); // Redirect ke halaman cart
    exit;
}

// Ambil kategori yang dipilih user
$kategori_filter = isset($_GET['kategori']) ? $conn->real_escape_string($_GET['kategori']) : '';

// Query produk
if (!empty($kategori_filter)) {
    $sql = "SELECT * FROM products WHERE kategori = '$kategori_filter'";
} else {
    $sql = "SELECT * FROM products";
}
$result = $conn->query($sql);

// Ambil kategori unik
$kategori_sql = "SELECT DISTINCT kategori FROM products";
$kategori_result = $conn->query($kategori_sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Commerce Sederhana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Toko Online</a>
        <a class="btn btn-warning" href="CartProduk.php">Cart (<?= array_sum($_SESSION['cart']); ?>)</a>
    </div>
</nav>

<div class="container">
     <!-- Filter kategori -->
    <form method="GET" class="mb-4"> 
        <div class="row">
            <div class="col-md-4">
                <select name="kategori" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <?php while ($kategori = $kategori_result->fetch_assoc()) { ?>
                        <option value="<?= htmlspecialchars($kategori['kategori']); ?>" 
                            <?= ($kategori_filter == $kategori['kategori']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($kategori['kategori']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </form>
    
    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?= htmlspecialchars($row['gambar']); ?>" class="card-img-top" alt="<?= htmlspecialchars($row['product_name']); ?>" style="height:200px;object-fit:cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['product_name']); ?></h5>
                        <p class="card-text"><?= htmlspecialchars($row['description']); ?></p>
                        <h6 class="text-danger">Rp <?= number_format($row['price'], 0, ',', '.'); ?></h6>
                        <form method="POST" class="d-flex">
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                            <input type="number" name="qty" value="1" min="1" class="form-control me-2" style="width:70px">
                            <button type="submit" name="add_to_cart" class="btn btn-success btn-sm">+ Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
