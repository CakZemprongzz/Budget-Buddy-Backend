<?php
require_once 'db.php';
require 'cors.php';

// Assuming year and month are passed via GET request (e.g., get_transactions.php?year=2024&month=5)
$year = $_GET['year'];
$month = $_GET['month'];

// Ensuring that month and year are numbers to prevent SQL injection
$year = intval($year);
$month = intval($month);

// Constructing the date range for the month
$start_date = "{$year}-{$month}-01";
$end_date = date("Y-m-t", strtotime($start_date)); // Gets the last day of the given month

$sql = "SELECT category_id, SUM(amount) AS total_spent FROM Transactions
        WHERE transaction_date >= ? AND transaction_date <= ?
        GROUP BY category_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([$start_date, $end_date]);
$monthly_totals = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($monthly_totals as $total) {
    echo "Category ID: " . $total['category_id'] . " Total Spent: " . $total['total_spent'] . "<br>";
}
?>
