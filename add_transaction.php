<?php
require_once 'db.php';
require 'cors.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $transaction_date = $_POST['transaction_date'];
    $description = $_POST['description'];

    $sql = "INSERT INTO Transactions (user_id, category_id, amount, transaction_date, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $category_id, $amount, $transaction_date, $description]);

    echo "Transaction added successfully!";
}
?>
