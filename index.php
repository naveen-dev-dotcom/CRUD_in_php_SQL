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
    include 'db.php';
    session_start();
    if (isset($_SESSION['user_id'])) {
        echo '<a href="add_post.php"><button>Add New Post</button></a> ';
        echo '<a href="logout.php"><button>Logout</button></a>';
    } else {
        echo '<a href="login.php"><button>Login</button></a>';
    }
    echo "<hr>";

    $result = mysqli_query($conn, "SELECT * FROM posts ORDER BY created_at DESC");
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="post">';
        echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
        echo '<p>' . nl2br(htmlspecialchars($row['content'])) . '</p>';
        if (isset($_SESSION['user_id'])) {
            echo '<div class="actions">';
            echo '<a href="edit_post.php?id=' . $row['id'] . '">Edit</a> | ';
            echo '<a href="delete_post.php?id=' . $row['id'] . '" onclick="return confirm(\'Delete this post?\')">Delete</a>';
            echo '</div>';
        }
        echo '</div>';
    }
    ?>
</div>
</body>
</html>
