<?php
require_once 'config/config.php';
require_once 'database/db_connect.php';
include 'includes/header.php';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- HERO -->
<section class="hero text-center text-white py-5" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 0 0 50px 50px;">
    <div class="container py-5">
        <h1 class="display-3 fw-bold mb-0">
            Welcome to <span style="color:#fbbf24;">Dragonstone</span>
        </h1>
    </div>
</section>

<!-- ABOUT DRAGONSTONE -->
<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container text-center">
        <h2 class="fw-bold mb-4" style="color: #1e293b;">About <span style="color:#fbbf24;">Dragonstone</span></h2>
        <p class="lead text-muted mb-3" style="max-width: 900px; margin: 0 auto; line-height: 1.8;">
            At <strong>Dragonstone</strong>, we believe in blending innovation with sustainability. Our store offers a 
            wide range of premium-quality products designed to enhance your everyday lifestyle — from eco-friendly essentials 
            to high-end accessories. Every item we sell is carefully selected to ensure durability, value, and environmental responsibility.
        </p>
        <p class="text-muted" style="max-width: 900px; margin: 15px auto 30px; line-height: 1.8;">
            Whether you’re shopping for your home, upgrading your tech, or discovering the latest trends, 
            Dragonstone is your trusted destination for quality and reliability. Join us in shaping a more sustainable and stylish future.
        </p>
        <a href="<?= BASE_URL ?>/pages/shop.php" class="btn btn-dark btn-lg px-5 py-3 rounded-pill">
            Explore Our Products →
        </a>
    </div>
</section>

<!-- CATEGORIES -->
<section class="container my-5">
    <h2 class="text-center mb-5 fw-bold" style="color:#1e293b;">Shop by Category</h2>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
        <?php
        $stmt = $conn->prepare("SELECT id, name, image FROM categories ORDER BY name");
        if ($stmt === false) {
            echo "<p class='text-danger text-center'>Error loading categories.</p>";
        } else {
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                echo "<p class='text-center text-muted'>No categories found.</p>";
            } else {
                while ($cat = $result->fetch_assoc()) {
                    $image_path = !empty($cat['image']) ? BASE_URL . '/uploads/categories/' . htmlspecialchars($cat['image']) : '';
        ?>
                    <div class="col">
                        <a href="<?= BASE_URL ?>/pages/category.php?id=<?= $cat['id'] ?>" class="text-decoration-none">
                            <div class="text-center p-3 bg-white rounded-4 shadow-sm" style="transition: all 0.3s; border: 1px solid #eee; height: 120px; display: flex; flex-direction: column; justify-content: center;">
                                <?php if (!empty($cat['image'])): ?>
                                    <img src="<?= $image_path ?>" alt="<?= htmlspecialchars($cat['name']) ?>" style="width: 60px; height: 60px; object-fit: contain; margin: 0 auto 8px;">
                                <?php endif; ?>
                                <p class="mb-0 fw-medium text-dark small"><?= htmlspecialchars($cat['name']) ?></p>
                            </div>
                        </a>
                    </div>
        <?php
                }
            }
            $stmt->close();
        }
        ?>
    </div>
</section>
<div class="text-center mt-3">
  
</div>


<?php include 'includes/footer.php'; ?>

