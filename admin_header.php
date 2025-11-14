<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../database/db_connect.php";
require_once "../config/config.php";

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch statistics
$totalProducts = 0;
$totalOrders = 0;
$totalRevenue = 0;

// Total Products
$productQuery = $conn->query("SELECT COUNT(*) AS total FROM products");
if ($productQuery && $row = $productQuery->fetch_assoc()) {
    $totalProducts = $row['total'];
}

// Total Orders
$orderQuery = $conn->query("SELECT COUNT(*) AS total FROM orders");
if ($orderQuery && $row = $orderQuery->fetch_assoc()) {
    $totalOrders = $row['total'];
}

// Total Revenue
$revenueQuery = $conn->query("SELECT SUM(total_amount) AS total FROM orders");
if ($revenueQuery && $row = $revenueQuery->fetch_assoc()) {
    $totalRevenue = $row['total'] ?? 0;
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | Dragonstone</title>
</head>
<body style="margin:0; font-family:Arial, sans-serif; background-color:#f4f7fa;">

<div style="display:flex; min-height:100vh; flex-wrap:wrap;">

  <!-- Sidebar -->
  <aside style="width:250px; background-color:#1e1e2f; color:#fff; display:flex; flex-direction:column; padding:20px; flex-shrink:0;">
    <h2 style="font-size:24px; margin-bottom:30px; text-align:center; color:#1abc9c;">Dragonstone Admin</h2>
    <?php
    $links = [
        'dashboard.php'=>'Dashboard',
        'add-product.php'=>'Add Product',
        'edit-product.php'=>'Edit Products',
        'manage-orders.php'=>'Orders',
        '/ecommerce-project-final/pages/shop.php'=>'Back to Store'
    ];
    foreach($links as $url=>$title):
        $isActive = ($currentPage == basename($url)) ? true : false;
        $activeStyle = $isActive ? 'background-color:#16a085; color:#fff; font-weight:bold;' : 'color:#ccc;';
    ?>
      <a href="<?= $url ?>" style="text-decoration:none; padding:12px 15px; margin-bottom:8px; border-radius:6px; font-weight:500; <?= $activeStyle ?>;"
         onmouseover="this.style.backgroundColor='#1abc9c'; this.style.color='#fff';"
         onmouseout="this.style.backgroundColor='<?= $isActive?'#16a085':'#1e1e2f' ?>'; this.style.color='<?= $isActive?'#fff':'#ccc' ?>';">
         <?= $title ?>
      </a>
    <?php endforeach; ?>
  </aside>

  <!-- Main Content -->
  <div style="flex:1; padding:30px; min-width:300px;">

    <!-- Header -->
    <header style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; background-color:#fff; padding:15px 20px; border-radius:8px; box-shadow:0 3px 10px rgba(0,0,0,0.1); flex-wrap:wrap;">
      <h1 style="font-size:22px; text-transform:capitalize; color:#333; margin:5px 0;"><?= ucfirst(pathinfo($currentPage, PATHINFO_FILENAME)) ?></h1>
      <a href="logout.php" style="text-decoration:none; background-color:#e74c3c; color:#fff; padding:8px 16px; border-radius:6px;"
         onmouseover="this.style.backgroundColor='#c0392b';"
         onmouseout="this.style.backgroundColor='#e74c3c';">Logout</a>
    </header>

    <!-- Dashboard Cards -->
    <section>
      <h1 style="margin-bottom:20px; color:#333; text-align:center;">Admin Dashboard</h1>
      <div style="display:flex; gap:20px; justify-content:center; flex-wrap:wrap;">

        <div style="background-color:#fff; border-radius:12px; padding:30px 20px; width:250px; text-align:center; box-shadow:0 4px 12px rgba(0,0,0,0.1); border-top:4px solid #1e90ff; transition:all 0.2s;"
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.15)';"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';">
          <div style="font-size:40px; margin-bottom:15px;">📦</div>
          <h3 style="margin-bottom:10px; font-size:20px; color:#555;">Total Products</h3>
          <p style="font-size:28px; font-weight:bold;"><?= htmlspecialchars($totalProducts) ?></p>
        </div>

        <div style="background-color:#fff; border-radius:12px; padding:30px 20px; width:250px; text-align:center; box-shadow:0 4px 12px rgba(0,0,0,0.1); border-top:4px solid #ff8c00; transition:all 0.2s;"
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.15)';"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';">
          <div style="font-size:40px; margin-bottom:15px;">🛒</div>
          <h3 style="margin-bottom:10px; font-size:20px; color:#555;">Total Orders</h3>
          <p style="font-size:28px; font-weight:bold;"><?= htmlspecialchars($totalOrders) ?></p>
        </div>

        <div style="background-color:#fff; border-radius:12px; padding:30px 20px; width:250px; text-align:center; box-shadow:0 4px 12px rgba(0,0,0,0.1); border-top:4px solid #28a745; transition:all 0.2s;"
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.15)';"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';">
          <div style="font-size:40px; margin-bottom:15px;">💰</div>
          <h3 style="margin-bottom:10px; font-size:20px; color:#555;">Total Revenue</h3>
          <p style="font-size:28px; font-weight:bold;">$<?= number_format($totalRevenue, 2) ?></p>
        </div>

      </div>
    </section>

  </div>
</div>
</body>
</html>
