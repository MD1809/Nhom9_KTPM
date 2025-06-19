<?php session_start(); ?>
<?php require_once('../laptop_store/settings/config.php'); ?>


<?php
    $loginErr='';
    if(isset($_POST['submit'])){
        if(!isset($_POST['login-phone']) || trim($_POST['login-phone']) === '' ||
           !isset($_POST['login-pass']) || trim($_POST['login-pass']) === ''){
            $loginErr = "Điền đầy đủ thông tin";
        }else{
            $phone = $_POST['login-phone'];
            $password = $_POST['login-pass'];  
            $sql = "SELECT * FROM users WHERE phone = '$phone'";
            $result = $conn -> query($sql);
            if($user = $result->fetch_assoc()){
                if($user['is_active'] == 0){
                    echo "<script>alert('Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên.'); 
                            window.location.href='login.php';</script>";
                    exit();
                }
                if($password == $user['pass']){
                    if($user['role'] == "Khách hàng"){
                        $_SESSION["id"] = "$user[user_id]";
                        $user_id = $_SESSION["id"];

                        // thêm lại session_cart
                        // $cart_items = [];
                        // $stmt = $conn->prepare("
                        //     SELECT Cart.product_id, Products.name, Products.image, Products.price, Cart.quantity
                        //     FROM Cart
                        //     JOIN Products ON Cart.product_id = Products.product_id
                        //     WHERE Cart.user_id = ?
                        // ");
                        // $stmt->bind_param("i", $user_id);
                        // $stmt->execute();
                        // $result = $stmt->get_result();

                        // while ($row = $result->fetch_assoc()) {
                        //     $cart_items[] = [
                        //         'product_id' => $row['product_id'],
                        //         'name'       => $row['name'],
                        //         'price'      => $row['price'],
                        //         'image'      => $row['image'],
                        //         'quantity'   => $row['quantity']
                        //     ];
                        // }

                        // $_SESSION['cart'] = $cart_items;

                        // $stmt->close();
    
                        // $sql = "SELECT product_id, quantity, price FROM saved_carts WHERE user_id = ?";
                        // $stmt = $conn->prepare($sql);
                        // $stmt->bind_param("i", $_SESSION["id"]);
                        // $stmt->execute();
                        // $result = $stmt->get_result();
    
                        // $_SESSION['cart'] = [];
                        // while ($row = $result->fetch_assoc()) {
                        //     $idProduct = $row['product_id'];
                        //     $sqlPd = "SELECT name, image FROM products WHERE id_product = ?";
                        //     $stmtPd = $conn->prepare($sqlPd);
                        //     $stmtPd->bind_param("i", $idProduct);
                        //     $stmtPd->execute();
                        //     $resultPd = $stmtPd->get_result();
                        //     while($rowPd = $resultPd->fetch_assoc()){
                        //         $_SESSION['cart'][] = [
                        //             'product_id' => $row['product_id'],
                        //             'quantity' => $row['quantity'],
                        //             'price' => $row['price'],
                        //             'name' => $rowPd['name'],
                        //             'image' => $rowPd['image']
                        //         ];
                        //     }
                        // }
                        // $stmt->close();
    
                        // $conn->query("DELETE FROM saved_carts WHERE user_id = $user_id");
    
                        if (isset($_GET['return'])) {
                            $return_url = $_GET['return'];
                            header("Location: $return_url");
                            exit();
                        }else{
                            $loginErr = "thành công";
                            header("Location: index.php");
                            exit();
                        }

                    }else{
                        header("Location: ../laptop_store/admin/Ad_index.php");
                        exit();
                    }
                }else{
                    $loginErr = "Sai mật khẩu";
                }
            }else{
                $loginErr = "Tài khoản không tồn tại";
            }
        }
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
<body id="body">

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="login_form">
        <div class="overlay"></div>
        <div class="login__container">
            <div class="login__header-form">
                <h4 class="login__title">Đăng nhập</h4>
                <div class="login__close go-back-btn">
                    <a href="index.php" style="color: #333;"><i class="fa-solid fa-xmark login__close--icon"></i></a>
                </div>
            </div>
            <div class="general-message-login fail"><?php echo $loginErr; ?></div>
            <div class="login__body-form">
                <div class="login__input">
                    <input name="login-phone" type="text" placeholder="Phone" class="login__input-info Username">
                    <input name="login-pass" type="password" placeholder="Password" class="login__input-info Password">
                </div>
                <div class="login__expand">
                    <div class="login__remember-account">
                        <input type="radio" class="remember-account-click cursor_hover">
                        <p class="remember-account-text">Remember</p>
                    </div>
                    <div class="login__forget-pass cursor_hover">
                        <a href="#" class="login__forget-pass--link">Forget Password</a>
                    </div>
                </div>
            </div>
            <div class="login__footer-form">
                <div class="login__action" type="submit" name="submit">
                    <button class="button cursor_hover" type="submit" name="submit">Đăng nhập</button>
                </div>
                <div class="login__link-register">
                    <p class="login__link-register--text">
                        Bạn chưa có tài khoản?
                        <a href="register.php" class="login__link-register--link">Đăng ký</a>
                    </p>
                </div>
            </div>
        </div>
    </form>
    <script src="goback_common.js"></script>
</body>
</html>