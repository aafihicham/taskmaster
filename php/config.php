<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "taskmaster";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; // Uncomment for testing
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>
