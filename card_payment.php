<?php
session_start();
include '../includes/header.php';
include '../database/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login_user.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart total
$total = 0;
$sql = "SELECT c.quantity, p.price 
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($item = $result->fetch_assoc()) {
    $total += $item['price'] * $item['quantity'];
}
$stmt->close();

// When Pay Now is clicked
if (isset($_POST['pay_now'])) {
    // 1. Create the order
    $order_sql = "INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'paid')";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("id", $user_id, $total);
    $order_stmt->execute();
    $order_id = $conn->insert_id;
    $order_stmt->close();

    // 2. Move items from cart to order_items
    $sql_items = "SELECT c.product_id, c.quantity, p.price 
                  FROM cart c
                  JOIN products p ON c.product_id = p.id
                  WHERE c.user_id = ?";
    $stmt_items = $conn->prepare($sql_items);
    $stmt_items->bind_param("i", $user_id);
    $stmt_items->execute();
    $items = $stmt_items->get_result();

    $insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    while ($item = $items->fetch_assoc()) {
        $insert_item->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $insert_item->execute();
    }
    $insert_item->close();
    $stmt_items->close();

    // 3. Clear cart
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");

    // 4. Redirect to success page
    $_SESSION['order_success'] = [
        'id' => $order_id,
        'total' => $total
    ];
    header("Location: order_success.php");
    exit;
}
?>

<div class="container mt-5 text-center">
    <h2>Pay with Card (Visa / MasterCard)</h2>
    <p>Total to pay: <strong>R <?= number_format($total, 2) ?></strong></p>

    <form method="post">
        <!-- Simple fake card input just for UI -->
        <div class="mb-3" style="max-width: 400px; margin: 0 auto;">
            <input type="text" class="form-control mb-3" placeholder="Card Number" value="1234 5678 9012 3456" readonly>
            <input type="text" class="form-control mb-3" placeholder="Expiry Date" value="12/30" readonly>
            <input type="text" class="form-control mb-3" placeholder="CVV" value="123" readonly>
            <button type="submit" name="pay_now" class="btn btn-success btn-lg w-100">Pay Now</button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
