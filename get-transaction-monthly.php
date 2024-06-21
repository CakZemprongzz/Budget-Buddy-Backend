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

$months = [
    "January" => ['income' => 0, 'expense' => 0],
    "February" => ['income' => 0, 'expense' => 0],
    "March" => ['income' => 0, 'expense' => 0],
    "April" => ['income' => 0, 'expense' => 0],
    "May" => ['income' => 0, 'expense' => 0],
    "June" => ['income' => 0, 'expense' => 0],
    "July" => ['income' => 0, 'expense' => 0],
    "August" => ['income' => 0, 'expense' => 0],
    "September" => ['income' => 0, 'expense' => 0],
    "October" => ['income' => 0, 'expense' => 0],
    "November" => ['income' => 0, 'expense' => 0],
    "December" => ['income' => 0, 'expense' => 0]
];

foreach ($transactions as $transaction) {
    $monthIndex = intval($transaction['month']); // Get month index
    $monthName = date('F', mktime(0, 0, 0, $monthIndex, 10)); // Convert month index to month name
    if ($transaction['in_out'] === 'in') {
        $months[$monthName]['income'] += floatval($transaction['total_amount']);
    } else {
        $months[$monthName]['expense'] += floatval($transaction['total_amount']);
    }
}

echo json_encode($months);
?>
