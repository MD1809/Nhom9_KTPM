<?php session_start(); ?>
<?php require_once("../settings/config.php"); ?>

<?php
$phone_err = $phone_err_length = $password_err_length = "";
$user_name = $phone = $pass = $role = $is_active = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['adduser_name'];
    $phone     = $_POST['adduser_phone'];
    $pass      = $_POST['adduser_password'];
    $role      = $_POST['adduser_role'];
    $is_active = $_POST['adduser_is_active'];

    // Kiểm tra định dạng số điện thoại
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $phone_err_length = "Số điện thoại phải gồm đúng 10 chữ số.";
    } else {
        // Kiểm tra số điện thoại đã tồn tại
        $checkStmt = $conn->prepare("SELECT user_id FROM users WHERE phone = ?");
        $checkStmt->bind_param("s", $phone);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $phone_err = "Số điện thoại đã tồn tại.";
        }
        $checkStmt->close();
    }
    // Kiểm tra độ dài mật khẩu
    if (strlen($pass) < 6) {
        $password_err_length = "Mật khẩu phải có ít nhất 6 ký tự.";
    }

    // Nếu không có lỗi thì thêm vào CSDL
    if (empty($phone_err) && empty($phone_err_length)) {
        $stmt = $conn->prepare("INSERT INTO users (user_name, phone, pass, role, is_active) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $user_name, $phone, $pass, $role, $is_active);

        if ($stmt->execute()) {
            header("Location: ../admin/Ad_users.php");
            exit();
        } else {
            echo "Lỗi thêm người dùng: " . $stmt->error;
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm người dùng</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/styleAd.css">
</head>
<body>
    <div class="container_form-adduser">
        <h2 class="adduser_title">Thêm người dùng mới</h2>
        <form method="POST">
            <div class="adduser_info_item">
                <label for="adduser_name">Tên người dùng</label>
                <input type="text" name="adduser_name" id="adduser_name" value="<?php echo htmlspecialchars($user_name); ?>" required>
            </div>

            <div class="adduser_info_item">
                <label for="adduser_phone">Số điện thoại</label>
                <input type="text" name="adduser_phone" id="adduser_phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                <?php if (!empty($phone_err)): ?>
                    <span style= "margin: 3px 0 0 4px; color: red; font-size: 14px;"><?php echo $phone_err; ?></span>
                <?php endif; ?>
                <?php if (!empty($phone_err_length)): ?>
                    <span style="margin: 3px 0 0 4px; color: red; font-size: 14px;"><?php echo $phone_err_length; ?></span>
                <?php endif; ?>
            </div>

            <div class="adduser_info_item">
                <label for="adduser_password">Mật khẩu</label>
                <input type="password" name="adduser_password" id="adduser_password" value="<?php echo htmlspecialchars($pass); ?>" required>
                <?php if (!empty($password_err_length)): ?>
                    <span style="margin: 3px 0 0 4px; color: red; font-size: 14px;"><?php echo $password_err_length; ?></span>
                <?php endif; ?>
            </div>

            <div class="adduser_info_item">
                <label for="adduser_role">Vai trò</label>
                <select name="adduser_role" id="adduser_role" value="<?php echo htmlspecialchars($role); ?>" required>
                    <option value="Khách hàng">Khách hàng</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>

            <div class="adduser_info_item">
                <label for="adduser_is_active">Trạng thái</label>
                <select name="adduser_is_active" id="adduser_is_active" value="<?php echo htmlspecialchars($is_active); ?>" required>
                    <option value="1">Hoạt động</option>
                    <option value="0">Vô hiệu hóa</option>
                </select>
            </div>

            <div class="adduser_info_item">
                <button type="adduser_submit">Thêm người dùng</button>
                <a href="../admin/Ad_users.php" style="margin-left: 10px;">Quay lại</a>
            </div>
        </form>
    </div>
</body>
</html>
