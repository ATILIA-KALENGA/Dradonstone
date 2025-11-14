<?php
require_once "../database/db_connect.php";
require_once "../config/config.php";

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Order ID not provided.");
}

$order_id = intval($_GET['id']);

// Fetch order + user info
$query = "
    SELECT o.*, u.username, u.email
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    die("Order not found.");
}

// Fetch order items
$item_query = "
    SELECT oi.*, p.name, p.image
    FROM order_items oi
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
";
$stmt = $conn->prepare($item_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #<?= $order_id ?> Details - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        body {
            background: #f7f8fa;
            font-family: Arial, sans-serif;
        }
        header {
            background: #222;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            font-size: 22px;
            margin: 0;
        }
        .btn-back {
            background: #444;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn-back:hover {
            background: #666;
        }
        .container {
            max-width: 950px;
            background: white;
            margin: 40px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        table th {
            background: #f0f0f0;
        }
        img {
            border-radius: 6px;
        }
        footer {
            text-align: center;
            margin-top: 40px;
            padding: 15px;
            color: #666;
        }
    </style>
</head>
<body>

<header>
    <h1>Order #<?= $order_id ?> Details</h1>
    <!-- FIXED: Link to manage-orders.php (your real file) -->
    <a href="manage-orders.php" class="btn-back">Back to Orders</a>
</header>

<div class="container">
    <h2>Customer Info</h2>
    <p><strong>Customer:</strong> <?= htmlspecialchars($order['username'] ?? 'Guest') ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($order['email'] ?? '-') ?></p>
    <p><strong>Status:</strong> <?= ucfirst($order['status'] ?? 'pending') ?></p>

    <!-- REAL TOTAL FROM ITEMS -->
    <?php
    $real_total = 0;
    if ($items && $items->num_rows > 0) {
        $items->data_seek(0);
        while ($item = $items->fetch_assoc()) {
            $real_total += $item['quantity'] * $item['price'];
        }
        $items->data_seek(0);
    }
    ?>
    <p><strong>Total:</strong> R <?= number_format($real_total, 2) ?></p>

    <p><strong>Date:</strong> <?= $order['created_at'] ?></p>

    <h2>Products</h2>
    <table>
        <thead>
            <tr>
                <!-- IMAGE COLUMN REMOVED -->
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($items && $items->num_rows > 0): ?>
            <?php while ($item = $items->fetch_assoc()): ?>
                <tr>
                    <!-- NO IMAGE COLUMN -->
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>R <?= number_format($item['price'], 2) ?></td>
                    <td>R <?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" style="text-align:center;">No items found for this order.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<footer>
  &copy; <?= date('Y') ?> Dragonstone Admin Panel
</footer>

</body>
</html>