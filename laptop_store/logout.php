<?php session_start(); ?>
<?php require_once('../shop/settings/config.php'); ?>

<?php


// if (isset($_SESSION['id']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
//     $user_id = $_SESSION['id'];
//     $cart = $_SESSION['cart'];

//     $stmt = $conn->prepare("INSERT INTO Cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
//     foreach ($cart as $item) {
//         $stmt->bind_param("iii", $user_id, $item['product_id'], $item['quantity']);
//         $stmt->execute();
//     }
//     $stmt->close();
// }

// Xoá session
unset($_SESSION['user_id']);
// unset($_SESSION['cart']);
session_destroy();

header("Location: index.php");
exit();
?>
