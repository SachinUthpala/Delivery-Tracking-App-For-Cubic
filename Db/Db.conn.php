<?php



$serverName = "localhost"; // Remove 'https://' and any protocol prefixes
$userName = "demo";
$password = "iRoDUi8QoZgQIYs";
$dbName = "demo";

try {
    $conn = new PDO("mysql:host=$serverName;dbname=$dbName", $userName, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection Failed: " . $e->getMessage();
    exit; // Stop execution if connection fails
}



?>