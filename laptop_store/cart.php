<?php session_start(); ?>
<?php require_once('../laptop_store/settings/config.php'); ?>

<?php
  $user_id = $_SESSION['id'] ?? null;
  if (!$user_id) {
      echo '<p class="error">Bạn chưa đăng nhập.</p>';
      exit();
  }
?>

<?php require_once('header.php') ?>
  <main class="mainincart">
    <form action="../laptop_store/prepare_order.php" method="post">
      <div class="cart-container">
        <div class="cart_title">
          <h1>Giỏ hàng của bạn</h1>
          
          <div class="ordered">
            <?php $tab = $_GET['tab'] ?? 'cart'; ?>
            <ul class="ordered_list">
              <li><a href="cart.php?tab=cart" class="ordered_item-link <?= $tab == 'cart' ? 'selected' : '' ?>">Giỏ hàng</a></li>
              <li><a href="cart.php?tab=pending" class="ordered_item-link <?= $tab == 'pending' ? 'selected' : '' ?>">Chờ xử lý</a></li>
              <li><a href="cart.php?tab=success" class="ordered_item-link <?= $tab == 'success' ? 'selected' : '' ?>">Thành công</a></li>
              <li><a href="cart.php?tab=cancelled" class="ordered_item-link <?= $tab == 'cancelled' ? 'selected' : '' ?>">Đã hủy</a></li>
            </ul>
          </div>
          <!-- <div class="cart__close go-back-btn">
              <i class="fa-solid fa-xmark login__close--icon"></i>
          </div> -->
        </div>
        <?php
          $status_map = [
            'cart' => 'Giỏ hàng',
            'pending' => 'Chờ xử lý',
            'shipping' => 'Đang giao',
            'success' => 'Hoàn thành',
            'cancelled' => 'Hủy'
          ];

          if ($tab === 'cart') {
            // Truy vấn sản phẩm trong giỏ hàng
            $sql = "SELECT COUNT(*) AS total FROM Cart WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['total'] == 0) {
              echo '<div class="empty-cart"> Giỏ hàng trống! </div>';
              echo '<div class="addincart"><a href="index.php" class="add-btn">Thêm sản phẩm</a></div>';
              exit();
            }

            $sql = "SELECT c.product_id, c.quantity, p.product_name, p.price, p.image 
                    FROM Cart c
                    JOIN products p ON c.product_id = p.product_id
                    WHERE c.user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $total = 0;
            while ($row = $result->fetch_assoc()) {
              $product_id = $row['product_id'];
              $name = htmlspecialchars($row['product_name']);
              $price = $row['price'];
              $image = htmlspecialchars($row['image']);
              $quantity = $row['quantity'];

              $formattedPrice = number_format($price, 0, ',', '.') . '₫';

              echo '
              <div class="cart-item">
                <div class="check">
                  <input type="checkbox" name="selected[]" value="' . $product_id . '" class="checkselect" checked>
                </div>
                <img src="assets/image/' . $image . '" alt="' . $name . '">
                <div class="item-details">
                  <h2>' . $name . '</h2>
                  <div class="cart_price-qty">
                    <p>Giá: <span class="price">' . $formattedPrice . '</span></p>
                    <div class="detailProduct-quantity">
                      <button type="button" class="change-qty decrease-qty">-</button>
                      <input type="number" name="slProduct" class="quantity-input" min="1" value="' . $quantity . '" data-id="' . $product_id . '">
                      <button type="button" class="change-qty increase-qty">+</button>
                    </div>
                  </div>
                  <input type="hidden" name="items[' . $product_id . '][quantity]" value="' . $quantity . '">
                  <input type="hidden" name="items[' . $product_id . '][name]" value="' . $name . '">
                  <input type="hidden" name="items[' . $product_id . '][price]" value="' . $price . '">
                  <input type="hidden" name="items[' . $product_id . '][image]" value="' . $image . '">
                </div>
                <div class="item-total">
                  <a href="remove_product-cart.php?product_id=' . $product_id . '" class="remove-btn" onclick="return confirm(\'Bạn có chắc muốn xoá sản phẩm này?\')">Xoá</a>
                </div>
              </div>';

              $total += $price * $quantity;
            }

            echo '
            <div class="cart-summary">
              <h3>Tổng thanh toán: <span class="grand-total">' . number_format($total, 0, ',', '.') . '₫</span></h3>
              <button type="submit" class="checkout-btn" name="orders-btn">Mua ngay</button>
            </div>';
          }else {
            // Truy vấn đơn hàng theo trạng thái (chờ xử lý, đang giao, Hoàn thành, huỷ)
            $status = $status_map[$tab] ?? 'Chờ xử lý';
            $sql = "SELECT o.order_id, o.order_date, o.status, od.*, p.product_name, p.image 
                    FROM Orders o
                    JOIN Orderdetails od ON o.order_id = od.order_id
                    JOIN products p ON od.product_id = p.product_id
                    WHERE o.user_id = ? AND o.status = ?
                    ORDER BY o.order_date DESC, o.order_id DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $user_id, $status);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
              echo '<p class="cart_notify">Không có đơn nào!</p>';
            }else {
              while ($order = $result->fetch_assoc()) {
                $product_id_status = $order['product_id'];
                $name_status = htmlspecialchars($order['product_name']);
                $price_status = $order['priceEach'];
                $image_status = htmlspecialchars($order['image']);
                $quantity_status = $order['quantityOrdered'];
                $formattedPrice_status = number_format($price_status, 0, ',', '.') . '₫';
              
                echo '
                <div class="cart-item">
                  <img src="assets/image/' . $image_status . '" alt="' . $name_status . '">
                  <div class="item-details">
                    <h2>' . $name_status . '</h2>
                    <div class="cart_price-qty">
                      <p>Giá: <span class="price">' . $formattedPrice_status . '</span></p>
                      <div class="detailProduct-quantity">
                        <span>SL: ' . $quantity_status . '</span>
                      </div>
                      <span>Trạng thái: ' . $status . '</span>
                    </div>
                  </div>
                  <div class="item-total">
                    <p>Tổng: <strong>' . number_format($price_status*$quantity_status, 0, ',', '.') .'₫ </strong></p>';
                    if($status == "Chờ xử lý"){
                      echo' <a href="cancel_order.php?order_id=' . $order['order_id'] . '" class="remove-btn" onclick="return confirm(\'Bạn có chắc muốn hủy đơn hàng này không?\')">Hủy</a>';
                    }
                  echo'</div>
                </div>';
              }
            }
          }
        ?>
      </div>
    </form>
  </main>
  <script src="goback_common.js"></script>
  <script>
  // Hàm cập nhật tổng tiền giỏ hàng
  function updateTotals() {
    let grandTotal = 0;

    document.querySelectorAll(".cart-item").forEach(function (cartItem) {
      const quantityInput = cartItem.querySelector('.quantity-input');
      const hiddenInput = cartItem.querySelector('input[type="hidden"][name^="items"][name$="[quantity]"]');
      const priceElement = cartItem.querySelector(".price");

      // Giá sản phẩm (loại bỏ ký tự và chuyển về số)
      const price = parseInt(priceElement.innerText.replace(/[₫.,]/g, ""));

      const qty = parseInt(quantityInput.value);
      const subtotal = price * qty;

      // Cập nhật input ẩn
      hiddenInput.value = qty;

      // tính tổng tiền
      grandTotal += subtotal;
    });

    // Cập nhật tổng thanh toán toàn bộ
    const grandTotalElement = document.querySelector(".grand-total");
    if (grandTotalElement) {
      grandTotalElement.innerText = grandTotal.toLocaleString("vi-VN") + "₫";
    }
  }

  function updateCart(productId, newQuantity) {
    fetch('../laptop_store/update_cart-quantity.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({product_id: productId, quantity: newQuantity})
    });
  }

  // Gán sự kiện tăng giảm số lượng
  document.querySelectorAll(".cart-item").forEach(function(cartItem) {
    const decreaseBtn = cartItem.querySelector(".decrease-qty");
    const increaseBtn = cartItem.querySelector(".increase-qty");
    const quantityInput = cartItem.querySelector(".quantity-input");

    increaseBtn.addEventListener("click", () => {
      quantityInput.value = parseInt(quantityInput.value) + 1;
      updateTotals();
      const productId = quantityInput.getAttribute('data-id');
      updateCart(productId, quantityInput.value);
    });

    decreaseBtn.addEventListener("click", () => {
      if (parseInt(quantityInput.value) > 1) {
        quantityInput.value = parseInt(quantityInput.value) - 1;
        updateTotals();
        const productId = quantityInput.getAttribute('data-id');
        updateCart(productId, quantityInput.value);
      }
    });
  });

  // Gọi 1 lần khi trang load để đảm bảo đúng tổng ban đầu
  updateTotals();
</script>
  


</body>
</html>
