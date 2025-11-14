<?php
session_start();
include '../includes/header.php';
include '../database/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login_user.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<style>
body {
    background: linear-gradient(to bottom, #f8f9fa, #e8f5e9);
}

.my-orders-section {
    min-height: 75vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 60px 20px;
}

.my-orders-section h2 {
    font-size: 2rem;
    color: #222;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.styled-table {
    width: 90%;
    max-width: 800px;
    border-collapse: collapse;
    font-size: 1rem;
    background-color: #fff;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    overflow: hidden;
}

.styled-table th,
.styled-table td {
    padding: 14px 20px;
    border-bottom: 1px solid #ddd;
}

.styled-table th {
    background-color: #222;
    color: #ffcc00;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.styled-table tr:last-child td {
    border-bottom: none;
}

.styled-table tbody tr:hover {
    background-color: #f8f8f8;
    transition: 0.2s ease;
}

.status {
    padding: 6px 14px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-completed {
    background-color: #d4edda;
    color: #155724;
}

.status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
}

.btn-view {
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s ease;
}

.btn-view:hover {
    background-color: #0056b3;
}

.empty-message {
    background-color: #fff;
    padding: 40px 60px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.empty-message p {
    font-size: 1.1rem;
    color: #444;
    margin-bottom: 20px;
}

.btn-primary {
    background-color: #222;
    color: #ffcc00;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: 0.3s ease;
}

.btn-primary:hover {
    background-color: #ffcc00;
    color: #222;
}
</style>

<div class="my-orders-section">
    <h2>🧾 My Orders</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Total (R)</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($row['id']) ?></td>
                        <td>R <?= number_format($row['total_amount'], 2) ?></td>
                        <td>
                            <span class="status status-<?= htmlspecialchars($row['status']) ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <a href="order_detail.php?id=<?= $row['id'] ?>" class="btn-view">View</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- ✅ Added "Go Back to Shop" Button -->
        <div style="text-align: center; margin-top: 25px;">
            <a href="shop.php" class="btn-primary">← Go Back to Shop</a>
        </div>

    <?php else: ?>
        <div class="empty-message">
            <p>You have no orders yet.</p>
            <a href="shop.php" class="btn-primary">🛍️ Start Shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
