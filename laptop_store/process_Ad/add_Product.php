<?php session_start(); ?>
<?php require_once('../settings/config.php'); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $name        = $_POST['product_name'];
    $price       = $_POST['price'];
    $description = $_POST['description'];
    $quantity    = $_POST['quantityInStock'];
    $brand_id    = $_POST['brand_id'];
    $category_id = $_POST['category_id'];

    // Thông số kỹ thuật
    $cpu     = $_POST['cpu'];
    $ram     = $_POST['ram'];
    $storage = $_POST['storage'];
    $screen  = $_POST['screen'];
    $gpu     = $_POST['gpu'];
    $os      = $_POST['os'];

    // Xử lý ảnh tải lên
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = basename($_FILES['image']['name']);
        $imageTmp  = $_FILES['image']['tmp_name'];
        $uploadDir = '../assets/image/';

        // Tạo thư mục nếu chưa có
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $targetPath = $uploadDir . $imageName;

        if (move_uploaded_file($imageTmp, $targetPath)) {
            // Thành công - tiếp tục
        } else {
            die("Lỗi khi tải ảnh lên.");
        }
    } else {
        die("Không có ảnh hợp lệ.");
    }

    // Thêm vào bảng specifications
    $stmt_spec = $conn->prepare("INSERT INTO specifications (cpu, ram, storage, screen, gpu, os) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_spec->bind_param("ssssss", $cpu, $ram, $storage, $screen, $gpu, $os);

    if ($stmt_spec->execute()) {
        $spec_id = $stmt_spec->insert_id;

        // Thêm vào bảng products
        $stmt_product = $conn->prepare("INSERT INTO products (product_name, description, price, image, quantityInStock, brand_id, category_id, spec_id) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_product->bind_param("ssdsiiii", $name, $description, $price, $imageName, $quantity, $brand_id, $category_id, $spec_id);

        if ($stmt_product->execute()) {
            header("Location: ../Admin/Ad_Products.php");
            exit();
        } else {
            echo "Lỗi khi thêm sản phẩm: " . $stmt_product->error;
        }

        $stmt_product->close();
    } else {
        echo "Lỗi khi thêm thông số kỹ thuật: " . $stmt_spec->error;
    }

    $stmt_spec->close();
}

$conn->close();
?>
