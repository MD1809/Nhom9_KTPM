<?php
session_start();
require_once('../laptop_store/settings/config.php');

// Lấy user ID từ session
$user_id = $_SESSION['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
    // Lấy thông tin địa chỉ từ form
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $city = $_POST['province'];
    $district = $_POST['district'];
    $ward = $_POST['ward'];
    $address_line = $_POST['address_detail'];
    $note = $_POST['note'];
    $total_price = floatval(str_replace('.', '', $_POST['total_price']));
    $payment = $_POST['payment'] ?? 'COD';
    $items = $_POST['items'] ?? [];

    if (empty($items)) {
        echo'Đơn hàng trống!';
    }

    // 1. Thêm địa chỉ mới vào bảng UserAddresses
    $sql_address = "INSERT INTO UserAddresses (recipient_name, phone, address_line, city, district, ward, user_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_address = $conn->prepare($sql_address);
    $stmt_address->bind_param("ssssssi", 
        $fullname, $phone, $address_line, $city, $district, $ward, $user_id
    );
    $stmt_address->execute();
    $address_id = $stmt_address->insert_id;

    // 2. Thêm đơn hàng vào bảng Orders
    $sql_order = "INSERT INTO Orders (total_amount, notes, status, user_id, address_id)
                  VALUES (?, ?, 'Chờ xử lý', ?, ?)";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("dsii", 
        $total_price, $note, $user_id, $address_id
    );
    $stmt_order->execute();
    $order_id = $stmt_order->insert_id;

    // 3. Thêm chi tiết đơn hàng vào Orderdetails
    $sql_detail = "INSERT INTO Orderdetails (order_id, product_id, quantityOrdered, priceEach) 
                   VALUES (?, ?, ?, ?)";
    $stmt_detail = $conn->prepare($sql_detail);

    foreach ($items as $product_id => $item) {
        $quantity = (int)$item['quantity'];
        $price = (float)$item['price'];
        $stmt_detail->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        $stmt_detail->execute();
    }

    // 4. Xóa dữ liệu 
    if(isset($_SESSION['order_product'])){
        unset($_SESSION['order_product']);
    }
    unset($_SESSION['order_items']);
    unset($_SESSION['order_selected']);

    $stmt = $conn->prepare("DELETE FROM Cart WHERE product_id = ? AND user_id = ?");

    foreach ($items as $product_id => $item) {
        $stmt->bind_param("ii", $product_id, $user_id);
        $stmt->execute();
    }

    // 5. Chuyển hướng sang trang cảm ơn
    header("Location: order.php");
    exit();
} else {
    echo "Lỗi: Không thể xử lý đơn hàng.";
}
?>
