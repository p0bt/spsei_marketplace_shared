-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 12. dub 2022, 20:22
-- Verze serveru: 10.4.19-MariaDB
-- Verze PHP: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `spsei_marketplace`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `banned_ips`
--

CREATE TABLE `banned_ips` (
  `bi_id` int(11) NOT NULL,
  `ip_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `author` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `books`
--

INSERT INTO `books` (`book_id`, `name`, `author`) VALUES
(1, 'Matematika I', 'Jiřina Petáková'),
(2, 'Literatura v kostce I', 'Marie Sochrová');

-- --------------------------------------------------------

--
-- Struktura tabulky `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `name` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `classes`
--

INSERT INTO `classes` (`class_id`, `name`) VALUES
(2, 'I1A'),
(3, 'I1B'),
(4, 'I1C'),
(5, 'I2A');

-- --------------------------------------------------------

--
-- Struktura tabulky `class_room`
--

CREATE TABLE `class_room` (
  `cr_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `room_code` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `class_room`
--

INSERT INTO `class_room` (`cr_id`, `class_id`, `room_code`) VALUES
(16, 2, 'C206'),
(17, 4, 'C106'),
(23, 3, 'C306');

-- --------------------------------------------------------

--
-- Struktura tabulky `offers`
--

CREATE TABLE `offers` (
  `offer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(100) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `offers`
--

INSERT INTO `offers` (`offer_id`, `user_id`, `name`, `description`, `book_id`, `category`, `price`, `image_path`, `date`) VALUES
(4, 19, NULL, 'Nová, skoro nepoužívaná', 1, 'ucebnice', 29, 'offer_u19_6222040a06a05', '2022-03-04 13:20:26'),
(5, 19, 'Literatura v kostce', 'Potrhaná, vazba zničena', NULL, 'sesit', 129, 'offer_u19_62221bbcd9916', '2022-03-05 15:01:32'),
(7, 19, 'Dějepis učebnice', 'stará jak cyp, střídá barvy', NULL, 'sesit', 120, 'offer_u19_6251d61d2ae3b', '2022-04-09 20:53:17'),
(8, 25, 'dejepis', 'nn', NULL, 'sesit', 22, 'offer_u25_6252a982458b6', '2022-04-10 11:55:14');

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `last_update` datetime DEFAULT NULL,
  `register_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `class_id`, `email`, `password`, `ip_address`, `admin`, `last_update`, `register_date`) VALUES
(19, 'Jan', 'Novák', 3, 'test@test.cz', '$2y$10$M./BL.2apvUSH2u71N5Yce0W828hBN05bu.rZkAzHujI.733oIHdO', '::1', 1, '2022-04-09 21:58:14', '2022-02-27 15:13:04'),
(20, NULL, NULL, NULL, 'zabanovanej@email.cz', '$2y$10$cqPvfurzzdF/nWsR8gmyZOQJJ8g4CxlDAZ3Dx46KK1TZVhPpDUOkO', '::2', 0, NULL, '2022-04-03 19:39:17'),
(24, NULL, NULL, NULL, 'petik.butor@email.cz', '$2y$10$/U.PcC85r2YWE71YB3iOz.6u0QfkuH5Y.ri7EwUYm2zzyZgnQE1Wm', '::1', 0, NULL, '2022-04-09 21:09:12'),
(25, NULL, NULL, 3, 'joadibha@seznam.cz', '$2y$10$BEQZFsFFk1SXLbCM4Ls46OQij2WbM7Um64AMeyinyT4jg2.y3w41.', '::1', 0, NULL, '2022-04-10 11:54:56');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `banned_ips`
--
ALTER TABLE `banned_ips`
  ADD PRIMARY KEY (`bi_id`) USING BTREE,
  ADD UNIQUE KEY `ip_address` (`ip_address`);

--
-- Indexy pro tabulku `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexy pro tabulku `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexy pro tabulku `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexy pro tabulku `class_room`
--
ALTER TABLE `class_room`
  ADD PRIMARY KEY (`cr_id`),
  ADD UNIQUE KEY `class_id` (`class_id`),
  ADD UNIQUE KEY `room_code` (`room_code`);

--
-- Indexy pro tabulku `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`);

--
-- Indexy pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `banned_ips`
--
ALTER TABLE `banned_ips`
  MODIFY `bi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pro tabulku `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pro tabulku `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pro tabulku `class_room`
--
ALTER TABLE `class_room`
  MODIFY `cr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pro tabulku `offers`
--
ALTER TABLE `offers`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
