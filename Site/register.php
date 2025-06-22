<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Валидация (можно расширить)
    if ($username && $phone && $email && $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare("INSERT INTO users (username, phone, email, password) VALUES (:username, :phone, :email, :password)");
            $stmt->execute([
                ':username' => $username,
                ':phone'    => $phone,
                ':email'    => $email,
                ':password' => $hashed_password
            ]);
            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            echo "Ошибка регистрации: " . $e->getMessage();
        }
    } else {
        echo "Пожалуйста, заполните все поля!";
    }
}
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
