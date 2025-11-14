<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "../database/db_connect.php";
require_once "../config/config.php";

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// --- Get product ID from URL ---
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($product_id <= 0) {
    die("Invalid request: product ID missing or invalid.");
}

// --- Fetch existing product ---
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
if (!$stmt) die("Prepare failed: " . $conn->error);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) die("Product not found.");

// --- Fetch categories for dropdown ---
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");

// --- Handle Update ---
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = intval($_POST['category_id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);

    // Update product in DB (no image)
    $stmt = $conn->prepare("UPDATE products SET category_id=?, name=?, description=?, price=? WHERE id=?");
    if (!$stmt) die("Prepare failed: " . $conn->error);

    $stmt->bind_param("issdi", $category_id, $name, $description, $price, $product_id);

    if ($stmt->execute()) {
        $message = "✅ Product updated successfully.";
        // Refresh product info
        $product['category_id'] = $category_id;
        $product['name'] = $name;
        $product['description'] = $description;
        $product['price'] = $price;
    } else {
        $message = "❌ Error updating product: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Product - Dragonstone Admin</title>
<link rel="stylesheet" href="../assets/css/admin.css">
<style>
.container { max-width:700px; margin:20px auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1);}
label { display:block; margin-top:10px; font-weight:bold; }
input[type=text], input[type=number], textarea, select { width:100%; padding:8px; margin-top:5px; border-radius:5px; border:1px solid #ccc; }
.btn-submit { margin-top:15px; padding:10px 20px; background:#1e90ff; color:#fff; border:none; border-radius:6px; cursor:pointer; }
.btn-submit:hover { background:#0b78d1; }
.alert { padding:10px; margin-bottom:15px; border-radius:6px; background:#d4edda; color:#155724; }
</style>
</head>
<body>

<header style="text-align:center; margin-bottom:20px;">
    <h1>Edit Product</h1>
</header>

<div class="container">

    <?php if (!empty($message)): ?>
        <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Category</label>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>Description</label>
        <textarea name="description" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>

        <label>Price (R)</label>
       <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($product['price']) ?>" required>

        <button type="submit" class="btn-submit">Update Product</button>
    </form>

    <p style="margin-top:15px;"><a href="manage-product.php" style="text-decoration:none; color:#1e90ff;">← Back to Products</a></p>
</div>

</body>
</html>
