<?php session_start(); ?>
<?php require_once("../settings/config.php"); ?>

<?php
if (!isset($_GET['id'])) {
    die("Không có đơn hàng.");
}

$order_id = intval($_GET['id']);

// Lấy thông tin đơn hàng
$sql = "SELECT * FROM orders WHERE order_id = $order_id";
$order = $conn->query($sql)->fetch_assoc();

if (!$order) {
    die("Không tìm thấy đơn hàng.");
}

// Lấy thông tin người dùng
$sql_user = "SELECT user_name, phone, FROM users WHERE user_id = " . intval($order['user_id']);
$user = $conn->query($sql_user)->fetch_assoc();

// Lấy thông tin địa chỉ giao hàng
$sql_address = "SELECT * FROM useraddresses WHERE address_id = " . intval($order['address_id']);
$address = $conn->query($sql_address)->fetch_assoc();

// Lấy sản phẩm trong đơn hàng
$sql_items = "SELECT p.product_name, p.image, od.quantityOrdered, od.priceEach
              FROM orderdetails od 
              JOIN products p ON od.product_id = p.product_id
              WHERE od.order_id = $order_id";
$items = $conn->query($sql_items);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/styleAd.css">
    <title>Chi tiết đơn hàng</title>
</head>
<body>
<div id="container-Order">
    <div class="detailorder__close">
        <a href="../admin/Ad_order.php"><i class="fa-solid fa-xmark login__close--icon"></i></a>
    </div>
    <form class="container-order">
        <div class="shipping-info">
            <h2>THÔNG TIN GIAO HÀNG</h2>
            <div class="input-group">
                <div class="card_item">
                    <label>Họ và tên: </label>
                    <span><?= htmlspecialchars($address['recipient_name']) ?></span>
                </div>
                <div class="card_item">
                    <label>Số điện thoại: </label>
                    <span><?= htmlspecialchars($address['phone']) ?></span>
                </div>
                <div class="card_item">
                    <label>Địa chỉ: </label>
                    <span><?= htmlspecialchars($address['ward'] . ' - ' . $address['district'] . ' - ' . $address['city']) ?></span>
                </div>
                <div class="card_item">
                    <label>Địa chỉ chi tiết: </label>
                    <span><?= htmlspecialchars($address['address_line']) ?></span>
                </div>
                <div class="card_item">
                    <label>Phương thức thanh toán: </label>
                    <span><?= htmlspecialchars($order['peyment_method'] ?? 'Không có') ?></span>
                </div>
                <div class="card_item">
                    <label>Ghi chú: </label>
                    <textarea readonly name="note"><?= htmlspecialchars($order['notes'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div class="order-summary">
            <h2>ĐƠN HÀNG</h2>
            <div class="order-items">
                <?php while ($item = $items->fetch_assoc()): ?>
                    <div class="order-item">
                        <img src="../assets/image/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                        <div class="item-details">
                            <p><?= htmlspecialchars($item['product_name']) ?></p>
                            <span class="item-price"><?= number_format($item['priceEach'], 0, ',', '.') ?>₫</span>
                        </div>
                        <div class="quantity-control">
                            <label>SL:</label>
                            <span><?= $item['quantityOrdered'] ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </form>
</div>
</body>
</html>
