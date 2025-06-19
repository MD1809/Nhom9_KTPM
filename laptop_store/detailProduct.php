<?php session_start(); ?>
<?php require_once('../laptop_store/settings/config.php'); ?>

<?php $product_id = $_GET['id'] ?? null; ?>

<?php
// Xử lý khi người dùng gửi đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['id'])) {
        echo "<script>alert('Bạn cần đăng nhập để gửi đánh giá.');</script>";
    } else {
        $user_id = $_SESSION['id'];
        $comment = trim($_POST['comment']);

        if ($comment !== '') {
            $stmt = $conn->prepare("INSERT INTO ProductReviews (comment, product_id, user_id) VALUES (?, ?, ?)");
            $stmt->bind_param("sii", $comment, $product_id, $user_id);
            if ($stmt->execute()) {
                echo "<script>alert('Đánh giá đã được lưu.'); window.location.href = 'detailProduct.php?id=$product_id';</script>";
                exit;
            } else {
                echo "<script>alert('Có lỗi xảy ra khi lưu đánh giá.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Vui lòng nhập nội dung đánh giá.');</script>";
        }
    }
}
?>

<?php
    // lấy dữ liệu từ cơ sở 
    $sql_detailproduct = " SELECT 
                            p.product_id,
                            p.product_name,
                            p.price,
                            p.quantityInStock,
                            p.image,
                            p.description,
                            b.brand_name,
                            c.category_name,
                            s.cpu,
                            s.ram,
                            s.storage,
                            s.screen,
                            s.gpu,
                            s.os
                        FROM Products p
                        LEFT JOIN Brands b ON p.brand_id = b.brand_id
                        LEFT JOIN Categories c ON p.category_id = c.category_id
                        LEFT JOIN Specifications s ON p.spec_id = s.spec_id
                        WHERE p.product_id = $product_id";
    $result_detailproduct = $conn->query($sql_detailproduct);
    if ($result_detailproduct->num_rows > 0) {
        while($line_detailproduct = $result_detailproduct->fetch_assoc()){
            $detail_id       = $line_detailproduct['product_id'];
            $detail_name     = $line_detailproduct['product_name'];
            $detail_price    = $line_detailproduct['price'];
            $detail_stock    = $line_detailproduct['quantityInStock'];
            $detail_image    = $line_detailproduct['image'];
            $detail_descri   = $line_detailproduct['description'];
            $detail_brand    = $line_detailproduct['brand_name'];
            $detail_category = $line_detailproduct['category_name'];
            $detail_cpu      = $line_detailproduct['cpu'];
            $detail_ram      = $line_detailproduct['ram'];
            $detail_storage  = $line_detailproduct['storage'];
            $detail_screen   = $line_detailproduct['screen'];
            $detail_gpu      = $line_detailproduct['gpu'];
            $detail_os       = $line_detailproduct['os'];
        }
    }

