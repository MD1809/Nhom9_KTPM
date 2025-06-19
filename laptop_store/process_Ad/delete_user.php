<?php
session_start();
require_once("../settings/config.php");

if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    $check_orders_sql = "SELECT COUNT(*) FROM Orders WHERE user_id = ?";
    $stmt_check_orders = $conn->prepare($check_orders_sql);
    $stmt_check_orders->bind_param("i", $user_id);
    $stmt_check_orders->execute();
    $stmt_check_orders->bind_result($order_count);
    $stmt_check_orders->fetch();
    $stmt_check_orders->close();

    // Nếu người dùng có đơn hàng
    if ($order_count > 0) {
        // Lưu thông báo lỗi vào session
        $_SESSION['alert_message'] = "Không thể xóa người dùng này vì họ đang có đơn hàng liên quan.";
        header("Location: ../admin/Ad_users.php");
        exit();
    }

    // Nếu không có đơn hàng nào, tiến hành xóa người dùng
    $stmt_delete_user = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt_delete_user->bind_param("i", $user_id);

    if ($stmt_delete_user->execute()) {
        // Lưu thông báo thành công vào session
        $_SESSION['alert_message'] = "Người dùng đã được xóa thành công!";
        header("Location: ../admin/Ad_users.php");
        exit();
    }

    // Chuẩn bị câu lệnh SQL DELETE
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        // Xóa thành công, chuyển hướng về trang danh sách người dùng
        header("Location: ../admin/Ad_users.php");
        exit();
    }
    $stmt->close();
} else {
    echo' lỗi ';
}

$conn->close();
?>