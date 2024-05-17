<?php
// db.php
$host = "aws-0-ap-southeast-1.pooler.supabase.com";
$dbname = "postgres";
$user = "postgres.xbwkjgkihzrnoomxexwa";
$password = "MYnraZWntBuJt6AM";

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>