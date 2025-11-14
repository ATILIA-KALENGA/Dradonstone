<?php
require_once "../database/db_connect.php";
require_once "../config/config.php";

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// --- Delete Category ---
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "🗑️ Category deleted successfully.";
    } else {
        $message = "❌ Error deleting category: " . $conn->error;
    }
    $stmt->close();
}

// --- Fetch all categories ---
$result = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories - Dragonstone Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<header>
  <h1>Manage Categories</h1>
  <a href="index.php" class="btn-back">← Back to Dashboard</a>
  <a href="add-category.php" class="btn-add">+ Add Category</a>
</header>

<div class="container">
    <?php if (!empty($message)): ?>
        <div class="alert"><?= $message ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Description</th>
                <th>Image</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>
                            <?php if (!empty($row['image'])): ?>
                                <img src="../<?= htmlspecialchars($row['image']) ?>" alt="Category Image" width="50">
                            <?php else: ?>
                                <span>No image</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <a href="edit-category.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                            <a href="?delete=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No categories found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<footer>
  &copy; <?= date('Y') ?> Dragonstone Admin Panel
</footer>

</body>
</html>
