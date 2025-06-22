# Используем официальный образ PHP с Apache
FROM php:8.2-apache

# Копируем содержимое папки Site/ в корень веб-сервера
COPY Site/ /var/www/html/

# Включаем модуль mod_rewrite (на случай, если пригодится .htaccess или красивые URL)
RUN a2enmod rewrite

# Устанавливаем расширение для подключения к PostgreSQL через PDO
RUN docker-php-ext-install pdo_pgsql
