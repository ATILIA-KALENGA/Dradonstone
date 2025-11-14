<?php
require_once "database/db_connect.php";

$result = $conn->query("SHOW TABLES;");
if (!$result) {
    die("Error: " . $conn->error);
}
while ($row = $result->fetch_array()) {
    echo $row[0] . "<br>";
}
?>
