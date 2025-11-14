<?php
include_once("../config/db_connect.php");
header("Content-Type: application/json");

try {
    $stmt = $conn->query("SELECT * FROM Product ORDER BY ProductID DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $products
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
