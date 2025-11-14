<?php
session_start();
include '../includes/header.php';
include '../database/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login_user.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "<div class='container'><p class='error-message'>Order ID not specified.</p></div>";
    include '../includes/footer.php';
    exit;
}

$order_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch order info
$order_sql = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "<div class='container'><p class='error-message'>Order not found.</p></div>";
    include '../includes/footer.php';
    exit;
}
?>

<style>
/* --- Embedded Order Details Styles --- */
.order-detail-container {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    padding: 30px;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    font-family: "Segoe UI", sans-serif;
}
.order-detail-header {
    text-align: center;
    margin-bottom: 30px;
}
.order-detail-header h2 {
    font-size: 28px;
    color: #333;
    font-weight: 600;
}
.order-summary {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    background: #f9f9f9;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 25px;
}
.order-summary p {
    margin: 6px 12px;
    font-size: 16px;
    color: #444;
}
.status {
    padding: 5px 10px;
    border-radius: 6px;
    font-weight: 500;
}
.status-pending { background: #ffeeba; color: #856404; }
.status-completed { background: #c3e6cb; color: #155724; }
.status-cancelled { background: #f5c6cb; color: #721c24; }

.styled-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
.styled-table th, .styled-table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: center;
}
.styled-table th {
    background: #007bff;
    color: white;
}
.styled-table tr:nth-child(even) {
    background: #f9f9f9;
}
.empty-message {
    text-align: center;
    padding: 20px;
    font-size: 16px;
    color: #555;
}
.back-link {
    text-align: center;
    margin-top: 30px;
}
.btn-primary {
    display: inline-block;
    background: #007bff;
    color: white;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.3s;
}
.btn-primary:hover {
    background: #0056b3;
}
.error-message {
    text-align: center;
    background: #f8d7da;
    color: #721c24;
    padding: 12px;
    margin: 20px auto;
    border-radius: 8px;
    max-width: 600px;
}
</style>

<div class="order-detail-container">
    <div class="order-detail-header">
        <h2>📦 Order Details</h2>
    </div>

    <div class="order-summary">
    <p><strong>Order ID:</strong> #<?= htmlspecialchars($order['id']) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
    <p><strong>Status:</strong>
        <span class="status status-<?= htmlspecialchars($order['status']) ?>">
            <?= ucfirst($order['status']) ?>
        </span>
    </p>
    <p><strong>Total:</strong> R <?= number_format($order['total_amount'], 2) ?></p>
</div>

    <h3 style="text-align:center; margin-bottom:15px;">🛒 Ordered Products</h3>

    <?php
    $items_sql = "SELECT oi.*, p.name AS product_name
                  FROM order_items oi
                  LEFT JOIN products p ON oi.product_id = p.id
                  WHERE oi.order_id = ?";
    $stmt_items = $conn->prepare($items_sql);
    $stmt_items->bind_param("i", $order_id);
    $stmt_items->execute();
    $items = $stmt_items->get_result();
    ?>

    <?php if ($items && $items->num_rows > 0): ?>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price ($)</th>
                    <th>Subtotal ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $items->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                        <td><?= number_format($row['price'] * $row['quantity'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-message">
            <p>No items found for this order.</p>
        </div>
    <?php endif; ?>

    <div class="back-link">
        <a href="my_orders.php" class="btn-primary">⬅ Back to My Orders</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
