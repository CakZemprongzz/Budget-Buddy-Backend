<?php
require_once 'db.php';
require 'cors.php';

header('Content-Type: application/json');

$year = $_GET['year'];
$month = $_GET['month'];
$user_id = $_GET['user_id'];

// Sanitizing the input
$year = intval($year);
$month = intval($month);
$user_id = intval($user_id);

$start_date = "{$year}-{$month}-01";
$end_date = date("Y-m-t", strtotime($start_date));

// Include in_out in the SQL query
$sql = "SELECT category_id, description, transaction_date, amount, in_out FROM Transactions
        WHERE user_id = ? AND transaction_date >= ? AND transaction_date <= ?
        ORDER BY transaction_date ASC"; 
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $start_date, $end_date]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$formatted_data = [
    'months' => date('F', strtotime($start_date)),
    'year' => strval($year),
    'days' => []
];

$grouped_transactions = [];
foreach ($transactions as $transaction) {
    $day = date('d', strtotime($transaction['transaction_date']));
    if (!isset($grouped_transactions[$day])) {
        $grouped_transactions[$day] = [];
    }
    $grouped_transactions[$day][] = [
        'amount' => floatval($transaction['amount']),
        'note' => $transaction['description'],
        'category' => getCategoryName($transaction['category_id']),
        'type' => $transaction['in_out'] // Added to include the transaction type
    ];
}

foreach ($grouped_transactions as $day => $transactions) {
    $formatted_data['days'][] = [
        'date' => $day,
        'datas' => $transactions
    ];
}

echo json_encode($formatted_data);

function getCategoryName($category_id) {
    global $pdo;
    $sql = "SELECT name FROM Categories WHERE category_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$category_id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    return $category ? $category['name'] : 'Unknown';
}
?>
