
<?php
session_start();


if (isset($_POST['add-button']) && $_POST['add-button'] === 'addbutton') {
    header("location: ../index.php");
    exit();
}


$selected_ids = $_POST['selected'] ?? [];
$all_items = $_POST['items'] ?? [];

if (empty($selected_ids) || empty($all_items)) {
    header('Location: cart.php?error=Không có sản phẩm nào được chọn.');
    exit();
}

$order_items = [];
foreach ($selected_ids as $product_id) {
    $pid = $product_id;
    if (isset($all_items[$pid])) {
        $item = $all_items[$pid];
        $item['product_id'] = $pid; // Gắn lại id vào mảng nếu cần
        $order_items[$pid] = $item;
    }
}

if (empty($order_items)) {
    header('Location: cart.php?error=Sản phẩm không hợp lệ.');
    exit();
}

$_SESSION['order_items'] = $order_items;
$_SESSION['order_selected'] = array_keys($order_items);

header('Location: order.php');
exit();


?>
