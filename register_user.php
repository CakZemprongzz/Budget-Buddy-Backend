<?php
require_once 'db.php';
require 'cors.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password']; // Retrieve the password from POST data

    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists
    $checkSql = "SELECT * FROM Users WHERE username = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$username]);
    if ($checkStmt->rowCount() > 0) {
        $response['status'] = 'error';
        $response['message'] = 'Username already exists.';
    } else {
        // Update the SQL to include the password column
        $sql = "INSERT INTO Users (username, password) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$username, $hashed_password])) {
            $response['status'] = 'success';
            $response['message'] = 'User registered successfully!';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'User registration failed.';
        }
    }

    echo json_encode($response);
}
?>
