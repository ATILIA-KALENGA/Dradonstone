<?php
session_start();
include '../includes/header.php';
include '../config/config.php';
include '../database/db_connect.php';

$user_id = $_SESSION['user_id'] ?? 0;
$message = '';

// === POST SUBMISSION ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_content'])) {
    $content = trim($_POST['post_content']);
    if ($content && $user_id) {
        // Insert post
        $stmt = $conn->prepare("INSERT INTO community_posts (user_id, content) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $content);
        $stmt->execute();

        // Award 10 EcoPoints
        $stmt = $conn->prepare("UPDATE users SET ecopoints = ecopoints + 10 WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $message = "<div class='alert alert-success'>Post shared! +10 EcoPoints earned!</div>";
    }
}

// === REDEEM ECOPOINTS ===
if (isset($_POST['redeem']) && $user_id) {
    $points_needed = 100;
    $stmt = $conn->prepare("SELECT ecopoints FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user['ecopoints'] >= $points_needed) {
        // Deduct points
        $stmt = $conn->prepare("UPDATE users SET ecopoints = ecopoints - ? WHERE id = ?");
        $stmt->bind_param("ii", $points_needed, $user_id);
        $stmt->execute();

        // Apply R50 discount to session
        $_SESSION['discount'] = 50;
        $message = "<div class='alert alert-success'>Redeemed 100 EcoPoints! R50 discount applied to your next order!</div>";
    } else {
        $message = "<div class='alert alert-warning'>Not enough EcoPoints. Need 100, you have {$user['ecopoints']}.</div>";
    }
}

// === FETCH POSTS ===
$posts = $conn->query("
    SELECT p.id, p.content, p.created_at, u.name 
    FROM community_posts p 
    JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC
")->fetch_all(MYSQLI_ASSOC);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="text-center mb-4">Community Hub</h1>
            <p class="text-center text-muted mb-5">Share tips, DIY projects, and earn EcoPoints!</p>

            <?= $message ?>

            <!-- Post Form -->
            <?php if ($user_id): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <form method="POST">
                        <textarea name="post_content" class="form-control" rows="3" placeholder="Share a sustainability tip, DIY idea, or review..." required></textarea>
                        <button type="submit" class="btn btn-success mt-3">Post (+10 EcoPoints)</button>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-info text-center">
                <a href="login_user.php">Log in</a> to join the community and earn EcoPoints!
            </div>
            <?php endif; ?>

            <!-- Redeem Section -->
            <?php if ($user_id): 
                $stmt = $conn->prepare("SELECT ecopoints FROM users WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $ecopoints = $stmt->get_result()->fetch_assoc()['ecopoints'];
            ?>
            <div class="card mb-4 bg-light border-success">
                <div class="card-body text-center">
                    <h5>Your EcoPoints: <strong class="text-success"><?= $ecopoints ?></strong></h5>
                    <form method="POST" class="d-inline">
                        <button name="redeem" type="submit" class="btn btn-warning btn-sm" <?= $ecopoints < 100 ? 'disabled' : '' ?>>
                            Redeem 100 Points → R50 Discount
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <!-- Posts Feed -->
            <h4>Community Posts</h4>
            <?php if ($posts): ?>
                <?php foreach ($posts as $post): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <strong><?= htmlspecialchars($post['name']) ?></strong>
                            <small class="text-muted"><?= date('M j, Y g:i A', strtotime($post['created_at'])) ?></small>
                        </div>
                        <p class="mt-2 mb-0"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-muted">No posts yet. Be the first!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>