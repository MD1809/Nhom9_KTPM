<?php
session_start();
require_once('../settings/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $id = intval($_POST['product_id']);
    $name = $_POST['product_name'];
    $price = floatval($_POST['price']);
    $description = $_POST['description'];

    $cpu = $_POST['cpu'];
    $ram = $_POST['ram'];
    $storage = $_POST['storage'];
    $screen = $_POST['screen'];
    $gpu = $_POST['gpu'];
    $os = $_POST['os'];

    // Xử lý ảnh nếu có upload
    $imageName = '';  // Ảnh sẽ được dùng để cập nhật (nếu có ảnh mới)

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/image/';
        $imageName = basename($_FILES['image']['name']);
        $imageTmp  = $_FILES['image']['tmp_name'];

        // Kiểm tra định dạng file ảnh
        $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $allowedExt)) {
            die("Chỉ chấp nhận các định dạng ảnh: jpg, jpeg, png, gif, webp");
        }

        // Tạo thư mục nếu chưa có
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $targetPath = $uploadDir . $imageName;
        if (!move_uploaded_file($imageTmp, $targetPath)) {
            die("Lỗi khi tải ảnh lên máy chủ.");
        }
    }

    // 1. Lấy spec_id của sản phẩm
    $stmt = $conn->prepare("SELECT spec_id FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($spec_id);
    $stmt->fetch();
    $stmt->close();

    // Nếu chưa có spec_id, tạo mới bản ghi Specifications
    if (empty($spec_id)) {
        $stmt = $conn->prepare("INSERT INTO specifications (cpu, ram, storage, screen, gpu, os) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $cpu, $ram, $storage, $screen, $gpu, $os);
        $stmt->execute();
        $new_spec_id = $stmt->insert_id;
        $stmt->close();

        // Gán spec_id vào sản phẩm
        $stmt = $conn->prepare("UPDATE products SET spec_id = ? WHERE product_id = ?");
        $stmt->bind_param("ii", $new_spec_id, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // 2. Cập nhật bảng specifications
        $stmt = $conn->prepare("UPDATE specifications SET cpu=?, ram=?, storage=?, screen=?, gpu=?, os=? WHERE spec_id=?");
        $stmt->bind_param("ssssssi", $cpu, $ram, $storage, $screen, $gpu, $os, $spec_id);
        $stmt->execute();
        $stmt->close();
    }

    // 3. Cập nhật bảng products
    $stmt = $conn->prepare("UPDATE products SET product_name=?, description=?, price=?, image=? WHERE product_id=?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $imageName, $id);
    $stmt->execute();
    $stmt->close();

    // Chuyển hướng về trang quản lý sản phẩm
    header("Location: ../Admin/Ad_Products.php");
    exit();
}

$conn->close();
?>
