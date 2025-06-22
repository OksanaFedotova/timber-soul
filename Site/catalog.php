<?php
session_start();
require_once 'db.php';

// Получаем параметры фильтрации
$types = $_GET['types'] ?? [];
$price_min = $_GET['price_min'] ?? '';
$price_max = $_GET['price_max'] ?? '';
$materials = $_GET['materials'] ?? [];

// Формируем SQL запрос
$sql = "SELECT * FROM products WHERE 1=1";

// Фильтр по типу
if (!empty($types)) {
    $types_str = implode("','", $types);
    $sql .= " AND type IN ('$types_str')";
}

// Фильтр по цене
if ($price_min !== '') {
    $sql .= " AND price >= $price_min";
}
if ($price_max !== '') {
    $sql .= " AND price <= $price_max";
}


// Фильтр по материалу
if (!empty($materials)) {
    $materials_str = implode("','", $materials);
    $sql .= " AND material IN ('$materials_str')";
}


$result = $conn->query($sql);
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
            <?php
            require_once 'header.php';
            ?>
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
                                    <svg width="24" height="24" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.66275 11.2136L8.82377 17.7066C10.0068 18.9533 11.9932 18.9533 13.1762 17.7066L19.3372 11.2136C21.5542 8.87708 21.5543 5.08892 19.3373 2.75244C17.1203 0.415973 13.5258 0.415974 11.3088 2.75245V2.75245C11.1409 2.92935 10.8591 2.92935 10.6912 2.75245V2.75245C8.47421 0.415974 4.87975 0.415974 2.66275 2.75245C0.44575 5.08892 0.445751 8.87708 2.66275 11.2136Z" stroke="#6B6B6B" stroke-width="1.5" />
                                    </svg>
                                    <span class="counter-badge">0</span>
                                </a>
                                <a href="cart.php" class="counter cart-counter">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 10V6C8 3.79086 9.79086 2 12 2C14.2091 2 16 3.79086 16 6V10M3 10H21L20.0826 18.3255C19.935 20.0173 18.5075 21.3101 16.811 21.2539L15.3774 21.2066C13.9299 21.1585 12.0701 21.1585 10.6226 21.2066L9.18904 21.2539C7.49251 21.3101 6.06503 20.0173 5.91745 18.3255L5 10H3Z" stroke="#6B6B6B" stroke-width="1.5" />
                                    </svg>
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
                                        <input type="number" name="price_min" placeholder="от 40" value="<?php echo $price_min; ?>">
                                        <input type="number" name="price_max" placeholder="до 2312455" value="<?php echo $price_max; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="filter-group">
                                <h3>Материал корпуса</h3>
                                <div class="filter-options">
                                    <label><input type="checkbox" name="materials[]" value="береза" <?php echo in_array('береза', $materials) ? 'checked' : ''; ?>> Березовый массив</label>
                                    <label><input type="checkbox" name="materials[]" value="дуб" <?php echo in_array('дуб', $materials) ? 'checked' : ''; ?>> Дубовый массив</label>
                                    <label><input type="checkbox" name="materials[]" value="ясень" <?php echo in_array('ясень', $materials) ? 'checked' : ''; ?>> Ясеневый массив</label>
                                    <label><input type="checkbox" name="materials[]" value="бук" <?php echo in_array('бук', $materials) ? 'checked' : ''; ?>> Буковый массив</label>
                                </div>
                            </div>

                            <div class="filter-actions">
                                <button type="submit" class="btn apply-btn">Применить</button>
                                <button type="button" class="btn clear-btn" onclick="clearFilters()">Очистить фильтр</button>
                            </div>
                        </form>
                    </aside>

                    <!-- Блок товаров -->
                    <div class="products-flex">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="product-card" data-id="<?php echo $row['id']; ?>">
                                    <div class="product-image">
                                        <img src="./img/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" onclick="window.location.href='product.php?id=<?php echo $row['id']; ?>'">
                                    </div>
                                    <div class="product-info">
                                        <div class="price-section">
                                            <span class="current-price"><?php echo number_format($row['price'], 0, '', ' '); ?> ₽</span>
                                            <span class="old-price"><?php echo number_format($row['old_price'], 0, '', ' '); ?> ₽</span>
                                            <span class="discount">-<?php echo $row['discount']; ?>%</span>
                                        </div>
                                        <h3 class="product-name"><?php echo $row['name']; ?></h3>
                                        <div class="product-specs">
                                            <p>Ширина: <span><?php echo $row['width']; ?> </span></p>
                                            <p>Высота: <span><?php echo $row['height']; ?></span></p>
                                            <p>Глубина: <span><?php echo $row['depth']; ?> </span></p>
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
                            <?php endwhile; ?>
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
<?php $conn->close(); ?>