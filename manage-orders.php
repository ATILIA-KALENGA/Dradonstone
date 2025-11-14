<?php
require_once "../database/db_connect.php";
require_once "../config/config.php";

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// --- Handle Delete ---
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "🗑️ Order deleted successfully.";
    } else {
        $message = "❌ Error deleting order: " . $conn->error;
    }
    $stmt->close();
}

// --- Handle Status Update ---
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    if ($stmt->execute()) {
        $message = "✅ Order status updated.";
    } else {
        $message = "❌ Failed to update order status.";
    }
    $stmt->close();
}

// --- Fetch Orders with Total ---
$query = "
    SELECT 
        o.*, 
        u.username, 
        u.email,
        COALESCE(SUM(oi.quantity * oi.price), 0) AS order_total
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    LEFT JOIN order_items oi ON o.id = oi.order_id
    GROUP BY o.id
    ORDER BY o.created_at DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Dragonstone Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        body {
            background: #f5f6fa;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        header {
            background: #1e1e2d;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 { margin: 0; font-size: 22px; }
        .btn-back {
            background: #444;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn-back:hover { background: #666; }
        .container {
            max-width: 1100px;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
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
            font-weight: 600;
        }
        .status {
            padding: 5px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
        }
        .status-pending { background: #ffeeba; color: #856404; }
        .status-processing { background: #bee5eb; color: #0c5460; }
        .status-shipped { background: #b8daff; color: #004085; }
        .status-delivered { background: #c3e6cb; color: #155724; }
        .status-cancelled { background: #f5c6cb; color: #721c24; }
        .btn-update, .btn-view, .btn-delete {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-size: 13px;
        }
        .btn-update { background: #007bff; }
        .btn-view { background: #28a745; }
        .btn-delete { background: #dc3545; }
        .btn-update:hover { background: #0056b3; }
        .btn-view:hover { background: #1e7e34; }
        .btn-delete:hover { background: #c82333; }
        .alert {
            background: #d1ecf1;
            color: #0c5460;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            padding: 10px;
        }
    </style>
</head>
<body>

<header>
    <h1>Manage Orders</h1>
    <a href="index.php" class="btn-back">Dashboard</a>
</header>

<div class="container">
    <?php if (!empty($message)): ?>
        <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Total (R)</th>
                <th>Status</th>
                <th>Created</th>
                <th>Update Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['username'] ?? 'Guest') ?></td>
                    <td><?= htmlspecialchars($row['email'] ?? '-') ?></td>
                    <td>R <?= number_format($row['order_total'] ?? 0, 2) ?></td>
                    <td>
                        <span class="status status-<?= $row['status'] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                            <select name="status" required>
                                <?php
                                $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
                                foreach ($statuses as $status) {
                                    $selected = ($status == $row['status']) ? 'selected' : '';
                                    echo "<option value='$status' $selected>" . ucfirst($status) . "</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" name="update_status" class="btn-update">Check</button>
                        </form>
                    </td>
                    <td>
                        <!-- FIXED: Correct link to order detail -->
                        <a href="order-detail.php?id=<?= $row['id'] ?>" class="btn-view">View</a>
                        <a href="?delete=<?= $row['id'] ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Are you sure you want to delete this order?');">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8" style="text-align:center;">No orders found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<footer>
  &copy; <?= date('Y') ?> Dragonstone Admin Panel
</footer>

</body>
</html>