<?php
session_start();
include '../includes/header.php';
include '../database/db_connect.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
 echo "<script>window.location.href='../index.php';</script>";
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    if (!$name || !$email || !$password || !$confirm) {
        $error = 'Please fill in all fields.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if (!$check_stmt) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $error = 'Email is already registered.';
        } else {
            // Hash password securely
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $insert_stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if (!$insert_stmt) {
                die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }
            $insert_stmt->bind_param("sss", $name, $email, $hashed);

            if ($insert_stmt->execute()) {
                // Auto login
                $_SESSION['user_id'] = $insert_stmt->insert_id;
                $_SESSION['user_name'] = $name;
                
                echo "<script>alert('Registration successful! Welcome, $name'); window.location.href='../index.php';</script>";
                exit;
            } else {
                $error = 'Error creating account. Please try again.';
            }
        }
    }
}
?>

<main class="container auth-container">
    <h2 class="page-title">Create an Account</h2>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" class="auth-form">
        <label>Full Name:</label>
        <input type="text" name="full_name" required>

        <label>Email Address:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" class="btn">Register</button>
    </form>

    <p>Already have an account? <a href="login_user.php">Login here</a>.</p>
</main>

<?php include '../includes/footer.php'; ?>
