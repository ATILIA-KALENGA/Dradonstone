<?php
session_start();
include '../includes/db_connect.php'; // adjust path if needed

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: ../pages/cart.php?error=empty_cart");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];
$total = 0;

// Calculate total price
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Insert into orders table
$status = 'Pending';
$date = date('Y-m-d H:i:s');

$order_query = "INSERT INTO orders (user_id, total_amount, status, created_at)
                VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("idss", $user_id, $total, $status, $date);
$stmt->execute();

$order_id = $stmt->insert_id;

// Insert each cart item into order_items table
$item_query = "INSERT INTO order_items (order_id, product_id, quantity, price)
               VALUES (?, ?, ?, ?)";
$item_stmt = $conn->prepare($item_query);

foreach ($cart as $item) {
    $item_stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
    $item_stmt->execute();
}

// Clear cart after placing order
unset($_SESSION['cart']);

// Redirect to "My Orders" page
header("Location: ../pages/my_orders.php?success=order_placed");
exit();
?>
