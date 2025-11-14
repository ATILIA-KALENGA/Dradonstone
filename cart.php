<?php
session_start();
include '../includes/header.php';
include '../database/db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login_user.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle remove from cart
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    $conn->query("DELETE FROM cart WHERE user_id=$user_id AND product_id=$product_id");
}

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_qty'])) {
    foreach ($_POST['quantity'] as $product_id => $qty) {
        $qty = max(1, intval($qty));
        $conn->query("UPDATE cart SET quantity=$qty WHERE user_id=$user_id AND product_id=$product_id");
    }
}

// Fetch cart items
$sql = "SELECT c.*, p.name, p.price, p.image 
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = $user_id";
$result = $conn->query($sql);

$total = 0;
$discount = $_SESSION['discount'] ?? 0; // R50 if redeemed
?>

<div class="container">
    <h2>Your Cart</h2>
    <?php if ($result->num_rows > 0): ?>
        <form method="post">
            <table class="cart-table">
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): 
                    $subtotal = $row['price'] * $row['quantity'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td>
                            <?php
                            $image_path = !empty($row['image']) 
                                ? BASE_URL . '/uploads/products/' . htmlspecialchars($row['image'])
                                : BASE_URL . '/assets/img/placeholder.jpg';
                            ?>
                            <img src="<?= $image_path ?>" 
                                 alt="<?= htmlspecialchars($row['name']) ?>" 
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                        </td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>R<?= number_format($row['price'], 2) ?></td>
                        <td>
                            <input type="number" name="quantity[<?= $row['product_id'] ?>]" 
                                   value="<?= $row['quantity'] ?>" min="1" style="width:60px;">
                        </td>
                        <td>R<?= number_format($subtotal, 2) ?></td>
                        <td><a href="?remove=<?= $row['product_id'] ?>" class="btn-delete">Remove</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <button type="submit" name="update_qty">Update Quantities</button>
        </form>

        <?php 
        // Now calculate the total after discount (AFTER total is known)
        $total_after_discount = max(0, $total - $discount);
        // Clear discount so it doesn’t apply twice
        unset($_SESSION['discount']);
        ?>

        <div class="cart-summary">
            <p><strong>Subtotal:</strong> R<?= number_format($total, 2) ?></p>

            <?php if ($discount > 0): ?>
                <p class="text-success"><strong>EcoPoints Discount:</strong> -R<?= number_format($discount, 2) ?></p>
            <?php endif; ?>

            <h3>Total to Pay: R<?= number_format($total_after_discount, 2) ?></h3>
        </div>

        <a href="checkout.php" class="btn">Proceed to Checkout</a>

    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
