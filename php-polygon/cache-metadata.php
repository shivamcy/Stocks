<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$user = "root";
$password = "1234";
$database = "stocks";
$table = "price_changes";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Create a new PDO instance for database connection
    $pdo = new PDO("mysql:host=localhost;dbname=$database", $user, $password, $options);

    // Query to select distinct start_date and end_date pairs from the table
    $query = "SELECT DISTINCT start_date, end_date FROM $table;";
    $stmt = $pdo->query($query);

    // Fetch all results
    $datePairs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert the result to JSON and output
    echo json_encode($datePairs);
} catch (PDOException $e) {
    // Handle any connection errors
    echo "Error!: " . $e->getMessage() . "<br/>";
}
