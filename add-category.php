<?php
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';

// Handle Add Category
if ($_POST && isset($_POST['add_category'])) {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');

    if ($name) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, description, image) VALUES (?, ?, ?)");
        $stmt->execute([$name, $description, $image]);
        $message = "Category added successfully!";
    } else {
        $message = "Category name is required.";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
    $message = "Category deleted.";
}

// Fetch Categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category - Dragonstone Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .admin-header {
            background: linear-gradient(135deg, #1e3a8a, #111827);
            color: white;
            padding: 1.5rem 0;
            border-radius: 0 0 16px 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .btn-primary {
            background: #3b82f6; border: none; border-radius: 8px; padding: 0.6rem 1.5rem;
            font-weight: 600; transition: all 0.2s;
        }
        .btn-primary:hover { background: #2563eb; transform: translateY(-1px); }
        .btn-danger { border-radius: 8px; font-weight: 500; }
        .form-control, .form-control:focus {
            border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: none;
            padding: 0.65rem 1rem;
        }
        .table { border-radius: 12px; overflow: hidden; }
        .table thead { background: #1e293b; color: white; }
        .table tbody tr:hover { background: #f1f5f9; }
        .alert { border-radius: 8px; font-weight: 500; }
        .back-link { color: #94a3b8; font-size: 0.95rem; text-decoration: none; }
        .back-link:hover { color: #3b82f6; }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="admin-header text-center">
    <div class="container">
        <h1 class="h3 mb-1">Add New Category</h1>
        <a href="index.php" class="back-link">Back to Dashboard</a>
    </div>
</div>

<div class="container mt-4">

    <!-- MESSAGE -->
    <?php if ($message): ?>
        <div class="alert alert-<?= strpos($message, 'success') !== false ? 'success' : 'danger' ?> alert-dismissible fade show">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ADD FORM -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g., Electronics">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Image URL / Path</label>
                    <input type="text" name="image" class="form-control" placeholder="e.g., images/categories/tech.jpg">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Optional description..."></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" name="add_category" class="btn btn-primary">
                        Add Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EXISTING CATEGORIES -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Existing Categories</h5>
        </div>
        <div class="card-body p-0">
            <?php if ($categories): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Image</th>
                                <th>Created</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td><strong>#<?= $cat['id'] ?></strong></td>
                                    <td><?= htmlspecialchars($cat['name'] ?? '') ?></td>
                                    <td class="text-muted small">
                                        <?= htmlspecialchars($cat['description'] ?? '<em>No description</em>') ?>
                                    </td>
                                    <td>
                                        <?php if ($cat['image']): ?>
                                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($cat['image']) ?>" 
                                                 alt="" style="width:40px; height:40px; object-fit:cover; border-radius:6px;">
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small text-muted">
                                        <?= date('M j, Y', strtotime($cat['created_at'])) ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="?delete=<?= $cat['id'] ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Delete this category?')">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-muted py-4">No categories yet.</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>