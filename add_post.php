<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$msg = '';
if (isset($_POST['add'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $msg = '<div class="alert">Please fill all fields.</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);
        if ($stmt->execute()) {
            $msg = '<div class="success">Post added successfully!</div>';
        } else {
            $msg = '<div class="alert">Error adding post. Please try again.</div>';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <h2>Add New Post</h2>

    <?= $msg ?>

    <form method="post" novalidate>
        <input type="text" name="title" placeholder="Title" required value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>">
        <textarea name="content" placeholder="Content" rows="6" required><?= isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '' ?></textarea>
        <button type="submit" name="add">Add Post</button>
    </form>

    <p>
        <a href="index.php">Back to Posts</a> |
        <a href="logout.php">Logout</a>
    </p>

</div>
</body>
</html>
