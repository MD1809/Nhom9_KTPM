<?php session_start(); ?>
<?php require_once("../settings/config.php"); ?>

<?php
$sql = "SELECT user_id, user_name, phone, role, is_active FROM users ORDER BY user_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Người Dùng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/styleAd.css">
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
                <h2>Quản lý người dùng</h2>
            </div>
            <div class="card">
                <div class="add-user">
                    <button onclick="location.href='../process_Ad/add_user.php'">
                        <i class="fa-solid fa-plus"></i>
                        Thêm tài khoản
                    </button>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên người dùng</th>
                        <th>Số điện thoại</th>
                        <th>Quyền</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <?php
                                $is_active = $row['is_active'];
                            ?>
                            <tr>
                                <td><?= $row['user_id'] ?></td>
                                <td><?= htmlspecialchars($row['user_name']) ?></td>
                                <td><?= htmlspecialchars($row['phone']) ?></td>
                                <td><?= htmlspecialchars($row['role']) ?></td>
                                <td>
                                    <select class="btn btn_select"
                                            onchange="confirmChange(this, <?= $row['user_id'] ?>)"
                                            data-current="<?= $is_active ?>">
                                        <option value="1" <?= $is_active == 1 ? 'selected' : '' ?>>Hoạt động</option>
                                        <option value="0" <?= $is_active == 0 ? 'selected' : '' ?>>Vô hiệu hóa</option>
                                    </select>
                                    <a class="delete-user" href="../process_Ad/delete_user.php?user_id=<?php echo $row['user_id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này không?');">
                                        <i class="fa-solid fa-eraser"></i>
                                        Xóa
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">Không có người dùng nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>

    <script>
        function confirmChange(selectElement, userId) {
            const statusText = selectElement.value === '1' ? 'Hoạt động' : 'Vô hiệu hóa';

            if (confirm(`Bạn có chắc chắn muốn thay đổi trạng thái sang "${statusText}" không?`)) {
                const newValue = selectElement.value;
                selectElement.style.backgroundColor = newValue === '1' ? '#02a502' : '#e74c3c';
                window.location.href = `../process_Ad/change_isactive.php?id=${userId}&isactive=${newValue}`;
            } else {
                const currentValue = selectElement.getAttribute('data-current');
                selectElement.value = currentValue;
                selectElement.style.backgroundColor = currentValue === '1' ? '#02a502' : '#e74c3c';
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            const selects = document.querySelectorAll('.btn_select');
            selects.forEach(select => {
                const value = select.value;
                select.style.backgroundColor = value === '1' ? '#02a502' : '#e74c3c';
            });
        });
    </script>
    <?php
    // Kiểm tra nếu có thông báo trong session
    if (isset($_SESSION['alert_message'])) {
        $message = htmlspecialchars($_SESSION['alert_message'], ENT_QUOTES, 'UTF-8');
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
        unset($_SESSION['alert_message']); 
    }
    ?>
</body>
</html>
