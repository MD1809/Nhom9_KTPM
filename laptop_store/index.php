<?php session_start(); ?>
<?php require_once('../laptop_store/settings/config.php'); ?>


<?php require_once('header.php') ?>
    <main>
        <div class="width-page_use">
            <?php
                require_once('filter_handler.php');
                // Nếu có từ khóa tìm kiếm
                if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
                    $keyword = "%" . trim($_GET['keyword']) . "%";

                    $stmt = $conn->prepare("SELECT product_id, product_name, price, image FROM products WHERE product_name LIKE ?");
                    $stmt->bind_param("s", $keyword);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    echo '<h2 class="product__title" style="margin: 40px 0 30px 0";>Kết quả tìm kiếm</h2>';
                    echo '<div class="product__list">';

                    if ($result->num_rows > 0) {
                        while($product = $result->fetch_assoc()) {
                            $id = $product['product_id'];
                            $image = $product['image'];
                            $name = $product['product_name'];
                            $price = $product['price'];

                            echo '<article class="product-card">
                                    <a href="detailProduct.php?id=' . $id . '" class="product-card_link">
                                        <img src="assets/image/' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($name) . '" class="product-card__image">
                                        <div class="product-card__describe">
                                            <h4 class="product-card__name">' . htmlspecialchars($name) . '</h4>
                                            <p class="product-card__price">' . number_format($price) . 'đ</p>
                                        </div>
                                        <div class="product-card__review">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                    </a>
                                </article>';
                        }
                    } else {
                        echo '<p>Không tìm thấy sản phẩm phù hợp.</p>';
                    }
                    echo '</div>';
                } else {
                    echo'
                    <div class="container-main">
                        <form action="" method="get">
                            <div class="sidebar">
                            <ul>
                                <li>
                                    <strong>Hãng</strong>
                                    <ul class="sidebar-brand">
                                        <div class="brand-column">
                                            <li><input type="radio" name="brand" value="Dell"> Dell</li>
                                            <li><input type="radio" name="brand" value="Asus"> Asus</li>
                                            <li><input type="radio" name="brand" value="Acer"> Acer</li>
                                        </div>
                                        <div class="brand-column">
                                            <li><input type="radio" name="brand" value="Lenovo"> Lenovo</li>
                                            <li><input type="radio" name="brand" value="Hp"> Hp</li>
                                            <li><input type="radio" name="brand" value="Macbook"> Macbook</li>
                                        </div>
                                    </ul>
                                </li>
                                <li>
                                    <strong>Phân khúc</strong>
                                    <ul>
                                        <li><input type="radio" name="price" value="0-10"> Dưới 10 triệu</li>
                                        <li><input type="radio" name="price" value="10-15"> Từ 10 đến 15 triệu</li>
                                        <li><input type="radio" name="price" value="15-25"> Từ 15 đến 25 triệu</li>
                                        <li><input type="radio" name="price" value="25+"> Trên 25 triệu</li>
                                    </ul>
                                </li>
                                <li>
                                    <strong>Nhu cầu sử dụng</strong>
                                    <ul>
                                        <li><input type="radio" name="purpose" value="Laptop Văn phòng"> Văn phòng</li>
                                        <li><input type="radio" name="purpose" value="Laptop Gamming"> Gamming</li>
                                    </ul>
                                </li>
                            </ul>
                            <div class="action-search">
                                <button type="submit" name="filter_submit" value="1">Tìm kiếm</button>
                            </div>
                        </div>
                        </form>
                        <div class="main-banner">

                        </div>
                        <div class="right-banner">
                            <h3>Sản phẩm mới</h3>
                            <div class="product-new">';
                                $products_new = "SELECT 
                                                    product_id,
                                                    product_name,
                                                    price,
                                                    image
                                                FROM 
                                                    Products
                                                ORDER BY 
                                                    product_id DESC
                                                LIMIT 3";
                                $result_products_new = $conn->query($products_new);
                                if ($result_products_new->num_rows > 0) {
                                    while($product_new = $result_products_new->fetch_assoc()) {
                                        $id_new = $product_new['product_id'];
                                        $image_new = $product_new['image'];
                                        $name_new = $product_new['product_name'];
                                        $price_new = $product_new['price'];
                                        echo'
                                        <a href="detailProduct.php?id=' . $id_new . '" class="product-new_link">
                                            <img src="assets/image/' . htmlspecialchars($image_new) . '" alt="' . htmlspecialchars($name_new) . '">
                                            <div>
                                                <h4 class="productnew-card__name">' . htmlspecialchars($name_new) . '</h4>
                                                <p class="productnew-card__price">' . number_format($price_new) . 'đ</p>
                                            </div>
                                        </a>';
                                    }
                                }
                            echo'
                            </div>
                        </div>
                    </div>';
                    echo'
                    <section class="product-bestsell">
                        <h2 class="product__title">Top sản phẩm bán chạy nhất</h2>
                        <div class="warpper">
                            <div class="product-bestsell__list">';
                                $products_bestsell = "SELECT 
                                                        p.product_id,
                                                        p.product_name,
                                                        p.image,
                                                        p.price,
                                                        SUM(od.quantityOrdered) AS total_sold
                                                    FROM 
                                                        Orderdetails od
                                                    JOIN 
                                                        Orders o ON od.order_id = o.order_id
                                                    JOIN 
                                                        Products p ON od.product_id = p.product_id
                                                    WHERE 
                                                        o.status = 'Hoàn thành'
                                                    GROUP BY 
                                                        p.product_id, p.product_name
                                                    ORDER BY 
                                                        total_sold DESC
                                                        LIMIT 10";
                                $result_products_bestsell = $conn->query($products_bestsell);
                                if ($result_products_bestsell->num_rows > 0) {
                                    while($products_bestsell = $result_products_bestsell->fetch_assoc()) {
                                        $id_bestsell = $products_bestsell['product_id'];
                                        $image_bestsell = $products_bestsell['image'];
                                        $name_bestsell = $products_bestsell['product_name'];
                                        $price_bestsell = $products_bestsell['price'];
                                        echo'
                                        <article class="product-card-bestsell"> 
                                            <a href="detailProduct.php?id=' . $id_bestsell . '" class="product-card_link">
                                                <img src="assets/image/' . htmlspecialchars($image_bestsell) . '" alt="' . htmlspecialchars($name_bestsell) . '" class="product-card__image">
                                                <div class="product-card__describe">
                                                    <h4 class="product-card__name">' . htmlspecialchars($name_bestsell) . '</h4>
                                                    <p class="product-card__price">' . number_format($price_bestsell) . 'đ</p>
                                                </div>
                                                <div class="product-card__review">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                </div>                                              
                                            </a>
                                        </article>';
                                    }
                                }
                            echo'</div>';
                        echo'
                        </div>
                        <div class="changeslide">
                            <button class="btn-change btn-change-prev">&lt;</button>
                            <button class="btn-change btn-change-next">&gt;</button>
                        </div>
                    </section>';

                    echo'
                    <section class="product">
                        <h2 class="product__title">Laptop Gamming</h2>
                        <div class="warpper-gamming">
                            <div class="product__list">';
                            $sql_laptop = "SELECT product_id, product_name, price, image
                                            FROM products 
                                            WHERE category_id = 1 
                                            LIMIT 10";
                            $result_laptops = $conn->query($sql_laptop);
                            if ($result_laptops->num_rows > 0) {
                                while($product = $result_laptops->fetch_assoc()) {
                                    $id = $product['product_id'];
                                    $image = $product['image'];
                                    $name = $product['product_name'];
                                    $price = $product['price'];
                                    echo'
                                    <article class="product-card"> 
                                        <a href="detailProduct.php?id=' . $id . '" class="product-card_link">
                                            <img src="assets/image/' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($name) . '" class="product-card__image">
                                            <div class="product-card__describe">
                                                <h4 class="product-card__name">' . htmlspecialchars($name) . '</h4>
                                                <p class="product-card__price">' . number_format($price) . 'đ</p>
                                            </div>
                                            <div class="product-card__review">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                            </div>
                                        </a>
                                    </article>';
                                }
                            }
                            echo'</div>';
                        echo '</div>';
                    echo '</section>';

                    echo'
                    <section class="product">
                        <h2 class="product__title">Laptop văn phòng</h2>
                        <div class="warpper-office">
                            <div class="product__list">';
                            $sql_laptop_o = "SELECT product_id, product_name, price, image
                                            FROM products 
                                            WHERE category_id = 2 
                                            LIMIT 10";
                            $result_laptops_o = $conn->query($sql_laptop_o);
                            if ($result_laptops_o->num_rows > 0) {
                                while($product_o = $result_laptops_o->fetch_assoc()) {
                                    $id_o = $product_o['product_id'];
                                    $image_o = $product_o['image'];
                                    $name_o = $product_o['product_name'];
                                    $price_o = $product_o['price'];
                                    echo'
                                    <article class="product-card"> 
                                        <a href="detailProduct.php?id=' . $id_o . '" class="product-card_link">
                                            <img src="assets/image/' . htmlspecialchars($image_o) . '" alt="' . htmlspecialchars($name_o) . '" class="product-card__image">
                                            <div class="product-card__describe">
                                                <h4 class="product-card__name">' . htmlspecialchars($name_o) . '</h4>
                                                <p class="product-card__price">' . number_format($price_o) . 'đ</p>
                                            </div>
                                            <div class="product-card__review">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                            </div>
                                        </a>
                                    </article>';
                                }
                            }
                            echo'</div>';
                        echo '</div>';
                    echo '</section>';
                }
            ?> 
        </div>
    </main>
    <?php require_once('footer.php') ?>
    <script>
        const list_slide = document.querySelector(".product-bestsell__list");
        const list_card = document.querySelectorAll(".product-card-bestsell");

        const btn_prev = document.querySelector(".btn-change-prev");
        const btn_next = document.querySelector(".btn-change-next");

        const cardPerPage = 5;
        let totalcard = list_card.length; 
        let currentIndex = 0;
        let widthcard = 224+16;
        function change(){
            if((currentIndex+1)*cardPerPage >= totalcard){
                currentIndex = 0;
            }else{
                currentIndex++;
            }

            let offset = currentIndex * cardPerPage * widthcard;

            list_slide.style.transform = `translateX(-${offset}px)`;
        }

        list_slide.addEventListener("mouseenter", ()=>clearInterval(autochangeslide));
        list_slide.addEventListener("mouseleave", ()=>{
            autochangeslide = setInterval(() => {change()}, 4000);
        });

        let autochangeslide = setInterval(() => {
            change();
        }, 4000);

        // prev
        btn_prev.addEventListener("click", ()=>{
            clearInterval(autochangeslide);
            if(currentIndex === 0){
                currentIndex = 0;
                list_slide.style.transform = `translateX(0px)`;
            }else{
                currentIndex--;
                let offset = currentIndex * cardPerPage * widthcard;
                list_slide.style.transform = `translateX(-${offset}px)`;
            }
            autochangeslide = setInterval(() => {
               change();
            }, 4000);
        });
        // next
        btn_next.addEventListener("click", ()=>{
            clearInterval(autochangeslide);
            change();
            autochangeslide = setInterval(() => {
               change();
            }, 4000);
        });

    </script>
</body>
</html>