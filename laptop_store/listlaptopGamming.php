<?php session_start(); ?>
<?php require_once('settings/config.php'); ?>

<?php require_once('header.php'); ?>
<main>
    <div class="width-page_use">
        <section class="product">
            <h2 style="margin-bottom: 20px;">Laptop Gaming</h2>
            <div class="product__list">
                <?php
                    // Truy vấn sản phẩm laptop gaming bằng JOIN
                    $sql_laptop = "
                        SELECT p.product_id, p.product_name, p.price, p.image
                        FROM Products p
                        JOIN Categories c ON p.category_id = c.category_id
                        WHERE c.category_id = '1'
                    ";
                    $result_laptops = $conn->query($sql_laptop);

                    if ($result_laptops->num_rows > 0) {
                        while($product = $result_laptops->fetch_assoc()) {
                            $id = $product['product_id'];
                            $image = $product['image'];
                            $name = $product['product_name'];
                            $price = $product['price'];

                            echo '<article class="product-card">';
                            echo '    <a href="detailProduct.php?id='.$id.'" class="product-card_link">';
                            echo '        <img src="assets/image/' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($name) . '" class="product-card__image">';
                            echo '        <div class="product-card__describe">';
                            echo '            <h4 class="product-card__name">' . htmlspecialchars($name) . '</h4>';
                            echo '            <p class="product-card__price">' . number_format($price) . 'đ</p>'; 
                            echo '        </div>';
                            echo '        <div class="product-card__review">';
                            echo '            <i class="fa-solid fa-star"></i>';
                            echo '            <i class="fa-solid fa-star"></i>';
                            echo '            <i class="fa-solid fa-star"></i>';
                            echo '            <i class="fa-solid fa-star"></i>';
                            echo '            <i class="fa-solid fa-star"></i>';
                            echo '        </div>';
                            echo '    </a>';
                            echo '</article>';
                        }
                    } else {
                        echo '<p>Không có sản phẩm Laptop Gaming nào.</p>';
                    }
                ?>
            </div>
        </section>
    </div>
</main>

<?php require_once('footer.php'); ?>
</body>
</html>
