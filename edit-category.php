<?php
require_once "../database/db_connect.php";
require_once "../config/config.php";
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid request: category ID missing.");
}

$category_id = intval($_GET['id']);

// --- Fetch category data ---
$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();
$stmt->close();

if (!$category) {
    die("Category not found.");
}

// --- Handle form submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $image_path = $category['image']; // keep old image by default

    // Handle new image upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/categories/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $filename = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = "uploads/categories/" . $filename;
        }
    }

    // Update category in database
    $stmt = $conn->prepare("UPDATE categories SET name=?, description=?, image=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $description, $image_path, $category_id);

    if ($stmt->execute()) {
        $message = "✅ Category updated successfully.";
        $category['name'] = $name;
        $category['description'] = $description;
        $category['image'] = $image_path;
    } else {
        $message = "❌ Error updating category: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category - Dragonstone Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<header>
    <h1>Edit Category</h1>
    <nav>
        <a href="manage-category.php" class="btn-back">← Back to Categories</a>
    </nav>
</header>

<div class="container">

    <?php if (!empty($message)): ?>
        <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Category Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>

        <label>Description</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($category['description']) ?></textarea>

        <label>Current Image</label><br>
        <?php if (!empty($category['image'])): ?>
            <img src="../<?= htmlspecialchars($category['image']) ?>" width="100" alt="Category Image"><br><br>
        <?php else: ?>
            <span>No image uploaded.</span><br><br>
        <?php endif; ?>

        <label>Upload New Image (optional)</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" class="btn-submit">Update Category</button>
    </form>
</div>

<footer>
  &copy; <?= date('Y') ?> Dragonstone Admin Panel
</footer>

</body>
</html>
