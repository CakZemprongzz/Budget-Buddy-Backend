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

// Query to fetch transactions monthly
$sql = "SELECT EXTRACT(MONTH FROM transaction_date) as month, in_out, SUM(amount) as total_amount 
        FROM Transactions
        WHERE user_id = ? AND transaction_date BETWEEN ? AND ?
        GROUP BY EXTRACT(MONTH FROM transaction_date), in_out";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $start_date, $end_date]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$monthly_summary = [];

// Initialize months
for ($m = 1; $m <= 12; $m++) {
    $monthName = date('F', mktime(0, 0, 0, $m, 10));
    $monthly_summary[$monthName] = ['income' => 0, 'expense' => 0];
}

// Populate monthly summary
foreach ($transactions as $transaction) {
    $monthName = date('F', mktime(0, 0, 0, $transaction['month'], 10));
    if ($transaction['in_out'] === 'in') {
        $monthly_summary[$monthName]['income'] += floatval($transaction['total_amount']);
    } else {
        $monthly_summary[$monthName]['expense'] += floatval($transaction['total_amount']);
    }
}

echo json_encode([
    'monthlyTransactions' => array_map(function ($month, $data) {
        return ['month' => $month, 'income' => $data['income'], 'expense' => $data['expense']];
    }, array_keys($monthly_summary), $monthly_summary)
]);
?>
