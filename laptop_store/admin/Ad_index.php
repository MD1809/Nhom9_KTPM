<?php session_start(); ?>
<?php require_once("../settings/config.php") ?>

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
            <h2>Chào mừng bạn đến trang quản trị!</h2>
        </div>

        <div class="dashboard">

            <?php $orderResult = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");  ?>
            <?php $totalOrders = $orderResult->fetch_assoc()['total_orders'];  ?>
            <div class="card-item">
                <p>Tổng đơn hàng </p>
                <span><?php echo $totalOrders > 0 ? $totalOrders : '0'; ?></span>
            </div>

            <?php $productResult = $conn->query("SELECT COUNT(*) AS total_products FROM products"); ?>
            <?php $totalProducts = $productResult->fetch_assoc()['total_products']; ?>
            <div class="card-item">
                <p>Tổng sản phẩm </p>
                <span><?php echo $totalProducts > 0 ? $totalProducts : '0'; ?></span>
            </div>

            <?php $revenueResult = $conn->query("SELECT SUM(total_amount) AS total_revenue FROM orders WHERE status = 'Hoàn thành'"); ?>
            <?php $totalRevenue = $revenueResult->fetch_assoc()['total_revenue']; ?>
            <div class="card-item">
                <p>Doanh thu</p>
                <span><?php echo $totalRevenue !== null ? number_format($totalRevenue, 0, ',', '.') . '₫' : '0₫'; ?></span>
            </div>

            <?php $userResult = $conn->query("SELECT COUNT(*) AS total_users FROM users"); ?>
            <?php $totalUsers = $userResult->fetch_assoc()['total_users']; ?>
            <div class="card-item">
                <p>Số người dùng</p>
                <span><?php echo $totalUsers > 0 ? $totalUsers : '0'; ?></span>
            </div>
        </div>
    </main>
</div>

</body>
</html>
