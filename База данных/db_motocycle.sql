-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Май 22 2024 г., 15:33
-- Версия сервера: 8.0.30
-- Версия PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `db_motocycle`
--

-- --------------------------------------------------------

--
-- Структура таблицы `moto_motorcycles`
--

CREATE TABLE `moto_motorcycles` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `type_id` int DEFAULT NULL,
  `discontinued` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `moto_motorcycles`
--

INSERT INTO `moto_motorcycles` (`id`, `name`, `type_id`, `discontinued`) VALUES
(1, 'AJP PR7', 1, 0),
(2, 'Husqvarna 701 Enduro', 2, 1),
(3, 'SWM Superdual', 1, 0),
(4, 'Beta Alp 4.0', 2, 0),
(5, 'Bimota Impeto', 3, 0),
(6, 'Moto Morini Corsaro', 3, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `moto_types`
--

CREATE TABLE `moto_types` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `moto_types`
--

INSERT INTO `moto_types` (`id`, `name`) VALUES
(1, 'Приключенческий'),
(2, 'Эндуро'),
(3, 'Голый');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `moto_motorcycles`
--
ALTER TABLE `moto_motorcycles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`);

--
-- Индексы таблицы `moto_types`
--
ALTER TABLE `moto_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `moto_motorcycles`
--
ALTER TABLE `moto_motorcycles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `moto_types`
--
ALTER TABLE `moto_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `moto_motorcycles`
--
ALTER TABLE `moto_motorcycles`
  ADD CONSTRAINT `moto_motorcycles_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `moto_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
