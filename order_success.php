<?php
session_start();
include '../includes/header.php';

if (!isset($_SESSION['order_success'])) {
    header('Location: shop.php');
    exit;
}

$order_info = $_SESSION['order_success'];
unset($_SESSION['order_success']); // clear it after use
?>

<div style="
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80vh;
">
    <div style="
        background-color: #fff3cd;
        border: 2px solid #ffeeba;
        padding: 40px 60px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    ">
        <h2 style="color: #333; margin-bottom: 10px;">Order Confirmed!</h2>
        <p style="font-size: 18px;">Order ID: <strong>#<?= htmlspecialchars($order_info['id']) ?></strong></p>
        <p style="font-size: 18px;">Total: <strong>R <?= number_format($order_info['total'], 2) ?></strong></p>
        <div style="margin-top: 20px;">
            <a href="my_orders.php" class="btn btn-success me-2" style="margin-right:10px;">View My Orders</a>
            <a href="shop.php" class="btn btn-outline-success">Continue Shopping</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
