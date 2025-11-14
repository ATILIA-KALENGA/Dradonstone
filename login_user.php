<?php
session_start();
include '../includes/header.php';
include '../database/db_connect.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='shop.php';</script>";
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email && $password) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        if (!$stmt) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['is_admin'] = $user['is_admin'] ?? 0;

// ADMIN: Redirect to dashboard
if ($_SESSION['is_admin'] == 1) {
    header("Location: " . BASE_URL . "/admin/index.php");
    exit;
} else {
    // Normal user → go to home or shop
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

            // Redirect to shop.php
            echo "<script>alert('Welcome back, {$user['username']}!'); window.location.href='shop.php';</script>";
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Please enter both email and password.';
    }
}
?>

<main class="container auth-container">
    <h2 class="page-title">Customer Login</h2>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" class="auth-form">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</main>

<?php include '../includes/footer.php'; ?>
