<?php
include 'koneksi.php';

$id           = intval($_POST['id']);
$product_name = trim($_POST['product_name']);
$description  = trim($_POST['description']);
$kategori     = trim($_POST['kategori']);
$price        = floatval($_POST['price']);
$stock        = intval($_POST['stock']);
$gambar_lama  = $_POST['gambar_lama'];

$gambar_name = $gambar_lama;
$upload_dir = __DIR__ . "/uploads/";

// Cek apakah ada gambar baru
if (!empty($_FILES['gambar']['name'])) {
    $file_name = $_FILES['gambar']['name'];
    $file_tmp  = $_FILES['gambar']['tmp_name'];
    $file_size = $_FILES['gambar']['size'];
    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($file_ext, $allowed_ext) && $file_size <= 5*1024*1024) {
        $gambar_name = uniqid('img_') . '.' . $file_ext;
        move_uploaded_file($file_tmp, $upload_dir . $gambar_name);

        // Hapus gambar lama jika bukan URL dan masih ada di folder
        if (!filter_var($gambar_lama, FILTER_VALIDATE_URL) && !empty($gambar_lama) && file_exists($upload_dir . $gambar_lama)) {
            unlink($upload_dir . $gambar_lama);
        }
    } else {
        die("Format atau ukuran gambar tidak valid.");
    }
}

// Update data
$stmt = $conn->prepare("UPDATE products SET product_name=?, description=?, gambar=?, kategori=?, price=?, stock=? WHERE id=?");
$stmt->bind_param("ssssdii", $product_name, $description, $gambar_name, $kategori, $price, $stock, $id);

if ($stmt->execute()) {
    header("Location: dashboardProduk.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}
