<?php
    if (isset($_GET['filter_submit']) && $_GET['filter_submit'] == '1'){

        $where = [];
        $joins = [];
        
        if (!empty($_GET['brand'])) {
            $brand = $conn->real_escape_string($_GET['brand']);
            $joins[] = "INNER JOIN Brands ON Products.brand_id = Brands.brand_id";
            $where[] = "Brands.brand_name = '$brand'";
        }
        
        if (!empty($_GET['purpose'])) {
            $category = $conn->real_escape_string($_GET['purpose']);
            $joins[] = "INNER JOIN Categories ON Products.category_id = Categories.category_id";
            $where[] = "Categories.category_name = '$category'";
        }
        
        if (!empty($_GET['price'])) {
            $price = $_GET['price'];
            if ($price == '0-10') {
                $where[] = "Products.price < 10000000";
            } elseif ($price == '10-15') {
                $where[] = "Products.price BETWEEN 10000000 AND 15000000";
            } elseif ($price == '15-25') {
                $where[] = "Products.price BETWEEN 15000000 AND 25000000";
            } elseif ($price == '25+') {
                $where[] = "Products.price > 25000000";
            }
        }
        
        $filter = "SELECT Products.*
                FROM Products 
                " . implode(' ', array_unique($joins));
        
        if (!empty($where)) {
            $filter .= " WHERE " . implode(' AND ', $where);
        }
        
        $result_filter = $conn->query($filter);
        
        // Hiển thị sản phẩm
        echo'
        <h2>Kết quả lọc sản phẩm:</h2>
        <div class="warpper-filter">
            <div class="product__list-filter">';
                if ($result_filter->num_rows > 0) {
                    while($product_filter = $result_filter->fetch_assoc()) {
                        $id_filter = $product_filter['product_id'];
                        $image_filter = $product_filter['image'];
                        $name_filter = $product_filter['product_name'];
                        $price_filter = $product_filter['price'];
                        echo'
                        <article class="product-card"> 
                            <a href="detailProduct.php?id=' . $id_filter . '" class="product-card_link">
                                <img src="assets/image/' . htmlspecialchars($image_filter) . '" alt="' . htmlspecialchars($name_filter) . '" class="product-card__image">
                                <div class="product-card__describe">
                                    <h4 class="product-card__name">' . htmlspecialchars($name_filter) . '</h4>
                                    <p class="product-card__price">' . number_format($price_filter) . 'đ</p>
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
                    echo "Không tìm thấy sản phẩm phù hợp.";
                }
            echo'</div>';
        echo'</div>';
        exit();
    }
?>
