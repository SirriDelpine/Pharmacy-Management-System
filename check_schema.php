<?php
require_once 'db.php';

echo "<h2>Database Schema Check</h2>";

// Check customer table
echo "<h3>Customer Table Columns:</h3>";
$result = $mysqli->query('SHOW COLUMNS FROM customer');
if ($result) {
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . $mysqli->error;
}

// Check admin table
echo "<h3>Admin Table Columns:</h3>";
$result = $mysqli->query('SHOW COLUMNS FROM admin');
if ($result) {
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . $mysqli->error;
}
?>
