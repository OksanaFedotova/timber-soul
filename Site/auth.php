<?php
// Запускаем сессию, чтобы сохранять данные о пользователе (например, кто вошёл в систему)
session_start();
// Говорим, что ответ будет в формате JSON (удобно для общения с фронтендом)
header('Content-Type: application/json');

// Подключаемся к базе данных MySQL (хранилищу информации)
require_once 'db.php';
// Проверяем, получилось ли подключиться, если нет — выдаём ошибку
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Ошибка подключения к базе данных']));
}

// Получаем "действие" из URL (например, login для входа или register для регистрации)
$action = $_GET['action'] ?? '';

try {
    // Смотрим, какое действие запросили, и выполняем нужную функцию
    if ($action === 'login') {
        handleLogin($conn); // Вызываем функцию для входа
    } elseif ($action === 'register') {
        handleRegister($conn); // Вызываем функцию для регистрации
    } else {
        // Если действие неизвестное, возвращаем ошибку
        echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
    }
} catch (Exception $e) {
    // Если что-то пошло не так, отправляем сообщение об ошибке
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// Закрываем соединение с базой данных, чтобы не держать его открытым
$conn->close();

// Функция для обработки входа пользователя
function handleLogin($conn) {
    // Берём логин и пароль, которые пользователь ввёл в форме
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    // Проверяем, чтобы поля не были пустыми
    if (empty($login) || empty($password)) {
        throw new Exception('Все поля обязательны для заполнения');
    }

    // Проверяем, ввёл ли пользователь email или телефон
    $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);
    
    // Готовим запрос к базе: ищем пользователя по email или телефону
    if ($isEmail) {
        // Если это email, ищем по email
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    } else {
        // Если это телефон, убираем всё, кроме цифр, и ищем по телефону
        $phone = preg_replace('/[^0-9]/', '', $login);
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE phone = ?");
        $login = $phone;
    }

    // Привязываем введённый логин к запросу
    $stmt->bind_param("s", $login);
    // Выполняем запрос
    $stmt->execute();
    // Получаем результат
    $result = $stmt->get_result();

    // Если пользователь не найден, выдаём ошибку
    if ($result->num_rows === 0) {
        throw new Exception('Пользователь не найден');
    }

    // Получаем данные пользователя из базы
    $user = $result->fetch_assoc();

    // Проверяем, совпадает ли введённый пароль с тем, что в базе
    if (!password_verify($password, $user['password'])) {
        throw new Exception('Неверный пароль');
    }

    // Если всё ок, сохраняем ID и имя пользователя в сессии
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    // Отправляем ответ, что вход успешен, и возвращаем имя пользователя
    echo json_encode([
        'success' => true,
        'username' => $user['username']
    ]);
}

// Функция для регистрации нового пользователя
function handleRegister($conn) {
    // Берём данные из формы (имя, телефон, email, пароль)
    $username = trim($_POST['username'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Проверяем, чтобы все поля были заполнены
    if (empty($username) || empty($phone) || empty($email) || empty($password)) {
        throw new Exception('Все поля обязательны для заполнения');
    }

    // Проверяем, совпадают ли пароль и его подтверждение
    if ($password !== $confirm_password) {
        throw new Exception('Пароли не совпадают');
    }

    // Проверяем, что email правильный
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Некорректный email');
    }

    // Проверяем, что пароль не короче 6 символов
    if (strlen($password) < 6) {
        throw new Exception('Пароль должен содержать минимум 6 символов');
    }

    // Проверяем, нет ли уже пользователя с таким email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        throw new Exception('Пользователь с таким email уже существует');
    }

    // Хешируем пароль, чтобы хранить его безопасно
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Добавляем нового пользователя в базу данных
    $stmt = $conn->prepare("INSERT INTO users (username, phone, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $phone, $email, $password_hash);
    
    // Если не удалось добавить, выдаём ошибку
    if (!$stmt->execute()) {
        throw new Exception('Ошибка при регистрации. Попробуйте позже');
    }

    // Сохраняем данные нового пользователя в сессии
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['username'] = $username;

    // Отправляем ответ, что регистрация успешна
    echo json_encode([
        'success' => true,
        'username' => $username
    ]);
}
?>