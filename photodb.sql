-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 04 2021 г., 14:54
-- Версия сервера: 8.0.24
-- Версия PHP: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `photodb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `authors`
--

CREATE TABLE `authors` (
  `idauthor` int NOT NULL,
  `iduser` int NOT NULL,
  `idcountry` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `surname` varchar(45) NOT NULL,
  `place` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `authors`
--

INSERT INTO `authors` (`idauthor`, `iduser`, `idcountry`, `name`, `surname`, `place`) VALUES
(4, 1, 1, 'Иван', 'Иванов', 'Тверь'),
(5, 2, 3, 'Ильнар', 'Ильнаров', 'Ашхабад'),
(6, 3, 2, 'Закир', 'Закиров', 'Каир');

-- --------------------------------------------------------

--
-- Структура таблицы `catalog`
--

CREATE TABLE `catalog` (
  `idcatalog` int NOT NULL,
  `idparent` int DEFAULT '0',
  `name` varchar(45) NOT NULL,
  `imagefile` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `catalog`
--

INSERT INTO `catalog` (`idcatalog`, `idparent`, `name`, `imagefile`) VALUES
(1, 0, 'Животный мир', 'id1_shutterstock_39057223.jpg'),
(2, 0, 'Растения', 'id2_7530610-r3l8t8d-880-_-21.jpg'),
(3, 0, 'Автомобили', 'id3_MERCS04.JPG'),
(4, 0, 'Авиация', 'id4_nt4918101.jpg'),
(5, 0, 'Подводный мир', 'id5_CSKS0028651.jpg'),
(6, 0, 'Космос', 'id6_EF06SI0118.jpg'),
(7, 1, 'Дикие животные', 'id7_md005469.jpg'),
(8, 1, 'Домашние животные', 'id8_EV601054.jpg'),
(9, 1, 'Рептилии', 'id9_085~344.jpg'),
(10, 1, 'Птицы', 'id10_bir-01wb107-003.jpg'),
(11, 1, 'Насекомые', 'id11_af001729.jpg'),
(12, 8, 'Лошади', 'id12_shutterstock_3227875.jpg'),
(13, 8, 'Собаки', 'id13_EV601021.jpg'),
(14, 8, 'Кошки', 'id14_cats p2 (2).jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `countres`
--

CREATE TABLE `countres` (
  `idcountry` int NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `countres`
--

INSERT INTO `countres` (`idcountry`, `name`) VALUES
(1, 'Россия'),
(2, 'Египет'),
(3, 'Туркменистан');

-- --------------------------------------------------------

--
-- Структура таблицы `photos`
--

CREATE TABLE `photos` (
  `idphoto` int NOT NULL,
  `idcatalog` int NOT NULL,
  `idauthor` int NOT NULL,
  `idcountry` int NOT NULL,
  `shortname` varchar(45) NOT NULL,
  `description` varchar(150) DEFAULT NULL,
  `place` varchar(45) DEFAULT NULL,
  `fdate` date DEFAULT NULL,
  `ftime` time DEFAULT NULL,
  `heightpix` int DEFAULT NULL,
  `widthpix` int DEFAULT NULL,
  `photofile` varchar(45) DEFAULT NULL,
  `photofilemiddle` varchar(45) DEFAULT NULL,
  `photofilesmall` varchar(45) DEFAULT NULL,
  `addtimestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `photos`
--

