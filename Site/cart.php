<?php
session_start();
require_once 'db.php';

// Получаем товары из корзины (из localStorage или БД)
$cart_items = [];
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

if (!empty($cart)) {
    // Защита от SQL-инъекций
    $ids = array_map('intval', $cart);
    $ids_str = implode(",", $ids);

    $sql = "SELECT * FROM products WHERE id IN ($ids_str)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }
}

// Обработка действий с корзиной
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = $_POST['product_id'] ?? 0;

    if ($action === 'update_quantity') {
        $quantity = $_POST['quantity'] ?? 1;
        // Обновляем количество в корзине
    } elseif ($action === 'remove_item') {
        // Удаляем товар из корзины
        $cart = array_diff($cart, [$product_id]);
        setcookie('cart', json_encode(array_values($cart)), time() + 86400 * 30, '/');
        header("Location: cart.php");
        exit();
    } elseif ($action === 'toggle_favorite') {
        // Добавляем/удаляем из избранного
        $favorites = json_decode($_COOKIE['favorites'] ?? '[]', true);
        if (in_array($product_id, $favorites)) {
            $favorites = array_diff($favorites, [$product_id]);
        } else {
            $favorites[] = $product_id;
        }
        setcookie('favorites', json_encode(array_values($favorites)), time() + 86400 * 30, '/');
    }
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Корзина - Timber Soul</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <div class="container_content">
            <?php require_once 'header.php'; ?>
        </div>
        <main>
            <section class="container_content cart-page">
                <h1>Корзина</h1>

                <div class="cart-layout">
                    <div class="cart-items">
                        <?php if (!empty($cart_items)): ?>
                            <?php foreach ($cart_items as $item): ?>
                                <div class="cart-item" data-id="<?php echo $item['id']; ?>">
                                    <div class="item-image">
                                        <img src="./img/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                    </div>
                                    <div class="item-info">
                                        <h3><?php echo $item['name']; ?></h3>
                                        <p class="item-description">Данный товар не участвует в <br> дополнительных скидках на заказ</p>

                                        <div class="item-actions">
                                            <button class="like-btn" onclick="toggleFavorite(<?php echo $item['id']; ?>)">
                                                <svg width="24" height="24" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M2.66275 11.2136L8.82377 17.7066C10.0068 18.9533 11.9932 18.9533 13.1762 17.7066L19.3372 11.2136C21.5542 8.87708 21.5543 5.08892 19.3373 2.75244C17.1203 0.415973 13.5258 0.415974 11.3088 2.75245V2.75245C11.1409 2.92935 10.8591 2.92935 10.6912 2.75245V2.75245C8.47421 0.415974 4.87975 0.415974 2.66275 2.75245C0.44575 5.08892 0.445751 8.87708 2.66275 11.2136Z" stroke="white" stroke-width="1.5" />
                                                </svg>
                                            </button>

                                            <button class="remove-btn" onclick="removeItem(<?php echo $item['id']; ?>)">
                                                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="50" height="50" rx="8" fill="#538E70" />
                                                    <path d="M15.3095 19.7068C14.3753 18.5267 15.2157 16.7895 16.7208 16.7895H33.2792C34.7843 16.7895 35.6247 18.5267 34.6905 19.7068L34.0798 20.4782C33.3805 21.3615 33 22.4551 33 23.5817V33C33 35.2092 31.2091 37 29 37H21C18.7909 37 17 35.2092 17 33V23.5817C17 22.4551 16.6195 21.3615 15.9202 20.4782L15.3095 19.7068Z" stroke="white" stroke-width="1.5" />
                                                    <path d="M30.3334 16.7895L29.4702 14.3362C29.1885 13.5356 28.4322 13 27.5835 13H22.4165C21.5678 13 20.8116 13.5356 20.5299 14.3362L19.6667 16.7895" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                                                </svg>

                                            </button>

                                            <div class="quantity-control">
                                                <button class="quantity-btn minus" onclick="updateQuantity(<?php echo $item['id']; ?>, -1)">-</button>
                                                <span class="quantity">1</span>
                                                <button class="quantity-btn plus" onclick="updateQuantity(<?php echo $item['id']; ?>, 1)">+</button>
                                            </div>

                                            <div class="item-prices">
                                                <span class="current-price"><?php echo number_format($item['price'], 0, '', ' '); ?> ₽</span>
                                                <span class="old-price"><?php echo number_format($item['old_price'], 0, '', ' '); ?> ₽</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-cart">
                                <p>Ваша корзина пуста</p>
                                <a href="catalog.php" class="btn">Перейти в каталог</a>
                            </div>
                        <?php endif; ?>

                        <div class="customer-info">
                            <h3>Информация о покупателе</h3>
                            <form>
                                <div class="form-group">
                                    <input type="text" placeholder="Ваше имя">
                                </div>
                                <div class="form-group">
                                    <input type="tel" placeholder="Телефон" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" placeholder="Электронная почта">
                                </div>
                            </form>
                        </div>
                        <div class="dostavka-info">
                            <h3>Доставка</h3>
                            <form>
                                <div class="form-group">
                                    <input type="text" placeholder="Населенный пункт">
                                </div>
                                <div class="form-group">
                                    <input type="text" placeholder="Улица" required>
                                </div>
                                <div class="form-groups">
                                    <div class="form-group">
                                        <input type="text" placeholder="Дом" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="Подъезд" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="Квартира/офис" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="Домофон" required>
                                    </div>
                                </div>
                                <textarea rows="5" placeholder="Комментарий к заказу"></textarea>
                            </form>
                        </div>
                    </div>

                    <?php if (!empty($cart_items)): ?>
                        <div class="cart-summary">


                            <div class="summary_cart">
                                <p>Ваша корзина</p>
                                <p><?php echo count($cart_items); ?> товар(а)</p>
                            </div>

                            <div class="summary-details">
                                <p>Итоговая стоимость</p>
                                <p class="total-price"><?php echo number_format(array_sum(array_column($cart_items, 'price')), 0, '', ' '); ?> ₽</p>
                            </div>



                            <div class="payment-methods">
                                <h3>Способы оплаты</h3>
                                <label class="custom-checkbox"><input type="checkbox" name="payment" value="Наличные">Наличные<span class="checkmark"></span></label>
                                <label class="custom-checkbox"><input type="checkbox" name="payment" value="Оплата СПБ / банковской картой">Оплата СПБ / банковской картой<span class="checkmark"></span></label>
                                <label class="custom-checkbox"><input type="checkbox" name="payment" value="Банковской картой онлайн">Банковской картой онлайн<span class="checkmark"></span></label>
                                <label class="custom-checkbox"><input type="checkbox" name="payment" value="Безналичный расчет">Безналичный расчет<span class="checkmark"></span></label>
                            </div>

                            <div class="bonus-info">
                                <p>Начислим 828 бонусов за заказ</p>
                                <p>Бонусы придут в течении 14 дней после доставки заказа</p>
                            </div>

                            <button class="btn checkout-btn">Оформить заказ</button>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

    <script src="script.js"></script>
    <script>
        function updateQuantity(productId, change) {
            // Логика обновления количества
            const quantityElement = document.querySelector(`.cart-item[data-id="${productId}"] .quantity`);
            let quantity = parseInt(quantityElement.textContent) + change;
            if (quantity < 1) quantity = 1;
            quantityElement.textContent = quantity;

            // Обновляем цену
            const priceElement = document.querySelector(`.cart-item[data-id="${productId}"] .current-price`);
            const basePrice = parseInt(priceElement.dataset.basePrice || priceElement.textContent.replace(/\D/g, ''));
            const newPrice = basePrice * quantity;
            priceElement.textContent = newPrice.toLocaleString('ru-RU') + ' ₽';
            priceElement.dataset.basePrice = basePrice; // Сохраняем базовую цену

            // Обновляем итоговую сумму
            updateTotalPrice();
        }

        function removeItem(productId) {
            if (confirm('Удалить товар из корзины?')) {
                // Обновляем localStorage
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                cart = cart.filter(id => id != productId);
                localStorage.setItem('cart', JSON.stringify(cart));

                fetch('cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=remove_item&product_id=${productId}`
                }).then(() => location.reload());
            }
        }

        function toggleFavorite(productId) {
            const btn = document.querySelector(`.cart-item[data-id="${productId}"] .like-btn`);
            btn.classList.toggle('active');

            fetch('cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=toggle_favorite&product_id=${productId}`
            }).then(() => updateHeaderCounters());
        }

        function updateTotalPrice() {
            let total = 0;
            document.querySelectorAll('.cart-item').forEach(item => {
                const price = parseInt(item.querySelector('.current-price').textContent.replace(/\D/g, ''));
                total += price;
            });
            document.querySelector('.total-price').textContent = total.toLocaleString('ru-RU') + ' ₽';
        }
    </script>
</body>

</html>
<?php $conn->close(); ?>