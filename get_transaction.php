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

$sql = "SELECT category_id, SUM(amount) AS total_spent FROM Transactions
        WHERE user_id = ? AND transaction_date >= ? AND transaction_date <= ?
        GROUP BY category_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $start_date, $end_date]);
$monthly_totals = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Encoding the result as JSON
echo json_encode($monthly_totals);
?>
