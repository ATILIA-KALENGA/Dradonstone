<?php
require_once 'database/db_connect.php';
?>
<h2>Categories in Database:</h2>
<ul>
<?php
$result = $conn->query("SELECT name FROM categories ORDER BY name");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<li><strong>" . $row['name'] . "</strong></li>";
    }
} else {
    echo "<li>No categories!</li>";
}
?>
</ul>