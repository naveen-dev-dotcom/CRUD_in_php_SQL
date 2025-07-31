<?php
session_start();
include 'db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id']);

// Delete the post
$sql = "DELETE FROM posts WHERE id=$id";
mysqli_query($conn, $sql);

// Redirect back to post list
header('Location: index.php');
exit;
?>
