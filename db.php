<?php
// Database connection using MySQLi
// Put this file in the project root alongside your .php/.html files
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'pharmacy_management_system';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_error) {
    die('Database connection error: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

// Use $mysqli for prepared statements in other pages
?>
