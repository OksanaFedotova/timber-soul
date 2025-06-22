<?php
// Запускаем сессию, если еще не запущена
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';  // должен содержать подключение $pdo через PDO к PostgreSQL

// Получаем параметры фильтрации из GET с безопасной обработкой
$types = $_GET['types'] ?? [];
$price_min = $_GET['price_min'] ?? '';
$price_max = $_GET['price_max'] ?? '';
$materials = $_GET['materials'] ?? [];

// Формируем SQL с подготовленными параметрами
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

// Фильтр по типу (если есть)
if (!empty($types) && is_array($types)) {
    $placeholders = implode(',', array_fill(0, count($types), '?'));
    $sql .= " AND type IN ($placeholders)";
    $params = array_merge($params, $types);
}

// Фильтр по цене
if ($price_min !== '' && is_numeric($price_min)) {
    $sql .= " AND price >= ?";
    $params[] = $price_min;
}
if ($price_max !== '' && is_numeric($price_max)) {
    $sql .= " AND price <= ?";
    $params[] = $price_max;
}

// Фильтр по материалу (если есть)
if (!empty($materials) && is_array($materials)) {
    $placeholders = implode(',', array_fill(0, count($materials), '?'));
    $sql .= " AND material IN ($placeholders)";
    $params = array_merge($params, $materials);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Каталог - Timber Soul</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <div class="container">

        <div class="container_content">
            <?php require_once 'header.php'; ?>
        </div>
        <main>

            <section class="container_content catalog-page">
                <div class="catalog_header">
                    <h1>Каталог</h1>

                    <div class="sort_not_title">
                        <div class="sort_block">
                            <span class="sort_how">Сортировать:</span>
                            <select id="sort-select">
                                <option value="">по популярности</option>
                                <option value="price-asc">по цене</option>
                                <option value="name-asc">по имени (А-Я)</option>
                                <option value="name-desc">по имени (Я-А)</option>
                            </select>
                        </div>
                        <div class="sorting-options">
                            <div class="header-counters">
                                <a href="#" class="counter like-counter">
                                    <!-- svg здесь -->
                                    <span class="counter-badge">0</span>
                                </a>
                                <a href="cart.php" class="counter cart-counter">
                                    <!-- svg здесь -->
                                    <span class="counter-badge">0</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="catalog-layout">
                    <!-- Блок фильтров -->
                    <aside class="filters-sidebar">
                        <form id="filter-form">
                            <div class="filter-group">
                                <h3>Тип товара</h3>
                                <div class="filter-options">
                                    <label><input type="checkbox" name="types[]" value="кровать" <?php echo in_array('кровать', $types) ? 'checked' : ''; ?>> Кровати</label>
                                    <label><input type="checkbox" name="types[]" value="шкаф" <?php echo in_array('шкаф', $types) ? 'checked' : ''; ?>> Шкафы</label>
                                    <label><input type="checkbox" name="types[]" value="кухонный гарнитур" <?php echo in_array('кухонный гарнитур', $types) ? 'checked' : ''; ?>> Кухонные гарнитуры</label>
                                    <label><input type="checkbox" name="types[]" value="модульная кухня" <?php echo in_array('модульная кухня', $types) ? 'checked' : ''; ?>> Модульные кухни</label>
                                    <label><input type="checkbox" name="types[]" value="тумба" <?php echo in_array('тумба', $types) ? 'checked' : ''; ?>> Тумбы</label>
                                    <label><input type="checkbox" name="types[]" value="комод" <?php echo in_array('комод', $types) ? 'checked' : ''; ?>> Комоды</label>
                                </div>
                            </div>

                            <div class="filter-group">
                                <h3>Цена, ₽</h3>
                                <div class="filter-options">
                                    <div class="price-range">
                                        <input type="number" name="price_min" placeholder="от 40" value="<?php echo htmlspecialchars($price_min); ?>">
                                        <input type="number" name="price_max" placeholder="до 2312455" value="<?php echo htmlspecialchars($price_max); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="filter-group">
                                <h3>Материал корпуса</h3>
                                <div class="filter-options">
                                    <label><input type="checkbox" name="materials[]" value="береза" <?php echo in_array('береза', $materials) ? 'checked' : ''; ?_
