<?php
require_once 'db.php';
require 'cors.php';

// Example: Fetching transaction history for a specific user and a specific month
$user_id = $_GET['user_id'];  // Assuming the user ID is passed as a parameter
$year = $_GET['year'];
$month = $_GET['month'];

// Validation to ensure the user_id, year, and month are integers (simple validation for demonstration)
$user_id = intval($user_id);
$year = intval($year);
$month = intval($month);

// Calculate the date range
$start_date = "{$year}-{$month}-01";
$end_date = date("Y-m-t", strtotime($start_date));

$sql = "SELECT transaction_id, category_id, amount, transaction_date, description
        FROM Transactions
        WHERE user_id = ? AND transaction_date >= ? AND transaction_date <= ?
        ORDER BY transaction_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $start_date, $end_date]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($transactions as $transaction) {
    echo "Transaction ID: {$transaction['transaction_id']} - Category ID: {$transaction['category_id']} - Amount: {$transaction['amount']} - Date: {$transaction['transaction_date']} - Description: {$transaction['description']}<br>";
}
?>
