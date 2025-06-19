<?php session_start(); ?>
<?php require_once('../laptop_store/settings/config.php'); ?>

<?php
//    $total = 0;
//    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
//        foreach ($_SESSION['cart'] as $index => $item){
//            $subtotal = $item['price'] * $item['quantity'];
//            $total += $subtotal;
//        }
//    }else{
//        $check_cart = " trống!";
//    } 
?>

<?php require_once('header.php') ?>
    <main>
        <div id="container-Order">
        <form action="place_order.php" method="post" class="container-order">
            <div class="shipping-info">
                <h3>THÔNG TIN GIAO HÀNG</h3>
                <div class="input-group">
                    <div class="input-item-o">
                        <input type="text" name="fullname" placeholder="Nhập vào Họ Tên" required>
                    </div>
                    <div class="input-item-o inputphone">
                        <input type="text" name="phone" placeholder="Nhập vào Số điện thoại" required>
                    </div>
                </div>
                <input id="email" type="email" name="email" placeholder="Nhập vào Email">
                <div class="input-group">
                <select name="province" required>
                    <option value="">Chọn Tỉnh...</option>
                    <option value="Hà Nội" selected>Hà Nội</option>
                </select>

                <select name="district" required>
                    <option value="">Chọn Quận...</option>
                    <option value="Hà Đông">Hà Đông</option>
                    <option value="Ba Đình">Ba Đình</option>
                    <option value="Hoàn Kiếm">Hoàn Kiếm</option>
                    <option value="Đống Đa">Đống Đa</option>
                    <option value="Cầu Giấy">Cầu Giấy</option>
                    <option value="Thanh Xuân">Thanh Xuân</option>
                    <option value="Nam Từ Liêm">Nam Từ Liêm</option>
                    <option value="Bắc Từ Liêm">Bắc Từ Liêm</option>
                    <option value="Long Biên">Long Biên</option>
                </select>

                <select name="ward" required>
                    <option value="">Chọn Phường...</option>
                    <option value="Yên Nghĩa">Yên Nghĩa</option>
                    <option value="Hà Cầu">Hà Cầu</option>
                    <option value="Mộ Lao">Mộ Lao</option>
                    <option value="Phúc La">Phúc La</option>
                    <option value="Kiến Hưng">Kiến Hưng</option>
                    <option value="Văn Quán">Văn Quán</option>
                    <option value="Vạn Phúc">Vạn Phúc</option>
                    <option value="La Khê">La Khê</option>
                    <option value="Yết Kiêu">Yết Kiêu</option>
                </select>
            </div>
                    <input id="addressdetail" name="address_detail" type="text" placeholder="Địa chỉ chi tiết (Số nhà, ấp...)">
                    <textarea name="note" placeholder="Ghi chú"></textarea>
                </div>
        
                <div class="order-summary">
                    <h3>ĐƠN HÀNG</h3>
                    <div class="order-items">
                        <?php
                            $tong_tien = 0;
                            $items = [];
                            $selected = [];

                            if (!empty($_SESSION['order_items']) && !empty($_SESSION['order_selected'])) {
                                $items = $_SESSION['order_items'];
                                $selected = $_SESSION['order_selected'];
                            } elseif (!empty($_SESSION['order_product'])) {
                                $items = $_SESSION['order_product'];
                                $selected = array_keys($items);
                            }
                            foreach ($selected as $product_id) {
                                if (!isset($items[$product_id])) continue;
                                
                                $item = $items[$product_id];
                                $image = htmlspecialchars($item['image']);
                                $name = htmlspecialchars($item['name']);
                                $price = (int)$item['price'];
                                $quantity = (int)$item['quantity'];
                                $total = $price * $quantity;
                                $tong_tien += $total;

                                echo '
                                <div class="order-item">
                                    <img src="assets/image/'.$image.'" alt="anh">
                                    <div class="item-details">
                                        <p>'.$name.'</p>
                                        <div class="quantity-control">
                                            <button class="sl decrease-qty" type="button">-</button>
                                            <input type="number"  name="items['. $product_id .'][quantity]" class="item-qty" value="'.$quantity.'" min="1">
                                            <button class="sl increase-qty" type="button">+</button>
                                        </div>
                                    </div>
                                    <div class="item-price" data-price="'.$price.'">'.number_format($price, 0, ',', '.').'đ </div>
                                    <button class="remove-item">x</button>

                                    <input type="hidden" name="items['. $product_id .'][name]" value="'. $name .'">
                                    <input type="hidden" name="items['. $product_id .'][price]" value="'. $price .'">
                                    <input type="hidden" name="items['. $product_id .'][image]" value="'. $image .'">
                                </div>';
                            }
                        ?>
                    </div>
                    <div class="order-totals">
                        <p>Giảm giá <span>-0₫</span></p>
                        <p>Phí giao hàng <span>Miễn phí</span></p>
                        <p class="total-amount">Tổng tiền 
                            <span id="total-price">
                                <?php echo !empty($selected) ? number_format($tong_tien, 0, ',', '.') : '0'; ?>đ
                            </span>  
                        </p>
                    </div>
                    <button class="add-more-products" name="btn-select-add" type="button" onclick="window.location.href='index.php'">Chọn thêm sản phẩm khác</button>
                </div>
        
                <div class="payment-method">
                    <h3>PHƯƠNG THỨC THANH TOÁN</h3>
                    <div class="radio-option">
                        <input type="radio" id="cod" name="payment" value="COD" checked>
                        <label>Thanh toán khi nhận hàng (COD)</label>
                    </div>
                </div>
                <input type="hidden" name="total_price" value="<?php echo $tong_tien; ?>">
            
                <button class="order_button" name="order_button" onclick="return confirm('Đồng ý xác nhận')">XÁC NHẬN ĐƠN HÀNG</button> 
            </form>
        </div>
    </main>

    <script>
        // Hàm định dạng tiền tệ
        function formatCurrency(number) {
            return number.toLocaleString('vi-VN') + 'đ';
        }
        // Hàm tính tổng tiền tất cả sản phẩm
        function updateTotalPrice() {
            let totalprice = 0;
            document.querySelectorAll('.order-item').forEach(function (item) {
                const qty = parseInt(item.querySelector('.item-qty').value);
                const price = parseFloat(item.querySelector('.item-price').dataset.price);
                totalprice += qty * price;
            });

            document.getElementById('total-price').textContent = formatCurrency(totalprice);
            document.querySelector('input[name="total_price"]').value = totalprice;

        }
        // Lặp qua tất cả các sản phẩm
        document.querySelectorAll('.order-item').forEach(function (item) {
            const decreaseBtn = item.querySelector('.decrease-qty');
            const increaseBtn = item.querySelector('.increase-qty');
            const qtyInput = item.querySelector('.item-qty');
            const priceInput = item.querySelector('.item-price');
            const unitPrice = parseInt(priceInput.dataset.price);

            function updatePrice() {
                let qty = parseInt(qtyInput.value);
                if (isNaN(qty) || qty < 1) qty = 1;
                priceInput.textContent = formatCurrency(qty * unitPrice);
                updateTotalPrice();
            }


            decreaseBtn.addEventListener('click', function (e) {
                e.preventDefault();
                let qty = parseInt(qtyInput.value);
                if (qty > 1) {
                    qtyInput.value = qty - 1;
                    updatePrice();
                }
            });

            increaseBtn.addEventListener('click', function (e) {
                e.preventDefault();
                qtyInput.value = parseInt(qtyInput.value) + 1;
                updatePrice();
            });

            qtyInput.addEventListener('input', updatePrice);
        });

        document.querySelectorAll('.remove-item').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const item = btn.closest('.order-item');
                item.remove(); // Xóa khỏi DOM => không gửi input khi submit
                updateTotalPrice(); // Cập nhật tổng
            });
        });

    </script>

</body>
</html>