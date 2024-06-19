<?php
require_once 'db.php';
require 'cors.php';

session_start();

header('Content-Type: application/json');

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database for the username
    $sql = "SELECT user_id, username, password FROM Users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Log the user in (set session variables)
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $response['status'] = 'success';
        $response['message'] = 'Login successful!';
        $response['user_id'] = $user['user_id'];
        $response['username'] = $user['username'];
    } else {
        // User not found or wrong password
        $response['status'] = 'error';
        $response['message'] = 'Login failed: Incorrect username or password.';
    }
    echo json_encode($response);
}
?>
