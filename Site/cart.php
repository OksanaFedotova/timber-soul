<?php
require_once 'db.php';

session_start();
header('Content-Type: text/html; charset=utf-8');

// Получаем товары из корзины (из cookie)
$cart_items = [];
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

if (!empty($cart) && is_array($cart)) {
    $ids = array_filter(array_map('intval', $cart));
    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Обработка действий с корзиной
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = intval($_POST['product_id'] ?? 0);

    if ($action === 'update_quantity') {
        // Здесь должна быть логика обновления количества в куках (не реализовано)
    } elseif ($action === 'remove_item') {
        $cart = array_filter($cart, fn($id) => intval($id) !== $product_id);
        setcookie('cart', json_encode(array_values($cart)), time() + 86400 * 30, '/');
        header("Location: cart.php");
        exit;
    } elseif ($action === 'toggle_favorite') {
        $favorites = json_decode($_COOKIE['favorites'] ?? '[]', true);
        if (in_array($product_id, $favorites)) {
            $favorites = array_diff($favorites, [$product_id]);
        } else {
            $favorites[] = $product_id;
        }
        setcookie('favorites', json_encode(array_values($favorites)), time() + 86400 * 30, '/');
        echo json_encode(['success' => true]);
        exit;
    }
}
?>
