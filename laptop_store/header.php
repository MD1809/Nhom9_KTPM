<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Laptop</title>
    </script>
</head>
<body>
    <header class="header__container">
        <div class="width-page_use header__top">
            <div class="header__logo">
                <a href="index.php" class="header__logo-link">
                    
                </a>
            </div>
            <?php
                if(!isset($_SESSION['id'])){
                    echo'
                    <div class="header__account">
                        <div class="header__register">
                            <a href="register.php" class="account-link header__register-link">Đăng ký</a>
                        </div>
                        <div class="header__login">
                            <a href="login.php" class="account-link header__login-link">Đăng nhập</a>
                        </div>
                    </div>';
                }else{
                    $sql = 'SELECT * FROM users WHERE user_id = '.$_SESSION["id"];
                    $result = $conn -> query($sql);
                    if($user = $result->fetch_assoc()){
                        $name = $user['user_name'];
                    }
                    echo'
                    <div class="header__account">
                        <span> <i class="fa-solid fa-circle-user"></i> </span>
                        <div class="account">'.$name.'</div>
                        <span> <i class="fa-solid fa-angle-down"></i> </span>
                        <div class="expand_account">
                            <ul class="expand_list">
                                <li class="expand_item expand_item-person"><a href="Profile.php" class="expand_item-link">Thông tin cá nhân</a></li>
                                <li class="expand_item expand_item-logout">
                                    <form action="logout.php" method="post" class="logout">
                                        <input type="submit" name="logout" class="btn-logout" value="Đăng xuất" onclick="return confirm(\'Bạn có chắc muốn Đăng xuất tài khoản?\')">
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>';
                }
            ?>
        </div>
        
        <div class="width-page_use header__bottom">
            <nav class="header__nav">
                <ul class="header__nav-list">
                    <li class="header__nav-item"><a href="index.php" class="header__nav-link">Trang chủ</a></li>
                    <li class="header__nav-item"><a href="listlaptopGamming.php" class="header__nav-link">Laptop Gamming</a></li>
                    <li class="header__nav-item"><a href="listlaptopoffice.php" class="header__nav-link">Laptop Văn phòng</a></li>
                </ul>
            </nav>
            <div class="search-bar">
                <div class="search-container">
                    <form method="get" action="index.php" class="search-container">
                        <input type="text" class="search-bar__input" name="keyword" 
                                placeholder="Tìm kiếm sản phẩm..." 
                                value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" >
                        <button type="submit" class="search-bar__button cursor_hover">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                    
                </div>
            </div>
            <div class="header__cart">
                <a href="cart.php" class="header__cart-link">
                    <span class="header__cart-icon">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </span>
                    <span class="header__cart-text">Giỏ hàng</span>
                </a>
            </div>
        </div>
    </header>
    <script>
        const Btn = document.querySelector('.header__account');
        const dropdown = document.querySelector('.expand_account');

        Btn.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
        });

        window.addEventListener('click', function () {
            dropdown.style.display = 'none';
        });
    </script>