<?php
session_start();
include 'db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
// Check if id is provided
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id']);

// Handle form submission
if (isset($_POST['update'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $sql = "UPDATE posts SET title='$title', content='$content' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header('Location: index.php');
        exit;
    } else {
        $message = '<div class="alert">Error updating post: ' . mysqli_error($conn) . '</div>';
    }
}

// Fetch current post data
$result = mysqli_query($conn, "SELECT * FROM posts WHERE id=$id");
if (mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit;
}
$post = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Edit Post</h2>

    <?php echo $message; ?>

    <form method="post">
        <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        <textarea name="content" rows="6" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        <button type="submit" name="update">Update Post</button>
    </form>

    <p><a href="index.php">Back to Posts</a> | <a href="logout.php">Logout</a></p>
</div>
</body>
</html>
