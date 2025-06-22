# Используем официальный образ PHP с Apache
FROM php:8.2-apache

# Обновляем пакеты и устанавливаем зависимости для pdo_pgsql
RUN apt-get update && apt-get install -y libpq-dev \
  && docker-php-ext-install pdo_pgsql \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# Копируем содержимое папки Site/ в корень веб-сервера
COPY Site/ /var/www/html/

# Включаем модуль mod_rewrite (для .htaccess и красивых URL)
RUN a2enmod rewrite
