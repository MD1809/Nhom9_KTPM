<?php session_start(); ?>
<?php require_once("../settings/config.php") ?>

<?php
    $sql = "SELECT product_id, product_name, price, image FROM products ORDER BY product_id DESC";
    $result = $conn->query($sql);
    if(($result->num_rows > 0)):
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/styleAd.css">
    <title>Quản Lý Shop</title>
</head>
<body>

<header>
    <h1>Quản trị Laptop Store</h1>
</header>

<div class="container">
    <nav class="sidebar">
        <h2>Menu Admin</h2>
        <a href="Ad_index.php">Trang chủ</a>
        <a href="Ad_Products.php">Quản lý sản phẩm</a>
        <a href="Ad_order.php">Quản lý đơn hàng</a>
        <a href="Ad_users.php">Quản lý người dùng</a>
        <a href="../logout.php" class="logout" onclick="return confirm('Bạn có chắc muốn Đăng xuất tài khoản?')">Đăng xuất</a>
    </nav>

    <main class="main-content">
        <div class="card">
            <h2>Quản lý sản phẩm</h2>
        </div>

        <div class="card">
            <div class="add-product">
                <button onclick="location.href='page_add_product.php'">
                    <i class="fa-solid fa-plus"></i>
                    Thêm sản phẩm
                </button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Giá</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($row = $result->fetch_assoc()){
                        echo'
                        <tr>
                            <td>'.$row['product_id'].'</td>
                            <td>'.$row['product_name'].'</td>
                            <td><img src="../assets/image/'.$row['image'].'" alt="ảnh"></td>
                            <td>'. number_format($row['price'], '0', ',', '.') .' ₫</td>
                            <td>
                                <a href="page_edit_product.php?id='.$row['product_id'].'" class="btn edit-btn">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Sửa
                                </a>
                                <a href="../process_Ad/delete_product.php?id='.$row['product_id'].'" class="btn delete-btn" onclick="return confirm(\'Bạn có chắc chắn muốn xoá sản phẩm này không?\');">
                                    <i class="fa-solid fa-eraser"></i> 
                                    Xoá
                                </a>
                            </td>
                        </tr>';
                    }
                endif;
                ?>
            </tbody>
        </table>
    </main>
</div>

</body>
</html>
