<?php
include 'koneksi.php';

// Ambil input
$product_name = trim($_POST['product_name']);
$description  = trim($_POST['description']);
$kategori     = trim($_POST['kategori']);
$price        = floatval($_POST['price']);
$stock        = intval($_POST['stock']);

$gambar_name = null;
$upload_dir = __DIR__ . "/uploads/";

// Pastikan folder uploads ada
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Proses upload file
if (!empty($_FILES['gambar']['name'])) {
    $file_name = $_FILES['gambar']['name'];
    $file_tmp  = $_FILES['gambar']['tmp_name'];
    $file_size = $_FILES['gambar']['size'];
    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($file_ext, $allowed_ext) && $file_size <= 5*1024*1024) {
        $gambar_name = uniqid('img_') . '.' . $file_ext;
        move_uploaded_file($file_tmp, $upload_dir . $gambar_name);
    } else {
        die("Format atau ukuran gambar tidak valid.");
    }
}

// Simpan ke database
$stmt = $conn->prepare("INSERT INTO products (product_name, description, gambar, kategori, price, stock) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssdi", $product_name, $description, $gambar_name, $kategori, $price, $stock);

if ($stmt->execute()) {
    header("Location: dashboardProduk.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}
