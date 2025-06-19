<?php
session_start();
require_once("../settings/config.php");

if (isset($_GET['id']) && isset($_GET['isactive'])) {
    $userId = intval($_GET['id']);
    $new = intval($_GET['isactive']);

    $sql = "UPDATE users SET is_active = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $new, $userId);
    if ($stmt->execute()) {
        header("Location: ../Admin/Ad_users.php");
        exit();
    } else {
        echo "Lỗi khi cập nhật trạng thái.";
    }
}
?>
