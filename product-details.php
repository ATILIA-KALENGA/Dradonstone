<?php
session_start();
include '../includes/header.php';
include '../database/db_connect.php';

if (!isset($_GET['id'])) {
    echo "<p>Product not found.</p>";
    include '../includes/footer.php';
    exit;
}

$product_id = intval($_GET['id']);

// Fetch product
$sql = "SELECT p.*, c.name AS category_name FROM products p 
        JOIN categories c ON p.category_id = c.id
        WHERE p.id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "<p>Product not found.</p>";
    include '../includes/footer.php';
    exit;
}

$product = $result->fetch_assoc();

// Handle add to cart
if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login_user.php');
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $quantity = intval($_POST['quantity']);

    $check = $conn->query("SELECT * FROM cart WHERE user_id=$user_id AND product_id=$product_id");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE cart SET quantity = quantity + $quantity WHERE user_id=$user_id AND product_id=$product_id");
    } else {
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)");
    }

    header('Location: cart.php');
    exit;
}
?>

<div class="container">
    <div class="product-detail">
        <img src="/ecommerce-project-final/uploads/products/<?= htmlspecialchars($product['image']) ?>" 
             width="250" 
             alt="<?= htmlspecialchars($product['name']) ?>">
        <div class="info">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <p><em>Category: <?= htmlspecialchars($product['category_name']) ?></em></p>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <h3>R<?= number_format($product['price'], 2) ?></h3>

            <form method="post">
                <input type="number" name="quantity" value="1" min="1" style="width:60px;">
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

<?php
// Fetch product CO2 data (assume $product from query)
$co2_saved = $product['carbon_footprint'] ?? 0; // grams
$equivalent_trees = round($co2_saved / 20000, 2); // 1 tree absorbs ~20kg/year 
?>
<div class="carbon-calculator card mb-4 p-4 bg-light">
    <h4>Carbon Footprint Impact</h4>
    <p>This product saves <strong><?= $co2_saved ?>g CO₂</strong> vs. conventional alternatives.</p>
    <p>Equivalent to planting <strong><?= $equivalent_trees ?> trees</strong> annually.</p>
    <div class="calculator">
        <label>Quantity: <input type="number" id="qty" value="1" min="1" onchange="updateFootprint()"></label>
        <p id="footprint">Total saved: <strong><?= $co2_saved ?>g CO₂</strong></p>
    </div>
</div>

<script>
function updateFootprint() {
    const qty = document.getElementById('qty').value;
    const saved = <?= $co2_saved ?> * qty;
    const trees = (saved / 20000).toFixed(2);
    document.getElementById('footprint').innerHTML = `Total saved: <strong>${saved}g CO₂</strong> (≈ ${trees} trees/year)`;
}
</script>

<?php include '../includes/footer.php'; ?>
