<?php
include_once("../includes/header.php");
include_once("../database/db_connect.php");

// Use correct column names: id, name, price, image
$stmt = $conn->prepare("SELECT id, name, price, image FROM products ORDER BY created_at DESC");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Our Products</h2>
<div class="products-grid">
<?php foreach ($products as $p): 
    // Build correct image path using BASE_URL
    $image_path = !empty($p['image']) 
        ? BASE_URL . '/uploads/products/' . htmlspecialchars($p['image']) 
        : BASE_URL . '/assets/img/placeholder.jpg';
?>
  <div class="product-card">
    <img src="<?= $image_path ?>" 
         alt="<?= htmlspecialchars($p['name']) ?>" 
         style="width: 100%; height: 250px; object-fit: cover;">
    <h3><?= htmlspecialchars($p['name']) ?></h3>
    <p class="price">$<?= number_format($p['price'], 2) ?></p>
    <a href="product-details.php?id=<?= $p['id'] ?>" class="btn">View Details</a>
  </div>
<?php endforeach; ?>
</div>

<?php include_once("../includes/footer.php"); ?>