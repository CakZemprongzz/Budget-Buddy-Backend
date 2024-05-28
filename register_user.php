<?php
require_once 'db.php';
require 'cors.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password']; // Retrieve the password from POST data

    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update the SQL to include the password column
    $sql = "INSERT INTO Users (username, password) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $hashed_password]);

    echo "User registered successfully!";
}
?>