INSERT INTO `photos` (`idphoto`, `idcatalog`, `idauthor`, `idcountry`, `shortname`, `description`, `place`, `fdate`, `ftime`, `heightpix`, `widthpix`, `photofile`, `photofilemiddle`, `photofilesmall`, `addtimestamp`) VALUES
(1, 12, 5, 3, 'Лошадь черная', 'Лошади \r\nЛошадь черная\r\nЛошадь черная на дыбах', 'Ашхабад', '2021-09-17', '18:00:00', 993, 1600, 'id1_shutterstock_17900476.jpg', 'id1_shutterstock_17900476.jpg', 'id1_shutterstock_17900476.jpg', '2021-10-07 13:41:17'),
(2, 12, 4, 2, 'Horse', 'Horse', 'Каир', '2021-10-04', '14:44:05', 1037, 1555, 'id2_shutterstock_21938680.jpg', 'id2_shutterstock_21938680.jpg', 'id2_shutterstock_21938680.jpg', '2021-10-07 13:48:22'),
(3, 12, 5, 1, 'Лошадь бегущая', 'Лошади2', 'Краснодар', '2021-10-16', '12:50:23', 837, 1322, 'id3_shutterstock_42699163.jpg', 'id3_shutterstock_42699163.jpg', 'id3_shutterstock_42699163.jpg', '2021-10-07 14:01:57'),
(4, 12, 6, 2, 'Лошадь бежит рысцой', 'Лошади', 'Каир', '2021-10-14', '16:00:00', 993, 1600, 'id4_shutterstock_45174319.jpg', 'id4_shutterstock_45174319.jpg', 'id4_shutterstock_45174319.jpg', '2021-10-07 10:41:17'),
(5, 12, 4, 2, 'Horse 2', 'Horse', 'Каир', '2021-10-04', '14:44:05', 1037, 1555, 'id5_shutterstock_45438043.jpg', 'id5_shutterstock_45438043.jpg', 'id5_shutterstock_45438043.jpg', '2021-10-07 10:48:22'),
(6, 12, 5, 1, 'Две лошади', 'Лошади2', 'Фивы', '2021-10-16', '12:50:23', 837, 1322, 'id6_shutterstock_46979482.jpg', 'id6_shutterstock_46979482.jpg', 'id6_shutterstock_46979482.jpg', '2021-10-07 11:01:57'),
(7, 12, 6, 2, 'Лошадь коричневая', 'Лошади', 'Александрия', '2021-10-14', '16:00:00', 993, 1600, 'id7_shutterstock_48204163.jpg', 'id7_shutterstock_48204163.jpg', 'id7_shutterstock_48204163.jpg', '2021-10-07 10:41:17'),
(8, 12, 4, 3, 'Horse arabian', 'Horse', 'Где то', '2021-10-04', '14:44:05', 1037, 1555, 'id8_shutterstock_7064140.jpg', 'id8_shutterstock_7064140.jpg', 'id8_shutterstock_7064140.jpg', '2021-10-07 10:48:22'),
(9, 12, 5, 1, 'Лошадь мощная', 'Лошади233', 'Полтава', '2021-10-16', '12:50:23', 837, 1322, 'id9_0789879 (5).jpg', 'id9_0789879 (5).jpg', 'id9_0789879 (5).jpg', '2021-10-07 11:01:57'),
(10, 11, 4, 1, 'Бабочка', '', '', '2021-10-30', '09:31:00', 465, 699, '49189617ce6ce9bb43.jpg', '49189617ce6ce9bb43_middle.jpg', '49189617ce6ce9bb43_small.jpg', '2021-10-30 06:31:42'),
(11, 11, 4, 1, 'dsdsa', '', '', '2021-10-14', '12:01:00', 465, 699, '29246617d0a8c844db.jpg', '29246617d0a8c844db_middle.jpg', '29246617d0a8c844db_small.jpg', '2021-10-30 09:04:12'),
(12, 11, 4, 1, 'www', '', '', '2021-10-14', '12:14:00', 465, 699, '54327617d0d53bc820.jpg', '54327617d0d53bc820_middle.jpg', '54327617d0d53bc820_small.jpg', '2021-10-30 09:16:03'),
(13, 3, 4, 1, 'машина', '', '', '2021-10-22', '15:33:00', 768, 1024, '68737617d3c77e2e12.jpg', '68737617d3c77e2e12_middle.jpg', '68737617d3c77e2e12_small.jpg', '2021-10-30 12:37:12');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `iduser` int NOT NULL,
  `namenik` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`iduser`, `namenik`, `email`, `password`, `role`) VALUES
(1, 'Freddy', 'freddy@eee.com', '$2y$10$8iTMNreJXYHZH64cWCaveOUqH4MwsiZRCK5kxgD2w5ocgcfdYH1cW', 'user'),
(2, 'Bob', 'bob@ooo.net', '$2y$10$8iTMNreJXYHZH64cWCaveOUqH4MwsiZRCK5kxgD2w5ocgcfdYH1cW', 'user'),
(3, 'Ben', 'ben@ggg.ru', '$2y$10$8iTMNreJXYHZH64cWCaveOUqH4MwsiZRCK5kxgD2w5ocgcfdYH1cW', 'user'),
(4, 'Афоня', 'afonya@mail.ru', '$2y$10$eGi9YAqIBjLylAPGL./uPeoOGr9MRIp3WvQZIGl9hzh..SuQqLeIG', 'admin'),
(15, 'A&lt;n&lt;nn', 'ann@man.ru', '$2y$10$OqaczseGDw80uiYcwME2Tuhugb98oTvDvB.dVHxEr/fRpnZyVnpqW', 'user');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`idauthor`),
  ADD KEY `fk_authors_countrys1_idx` (`idcountry`),
  ADD KEY `fk_authors_users1_idx` (`iduser`);

--
-- Индексы таблицы `catalog`
--
ALTER TABLE `catalog`
  ADD PRIMARY KEY (`idcatalog`);

--
-- Индексы таблицы `countres`
--
ALTER TABLE `countres`
  ADD PRIMARY KEY (`idcountry`);

--
-- Индексы таблицы `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`idphoto`),
  ADD KEY `fk_photos_catalog_idx` (`idcatalog`),
  ADD KEY `fk_photos_authors1_idx` (`idauthor`),
  ADD KEY `fk_photos_countrys1_idx` (`idcountry`);
ALTER TABLE `photos` ADD FULLTEXT KEY `fulltext_idx` (`shortname`,`description`,`place`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`iduser`),
  ADD UNIQUE KEY `email_idx` (`email`) USING BTREE;

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `authors`
--
ALTER TABLE `authors`
  MODIFY `idauthor` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `catalog`
--
ALTER TABLE `catalog`
  MODIFY `idcatalog` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `countres`
--
ALTER TABLE `countres`
  MODIFY `idcountry` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `photos`
--
ALTER TABLE `photos`
  MODIFY `idphoto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `iduser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `authors`
--
ALTER TABLE `authors`
  ADD CONSTRAINT `fk_authors_countrys1` FOREIGN KEY (`idcountry`) REFERENCES `countres` (`idcountry`),
  ADD CONSTRAINT `fk_authors_users1` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`);

--
-- Ограничения внешнего ключа таблицы `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `fk_photos_authors1` FOREIGN KEY (`idauthor`) REFERENCES `authors` (`idauthor`),
  ADD CONSTRAINT `fk_photos_catalog` FOREIGN KEY (`idcatalog`) REFERENCES `catalog` (`idcatalog`),
  ADD CONSTRAINT `fk_photos_countrys1` FOREIGN KEY (`idcountry`) REFERENCES `countres` (`idcountry`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
