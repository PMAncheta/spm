<?php
$host = 'localhost'; // Replace 'your_database_host' with your actual database host
$port = '3307'; // Default port 3306, replace port when its not 3306
$dbname = 'spm'; // Replace 'your_database_name' with your actual database name
$db_user = 'root'; // Replace 'your_database_user' with your actual database username
$db_password = 'root'; // Replace 'your_database_password' with your actual database password

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>