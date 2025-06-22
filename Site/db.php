<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dbUrl = getenv('DATABASE_URL');
if (!$dbUrl) {
    die("DATABASE_URL is not set");
}

// Разбираем URL в части
$db = parse_url($dbUrl);

$host = $db['host'];
$port = $db['port'];
$user = $db['user'];
$pass = $db['pass'];
$dbname = ltrim($db['path'], '/');

$conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
