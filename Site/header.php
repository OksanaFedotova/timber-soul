<?php
session_start();
require_once 'db.php';
?>
<header>
    <a href="/"><div class="logo">Timber Soul</div></a>
    <div class="header_search">
        <input type="text" placeholder="Что будем искать?">
        <img src="./img/search.svg" alt="" class="search">
    </div>
    <div class="user-actions">
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>Привет, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php">Выход</a>
        <?php else: ?>
            <a href="#" class="open-auth-modal" data-tab="login">Вход</a>
            <a href="#" class="open-auth-modal" data-tab="register">Регистрация</a>
        <?php endif; ?>
    </div>
</header>

<!-- Модальное окно авторизации -->
<div id="auth-modal" class="modal">
    <div class="modal-content">


        <div class="auth-tabs">
            <div class="tab-header">
                <button class="tab-link" data-tab="login">Вход</button>
                <button class="tab-link" data-tab="register">Регистрация</button>
                <span class="close-modal"><svg width="20" height="20" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.5">
                            <path d="M1.49999 20.9998L21.4998 1" stroke="#383838" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M1.49999 1.00018L21.4998 21" stroke="#383838" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </g>
                    </svg>
                </span>
            </div>

            <div class="tab-content">
                <!-- Форма входа -->
                <div id="login" class="tab-pane">
                    <form id="login-form" method="post">
                        <input type="text" name="login" placeholder="Телефон или почта" required>
                        <input type="password" name="password" placeholder="Пароль" required>
                        <div id="login-error" class="error-message"></div>
                        <button type="submit" class="btn">Войти!</button>
                    </form>
                </div>

                <!-- Форма регистрации -->
                <div id="register" class="tab-pane">
                    <form id="register-form" method="post">
                        <input type="text" name="username" placeholder="Ваше имя" required>
                        <input type="tel" name="phone" placeholder="Телефон" required>
                        <input type="email" name="email" placeholder="Электронная почта" required>
                        <input type="password" name="password" placeholder="Пароль" required>
                        <input type="password" name="confirm_password" placeholder="Подтвердите пароль" required>
                        <div id="register-error" class="error-message"></div>
                        <button type="submit" class="btn">Зарегистрироваться!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Открытие модального окна с нужной вкладкой
        document.querySelectorAll('.open-auth-modal').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const tabId = this.getAttribute('data-tab');
                const modal = document.getElementById('auth-modal');

                // Показываем модальное окно
                modal.style.display = 'block';

                // Активируем нужную вкладку
                switchTab(tabId);
            });
        });

        // Закрытие модального окна
        document.querySelector('.close-modal')?.addEventListener('click', function() {
            document.getElementById('auth-modal').style.display = 'none';
        });

        // Закрытие при клике вне окна
        window.addEventListener('click', function(event) {
            if (event.target.className === 'modal') {
                document.getElementById('auth-modal').style.display = 'none';
            }
        });

        // Переключение табов внутри модального окна
        const tabLinks = document.querySelectorAll('.tab-link');
        tabLinks.forEach(link => {
            link.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                switchTab(tabId);
            });
        });

        // Функция переключения табов
        function switchTab(tabId) {
            // Убираем активный класс у всех табов
            document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

            // Добавляем активный класс к выбранному табу
            document.querySelector(`.tab-link[data-tab="${tabId}"]`).classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }

        // Обработка формы входа
        document.getElementById('login-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('auth.php?action=login', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        document.getElementById('login-error').style.display = 'block';
                        document.getElementById('login-error').textContent = data.message;
                    }
                });
        });

        // Обработка формы регистрации
        document.getElementById('register-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            if (formData.get('password') !== formData.get('confirm_password')) {
                document.getElementById('register-error').style.display = 'block';
                document.getElementById('register-error').textContent = 'Пароли не совпадают';
                return;
            }

            fetch('auth.php?action=register', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        document.getElementById('register-error').style.display = 'none';
                        document.getElementById('register-error').textContent = data.message;
                    }
                });
        });
    });
</script>