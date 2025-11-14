<?php
session_start();
include('../../includes/config.php');
include('../../includes/header.php');

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// ✅ Validate product ID
if (!isset($_GET['product']) || !is_numeric($_GET['product'])) {
    echo "<div class='alert alert-danger text-center mt-4'>Invalid product ID.</div>";
    exit();
}

$product_id = intval($_GET['product']);
$user_id = $_SESSION['user_id'];

// ⚠️ Frequency must come from POST when confirming subscription
// If GET exists, use it only as default
$frequency = isset($_GET['freq']) ? htmlspecialchars($_GET['freq']) : 'monthly';

// ✅ Get product details
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("SQL ERROR (SELECT): " . $conn->error);
}

$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "<div class='alert alert-danger text-center mt-4'>Product not found.</div>";
    exit();
}

// ✅ Handle subscription form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Frequency from POST
    $frequency = $_POST['freq'];

    $start_date = date('Y-m-d');
    $next_billing_date = date('Y-m-d', strtotime("+1 month")); // default monthly for now

    // INSERT subscription
    $insert_query = "INSERT INTO subscriptions 
            (user_id, product_id, frequency, start_date, next_billing_date, status)
            VALUES (?, ?, ?, ?, ?, 'active')";

    $insert = $conn->prepare($insert_query);

    if (!$insert) {
        die("SQL ERROR (INSERT): " . $conn->error . "<br>QUERY: " . $insert_query);
    }

    // Correct binding
    $insert->bind_param("iisss", $user_id, $product_id, $frequency, $start_date, $next_billing_date);

    if ($insert->execute()) {

    echo "
    <div class='container d-flex justify-content-center align-items-center' style='min-height: 70vh;'>
        <div class='text-center'>
            <div class='alert alert-success rounded-pill px-4 py-3'>
                ✅ Subscription successful for <strong>{$product['name']}</strong>!
            </div>
        </div>
    </div>";
    
    exit();
    } else {
        echo "<div class='alert alert-danger text-center mt-4'>Error: Unable to subscribe.</div>";
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center">
                    <h4 class="mb-3">Subscribe to <?= htmlspecialchars($product['name']) ?></h4>

                    <img src="../../uploads/products/<?= htmlspecialchars($product['image']) ?>" 
                         alt="Product Image" 
                         style="max-width: 250px;">

                    <h5 class="text-warning mb-4">R<?= number_format($product['price'], 2) ?></h5>

                    <form method="post">
                        <div class="mb-3">
                            <label for="freq" class="form-label">Choose subscription frequency:</label>
                            <select name="freq" id="freq" class="form-select rounded-pill">
                                <option value="monthly" <?= $frequency == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                <option value="quarterly" <?= $frequency == 'quarterly' ? 'selected' : '' ?>>Quarterly</option>
                                <option value="yearly" <?= $frequency == 'yearly' ? 'selected' : '' ?>>Yearly</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4">Confirm Subscription</button>
                        <a href="../shop.php" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>
