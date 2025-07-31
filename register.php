<?php
session_start();
include 'db.php';

$msg = '';
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password_raw = $_POST['password'];

    if (empty($username) || empty($password_raw)) {
        $msg = '<div class="alert">Please fill all fields.</div>';
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $msg = '<div class="alert">Username already exists!</div>';
        } else {
            // Determine role: first registered user is admin else user
            $role = 'user';
            $result = $conn->query("SELECT COUNT(*) as count FROM users");
            $count = $result->fetch_assoc()['count'];
            if ($count == 0) {
                $role = 'admin';
            }

            $password = password_hash($password_raw, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $role);
            if ($stmt->execute()) {
                $msg = '<div class="success">Registration successful! You can now <a href="login.php">login</a>.</div>';
            } else {
                $msg = '<div class="alert">Registration failed. Please try again.</div>';
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Register</h2>
    <?= $msg ?>
    <form method="post" novalidate>
        <input type="text" name="username" placeholder="Username" required minlength="3" maxlength="100" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
        <input type="password" name="password" placeholder="Password" required minlength="6">
        <button type="submit" name="register">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</div>
</body>
</html>
