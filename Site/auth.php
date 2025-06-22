<?php
session_start();
header('Content-Type: application/json');

require_once 'db.php';

$action = $_GET['action'] ?? '';

try {
    if ($action === 'login') {
        handleLogin($conn);
    } elseif ($action === 'register') {
        handleRegister($conn);
    } else {
        echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function handleLogin($conn) {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($login) || empty($password)) {
        throw new Exception('Все поля обязательны для заполнения');
    }

    $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

    if ($isEmail) {
        $sql = "SELECT id, username, password FROM users WHERE email = :login";
    } else {
        $login = preg_replace('/\D/', '', $login);
        $sql = "SELECT id, username, password FROM users WHERE phone = :login";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        throw new Exception('Неверный логин или пароль');
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    echo json_encode(['success' => true, 'username' => $user['username']]);
}

function handleRegister($conn) {
    $username = trim($_POST['username'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($phone) || empty($email) || empty($password)) {
        throw new Exception('Все поля обязательны для заполнения');
    }

    if ($password !== $confirm_password) {
        throw new Exception('Пароли не совпадают');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Некорректный email');
    }

    if (strlen($password) < 6) {
        throw new Exception('Пароль должен содержать минимум 6 символов');
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    if ($stmt->fetch()) {
        throw new Exception('Пользователь с таким email уже существует');
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, phone, email, password) VALUES (:username, :phone, :email, :password)");
    $stmt->execute([
        'username' => $username,
        'phone'    => $phone,
        'email'    => $email,
        'password' => $password_hash
    ]);

    $user_id = $conn->lastInsertId();

    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;

    echo json_encode(['success' => true, 'username' => $username]);
}
?>