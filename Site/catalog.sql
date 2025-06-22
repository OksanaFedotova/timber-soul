-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 31 2025 г., 13:35
-- Версия сервера: 10.3.22-MariaDB
-- Версия PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `catalog`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `old_price` decimal(10,2) NOT NULL,
  `discount` int(11) NOT NULL,
  `width` int(10) NOT NULL,
  `height` int(10) NOT NULL,
  `depth` int(10) NOT NULL,
  `material` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `characteristics` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `production_time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_methods` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_info` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `type`, `price`, `old_price`, `discount`, `width`, `height`, `depth`, `material`, `image`, `description`, `characteristics`, `production_time`, `payment_methods`, `delivery_info`) VALUES
(1, 'Комод \"Прескотт\"', 'комод', '23535.00', '32521.00', 35, 240, 213, 50, 'дуб', 'comod.png', 'Комод «Прескотт» дополняет интерьер помещения – гостиной, спальни или прихожей – а благодаря своей вместительности также является важным функциональным элементом. В мебельном изделии доступны четыре зоны для размещения вещей – классические ящики с навесными петлями и два отдела с системой tip-on. Последние открываются легким нажатием на поверхность. Металлический каркас проходит по всему периметру модели, завершаясь четырьмя небольшими ножками. Такая конструкция обеспечивает необходимую устойчивость при полной загрузке. Выбор цвета покрытия древесины позволяет подстроиться под общий дизайн помещения.', 'Материал: массив дуба\nФурнитура: металлическая\nКоличество ящиков: 4\nМеханизм открывания: tip-on\nГарантия: 2 года', '14-21 день', 'Наличными при получении\nБанковской картой онлайн\nРассрочка на 6 месяцев', 'Доставка по Москве - бесплатно\nДоставка по России - от 1500 руб.\nСамовывоз со склада'),
(2, 'Шкаф', 'шкаф', '21000.00', '30000.00', 30, 240, 213, 40, 'ясень', 'comod.png', NULL, NULL, NULL, NULL, NULL),
(3, 'Стол', 'стол', '15000.00', '20000.00', 25, 240, 213, 60, 'сосна', 'comod.png', NULL, NULL, NULL, NULL, NULL),
(4, 'Стол', 'стол', '10000.00', '20000.00', 50, 250, 120, 30, 'дуб', 'comod.png', NULL, NULL, NULL, NULL, NULL),
(5, 'Комод \"Прескотт\"', 'комод', '23535.00', '32521.00', 35, 221, 215, 50, 'дуб', 'comod.png', NULL, NULL, NULL, NULL, NULL),
(6, 'Комод \"Ля-ля\"', 'комод', '50000.00', '25000.00', 50, 240, 213, 50, 'сосна', 'comod.png', 'Комод «Ля-ля» дополняет интерьер помещения – гостиной, спальни или прихожей – а благодаря своей вместительности также является важным функциональным элементом. В мебельном изделии доступны четыре зоны для размещения вещей – классические ящики с навесными петлями и два отдела с системой tip-on. Последние открываются легким нажатием на поверхность. Металлический каркас проходит по всему периметру модели, завершаясь четырьмя небольшими ножками. Такая конструкция обеспечивает необходимую устойчивость при полной загрузке. Выбор цвета покрытия древесины позволяет подстроиться под общий дизайн помещения.', 'Материал: массив сосны\r\nФурнитура: металлическая\r\nКоличество ящиков: 4\r\nМеханизм открывания: tip-on\r\nГарантия: 2 года', '14-21 день', 'Наличными при получении\r\nБанковской картой онлайн\r\nРассрочка на 6 месяцев', 'Доставка по Москве - бесплатно\r\nДоставка по России - от 1500 руб.\r\nСамовывоз со склада');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
