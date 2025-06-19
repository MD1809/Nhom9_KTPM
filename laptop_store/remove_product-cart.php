<?php
session_start();
require_once('../laptop_store/settings/config.php');

// Lấy user_id từ session
$user_id = $_SESSION['id'] ?? null;

// Lấy product_id từ URL
$product_id = $_GET['product_id'] ?? null;

// Kiểm tra hợp lệ
if ($user_id && $product_id && is_numeric($product_id)) {
    // Chuẩn bị và thực thi truy vấn
    $stmt = $conn->prepare("DELETE FROM Cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);

    if ($stmt->execute()) {
        // Xoá thành công, quay lại giỏ hàng
        header("Location: cart.php?success=Đã xoá sản phẩm khỏi giỏ hàng");
        exit();
    } else {
        // Lỗi truy vấn
        header("Location: cart.php?error=Lỗi khi xoá sản phẩm");
        exit();
    }
} else {
    // Dữ liệu không hợp lệ
    header("Location: cart.php?error=Yêu cầu không hợp lệ");
    exit();
}
?>
