<?php
session_start();
require_once('../laptop_store/settings/config.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$successMsg = '';
$errorMsg = '';

// Lấy thông tin người dùng hiện tại
$stmt = $conn->prepare("SELECT * FROM Users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Cập nhật thông tin
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_info"])) {
    $new_name = trim($_POST["user_name"]);
    $new_phone = trim($_POST["phone"]);

    // Kiểm tra SĐT trùng
    $checkStmt = $conn->prepare("SELECT user_id FROM Users WHERE phone = ? AND user_id != ?");
    $checkStmt->bind_param("si", $new_phone, $user_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $errorMsg = "Số điện thoại đã được sử dụng.";
    } else {
        $updateStmt = $conn->prepare("UPDATE Users SET user_name = ?, phone = ? WHERE user_id = ?");
        $updateStmt->bind_param("ssi", $new_name, $new_phone, $user_id);
        if ($updateStmt->execute()) {
            $successMsg = "Cập nhật thông tin thành công.";
            $user['user_name'] = $new_name;
            $user['phone'] = $new_phone;
        } else {
            $errorMsg = "Lỗi khi cập nhật thông tin.";
        }
    }
}

// Đổi mật khẩu
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["change_pass"])) {
    $current_pass = $_POST["current_pass"];
    $new_pass = $_POST["new_pass"];
    $confirm_pass = $_POST["confirm_pass"];

    if ($new_pass !== $confirm_pass) {
        $errorMsg = "Mật khẩu mới không trùng khớp.";
    } else {
        // Lấy mật khẩu hiện tại từ DB
        $passSql = "SELECT pass FROM Users WHERE user_id = ?";
        $passStmt = $conn->prepare($passSql);
        $passStmt->bind_param("i", $user_id);
        $passStmt->execute();
        $passResult = $passStmt->get_result();
        $passRow = $passResult->fetch_assoc();

        if (!$passRow["pass"]) {
            $errorMsg = "Mật khẩu hiện tại không đúng.";
        } else {
            $updatePassSql = "UPDATE Users SET pass = ? WHERE user_id = ?";
            $updatePassStmt = $conn->prepare($updatePassSql);
            $updatePassStmt->bind_param("si", $new_pass, $user_id);
            if ($updatePassStmt->execute()) {
                $successMsg = "Đổi mật khẩu thành công.";
            } else {
                $errorMsg = "Đổi mật khẩu thất bại.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            padding: 40px;
            display: flex;
            justify-content: center;
        }

        .card {
            background: #fff;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 500px;
        }

        h2, h3 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
            color: #34495e;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #fdfdfd;
        }

        .form-input:focus {
            border-color: #3498db;
            outline: none;
        }

        .form-button {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 8px;
        }

        .form-button:hover {
            background: #2980b9;
        }

        .toggle-button {
            background: #2ecc71;
        }

        .toggle-button:hover {
            background: #27ae60;
        }

        .alert {
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .alert-success {
            background: #eafaf1;
            color: #27ae60;
        }

        .alert-error {
            background: #fdecea;
            color: #c0392b;
        }

        #change-pass-form {
            display: none;
        }

        .hidden {
            display: none;
        }
        .close-profile {
            text-align: end;
            width: 100%;
            display: inline-block;
            text-decoration: none;
            font-size: 24px;
            color: black;
        }
    </style>
</head>
<body>
    <div class="card">
        <a href="index.php" class="close-profile">x</a>
        <h2>Thông tin cá nhân</h2>

        <?php if ($successMsg) echo "<div class='alert alert-success'>$successMsg</div>"; ?>
        <?php if ($errorMsg) echo "<div class='alert alert-error'>$errorMsg</div>"; ?>

        <!-- FORM cập nhật thông tin -->
        <form method="post" id="info-form">
            <div class="form-group">
                <label class="form-label">Họ tên:</label>
                <input type="text" name="user_name" class="form-input" value="<?= htmlspecialchars($user['user_name']) ?>" required disabled>
            </div>
            <div class="form-group">
                <label class="form-label">Số điện thoại:</label>
                <input type="text" name="phone" class="form-input" value="<?= htmlspecialchars($user['phone']) ?>" required disabled>
            </div>
            <button type="button" id="edit-button" class="form-button">Cập nhật thông tin</button>
            <button type="submit" name="update_info" id="save-button" class="form-button hidden">Lưu</button>
        </form>

        <!-- Nút hiện form đổi mật khẩu -->
        <button class="form-button toggle-button" onclick="toggleChangePass()">Đổi mật khẩu</button>

        <!-- FORM đổi mật khẩu -->
        <div id="change-pass-form">
            <h3>Đổi mật khẩu</h3>
            <form method="post">
                <div class="form-group">
                    <label class="form-label">Mật khẩu hiện tại:</label>
                    <input type="password" name="current_pass" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Mật khẩu mới:</label>
                    <input type="password" name="new_pass" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nhập lại mật khẩu mới:</label>
                    <input type="password" name="confirm_pass" class="form-input" required>
                </div>
                <button type="submit" name="change_pass" class="form-button">Xác nhận đổi mật khẩu</button>
            </form>
        </div>
    </div>

    <script>
        // Bật form đổi mật khẩu
        function toggleChangePass() {
            const form = document.getElementById('change-pass-form');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        }

        // Xử lý cập nhật thông tin
        const editBtn = document.getElementById('edit-button');
        const saveBtn = document.getElementById('save-button');
        const inputs = document.querySelectorAll('#info-form input');

        editBtn.addEventListener('click', () => {
            inputs.forEach(input => input.disabled = false);
            editBtn.classList.add('hidden');
            saveBtn.classList.remove('hidden');
        });
    </script>
</body>
</html>

