<?php
session_start();
require_once 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, phone, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $phone, $email, $password);
    if ($stmt->execute()) {
        header("Location: login.php");
    } else {
        echo "Ошибка регистрации";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация - Timber Soul</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">Timber Soul</div>
        <div class="user-actions">
            <a href="login.php">Вход</a> | <a href="register.php">Регистрация</a>
        </div>
    </header>
    <main>
        <section class="auth-form">
            <h2>Регистрация</h2>
            <form method="post" action="register.php">
                <input type="text" name="username" placeholder="Ваше имя" required>
                <input type="text" name="phone" placeholder="Телефон" required>
                <input type="email" name="email" placeholder="Электронная почта" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <button type="submit" class="btn">Зарегистрироваться!</button>
            </form>
        </section>
    </main>
</body>
</html>