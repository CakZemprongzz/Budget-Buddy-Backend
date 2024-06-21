<?php
require_once 'db.php';
require 'cors.php';

header('Content-Type: application/json');

$year = $_GET['year'];
$user_id = $_GET['user_id'];

// Sanitizing the input
$year = intval($year);
$user_id = intval($user_id);

$start_date = "{$year}-01-01";
$end_date = "{$year}-12-31";

// Query to fetch and sum transactions monthly
$sql = "SELECT EXTRACT(MONTH FROM transaction_date) as month, category_id, in_out, SUM(amount) as total_amount 
        FROM Transactions
        WHERE user_id = ? AND transaction_date BETWEEN ? AND ? 
        GROUP BY EXTRACT(MONTH FROM transaction_date), category_id, in_out";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $start_date, $end_date]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$monthly_summary = [];

foreach ($transactions as $transaction) {
    $month = date('F', mktime(0, 0, 0, $transaction['month'], 10)); // Convert month number to month name
    if (!isset($monthly_summary[$month])) {
        $monthly_summary[$month] = ['income' => 0, 'expense' => 0];
    }
    if ($transaction['in_out'] === 'in') {
        $monthly_summary[$month]['income'] += floatval($transaction['total_amount']);
    } else {
        $monthly_summary[$month]['expense'] += floatval($transaction['total_amount']);
    }
}

$formatted_data = [
    'year' => strval($year),
    'summary' => $monthly_summary
];

echo json_encode($formatted_data);
?>
