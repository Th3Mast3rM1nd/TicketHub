<?php
// Database connection settings
$dbHost = '127.0.0.1';
$dbName = 'tickethub';
$dbUser = 'tickethub_user';  
$dbPass = 'TicketHub_2024!'; 

try {
    $pdo = new PDO(
        "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
        $dbUser,
        $dbPass,
    );
} catch (PDOException $e) {
    error_log('DB connection failed: ' . $e->getMessage()); 
    die('Could not connect to the database. Please try again later.');
}
