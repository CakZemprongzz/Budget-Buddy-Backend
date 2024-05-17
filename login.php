<?php
require_once 'db.php';
require 'cors.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    // Query the database for the username
    $sql = "SELECT user_id, username FROM Users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // Log the user in (set session variables)
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        echo "Login successful! Welcome, " . $user['username'];
    } else {
        // User not found
        echo "Login failed: User not found.";
    }
}
?>
