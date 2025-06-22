-- Dump adapted for PostgreSQL

BEGIN;

-- Таблица orders
CREATE TABLE orders (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица products
CREATE TABLE products (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  type VARCHAR(50) NOT NULL,
  price NUMERIC(10,2) NOT NULL,
  old_price NUMERIC(10,2) NOT NULL,
  discount INTEGER NOT NULL,
  width INTEGER NOT NULL,
  height INTEGER NOT NULL,
  depth INTEGER NOT NULL,
  material VARCHAR(50) NOT NULL,
  image VARCHAR(255) NOT NULL,
  description TEXT,
  characteristics TEXT,
  production_time VARCHAR(50),
  payment_methods TEXT,
  delivery_info TEXT
);

-- Вставка данных в products
INSERT INTO products (id, name, type, price, old_price, discount, width, height, depth, material, image, description, characteristics, production_time, payment_methods, delivery_info) VALUES
(1, 'Комод "Прескотт"', 'комод', 23535.00, 32521.00, 35, 240, 213, 50, 'дуб', 'comod.png', 'Комод «Прескотт» дополняет интерьер помещения – гостиной, спальни или прихожей – а благодаря своей вместительности также является важным функциональным элементом. В мебельном изделии доступны четыре зоны для размещения вещей – классические ящики с навесными петлями и два отдела с системой tip-on. Последние открываются легким нажатием на поверхность. Металлический каркас проходит по всему периметру модели, завершаясь четырьмя небольшими ножками. Такая конструкция обеспечивает необходимую устойчивость при полной загрузке. Выбор цвета покрытия древесины позволяет подстроиться под общий дизайн помещения.', 'Материал: массив дуба\nФурнитура: металлическая\nКоличество ящиков: 4\nМеханизм открывания: tip-on\nГарантия: 2 года', '14-21 день', 'Наличными при получении\nБанковской картой онлайн\nРассрочка на 6 месяцев', 'Доставка по Москве - бесплатно\nДоставка по России - от 1500 руб.\nСамовывоз со склада'),
(2, 'Шкаф', 'шкаф', 21000.00, 30000.00, 30, 240, 213, 40, 'ясень', 'comod.png', NULL, NULL, NULL, NULL, NULL),
(3, 'Стол', 'стол', 15000.00, 20000.00, 25, 240, 213, 60, 'сосна', 'comod.png', NULL, NULL, NULL, NULL, NULL),
(4, 'Стол', 'стол', 10000.00, 20000.00, 50, 250, 120, 30, 'дуб', 'comod.png', NULL, NULL, NULL, NULL, NULL),
(5, 'Комод "Прескотт"', 'комод', 23535.00, 32521.00, 35, 221, 215, 50, 'дуб', 'comod.png', NULL, NULL, NULL, NULL, NULL),
(6, 'Комод "Ля-ля"', 'комод', 50000.00, 25000.00, 50, 240, 213, 50, 'сосна', 'comod.png', 'Комод «Ля-ля» дополняет интерьер помещения – гостиной, спальни или прихожей – а благодаря своей вместительности также является важным функциональным элементом. В мебельном изделии доступны четыре зоны для размещения вещей – классические ящики с навесными петлями и два отдела с системой tip-on. Последние открываются легким нажатием на поверхность. Металлический каркас проходит по всему периметру модели, завершаясь четырьмя небольшими ножками. Такая конструкция обеспечивает необходимую устойчивость при полной загрузке. Выбор цвета покрытия древесины позволяет подстроиться под общий дизайн помещения.', 'Материал: массив сосны\r\nФурнитура: металлическая\r\nКоличество ящиков: 4\r\nМеханизм открывания: tip-on\r\nГарантия: 2 года', '14-21 день', 'Наличными при получении\r\nБанковской картой онлайн\r\nРассрочка на 6 месяцев', 'Доставка по Москве - бесплатно\r\nДоставка по России - от 1500 руб.\r\nСамовывоз со склада');

-- Таблица users
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL
);

COMMIT;
