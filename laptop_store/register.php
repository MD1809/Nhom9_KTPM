<?php session_start(); ?>
<?php require_once('../laptop_store/settings/config.php'); ?>

<?php
    $nameErr = $phoneErr = $passwordErr = $pass_confirErr = "";
    $name = $phone = $password =  $pass_confir = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["fullname"])) {
            $nameErr = "Name is required";
        }else{
            $name = input($_POST["fullname"]);
        }

        if (empty($_POST["phone"])) {
            $phoneErr = "Phone is required";
        }else{
            $check_phone = input($_POST["phone"]);
            if(!preg_match('/^(03|05|07|08|09)[0-9]{8}$/', $check_phone)){
                $phoneErr = "Không đúng định dạng";
            }else{
                $phone = $check_phone;
            }
        }
        if (empty($_POST["password"])) {
            $passwordErr = "password is required";
        }else{
            $check_pass = input($_POST["password"]);
            if (strlen($check_pass) < 6){
                $passwordErr = "Mật khẩu lỗi ( ít nhất 6 ký tự )";
            }else{
                $password = $check_pass;
            }
        }
        if (empty($_POST["password_confirmation"])) {
            $pass_confirErr = "password is required";
        }else{
            $pass_confir = input($_POST["password_confirmation"]);
        }
        
        if(!empty($_POST["password"])){
            if($password != $pass_confir){
                $pass_confirErr = "Mật khẩu không chính xác";
            }
        }
    }

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>
<?php
    // kiểm tra tài khoản đã đăng ký chưa
    $mess_register = '';
    if(empty($nameErr) && empty($phoneErr) && empty($passwordErr) && empty($pass_confirErr)
    && isset($_POST['submit'])){
        $check_sql = "SELECT user_id FROM users WHERE phone = $phone";
        $result = $conn->query(($check_sql));
        if ($result->num_rows > 0){
            $mess_register = 'Số điện thoại đã đăng ký trước đó';
        }else{
            $sql_register = "INSERT INTO users (user_name, phone, pass) 
                            VALUES ('$name', '$phone', '$password')";
            $result_register = $conn->query(($sql_register));
            $mess_register = 'Đăng ký tài khoản thành công';
        }
    }else{
        $mess_register = '* bắt buộc';
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Laptop</title>
</head>
<body>
    <div class="registerform">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="container-register" id="form_1" method="post">
            <h1 class="register_header">Đăng ký</h1>
            <div class="general-message-fail"><?php  echo $mess_register ?></div>
            <div class="register_body">
                <div class="register_group">
                    <label class="reslabel" for="resname">Họ và tên:</label><br>
                    <div class="input">
                        <input class="resinput" id="resname" name="fullname" type="text" placeholder="Nhập họ và tên" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
                        <span> *</span>
                    </div>
                    <?php
                        if(isset($_POST['submit'])){
                            if($nameErr != ""){
                                echo '<p class="messnotify">' . $nameErr . '</p>';
                            }
                        }
                    ?>
                </div>
                <div class="register_group">
                    <label class="reslabel" for="resphone">Số điện thoại:</label><br>
                    <div class="input">
                        <input class="resinput" id="resphone" name="phone" type="text" placeholder="Nhập số điện thoại" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
                        <span>*</span>
                    </div>
                    <?php
                        if(isset($_POST['submit'])){
                            if($phoneErr != ""){
                                echo '<p class="messnotify">' . $phoneErr . '</p>';
                            }
                        }
                    ?>
                </div>
                <div class="register_group">
                    <label class="reslabel" for="respassword">Mật khẩu:</label><br>
                    <div class="input">
                        <input class="resinput" id="respassword" name="password" type="password" placeholder="Tối thiểu 6 kí tự">
                        <span>*</span>
                    </div>
                    <?php
                        if(isset($_POST['submit'])){
                            if($passwordErr != ""){
                                echo '<p class="messnotify">' . $passwordErr . '</p>';
                            }
                        }
                    ?>
                </div>
                <div class="register_group">
                    <label class="reslabel" for="resPassword_confirmation">Nhập lại mật khẩu:</label><br>
                    <div class="input">
                        <input class="resinput" id="resPassword_confirmation" name="password_confirmation" type="password" placeholder="Nhập mật khẩu">
                        <span>*</span>
                    </div>
                    <?php
                        if(isset($_POST['submit'])){
                            if($pass_confirErr != ""){
                                echo '<p class="messnotify">' . $pass_confirErr . '</p>';
                            }
                        }
                    ?>
                </div>
                <div class="register_button">
                    <button class="btn-res" type="submit" name="submit">Đăng ký</button>
                </div>
            </div>
            <div class="register_footer">
                <p>Bạn đã có tài khoản?
                    <a href="login.php">Đăng nhập</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>