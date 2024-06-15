<?php
require_once 'db.php';
require 'cors.php';

// Setting the response header to application/json
header('Content-Type: application/json');

// Assuming year, month, and user_id are passed via GET request (e.g., get_transactions.php?year=2024&month=5&user_id=1)
$year = $_GET['year'];
$month = $_GET['month'];
$user_id = $_GET['user_id'];

// Ensuring that month, year, and user_id are numbers to prevent SQL injection
$year = intval($year);
$month = intval($month);
$user_id = intval($user_id);

// Constructing the date range for the month
$start_date = "{$year}-{$month}-01";
$end_date = date("Y-m-t", strtotime($start_date)); // Gets the last day of the given month

$sql = "SELECT category_id, description, transaction_date, amount FROM Transactions
            WHERE user_id = ? AND transaction_date >= ? AND transaction_date <= ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $start_date, $end_date]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare the data structure
$formatted_data = [
    'months' => date('F', strtotime($start_date)), // Full month name
    'year' => strval($year),
    'datas' => []
];

foreach ($transactions as $transaction) {
    $formatted_data['datas'][] = [
        'amount' => floatval($transaction['amount']),
        'note' => $transaction['description'],
        'category' => getCategoryName($transaction['category_id']), // Assuming you have a function to get category name by ID
        'date' => date('d', strtotime($transaction['transaction_date']))
    ];
}

// Encoding the result as JSON
echo json_encode($formatted_data);

// Function to get category name by ID (assuming you have a categories table)
function getCategoryName($category_id) {
    global $pdo;
    $sql = "SELECT name FROM Categories WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$category_id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    return $category ? $category['name'] : 'Unknown';
}
?>
