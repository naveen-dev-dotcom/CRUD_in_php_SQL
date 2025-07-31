<?php
session_start();
include 'db.php';

// Get current user info for permissions and role display
$user_id = $_SESSION['user_id'] ?? null;
$user_role = '';
if ($user_id) {
    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($user_role);
    $stmt->fetch();
    $stmt->close();
}

// Search term (from GET)
$search = '';
if (isset($_GET['search']) && trim($_GET['search']) !== '') {
    $search = trim($_GET['search']);
}

// Pagination settings
$posts_per_page = 5;

// Count posts matching search or all
if ($search) {
    $like_term = "%$search%";
    $stmt = $conn->prepare("SELECT COUNT(*) FROM posts WHERE title LIKE ? OR content LIKE ?");
    $stmt->bind_param("ss", $like_term, $like_term);
} else {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM posts");
}
$stmt->execute();
$stmt->bind_result($total_posts);
$stmt->fetch();
$stmt->close();

$total_pages = ($total_posts > 0) ? ceil($total_posts / $posts_per_page) : 1;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $total_pages) $page = $total_pages;

$offset = ($page - 1) * $posts_per_page;

if ($search) {
    $stmt = $conn->prepare("SELECT id, title, content, created_at FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ssii", $like_term, $like_term, $posts_per_page, $offset);
} else {
    $stmt = $conn->prepare("SELECT id, title, content, created_at FROM posts ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $posts_per_page, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <h1>My Blog</h1>

    <?php if ($user_id): ?>
        <a href="add_post.php"><button>Add New Post</button></a>
        <a href="logout.php"><button>Logout</button></a>
    <?php else: ?>
        <a href="login.php"><button>Login</button></a>
    <?php endif; ?>

    <form method="get" action="index.php" class="search-form" style="margin-top: 20px;">
        <input type="text" name="search" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

    <hr>

    <?php if ($result->num_rows === 0): ?>
        <p>No posts found.</p>
    <?php else: ?>
        <?php while ($post = $result->fetch_assoc()): ?>
            <div class="post">
                <h2><?= htmlspecialchars($post['title']) ?></h2>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <small>Posted on: <?= htmlspecialchars($post['created_at']) ?></small>
                <?php if ($user_id): ?>
                    <div class="actions">
                        <a href="edit_post.php?id=<?= $post['id'] ?>">Edit</a>
                        <?php if ($user_role === 'admin'): ?>
                            | <a href="delete_post.php?id=<?= $post['id'] ?>" onclick="return confirm('Delete this post?')">Delete</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="index.php?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">&laquo; Previous</a>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $total_pages; $p++): ?>
            <?php if ($p == $page): ?>
                <strong><?= $p ?></strong>
            <?php else: ?>
                <a href="index.php?page=<?= $p ?>&search=<?= urlencode($search) ?>"><?= $p ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="index.php?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>

</div>
</body>
</html>

<?php $stmt->close(); ?>
