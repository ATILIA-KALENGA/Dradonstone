<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../database/db_connect.php';
require_once __DIR__ . '/../config/config.php';
include __DIR__ . '/../includes/header.php';

// Fetch all categories
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");

// Fetch latest products
$products = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 8");
?>

<style>
/* --- HOMEPAGE ENHANCED STYLE --- */
.home-container {
  font-family: "Segoe UI", sans-serif;
  background-color: #f8f9fa;
  padding-bottom: 40px;
}

/* Hero Section */
.hero {
  background: linear-gradient(135deg, #111827, #1e3a8a);
  color: white;
  text-align: center;
  padding: 80px 20px;
  border-radius: 0 0 30px 30px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}
.hero h1 {
  font-size: 42px;
  margin-bottom: 10px;
  letter-spacing: 1px;
}
.hero p {
  font-size: 18px;
  opacity: 0.9;
}
.hero a {
  display: inline-block;
  background: #facc15;
  color: #111827;
  padding: 12px 25px;
  margin-top: 20px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: 0.3s;
}
.hero a:hover {
  background: #fde047;
}

/* Categories Section */
.categories {
  padding: 50px 20px;
  text-align: center;
}
.categories h2 {
  font-size: 28px;
  margin-bottom: 25px;
  color: #111827;
}
.category-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 20px;
  max-width: 1000px;
  margin: 0 auto;
}
.category-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  overflow: hidden;
}
.category-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}
.category-card img {
  width: 100%;
  height: 130px;
  object-fit: cover;
}
.category-card h3 {
  padding: 12px;
  background: #f9fafb;
  color: #111827;
  font-size: 16px;
}

/* Products Section */
.products {
  padding: 50px 20px;
  text-align: center;
  background: white;
  border-radius: 30px 30px 0 0;
  margin-top: 40px;
}
.products h2 {
  font-size: 28px;
  margin-bottom: 25px;
  color: #111827;
}
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
  gap: 25px;
  max-width: 1100px;
  margin: 0 auto;
}
.product-card {
  background: #f9fafb;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}
.product-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
}
.product-card h3 {
  font-size: 16px;
  color: #111827;
  margin: 10px 0 5px;
}
.product-card .price {
  color: #1e40af;
  font-weight: bold;
  font-size: 15px;
  margin-bottom: 12px;
}
.product-card a {
  display: block;
  text-decoration: none;
  color: inherit;
}
.empty-message {
  color: #555;
  font-style: italic;
  margin-top: 20px;
}
</style>

<main class="home-container">

  <!-- HERO -->
  <section class="hero">
    <h1>Welcome to 🪨 Dragonstone</h1>
    <p>Discover the latest products and exclusive offers just for you.</p>
    <a href="shop.php">Shop Now →</a>
  </section>

  <!-- CATEGORIES -->
  <section class="categories">
    <h2>Shop by Category</h2>
    <div class="category-grid">
      <?php if ($categories && $categories->num_rows > 0): ?>
        <?php while ($cat = $categories->fetch_assoc()): ?>
          <?php if (!empty($cat['id'])): ?>
          <div class="category-card">
            <a href="category.php?id=<?= htmlspecialchars($cat['id']) ?>">
              <img src="../uploads/categories/<?= htmlspecialchars($cat['image']) ?>" alt="<?= htmlspecialchars($cat['name']) ?>">
              <h3><?= htmlspecialchars($cat['name']) ?></h3>
            </a>
          </div>
          <?php endif; ?>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="empty-message">No categories found.</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- PRODUCTS -->
  <section class="products">
    <h2>Featured Products</h2>
    <div class="product-grid">
      <?php if ($products && $products->num_rows > 0): ?>
        <?php while ($p = $products->fetch_assoc()): ?>
          <?php if (!empty($p['id'])): ?>
          <div class="product-card">
            <a href="product-details.php?id=<?= htmlspecialchars($p['id']) ?>">
              <img src="../uploads/products/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
              <h3><?= htmlspecialchars($p['name']) ?></h3>
              <p class="price">$<?= number_format($p['price'], 2) ?></p>
            </a>
          </div>
          <?php endif; ?>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="empty-message">No products available.</p>
      <?php endif; ?>
    </div>
  </section>

</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
