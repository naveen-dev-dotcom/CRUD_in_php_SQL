<?php
session_start();
include 'db.php';

// Get search term (if any)
$search = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
}

// Pagination config
$posts_per_page = 5;

// Count total posts matching search (or all)
if ($search) {
    $count_sql = "SELECT COUNT(*) FROM posts WHERE title LIKE '%$search%' OR content LIKE '%$search%'";
} else {
    $count_sql = "SELECT COUNT(*) FROM posts";
}
$count_result = mysqli_query($conn, $count_sql);
$total_posts = mysqli_fetch_array($count_result)[0];
$total_pages = ($total_posts > 0) ? ceil($total_posts / $posts_per_page) : 1;

// Current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $total_pages) $page = $total_pages;

// Calculate offset
$offset = ($page - 1) * $posts_per_page;

// Fetch posts with limit and offset
if ($search) {
    $sql = "SELECT * FROM posts 
            WHERE title LIKE '%$search%' OR content LIKE '%$search%'
            ORDER BY created_at DESC
            LIMIT $posts_per_page OFFSET $offset";
} else {
    $sql = "SELECT * FROM posts 
            ORDER BY created_at DESC
            LIMIT $posts_per_page OFFSET $offset";
}

$result = mysqli_query($conn, $sql);
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

    <?php
    // Show login/logout links based on session
    if (isset($_SESSION['user_id'])) {
        echo '<a href="add_post.php"><button>Add New Post</button></a> ';
        echo '<a href="logout.php"><button>Logout</button></a>';
    } else {
        echo '<a href="login.php"><button>Login</button></a>';
    }
    ?>

    <!-- Search form -->
    <form method="get" action="index.php" class="search-form">
        <input type="text" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <hr>

    <?php
    if (mysqli_num_rows($result) == 0) {
        echo '<p>No posts found.</p>';
    }

    // List posts
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="post">';
        echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
        echo '<p>' . nl2br(htmlspecialchars($row['content'])) . '</p>';
        echo '<small>Posted on: ' . $row['created_at'] . '</small>';
        if (isset($_SESSION['user_id'])) {
            echo '<div class="actions">';
            echo '<a href="edit_post.php?id=' . $row['id'] . '">Edit</a> | ';
            echo '<a href="delete_post.php?id=' . $row['id'] . '" onclick="return confirm(\'Delete this post?\')">Delete</a>';
            echo '</div>';
        }
        echo '</div>';
    }

    // Pagination links
    echo '<div class="pagination">';
    if ($page > 1) {
        $prev_page = $page - 1;
        echo "<a href='index.php?page=$prev_page&search=" . urlencode($search) . "'>&laquo; Previous</a> ";
    }

    // Show pages (you can limit the number of visible pages if you want)
    for ($p = 1; $p <= $total_pages; $p++) {
        if ($p == $page) {
            echo "<strong>$p</strong> ";
        } else {
            echo "<a href='index.php?page=$p&search=" . urlencode($search) . "'>$p</a> ";
        }
    }

    if ($page < $total_pages) {
        $next_page = $page + 1;
        echo "<a href='index.php?page=$next_page&search=" . urlencode($search) . "'>Next &raquo;</a>";
    }
    echo '</div>';
    ?>

</div>
</body>
</html>
