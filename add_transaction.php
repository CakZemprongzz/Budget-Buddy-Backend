<?php
require_once 'db.php';
require 'cors.php';

header('Content-Type: application/json'); // Set content type to JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST['category_id'];
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $transaction_date = $_POST['transaction_date'];
    $description = $_POST['description'];
    $in_out = $_POST['in_out'];

    $sql = "INSERT into transactions (category_id, user_id, amount, transaction_date, description, in_out) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    // Execute with parameters in the correct order
    if ($stmt->execute([$category_id, $user_id, $amount, $transaction_date, $description, $in_out])) {
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['status' => 'failed']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'failed', 'message' => 'Invalid request method.']);
}
?>
