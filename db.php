<?php
$conn = mysqli_connect('localhost', 'root', '', 'blog');
if (!$conn) {
    die('Connection Failed: ' . mysqli_connect_error());
}
?>
