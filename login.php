<?php
session_start();
require_once "../database/db_connect.php";
require_once "../config/config.php";

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "⚠️ Please fill in all fields.";
    } else {
        // Adjust query for your actual table and columns
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $row = $result->fetch_assoc()) {
                $hashed_input = hash('sha256', $password);

                if ($hashed_input === $row['password']) {
                    // ✅ Login successful
                    $_SESSION['admin_id'] = $row['id'];
                    $_SESSION['admin_name'] = $row['username'];
                    header("Location: index.php");
                    exit;
                } else {
                    $message = "❌ Incorrect password.";
                }
            } else {
                $message = "❌ No user found with that email.";
            }

            $stmt->close();
        } else {
            $message = "SQL prepare failed: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Dragonstone</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        /* Basic fallback styling if CSS fails to load */
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            color: #333;
        }
        header {
            background: #2c3e50;
            color: #fff;
            padding: 15px 0;
            text-align: center;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            background: #2c3e50;
            color: #fff;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background: #1a252f;
        }
        .alert {
            background: #fce4e4;
            color: #b71c1c;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>
<body>

<header>
    <h1>Dragonstone Admin Login</h1>
</header>

<div class="container">
    <?php if (!empty($message)): ?>
        <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" placeholder="Enter email" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn">Login</button>
    </form>
</div>

</body>
</html>
