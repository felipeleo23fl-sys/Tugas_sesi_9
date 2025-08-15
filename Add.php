<?php include 'koneksi.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Tambah Produk</h2>

    <form action="AddProduk.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="product_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Gambar (upload)</label>
            <input type="file" name="gambar" class="form-control">
            <small class="text-muted">Format: JPG, PNG, GIF, WEBP (maks 5MB)</small>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" name="kategori" class="form-control">
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" step="0.01" name="price" class="form-control">
        </div>

        <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="dashboardProduk.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>
</html>
