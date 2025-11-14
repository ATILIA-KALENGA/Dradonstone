<?php
session_start();
include '../includes/header.php';
include '../database/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login_user.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch cart items
$sql = "SELECT c.*, p.name, p.price 
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Your cart is empty. <a href='shop.php'>Shop now</a></p>";
    include '../includes/footer.php';
    exit;
}

$total = 0;
$items = [];
while ($row = $result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    $items[] = $row;
}
$stmt->close();

// ✅ Handle order confirmation
if (isset($_POST['confirm_order'])) {
    $payment_method = $_POST['payment_method'] ?? 'cod';
    $discount = $_SESSION['discount'] ?? 0;
    $final_total = max(0, $total - $discount); // Make sure not negative


    // ✅ Insert order properly
    $order_sql = "INSERT INTO orders (user_id, total_amount, status, payment_method) 
                  VALUES (?, ?, ?, ?)";
    $order_stmt = $conn->prepare($order_sql);
    $status = 'Pending';
    $order_stmt->bind_param("idss", $user_id, $final_total, $status, $payment_method);
    $order_stmt->execute();
    $order_id = $conn->insert_id;
    $order_stmt->close();

    // ✅ Insert order items
    $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $item_stmt = $conn->prepare($item_sql);
    foreach ($items as $item) {
        $item_stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $item_stmt->execute();
    }
    $item_stmt->close();

    // ✅ Clear cart
    $delete_sql = "DELETE FROM cart WHERE user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $user_id);
    $delete_stmt->execute();
    $delete_stmt->close();

    unset($_SESSION['discount']);

    // ✅ Show success
    echo "
    <div style='display:flex;justify-content:center;align-items:center;height:80vh;'>
        <div style='background-color:#fff3cd;border:2px solid #ffeeba;padding:40px 60px;border-radius:12px;text-align:center;box-shadow:0 4px 12px rgba(0,0,0,0.15);'>
            <h2 style='color:#333;margin-bottom:10px;'>Order Confirmed!</h2>
            <p style='font-size:18px;margin-bottom:5px;'>Order ID: <strong>#{$order_id}</strong></p>
            <p style='font-size:18px;margin-bottom:20px;'>Total: <strong>R " . number_format($final_total, 2) . "</strong></p>
            <a href='my_orders.php' class='btn btn-success me-2' style='margin-right:10px;'>View My Orders</a>
            <a href='shop.php' class='btn btn-outline-success'>Continue Shopping</a>
        </div>
    </div>";
    include '../includes/footer.php';
    exit;
}
?>

<div class="container">
    <h2>Order Summary</h2>
    <table>
        <tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th></tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>R<?= number_format($item['price'], 2) ?></td>
                <td>R<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Total: R<?= number_format($total, 2) ?></h3>

    <form method="post">
        <h4>Select Payment Method:</h4>
        <label><input type="radio" name="payment_method" value="cod" checked> Cash on Delivery</label><br>
        <label><input type="radio" name="payment_method" value="card"> Pay with Card (Visa / MasterCard)</label><br>
        <label><input type="radio" name="payment_method" value="payfast"> Pay via PayFast</label><br><br>
        <button type="submit" name="confirm_order">Confirm and Pay</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
