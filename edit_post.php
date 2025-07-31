<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$post_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT title, content FROM posts WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$stmt->bind_result($title, $content);
if (!$stmt->fetch()) {
    $stmt->close();
    header('Location: index.php');
    exit;
}
$stmt->close();

$msg = '';
if (isset($_POST['update'])) {
    $new_title = trim($_POST['title']);
    $new_content = trim($_POST['content']);

    if (empty($new_title) || empty($new_content)) {
        $msg = '<div class="alert">Please fill all fields.</div>';
    } else {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $new_title, $new_content, $post_id);
        if ($stmt->execute()) {
            $stmt->close();
            header('Location: index.php');
            exit;
        } else {
            $msg = '<div class="alert">Error updating post. Please try again.</div>';
        }
        $stmt->close();
    }
}
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

    <?= $msg ?>

    <form method="post" novalidate>
        <input type="text" name="title" placeholder="Title" required value="<?= htmlspecialchars($title) ?>">
        <textarea name="content" placeholder="Content" rows="6" required><?= htmlspecialchars($content) ?></textarea>
        <button type="submit" name="update">Update Post</button>
    </form>

    <p>
        <a href="index.php">Back to Posts</a> |
        <a href="logout.php">Logout</a>
    </p>

</div>
</body>
</html>
