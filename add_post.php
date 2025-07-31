<?php
session_start();
include 'db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';

if (isset($_POST['add'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $sql = "INSERT INTO posts (title, content) VALUES ('$title', '$content')";
    if (mysqli_query($conn, $sql)) {
        $message = '<div class="success">Post added successfully!</div>';
    } else {
        $message = '<div class="alert">Error: ' . mysqli_error($conn) . '</div>';
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

    <?php echo $message; ?>

    <form method="post">
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="content" placeholder="Content" required rows="6"></textarea>
        <button type="submit" name="add">Add Post</button>
    </form>

    <p><a href="index.php">Back to Posts</a> | <a href="logout.php">Logout</a></p>
</div>
</body>
</html>
