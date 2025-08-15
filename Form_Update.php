<?php
include 'koneksi.php';

// Ambil ID produk dari URL
$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM products WHERE id = $id");
$data = $result->fetch_assoc();

if (!$data) {
    die("Produk tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2>Edit Produk</h2>

    <form action="UpdateProduk.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">
        <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($data['gambar']) ?>">

        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="product_name" class="form-control" value="<?= htmlspecialchars($data['product_name']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($data['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Gambar Lama</label><br>
            <?php
            $gambar = $data['gambar'];
            if (filter_var($gambar, FILTER_VALIDATE_URL)) {
                echo "<img src='".htmlspecialchars($gambar)."' width='80'>";
            } elseif (!empty($gambar) && file_exists("uploads/".$gambar)) {
                echo "<img src='uploads/".htmlspecialchars($gambar)."' width='80'>";
            } else {
                echo "<span class='text-muted'>Tidak ada</span>";
            }
            ?>
        </div>

        <div class="mb-3">
            <label>Ganti Gambar (opsional)</label>
            <input type="file" name="gambar" class="form-control">
            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" name="kategori" class="form-control" value="<?= htmlspecialchars($data['kategori']) ?>">
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($data['price']) ?>">
        </div>

        <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" value="<?= htmlspecialchars($data['stock']) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="dashboardProduk.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>
</html>
