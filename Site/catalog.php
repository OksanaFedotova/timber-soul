<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

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

// Подготавливаем и выполняем запрос через PDO
$stmt = $conn->prepare($sql);
$stmt->execute($params);

// Получаем результат
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
                <!-- ... остальная часть шапки ... -->
            </div>

            <div class="catalog-layout">
                <!-- Блок фильтров -->
                <aside class="filters-sidebar">
                    <form id="filter-form">
                        <!-- ... фильтры ... -->
                    </form>
                </aside>

                <!-- Блок товаров -->
                <div class="products-flex">
                    <?php if (!empty($result)): ?>
                        <?php foreach ($result as $row): ?>
                            <div class="product-card" data-id="<?php echo $row['id']; ?>">
                                <div class="product-image">
                                    <img src="./img/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" onclick="window.location.href='product.php?id=<?php echo $row['id']; ?>'">
                                </div>
                                <div class="product-info">
                                    <div class="price-section">
                                        <span class="current-price"><?php echo number_format($row['price'], 0, '', ' '); ?> ₽</span>
                                        <span class="old-price"><?php echo number_format($row['old_price'], 0, '', ' '); ?> ₽</span>
                                        <span class="discount">-<?php echo $row['discount']; ?>%</span>
                                    </div>
                                    <h3 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h3>
                                    <div class="product-specs">
                                        <p>Ширина: <span><?php echo htmlspecialchars($row['width']); ?> </span></p>
                                        <p>Высота: <span><?php echo htmlspecialchars($row['height']); ?></span></p>
                                        <p>Глубина: <span><?php echo htmlspecialchars($row['depth']); ?> </span></p>
                                    </div>
                                    <div class="buttons_product">
                                        <button class="btn add-to-cart">В корзину </button>
                                        <button class="to_like like-btn">
                                            <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.66275 11.2136L8.82377 17.7066C10.0068 18.9533 11.9932 18.9533 13.1762 17.7066L19.3372 11.2136C21.5542 8.87708 21.5543 5.08892 19.3373 2.75244C17.1203 0.415973 13.5258 0.415974 11.3088 2.75245V2.75245C11.1409 2.92935 10.8591 2.92935 10.6912 2.75245V2.75245C8.47421 0.415974 4.87975 0.415974 2.66275 2.75245C0.44575 5.08892 0.445751 8.87708 2.66275 11.2136Z" stroke="white" stroke-width="1.5" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-results">
                            <p>Товары не найдены. Попробуйте изменить параметры фильтрации.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

</div>
<script src="script.js"></script>
</body>
</html>
