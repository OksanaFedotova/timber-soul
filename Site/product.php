<?php
require_once 'db.php'; // здесь PDO подключается

// Получаем ID товара из GET-параметра
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = [];

if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Если товар не найден, редирект
if (!$product) {
    header("Location: catalog.php");
    exit();
}

// Заглушка для дополнительных изображений
$additional_images = ['comod.png', 'comod.png', 'comod.png'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> - Timber Soul</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="container_content">
            <?php require_once 'header.php'; ?>
        </div>

        <main>
            <section class="container_content product-page">
                <div class="product-header">
                    <h1><?= htmlspecialchars($product['name']) ?></h1>
                    <div class="header-counters">
                        <a href="#" class="counter like-counter">
                            <!-- SVG и счётчик -->
                            <span class="counter-badge">0</span>
                        </a>
                        <a href="cart.php" class="counter cart-counter">
                            <!-- SVG и счётчик -->
                            <span class="counter-badge">0</span>
                        </a>
                    </div>
                </div>

                <div class="product-layout">
                    <div class="product-gallery">
                        <div class="thumbnails">
                            <?php foreach ($additional_images as $index => $image): ?>
                                <div class="thumbnail <?= $index === 0 ? 'active' : '' ?>" data-image="<?= ($index + 1) . htmlspecialchars($image) ?>">
                                    <img src="./img/<?= ($index + 1) . htmlspecialchars($image) ?>" alt="Изображение товара <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="main-image">
                            <img src="./img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                    </div>

                    <div class="product-info">
                        <div class="price-section">
                            <span class="current-price"><?= number_format($product['price'], 0, '', ' ') ?> ₽</span>
                            <span class="old-price"><?= number_format($product['old_price'], 0, '', ' ') ?> ₽</span>
                            <span class="discount">-<?= htmlspecialchars($product['discount']) ?>%</span>
                        </div>

                        <div class="product_all_side">
                            <div class="product_left_info">
                                <div class="material-selection">
                                    <h3>1. Порода дерева</h3>
                                    <select class="material-select">
                                        <?php
                                        $materials = ['дуб', 'ясень', 'береза', 'бук'];
                                        foreach ($materials as $material):
                                            $selected = $product['material'] === $material ? 'selected' : '';
                                            echo "<option value=\"$material\" $selected>$material</option>";
                                        endforeach;
                                        ?>
                                    </select>
                                </div>

                                <div class="dimensions">
                                    <h3>2. Размеры (Д х Ш х В, cм):</h3>
                                    <p class="size_product"><?= htmlspecialchars($product['width']) ?> × <?= htmlspecialchars($product['depth']) ?> × <?= htmlspecialchars($product['height']) ?></p>
                                </div>

                                <button class="btn custom-dimensions-btn">Заказать свои размеры</button>

                                <div class="product-actions">
                                    <button class="btn add-to-cart">В корзину</button>
                                    <button class="to_like like-btn" data-id="<?= $product['id'] ?>">
                                        <!-- SVG -->
                                    </button>
                                </div>
                            </div>

                            <div class="support-block">
                                <h3>Нужна помощь?</h3>
                                <p>Мы всегда на связи</p>
                                <div class="support-actions">
                                    <button class="btn support-btn">Позвонить</button>
                                    <button class="btn support-btn">Написать</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-tabs">
                    <div class="tab-header">
                        <button class="tab-link active" data-tab="description">Описание</button>
                        <button class="tab-link" data-tab="characteristics">Характеристики</button>
                        <button class="tab-link" data-tab="production">Срок изготовления</button>
                        <button class="tab-link" data-tab="payment">Способ оплаты</button>
                        <button class="tab-link" data-tab="delivery">Доставка</button>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active" id="description">
                            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                        </div>
                        <div class="tab-pane" id="characteristics">
                            <p><?= nl2br(htmlspecialchars($product['characteristics'])) ?></p>
                        </div>
                        <div class="tab-pane" id="production">
                            <p><?= htmlspecialchars($product['production_time']) ?></p>
                        </div>
                        <div class="tab-pane" id="payment">
                            <p><?= nl2br(htmlspecialchars($product['payment_methods'])) ?></p>
                        </div>
                        <div class="tab-pane" id="delivery">
                            <p><?= nl2br(htmlspecialchars($product['delivery_info'])) ?></p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>