?>
<?php require_once('header.php') ?>
    <main>
        <div class="container-detailProduct width-page_use">
            <div class="detailProduct">
                <div class="detailProduct_image">
                    <img class="detailProduct_image--item" src="assets/image/<?php echo $detail_image ?>" alt="<?php echo $detail_name ?>">
                </div>
                <div class="detailProduct_info">
                    <div class="product-info">
                        <h2 class="detailProduct_name"><?php echo $detail_name ?></h2>
                        <div class="detailProduct_price">Giá: <?php echo number_format($detail_price, 0, ',', '.') . ' đ'; ?></div>
                        <div class="detailProduct_brands">Hãng: <?php echo $detail_brand ?> </div>
                        <div class="detailProduct_">Sản phẩm: Mới 100% </div>
                        <div class="detailProduct_stocks">Kho còn: <?php echo $detail_stock ?> sản phẩm</div>
                    </div>
                    <div class="detailProduct-quantity">
                        <button class="change-qty decrease-qty">-</button>
                        <input type="number" name="slProduct" id="soluong" min="1" value="1">
                        <button class="change-qty increase-qty">+</button>
                    </div>
                    <form action="add_to_cart.php" method="post" class="btn__action">
                        <input type="hidden" name="product_id" value="<?php echo $detail_id ?>">
                        <input type="hidden" name="product_name" value="<?php echo $detail_name ?>">
                        <input type="hidden" name="product_price" value="<?php echo $detail_price ?>">
                        <input type="hidden" name="product_image" value="<?php echo $detail_image ?>">
                        <input type="hidden" name="quantity" id="quantityInput" value="1">
                        <input type="hidden" name="return_url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                        <button type="submit" name="addcart" id="addcart" value="addcart">Thêm vào giỏ hàng</button>
                        <button type="submit" name="order" id="order" value="order">Mua ngay</button>
                    </form>
                </div>
            </div>
            <div class="describe-product">
                <div class="change_describe">
                    <div class="des btn-describe select-des">Mô tả</div>
                    <div class="des btn-specification">Thông số kỹ thuật</div>
                    <div class="des btn-review">đánh giá</div>
                </div>
                <div class="content-common describe-content describe-action">
                    <?php echo '<p>'. nl2br(htmlspecialchars($detail_descri)) .'</p>';?>
                </div>
                <div class="content-common specification-content">
                    <table class="table-spec">
                        <tr>
                            <th>CPU</th>
                            <td><?php echo $detail_cpu ?></td>
                        </tr>
                        <tr>
                            <th>RAM</th>
                            <td><?php echo $detail_ram ?></td>
                        </tr>
                        <tr>
                            <th>Bộ nhớ</th>
                            <td><?php echo $detail_storage ?></td>
                        </tr>
                        <tr>
                            <th>Màn hình</th>
                            <td><?php echo $detail_screen ?></td>
                        </tr>
                        <tr>
                            <th>GPU</th>
                            <td><?php echo $detail_gpu ?></td>
                        </tr>
                        <tr>
                            <th>Windows</th>
                            <td><?php echo $detail_os ?></td>
                        </tr>
                    </table>
                </div>
                <div class="content-common review-content">
                    <div class="container-review">
                        <div class="list_review">
                            <?php
                            $sql_review = "SELECT 
                                                pr.comment,
                                                pr.review_date,
                                                u.user_name
                                            FROM 
                                                ProductReviews pr
                                            JOIN 
                                                Users u ON pr.user_id = u.user_id
                                            WHERE 
                                                pr.product_id = ? 
                                            ORDER BY 
                                                pr.review_date DESC";
                            $stmt = $conn->prepare($sql_review);
                            $stmt->bind_param("i", $product_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $user = htmlspecialchars($row['user_name']);
                                    $comment = nl2br(htmlspecialchars($row['comment']));
                                    $date = date("d/m/Y H:i", strtotime($row['review_date']));

                                    echo "<div class='review_item'>";
                                    echo "<strong>$user</strong> - <em>$date</em><br>";
                                    echo "<p>$comment</p>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<p>Chưa có đánh giá nào cho sản phẩm này.</p>";
                            }
                            $stmt->close();
                            ?>
                        </div>
                        <div class="add_review">
                            <h3 class="add_review-title">Thêm đánh giá mới</h3>
                            <form action="" method="post">
                                <textarea class="review-input" name="comment" rows="4" required placeholder="viết đánh giá tại đây"></textarea>
                                <div class="btn-addreview">
                                    <button type="submit" name="submit_review" class="btn-add-review">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="footer">
        <div class="footer__container">
            <!-- Cột: Thông tin cửa hàng -->
            <div class="footer__column">
                <div class="footer__about">
                    <h3 class="footer__title">Laptop ABC</h3>
                    <p class="footer__text">Chuyên cung cấp các dòng laptop chất lượng, uy tín, bảo hành chính hãng toàn quốc.</p>
                    <p class="footer__text">Địa chỉ: 123 XXXX, Hà Nội</p>
                    <p class="footer__text">Hotline: 0909 000 000</p>
                    <p class="footer__text">Email: support@laptopabc.vn</p>
                </div>
            </div>
            <!-- Cột: Liên kết nhanh -->
            <div class="footer__column">
                <div class="footer__links">
                    <h3 class="footer__title">Liên kết</h3>
                    <div class="footer__list">
                    <ul class="footer__link">
                        <li><a href="index.html" class="footer__link--item">Trang chủ</a></li>
                        <li><a href="products-laptop.html" class="footer__link--item">Laptop</a></li>
                        <li><a href="products-phukien.html" class="footer__link--item">Phụ kiện</a></li>
                    </ul>
                    <ul class="footer__link">
                        <li><a href="#" class="footer__link--item">Tin tức</a></li>
                        <li><a href="#" class="footer__link--item">Liên hệ</a></li>
                    </ul>
                    </div>
                </div>
            </div>
            <!-- Cột: Mạng xã hội -->
            <div class="footer__column-chat">
                <div class="footer__feedback">
                    <input class="footer__feedback--text" type="text" placeholder="Đóng góp của bạn">
                    <button class="footer__feedback--btn">
                        <span>Góp ý</span>
                    </button>
                </div>
                <div class="footer__social">
                    <h3 class="footer__title">Kết nối với chúng tôi qua:
                        <a href="#" class="footer__social-link">Facebook</a>
                    </h3>
                </div>
            </div>
        </div>
        <!-- Copyright -->
        <div class="footer__bottom">
            <p class="footer__copyright">© 2025 Laptop ABC. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const describe_content = document.querySelector(".describe-content");
        const specification_content = document.querySelector(".specification-content");
        const review_content = document.querySelector(".review-content");

        const btn_describe = document.querySelector(".btn-describe");
        const btn_specification = document.querySelector(".btn-specification");
        const btn_review = document.querySelector(".btn-review");

        btn_describe.addEventListener("click", ()=>{
            specification_content.classList.remove('describe-action');
            review_content.classList.remove('describe-action');
            describe_content.classList.add('describe-action');

            btn_specification.classList.remove('select-des');
            btn_review.classList.remove('select-des');
            btn_describe.classList.add('select-des');
        });

        btn_specification.addEventListener("click", ()=>{
            describe_content.classList.remove('describe-action');
            review_content.classList.remove('describe-action');
            specification_content.classList.add('describe-action');
            
            btn_describe.classList.remove('select-des');
            btn_review.classList.remove('select-des');
            btn_specification.classList.add('select-des');
        });

        btn_review.addEventListener("click", ()=>{
            describe_content.classList.remove('describe-action');
            specification_content.classList.remove('describe-action');
            review_content.classList.add('describe-action');
            
            btn_describe.classList.remove('select-des');
            btn_specification.classList.remove('select-des');
            btn_review.classList.add('select-des');
        });
    </script>
    <script>
        const decreaseBtn = document.querySelector(".decrease-qty");
        const increaseBtn = document.querySelector(".increase-qty");
        const quantityInput = document.getElementById("soluong");

        const getquantity = document.getElementById('quantityInput');

        increaseBtn.addEventListener("click", () => {
            let currentQty = parseInt(quantityInput.value);
            quantityInput.value = currentQty + 1;
            getquantity.value = quantityInput.value;
        });

        decreaseBtn.addEventListener("click", () => {
            let currentQty = parseInt(quantityInput.value);
            if (currentQty > 1) {
                quantityInput.value = currentQty - 1;
                getquantity.value = quantityInput.value;
            }
        });
    </script>
</body>
</html>