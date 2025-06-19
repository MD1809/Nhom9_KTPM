<?php session_start(); ?>
<?php require_once("../settings/config.php") ?>

<?php
    $sql = "
    SELECT 
        o.order_id,
        o.order_date,
        o.status,
        o.total_amount,
        ua.recipient_name,
        ua.phone
    FROM Orders o
    JOIN UserAddresses ua ON o.address_id = ua.address_id
    ORDER BY o.order_id DESC";
    $result = $conn->query($sql);
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
                <h2>Quản lý đơn hàng</h2>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(($result->num_rows > 0)){
                            while($row = $result->fetch_assoc()){
                                echo'
                                <tr>
                                    <td>' . $row['order_id'] . '</td>
                                    <td>' . $row['recipient_name'] . '</td>
                                    <td>' . $row['phone'] . '</td>
                                    <td>' . number_format($row['total_amount'], 0, ',', '.') . '₫' . '</td>
                                    <td>' . $row['order_date'] . '</td>
                                    <td>' . $row['status'] . '</td>
                                    <td>';
                                        if($row['status'] != "Hủy"){
                                            if($row['status'] == "Hoàn thành"){
                                                echo '<a href="page_detail_order.php?id='. $row['order_id'] .'" class="btn view-btn">Chi tiết</a>';
                                            }else{
                                                echo '<a href="../process_Ad/success_order.php?id='. $row['order_id'] .'" class="btn success-btn" onclick="return confirm(\'Xác nhận giao hàng thành công\')">Hoàn thành</a>';
                                                echo '<a href="page_detail_order.php?id='. $row['order_id'] .'" class="btn view-btn">Chi tiết</a>';
                                            }
                                        }
                                    echo'</td>
                                </tr>';
                            }
                        }else{
                            echo'<tr><td colspan="7">'. "Không có sản phẩm nào" .'</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
        </main>
    </div>

</body>
</html>
