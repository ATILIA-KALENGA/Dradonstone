<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/header.php';
include '../database/db_connect.php';

// Get category ID from URL
$category_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Redirect to homepage if ID is missing or invalid
if (!$category_id) {
    header("Location: index.php");
    exit;
}

// Fetch category details
$cat_stmt = $conn->prepare("SELECT name, image FROM categories WHERE id = ?");
$cat_stmt->bind_param("i", $category_id);
$cat_stmt->execute();
$category = $cat_stmt->get_result()->fetch_assoc();

// Redirect to homepage if category not found
if (!$category) {
    header("Location: index.php");
    exit;
}
?>

<?php if (!empty($category['image'])): ?>
    <div class="text-center mb-5">
        <img src="<?= BASE_URL ?>/uploads/categories/<?= htmlspecialchars($category['image']) ?>" 
             alt="<?= htmlspecialchars($category['name']) ?>" 
             class="img-fluid rounded-4 shadow" 
             style="max-height: 380px; width: auto; display: inline-block;">
    </div>
<?php endif; ?>

   <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
    <?php
    // Fetch products in this category
    $prod_stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE category_id = ?");
    $prod_stmt->bind_param("i", $category_id);
    $prod_stmt->execute();
    $products = $prod_stmt->get_result();

    if ($products && $products->num_rows > 0):
        while ($product = $products->fetch_assoc()):
            $image_path = !empty($product['image']) 
                ? BASE_URL . '/uploads/products/' . $product['image']
                : BASE_URL . '/assets/img/placeholder.jpg';
    ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="position-relative">
                        <img src="<?= $image_path ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" 
                             style="height: 140px; object-fit: cover;">
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <h6 class="card-title mb-1 text-dark" style="font-size: 0.9rem; line-height: 1.3;">
                            <?= htmlspecialchars($product['name']) ?>
                        </h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong class="text-warning">R <?= number_format($product['price'], 2) ?></strong>
                        </div>
                        <a href="product-details.php?id=<?= $product['id'] ?>" 
                           class="btn btn-sm btn-outline-warning mt-auto rounded-pill">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
    <?php
        endwhile;
    else:
        echo "<p class='text-center text-muted w-100'>No products available.</p>";
    endif;
    $prod_stmt->close();
    ?>
</div> <!-- Close .row -->
?>
</main>

<?php include '../includes/footer.php'; ?>