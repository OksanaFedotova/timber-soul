<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "sql300.infinityfree.com";    // замени на свой хост
$username = "if0_39186705";                 // твой логин
$password = "i6jHWcoUEv6RMw";                 // твой пароль
$dbname = "if0_39186705_catalog";           // имя твоей БД

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // Можно вернуть JSON, если скрипт API, или просто вывести ошибку
    die("Connection failed: " . $conn->connect_error);
}
?>