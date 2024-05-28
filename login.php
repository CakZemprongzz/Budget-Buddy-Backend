<?php
require_once 'db.php';
require 'cors.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username and password are provided
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password']; // Get password from POST request

        // Query the database for the username
        $sql = "SELECT user_id, username, password FROM Users WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            // Verify the hashed password
            if (password_verify($password, $user['password'])) {
                // Log the user in (set session variables)
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                echo "Login successful! Welcome, " . $user['username'];
            } else {
                echo "Login failed: Incorrect password.";
            }
        } else {
            // User not found
            echo "Login failed: User not found.";
        }
    } else {
        echo "Login failed: Username and password are required.";
    }
}
?>
