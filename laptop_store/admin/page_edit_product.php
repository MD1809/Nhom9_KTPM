<?php session_start(); ?>
<?php require_once("../settings/config.php") ?>

<?php
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    // Lấy thông tin sản phẩm
    $sqlProduct = "SELECT * FROM products WHERE product_id = '$id'";
    $resultProduct = $conn->query($sqlProduct);

    if (!$resultProduct || $resultProduct->num_rows === 0) {
        echo "Không tìm thấy sản phẩm.";
        exit;
    }

    $product = $resultProduct->fetch_assoc();

    // Lấy thông số kỹ thuật
    $sqlSpecs = "
        SELECT 
            s.cpu,
            s.ram,
            s.storage,
            s.screen,
            s.gpu,
            s.os
        FROM Products p
        JOIN Specifications s ON p.spec_id = s.spec_id
        WHERE p.product_id = $id
    ";
    $resultSpecs = $conn->query($sqlSpecs);

    $specs = [];
    if ($resultSpecs && $resultSpecs->num_rows > 0) {
        $row = $resultSpecs->fetch_assoc();
        $specs = [
            'cpu' => $row['cpu'],
            'ram' => $row['ram'],
            'storage' => $row['storage'],
            'screen' => $row['screen'],
            'gpu' => $row['gpu'],
            'os' => $row['os']
        ];
    }
} else {
    echo "Thiếu ID sản phẩm.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/styleAd.css">
</head>
<body>
    <form action="../process_Ad/edit_Product.php" method="POST" class="container_form" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= $id ?>">

        <h2>Thông tin sản phẩm</h2>
        <div class="edit_container">
            <div class="info_basic">
                <div class="info_item">
                    <label for="name">Tên máy tính</label>
                    <input type="text" id="name" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required />
                </div>

                <div class="info_item">
                    <label for="price">Giá</label>
                    <input type="text" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required />
                </div>

                <div class="info_item">
                    <label for="image">Ảnh sản phẩm (chọn ảnh mới nếu muốn thay)</label>
                    <input type="file" id="image" name="image" accept="image/*" />
                    <br>
                    <small>Ảnh hiện tại:</small><br>
                    <img src="../assets/image/<?= htmlspecialchars($product['image']) ?>" alt="Ảnh hiện tại" style="width: 150px; margin-top: 8px;">
                </div>
            </div>

            <div class="info_detail">
                <div class="info_item">
                    <label for="cpu">Bộ vi xử lý (CPU)</label>
                    <input type="text" id="cpu" name="cpu" value="<?= htmlspecialchars($specs['cpu'] ?? '') ?>" required />
                </div>

                <div class="info_item">
                    <label for="ram">Bộ nhớ RAM</label>
                    <input type="text" id="ram" name="ram" value="<?= htmlspecialchars($specs['ram'] ?? '') ?>" required />
                </div>

                <div class="info_item">
                    <label for="storage">Ổ cứng</label>
                    <input type="text" id="storage" name="storage" value="<?= htmlspecialchars($specs['storage'] ?? '') ?>" required />
                </div>

                <div class="info_item">
                    <label for="gpu">Card đồ họa</label>
                    <input type="text" id="gpu" name="gpu" value="<?= htmlspecialchars($specs['gpu'] ?? '') ?>" required />
                </div>

                <div class="info_item">
                    <label for="screen">Màn hình</label>
                    <input type="text" id="screen" name="screen" value="<?= htmlspecialchars($specs['screen'] ?? '') ?>" required />
                </div>

                <div class="info_item">
                    <label for="os">Hệ điều hành</label>
                    <input type="text" id="os" name="os" value="<?= htmlspecialchars($specs['os'] ?? '') ?>" required />
                </div>
            </div>
        </div>

        <div class="info_desc">
            <label for="description">Mô tả sản phẩm</label>
            <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <div class="info_item">
            <button type="submit">Xác nhận</button>
        </div>
    </form>
</body>
</html>
