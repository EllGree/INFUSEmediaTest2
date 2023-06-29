<?php
// MySQL server config:
const DB_HOST = 'localhost';
const DB_NAME = 'kittysan_ellgree';
const DB_USER = 'kittysan_ellgree';
const DB_PASSWD = 'wb)173P[VS';

header('Content-Type: text/plain');
if (!isset($_GET['method']) || !preg_match('/^(dice|log|view)$/', $_GET['method'])) {
    die('Wrong usage');
}
if ($_GET['method'] === 'dice') { // Dice call
    die((string) rand(1, 4));
}

$image_mark = intval(isset($_GET['id']) ? $_GET['id'] : 0);

if ($image_mark === 0) {
    die('0');
}

// DB Server connection
if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
    die('Mysqli module is required!');
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
if ($conn->connect_error) {
    die("MySQL connection failed: " . $conn->connect_error);
}

// Check db table existence
$result = $conn->query("SHOW TABLES LIKE 'logs'");
if ($result->num_rows === 0) {
    $conn->query(file_get_contents('database/logs.sql'));
}

// Get visitor's information
$ip_address = $_SERVER['REMOTE_ADDR'];
$user_agent = mysqli_real_escape_string($conn, $_SERVER['HTTP_USER_AGENT']);
$where = "ip_address = '{$ip_address}' AND user_agent = '{$user_agent}' AND image_mark = '{$image_mark}'";
$result = $conn->query("SELECT * FROM logs WHERE {$where}");
if ($result->num_rows > 0) { // Visitor exists, increment view_date
    $row = $result->fetch_assoc();
    $views_count = 1 + $row['views_count'];
    $sql = "UPDATE logs SET `views_count` = '$views_count' WHERE {$where}";
} else { // Insert new visitor
    $views_count = 1;
    $sql = "INSERT INTO logs (`ip_address`, `user_agent`, `image_mark`, `views_count`)
        VALUES ('{$ip_address}', '{$user_agent}', {$image_mark}, {$views_count})";
}
if ($_GET['method'] === 'log') { // Log call
    $conn->query($sql);
}
$conn->close();
die((string) $views_count);
