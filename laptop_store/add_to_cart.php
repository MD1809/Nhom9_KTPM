<?php
session_start();
require_once('../laptop_store/settings/config.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['order']) && $_POST['order'] == "order"
        || isset($_POST['addcart']) && $_POST['addcart'] == "addcart") {
        if(!isset($_SESSION['id'])){
            // Lấy URL trả về sau khi từ chối đăng nhập (hoặc sau khi login xong quay lại)
            $return_url = isset($_POST['return_url']) ? $_POST['return_url'] : 'index.php';
            echo "
            <script>
                if (confirm('Bạn cần đăng nhập để tiếp tục mua hàng. Bạn có muốn đăng nhập không?')) {
                    // Chuyển đến trang login và truyền return_url để quay lại sau
                    window.location.href = '../laptop_store/login.php?return=" . $return_url . "';
                } else {
                    // Quay về lại trang chi tiết sản phẩm
                    window.location.href = '$return_url';
                }
            </script>";
            exit();
        }
    }
}

$user_id = $_SESSION['id'] ?? null;
if (!$user_id) {
    echo '<p class="error">Bạn chưa đăng nhập.</p>';
    exit();
}

if (isset($_POST['product_id'], $_POST['product_name'], $_POST['product_price'], $_POST['product_image'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $img = $_POST['product_image'];
    $price = $_POST['product_price'];
    $quantity = $_POST['quantity'];

    if(isset($_POST['order']) && $_POST['order'] == "order"){
        $_SESSION['order_product'] = [
             $product_id =>[
                'product_id' => $product_id,
                'name' => $product_name,
                'price' => $price,
                'image' => $img,
                'quantity' => $quantity
            ]
        ];
        unset($_SESSION['order_items'], $_SESSION['order_selected']);
        header("Location: order.php");
        exit();

    }else{
        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ chưa
        $sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Đã có → cập nhật số lượng
            $row = $result->fetch_assoc();
            $new_qty = $row['quantity'] + $quantity;

            $update_sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("iii", $new_qty, $user_id, $product_id);
            $update_stmt->execute();
        } else {
            // Chưa có → thêm mới
            $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
            $insert_stmt->execute();
        }
    
        if (isset($_POST['addcart']) && $_POST['addcart'] == "addcart") {
            $returnUrl = $_POST['return_url'] ?? '../index.php';
            header("Location: $returnUrl");
            exit();
        }
    }
} else {
    echo "Thiếu dữ liệu sản phẩm!";
}
?>
