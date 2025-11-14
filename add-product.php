<?php
require_once "../database/db_connect.php";
require_once "../config/config.php";

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// --- Fetch categories ---
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");

// --- Add Product ---
if (isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);

    if (!empty($name) && $category_id > 0 && $price > 0) {
        $stmt = $conn->prepare("INSERT INTO products (category_id, name, description, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issd", $category_id, $name, $description, $price);
        if ($stmt->execute()) {
            $message = "✅ Product added successfully!";
        } else {
            $message = "❌ Database error: " . $conn->error;
        }
        $stmt->close();
    } else {
        $message = "⚠️ Please fill all required fields.";
    }
}

// --- Delete Product ---
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "🗑️ Product deleted successfully.";
    } else {
        $message = "❌ Error deleting product.";
    }
    $stmt->close();
}

// --- Fetch all products ---
$result = $conn->query("
    SELECT p.*, c.name AS category_name 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    ORDER BY p.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product - Dragonstone Admin</title>
<style>
body { font-family: Arial, sans-serif; background:#f4f6f8; margin:0; padding:0; }
header { background:#2c3e50; color:#fff; padding:15px; display:flex; justify-content:space-between; }
.container { width:90%; max-width:1000px; margin:30px auto; background:white; padding:20px; border-radius:8px; }
.alert { background:#eafaf1; padding:10px; border-left:5px solid #27ae60; margin-bottom:15px; }
.form-card { display:grid; grid-template-columns:1fr 1fr; gap:10px 20px; margin-bottom:30px; }
.form-card label { grid-column:span 2; font-weight:bold; }
.form-card input, .form-card select, .form-card textarea { width:100%; padding:8px; border-radius:5px; border:1px solid #ccc; }
.form-card button { grid-column:span 2; background:#27ae60; color:white; padding:10px; border:none; border-radius:5px; cursor:pointer; }
table { width:100%; border-collapse:collapse; }
table th { background:#3498db; color:white; padding:8px; }
table td { border-bottom:1px solid #ddd; padding:8px; }
.btn-edit, .btn-delete { padding:5px 10px; color:white; border-radius:4px; text-decoration:none; }
.btn-edit { background:#f1c40f; }
.btn-delete { background:#e74c3c; }
</style>
</head>
<body>

<header>
  <h2>Add New Product</h2>
</header>

<div class="container">
<?php if (!empty($message)): ?>
  <div class="alert"><?= $message ?></div>
<?php endif; ?>

<form action="" method="POST" class="form-card">
    <label>Product Name</label>
    <input type="text" name="name" required>

    <label>Description</label>
    <textarea name="description" rows="3"></textarea>

    <label>Price (R)</label>
    <input type="number" name="price" step="0.01" required>

    <label>Category</label>
    <select name="category_id" required>
        <option value="">-- Select Category --</option>
        <?php while ($cat = $categories->fetch_assoc()): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endwhile; ?>
    </select>

    <button type="submit" name="add_product">Add Product</button>
</form>

<h3>Existing Products</h3>
<table>
<thead>
<tr>
    <th>ID</th>
    <th>Category</th>
    <th>Name</th>
    <th>Price</th>
    <th>Created</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php if ($result->num_rows > 0): ?>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['category_name']) ?></td>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td>R<?= number_format($row['price'], 2) ?></td>
    <td><?= $row['created_at'] ?? '' ?></td>
    <td>
        <a href="edit-product.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
        <a href="?delete=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Delete this product?');">Delete</a>
    </td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="6" align="center">No products found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>

</body>
</html>
