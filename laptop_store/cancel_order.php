<?php
session_start();
require_once('../laptop_store/settings/config.php');

$user_id = $_SESSION['id'] ?? null;

if (!$user_id) {
    echo "Bạn chưa đăng nhập!";
    exit();
}

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    echo "Không tìm thấy mã đơn hàng!";
    exit();
}

// Kiểm tra xem đơn có thuộc user và có thể huỷ không
$sql = "SELECT status FROM Orders WHERE order_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Đơn hàng không tồn tại hoặc không thuộc về bạn.";
    exit();
}

$order = $result->fetch_assoc();
if ($order['status'] !== 'Chờ xử lý') {
    echo "Chỉ có thể huỷ đơn hàng ở trạng thái 'Chờ xử lý'.";
    exit();
}

// Cập nhật trạng thái đơn hàng thành 'Đã hủy'
$update = $conn->prepare("UPDATE Orders SET status = 'Hủy' WHERE order_id = ?");
$update->bind_param("i", $order_id);
if ($update->execute()) {
    header("Location: cart.php?tab=cancelled");
    exit();
} else {
    echo "Lỗi khi huỷ đơn.";
}
?>
