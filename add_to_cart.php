<?php
include_once("../config/db_connect.php");
header("Content-Type: application/json");
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit();
}

// Example: customerID should come from session (or API token)
$customerID = $_SESSION['UserID'] ?? null;
$productID = $_POST['product_id'] ?? null;
$quantity  = $_POST['quantity'] ?? 1;

if (!$customerID || !$productID) {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
    exit();
}

try {
    // Check or create a cart
    $cartStmt = $conn->prepare("SELECT CartID FROM Cart WHERE CustomerID = ?");
    $cartStmt->execute([$customerID]);
    $cart = $cartStmt->fetch();

    if (!$cart) {
        $conn->prepare("INSERT INTO Cart (CustomerID, CreatedAt) VALUES (?, NOW())")->execute([$customerID]);
        $cartID = $conn->lastInsertId();
    } else {
        $cartID = $cart['CartID'];
    }

    // Check if product exists
    $check = $conn->prepare("SELECT * FROM CartItem WHERE CartID = ? AND ProductID = ?");
    $check->execute([$cartID, $productID]);
    $existing = $check->fetch();

    // Get product price
    $priceStmt = $conn->prepare("SELECT Price FROM Product WHERE ProductID = ?");
    $priceStmt->execute([$productID]);
    $price = $priceStmt->fetchColumn();

    if ($existing) {
        $newQty = $existing['Quantity'] + $quantity;
        $conn->prepare("UPDATE CartItem SET Quantity = ? WHERE CartItemID = ?")->execute([$newQty, $existing['CartItemID']]);
    } else {
        $conn->prepare("INSERT INTO CartItem (CartID, ProductID, Quantity, UnitPrice) VALUES (?, ?, ?, ?)")
             ->execute([$cartID, $productID, $quantity, $price]);
    }

    echo json_encode(["status" => "success", "message" => "Product added to cart"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
