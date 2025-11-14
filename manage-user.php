<?php
require_once "../database/db_connect.php";
require_once "../config/config.php";

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// --- Handle Delete ---
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "🗑️ User deleted successfully.";
    } else {
        $message = "❌ Error deleting user: " . $conn->error;
    }
    $stmt->close();
}

// --- Handle Role Update ---
if (isset($_POST['update_role'])) {
    $user_id = intval($_POST['user_id']);
    $new_role = $_POST['role'];
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $new_role, $user_id);
    if ($stmt->execute()) {
        $message = "✅ User role updated.";
    } else {
        $message = "❌ Failed to update user role.";
    }
    $stmt->close();
}

// --- Fetch Users ---
$query = "SELECT * FROM users ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users - Dragonstone Admin</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<header>
  <h1>Manage Users</h1>
  <nav>
    <a href="index.php" class="btn-back">← Dashboard</a>
  </nav>
</header>

<div class="container">
  <?php if (!empty($message)): ?>
    <div class="alert"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Created</th>
        <th>Change Role</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td>
              <span class="role"><?= ucfirst($row['role']) ?></span>
            </td>
            <td><?= $row['created_at'] ?></td>
            <td>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                <select name="role" required>
                  <?php
                  $roles = ['admin', 'customer'];
                  foreach ($roles as $r) {
                    $selected = ($r == $row['role']) ? 'selected' : '';
                    echo "<option value='$r' $selected>" . ucfirst($r) . "</option>";
                  }
                  ?>
                </select>
                <button type="submit" name="update_role" class="btn-update">✔</button>
              </form>
            </td>
            <td>
              <a href="?delete=<?= $row['id'] ?>" 
                 class="btn-delete"
                 onclick="return confirm('Are you sure you want to delete this user?');">
                 Delete
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7" style="text-align:center;">No users found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<footer>
  &copy; <?= date('Y') ?> Dragonstone Admin Panel
</footer>

</body>
</html>
