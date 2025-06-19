<?php
    // update_cart_quantity.php
    session_start();
    require_once('../laptop_store/settings/config.php');

    $user_id = $_SESSION['id'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $product_id = $data['product_id'];
        $quantity = max(1, (int)$data['quantity']);

        $sql = "UPDATE Cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $stmt->execute();

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
?>