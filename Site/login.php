<?php
session_start();
require_once 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            header("Location: index.php");
        } else {
            echo "Неверный пароль";
        }
    } else {
        echo "Пользователь не найден";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход - Timber Soul</title>
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
            <h2>Вход</h2>
            <form method="post" action="login.php">
                <input type="text" name="email" placeholder="Телефон или почта" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <button type="submit" class="btn">Войти!</button>
            </form>
        </section>
    </main>
</body>
</html>