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
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "🗑️ Product deleted successfully.";
    } else {
        $message = "❌ Error deleting product: " . $conn->error;
    }
    $stmt->close();
}

// --- Fetch Products ---
$query = "
    SELECT p.*, c.name AS category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    ORDER BY p.id DESC
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Dragonstone Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<header>
    <h1>Manage Products</h1>
    <nav>
        <a href="index.php" class="btn-back">← Dashboard</a>
        <a href="add-product.php" class="btn-add">+ Add Product</a>
    </nav>
</header>

<div class="container">

    <?php if (!empty($message)): ?>
        <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price ($)</th>
                <th>Stock</th>
                <th>Image</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['category_name'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars(substr($row['description'], 0, 60)) ?>...</td>
                    <td><?= number_format($row['price'], 2) ?></td>
                    <td><?= $row['stock'] ?></td>
                    <td>
                        <?php if (!empty($row['image'])): ?>
                            <img src="../<?= htmlspecialchars($row['image']) ?>" width="50" alt="Product">
                        <?php else: ?>
                            <span>No image</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $row['created_at'] ?></td>
                     <td>
      <a href="edit-product.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
        <a href="?delete=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="9" style="text-align:center;">No products found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<footer>
  &copy; <?= date('Y') ?> Dragonstone Admin Panel
</footer>

</body>
</html>
