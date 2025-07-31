<?php
// Database connection with error hiding
$conn = new mysqli('localhost', 'root', '', 'blog');
if ($conn->connect_error) {
    die('Database connection failed.');
}
?>
