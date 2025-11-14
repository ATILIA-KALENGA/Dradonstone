<?php
// includes/config.php

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define base URL safely
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/ecommerce-project-final/');
}

// Database connection
$host = 'localhost';
$dbname = 'dragonstonedb';
$username = 'root';
$password = ''; // XAMPP default

$dsn = "mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}

// Timezone
date_default_timezone_set('Africa/Johannesburg');
