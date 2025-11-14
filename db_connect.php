<?php
// ===================================================
// Dragonstone Database Connection (MySQLi)
// ===================================================

$host = "localhost";       // Database host
$user = "root";            // Default username for XAMPP
$pass = "";                // Leave empty unless you set a password
$dbname = "dragonstonedb"; // Your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Set proper charset
$conn->set_charset("utf8mb4");

// ===================================================
// Connection successful
// ===================================================
// Uncomment this line for testing only
// echo "✅ Connected successfully to Dragonstone database";
?>
