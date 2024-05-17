<?php
require_once 'db.php';
require 'cors.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    $sql = "INSERT INTO Users (username) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);

    echo "User registered successfully!";
}
?>
