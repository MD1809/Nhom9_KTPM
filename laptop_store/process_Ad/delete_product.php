<?php
session_start();
require_once("../settings/config.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // 1. Lấy spec_id của sản phẩm để xóa sau
    $stmt = $conn->prepare("SELECT spec_id FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($spec_id);
    $stmt->fetch();
    $stmt->close();

    // 2. Xóa liên quan trong orderdetails (nếu có)
    $stmt = $conn->prepare("DELETE FROM orderdetails WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // 3. Xóa sản phẩm trong bảng products
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // 4. Nếu có spec_id thì xóa trong bảng specifications
    if (!empty($spec_id)) {
        $stmt = $conn->prepare("DELETE FROM specifications WHERE spec_id = ?");
        $stmt->bind_param("i", $spec_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: ../Admin/Ad_Products.php");
    exit();
} else {
    echo "Không tìm thấy ID sản phẩm.";
}

$conn->close();
?>
