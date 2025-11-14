<?php
session_start();
include '../database/db_connect.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    $logged_in = false;
} else {
    $logged_in = true;
    $user_id = $_SESSION['user_id'];
}

// Handle add to cart
if (isset($_POST['add_to_cart'])) {
    if (!$logged_in) {
        header('Location: login_user.php');
        exit;
    }

    $product_id = intval($_POST['product_id']);
    $quantity = 1;

    // Check if product already exists in cart
    $check = $conn->query("SELECT * FROM cart WHERE user_id=$user_id AND product_id=$product_id");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id=$user_id AND product_id=$product_id");
    } else {
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)");
    }

    header('Location: cart.php');
    exit;
}

// Handle search
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']);
}

// Fetch products (with search if provided)
if ($search_query) {
    $stmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p
                            JOIN categories c ON p.category_id = c.id
                            WHERE p.name LIKE ? OR c.name LIKE ?
                            ORDER BY p.created_at DESC");
    $like = "%$search_query%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT p.*, c.name AS category_name FROM products p
                            JOIN categories c ON p.category_id = c.id
                            ORDER BY p.created_at DESC");
}
?>

<div class="container">
    <h2>Shop Products</h2>

    <!-- Search Bar -->
    <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search_query) ?>">
        <button type="submit">Search</button>
    </form>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
    <?php
    if ($result && $result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
            $image_path = !empty($row['image']) 
                ? BASE_URL . '/uploads/products/' . htmlspecialchars($row['image'])
                : BASE_URL . '/assets/img/placeholder.jpg';
    ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="position-relative">
                        <img src="<?= $image_path ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>" 
                             style="height: 160px; object-fit: cover;">
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <h6 class="card-title mb-1 text-dark" style="font-size: 0.9rem;">
                            <?= htmlspecialchars($row['name']) ?>
                        </h6>
                        <small class="text-muted"><?= htmlspecialchars($row['category_name']) ?></small>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong class="text-warning">R<?= number_format($row['price'], 2) ?></strong>
                        </div>
                        <div class="mt-auto">
    <form method="post" class="d-inline">
        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
        <button type="submit" name="add_to_cart" class="btn btn-sm btn-outline-success rounded-pill me-2">
            Add to Cart
        </button>
    </form>

    <a href="product-details.php?id=<?= $row['id'] ?>" 
       class="btn btn-sm btn-outline-warning rounded-pill me-2">
        View Details
    </a>

    <!-- 🟡 Subscribe button -->
    <a href="/ecommerce-project-final/pages/subscription/subscribe.php?product=<?= $row['id'] ?>&freq=monthly" 
   class="btn btn-sm btn-outline-primary rounded-pill">
    Subscribe
</a>

</div>

                    </div>
                </div>
            </div>
    <?php
        endwhile;
    else:
        echo "<p class='text-center text-muted col-12'>No products found.</p>";
    endif;
    ?>
</div>

<?php include '../includes/footer.php'; ?>
