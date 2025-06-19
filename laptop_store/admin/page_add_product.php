<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/styleAd.css">
</head>
<body>
    <form action="../process_Ad/add_Product.php" method="POST" class="container_form" enctype="multipart/form-data">
        <h2>Thông tin sản phẩm</h2>

        <div class="edit_container">
            <div class="info_basic">
                <div class="info_item">
                    <label for="product_name">Tên máy tính</label>
                    <input type="text" name="product_name" id="product_name" required />
                </div>

                <div class="info_item">
                    <label for="price">Giá bán</label>
                    <input type="number" name="price" id="price" required />
                </div>

                <div class="info_item">
                    <label for="quantityInStock">Số lượng tồn kho</label>
                    <input type="number" name="quantityInStock" id="quantityInStock" required />
                </div>

                <div class="info_item">
                    <label for="image">Ảnh sản phẩm (chọn file)</label>
                    <input type="file" name="image" id="image" accept="image/*" required />
                </div>

                <div class="info_item">
                    <label for="brand_id">Thương hiệu</label>
                    <select name="brand_id" id="brand_id" required>
                        <option value="">-- Chọn thương hiệu --</option>
                        <option value="1">Dell</option>
                        <option value="2">Asus</option>
                        <option value="3">Acer</option>
                        <option value="4">Lenovo</option>
                        <option value="5">Hp</option>
                        <option value="6">Macbook</option>
                    </select>
                </div>

                <div class="info_item">
                    <label for="category_id">Danh mục</label>
                    <select name="category_id" id="category_id" required>
                        <option value="">-- Chọn danh mục --</option>
                        <option value="1">Gamming</option>
                        <option value="2">Văn phòng</option>
                    </select>
                </div>
            </div>

            <div class="info_detail">
                <div class="info_item">
                    <label for="cpu">Bộ vi xử lý (CPU)</label>
                    <input type="text" name="cpu" id="cpu" required />
                </div>

                <div class="info_item">
                    <label for="ram">RAM</label>
                    <input type="text" name="ram" id="ram" required />
                </div>

                <div class="info_item">
                    <label for="storage">Ổ cứng</label>
                    <input type="text" name="storage" id="storage" required />
                </div>

                <div class="info_item">
                    <label for="screen">Màn hình</label>
                    <input type="text" name="screen" id="screen" required />
                </div>

                <div class="info_item">
                    <label for="gpu">Card đồ họa (GPU)</label>
                    <input type="text" name="gpu" id="gpu" required />
                </div>

                <div class="info_item">
                    <label for="os">Hệ điều hành (OS)</label>
                    <input type="text" name="os" id="os" required />
                </div>
            </div>
        </div>

        <div class="info_desc">
            <label for="description">Mô tả sản phẩm</label>
            <textarea name="description" id="description" rows="4" required></textarea>
        </div>

        <div class="info_item">
            <button type="submit">Thêm sản phẩm</button>
        </div>
    </form>
</body>
</html>
