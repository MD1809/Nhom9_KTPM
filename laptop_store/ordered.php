<?php session_start(); ?>
<?php require_once('../laptop_store/settings/config.php'); ?>
<?php

if (!isset($_SESSION['id'])) {
  echo '<div class="checkpage">Đăng nhập để mua hàng.</div>';
}

$user_id = $_SESSION['id'];

// Lấy danh sách đơn hàng của người dùng
$sql_orders = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();
?>

<?php require_once('header.php') ?>
  <main class = "mainincart">
    <form action="/process_custom/remove_order.php" method="post">
      <div class="cart-container">
        <div class="cart_title">
          <h1>Giỏ hàng của bạn</h1>
          
          <div class="ordered">
            <ul class="ordered_list">
                <li class="ordered_list-item"><a href="cart.php" class="ordered_item-link"><span>Giỏ hàng</span></a></li>
                <li class="ordered_list-item"><a href="ordered.php" class="ordered_item-link selected"><span>Đã đặt</span></a></li>
            </ul>
          </div>

          <div class="ordered__close go-back-btn">
              <i class="fa-solid fa-xmark login__close--icon"></i>
          </div>
        </div>
        <?php
          if ($result_orders->num_rows > 0){
            while ($order = $result_orders->fetch_assoc()){
              $sql_items = "SELECT * FROM orderdetails WHERE order_id = ?";
              $stmt_items = $conn->prepare($sql_items);
              $stmt_items->bind_param("i", $order['id_order']);
              $stmt_items->execute();
              $result_items = $stmt_items->get_result();
              while ($item = $result_items->fetch_assoc()){
                $total = $item['priceEach'] * $item['quantity'];
                $sql_product = "SELECT * FROM products WHERE id_product = ?";
                $stmt_product = $conn->prepare($sql_product);
                $stmt_product->bind_param("i", $item['product_id']);
                $stmt_product->execute();
                $result_product = $stmt_product->get_result();
                $product = $result_product->fetch_assoc();
                echo'
                <div class="cart-item">
                  <img src="../shop/assets/image/' . $product['image'] . '" alt="' . $product['name'] . '">
                  <div class="item-details">
                    <h2>' . $product['name'] . '</h2>
                    <p>Giá:   <span class="pricesp">' . number_format($item['priceEach'], 0, ',', '.') . '₫ </span></p>
                    <div class="info_order">
                      <p>Số lượng: <span class="quantity">' . $item['quantity'] . '</span></p>
                      <p>Trạng thái: <span class="status">' . $order['status'] . '</span></p>
                    </div>
                  </div>
                  <div class="item-total">
                    <p>Tổng: <strong>' . number_format($total, 0, ',', '.') .'₫ </strong></p>';
                    if ($order['status'] == 'Chờ xử lý') {
                      echo '<a href="../shop/process_custom/remove_order.php?order_id=' . $order['id_order'] . '" class="remove-btn" onclick="return confirm(\'Bạn có chắc muốn hủy đơn này?\')">Hủy</a>';
                    }
                  echo'</div>
                </div>';
              }
            }
          }else{
            echo '<div class="checkpage">Không có đơn hàng nào.</div>';
          }
        ?>
      </div>
    </form>
  </main>
  <script src="goback_common.js"></script>
  
</body>
</html>

