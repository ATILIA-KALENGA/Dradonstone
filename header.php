<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include config first (for BASE_URL)
include_once __DIR__ . '/../config/config.php';

// Include database connection
include_once __DIR__ . '/../database/db_connect.php';

// Initialize cart count
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $res = $conn->query("SELECT SUM(quantity) AS total FROM cart WHERE user_id = $user_id");
    if ($res && $row = $res->fetch_assoc()) {
        $cart_count = $row['total'] ?? 0;
    }
}

// Get current page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dragonstone Store</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Your CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">

    <!-- SEO & Social -->
    <meta name="description" content="Eco-friendly products...">
    <link rel="canonical" href="<?= BASE_URL ?>/pages/<?= $current_page ?>">
    
    <meta property="og:title" content="DragonStone - Sustainable E-commerce">
    <meta property="og:description" content="Shop eco-friendly products.">
    <meta property="og:image" content="<?= BASE_URL ?>/assets/img/og-image.jpg">
    <meta property="og:url" content="<?= BASE_URL ?>/pages/<?= $current_page ?>">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="DragonStone - Sustainable E-commerce">
    <meta name="twitter:description" content="Shop eco-friendly products.">
    <meta name="twitter:image" content="<?= BASE_URL ?>/assets/img/og-image.jpg">
</head>
<body>
<header class="main-header">
    <div class="container header-container">
        <div class="logo">
            <a href="<?= BASE_URL ?>/index.php" class="d-flex align-items-center text-decoration-none">
                <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="Dragonstone Logo" width="36" height="36" class="me-2" style="border-radius: 6px;">
                <strong class="text-warning">Dragonstone</strong>
            </a>
        </div>

        <nav class="navbar">
            <a href="<?= BASE_URL ?>/index.php">Home</a>
            <a href="<?= BASE_URL ?>/pages/shop.php">Shop</a>
            <a href="<?= BASE_URL ?>/pages/cart.php">Cart (<?= $cart_count ?>)</a>
            <a href="<?= BASE_URL ?>/pages/checkout.php">Checkout</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- My Orders for customers -->
                <a href="<?= BASE_URL ?>/pages/my_orders.php">My Orders</a>

                <!-- ADMIN DASHBOARD — ONLY FOR ADMINS -->
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <a href="<?= BASE_URL ?>/admin/index.php" class="text-danger fw-bold">Admin Dashboard</a>
                <?php endif; ?>
            <?php endif; ?>

            <a href="<?= BASE_URL ?>/pages/about.php">About</a>
            <a href="<?= BASE_URL ?>/pages/contact.php">Contact</a>
        </nav>

        <div class="user-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                <a href="<?= BASE_URL ?>/pages/logout.php" class="btn-logout">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/pages/login_user.php" class="btn-login">Login</a>
                <a href="<?= BASE_URL ?>/pages/register.php" class="btn-register">Register</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="content"></main>