<?php
session_start();
require_once 'db.php';

$product_id = $_GET['id'] ?? 0;
$product = [];

if ($product_id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

if (!$product) {
    header("Location: catalog.php");
    exit();
}

// Получаем дополнительные изображения для товара (в реальном проекте нужно создать таблицу product_images)
$additional_images = ['comod.png', 'comod.png', 'comod.png']; // Заглушка
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> - Timber Soul</title>
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
            <section class="container_content product-page">
                <div class="product-header">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>

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

                <div class="product-layout">
                    <!-- Галерея товара -->
                    <div class="product-gallery">
                        <div class="thumbnails">
                            <?php foreach ($additional_images as $index => $image): ?>
                                <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" data-image="<?php echo ($index + 1) . htmlspecialchars($image); ?>">
                                    <img src="./img/<?php echo ($index + 1) . htmlspecialchars($image); ?>" alt="Изображение товара <?php echo $index + 1; ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="main-image">
                            <img src="./img/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                    </div>

                    <!-- Информация о товаре -->
                    <div class="product-info">
                        <div class="price-section">
                            <span class="current-price"><?php echo number_format($product['price'], 0, '', ' '); ?> ₽</span>
                            <span class="old-price"><?php echo number_format($product['old_price'], 0, '', ' '); ?> ₽</span>
                            <span class="discount">-<?php echo $product['discount']; ?>%</span>
                        </div>

                        <div class="product_all_side">
                            <div class="product_left_info">
                                <div class="material-selection">
                                    <h3>1. Порода дерева</h3>
                                    <select class="material-select">
                                        <option value="дуб" <?php echo $product['material'] === 'дуб' ? 'selected' : ''; ?>>Дуб</option>
                                        <option value="ясень" <?php echo $product['material'] === 'ясень' ? 'selected' : ''; ?>>Ясень</option>
                                        <option value="береза" <?php echo $product['material'] === 'береза' ? 'selected' : ''; ?>>Береза</option>
                                        <option value="бук" <?php echo $product['material'] === 'бук' ? 'selected' : ''; ?>>Бук</option>
                                    </select>
                                </div>

                                <div class="dimensions">
                                    <h3>2. Размеры (Д х Ш х В, cм):</h3>
                                    <p class="size_product"><?php echo $product['width']; ?> × <?php echo $product['depth']; ?> × <?php echo $product['height']; ?></p>
                                </div>

                                <button class="btn custom-dimensions-btn">Заказать свои размеры</button>

                                <div class="product-actions">
                                    <button class="btn add-to-cart">В корзину</button>
                                    <button class="to_like like-btn" data-id="<?php echo $product['id']; ?>">
                                        <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.66275 11.2136L8.82377 17.7066C10.0068 18.9533 11.9932 18.9533 13.1762 17.7066L19.3372 11.2136C21.5542 8.87708 21.5543 5.08892 19.3373 2.75244C17.1203 0.415973 13.5258 0.415974 11.3088 2.75245V2.75245C11.1409 2.92935 10.8591 2.92935 10.6912 2.75245V2.75245C8.47421 0.415974 4.87975 0.415974 2.66275 2.75245C0.44575 5.08892 0.445751 8.87708 2.66275 11.2136Z" stroke="white" stroke-width="1.5" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="support-block">
                                <h3>Нужна помощь?</h3>
                                <p>Мы всегда на связи</p>
                                <div class="support-actions">
                                    <button class="btn support-btn">Позвонить <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M17.5621 18.2183C15.6077 20.1726 10.6028 18.3363 6.38327 14.1167C2.16372 9.89718 0.327391 4.89227 2.2817 2.93795L3.56847 1.65118C4.4568 0.762856 5.92054 0.786338 6.83784 1.70363L8.83092 3.69672C9.74822 4.61401 9.7717 6.07776 8.88337 6.96609L8.60699 7.24247C8.12737 7.72209 8.08045 8.49581 8.5261 9.03587C8.95597 9.55679 9.4194 10.0756 9.92188 10.5781C10.4244 11.0806 10.9432 11.544 11.4641 11.9739C12.0042 12.4196 12.7779 12.3726 13.2575 11.893L13.5339 11.6166C14.4222 10.7283 15.886 10.7518 16.8033 11.6691L18.7964 13.6622C19.7137 14.5795 19.7371 16.0432 18.8488 16.9315L17.5621 18.2183Z" stroke="#383838" stroke-width="1.5" />
                                        </svg>
                                    </button>
                                    <button class="btn support-btn">Написать <svg width="22" height="21" viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.625 19.7002L3.09352 20.2858L3.09354 20.2858L2.625 19.7002ZM5.45216 17.4383L5.9207 18.0239L5.45216 17.4383ZM16 1V1.75C18.3472 1.75 20.25 3.65279 20.25 6H21H21.75C21.75 2.82436 19.1756 0.25 16 0.25V1ZM21 6H20.25V12H21H21.75V6H21ZM21 12H20.25C20.25 14.3472 18.3472 16.25 16 16.25V17V17.75C19.1756 17.75 21.75 15.1756 21.75 12H21ZM16 17V16.25H6.7016V17V17.75H16V17ZM5.45216 17.4383L4.98361 16.8527L2.15646 19.1146L2.625 19.7002L3.09354 20.2858L5.9207 18.0239L5.45216 17.4383ZM2.625 19.7002L2.15648 19.1145C1.99305 19.2453 1.75 19.1289 1.75 18.9189H1H0.25C0.25 20.386 1.94742 21.2027 3.09352 20.2858L2.625 19.7002ZM1 18.9189H1.75V6H1H0.25V18.9189H1ZM1 6H1.75C1.75 3.65279 3.65279 1.75 6 1.75V1V0.25C2.82436 0.25 0.25 2.82436 0.25 6H1ZM6 1V1.75H16V1V0.25H6V1ZM6.7016 17V16.25C6.07712 16.25 5.47124 16.4625 4.98361 16.8527L5.45216 17.4383L5.9207 18.0239C6.14235 17.8466 6.41775 17.75 6.7016 17.75V17Z" fill="#2C2C2C" />
                                            <circle cx="6.04999" cy="9.0498" r="1.25" fill="#2C2C2C" />
                                            <circle cx="11.05" cy="9.0498" r="1.25" fill="#2C2C2C" />
                                            <circle cx="16.05" cy="9.0498" r="1.25" fill="#2C2C2C" />
                                        </svg>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Табы с описанием товара -->
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
                            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                        </div>

                        <div class="tab-pane" id="characteristics">
                            <p><?php echo nl2br(htmlspecialchars($product['characteristics'])); ?></p>
                        </div>

                        <div class="tab-pane" id="production">
                            <p><?php echo htmlspecialchars($product['production_time']); ?></p>
                        </div>

                        <div class="tab-pane" id="payment">
                            <p><?php echo nl2br(htmlspecialchars($product['payment_methods'])); ?></p>
                        </div>

                        <div class="tab-pane" id="delivery">
                            <p><?php echo nl2br(htmlspecialchars($product['delivery_info'])); ?></p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="script.js"></script>

</body>

</html>
<?php $conn->close(); ?>