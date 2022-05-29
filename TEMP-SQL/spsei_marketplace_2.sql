-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Ned 08. kvě 2022, 21:20
-- Verze serveru: 10.4.24-MariaDB
-- Verze PHP: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Databáze: `spsei_marketplace`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `auctions`
--

CREATE TABLE `auctions` (
  `auction_id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `top_bid` int(11) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(19, 'Jana', 'Nováková', 5, 'test@test.cz', '$2y$10$M./BL.2apvUSH2u71N5Yce0W828hBN05bu.rZkAzHujI.733oIHdO', '::1', 1, '2022-04-22 15:49:54', '2022-02-27 15:13:04'),
(20, NULL, NULL, NULL, 'zabanovanej@email.cz', '$2y$10$cqPvfurzzdF/nWsR8gmyZOQJJ8g4CxlDAZ3Dx46KK1TZVhPpDUOkO', '::2', 0, NULL, '2022-04-03 19:39:17'),
(24, NULL, NULL, NULL, 'petik.butor@email.cz', '$2y$10$/U.PcC85r2YWE71YB3iOz.6u0QfkuH5Y.ri7EwUYm2zzyZgnQE1Wm', '::1', 0, NULL, '2022-04-09 21:09:12'),
(25, NULL, NULL, 3, 'joadibha@seznam.cz', '$2y$10$BEQZFsFFk1SXLbCM4Ls46OQij2WbM7Um64AMeyinyT4jg2.y3w41.', '::1', 0, NULL, '2022-04-10 11:54:56'),
(29, NULL, NULL, NULL, 'joadibha3@seznam.cz', '$2y$10$ki2Yy4QUHB7EEtHHGv4Wc.1uBwIpXiAT0/EMF9s/RUzTg0gFRWzGK', '::1', 0, NULL, '2022-04-22 11:52:38'),
(30, NULL, NULL, NULL, 'testicek@test.cz', '$2y$10$TLjpEGiSCKNlk9f7ldWX2ew/umga9X.kaxLlo1r4u/Oz40TtumdeW', '::1', 0, NULL, '2022-04-22 11:58:41'),
(31, NULL, NULL, NULL, 'testovanej@test.cz', '$2y$10$BQA6cQes12lkON2IhMeoCO2RnxRh1rx3d87UJkU9fnqo2tlCx8Ape', '::1', 0, NULL, '2022-04-22 11:59:22'),
(32, NULL, NULL, NULL, 'otestovanej@test.cz', '$2y$10$yfkNUrsgunLKGAgi.b.bbu./NPvCqoJ6N9q9pX5l4aCQnJWOezp2i', '::1', 0, NULL, '2022-04-22 11:59:58'),
(33, NULL, NULL, NULL, 'netestovanej@test.cz', '$2y$10$cUBwyjuzth5Pf1NLMwxW2eCTYU7dS7tm/ZbInQuimLCP32pSyAVlK', '::1', 0, NULL, '2022-04-22 12:00:28'),
(34, NULL, NULL, NULL, 'nepes@test.cz', '$2y$10$CH3Djwj1nS913ZKv0JGedOQE6jthyQQJN2buV4HGWsWROr0mNlOca', '::1', 0, NULL, '2022-04-22 12:00:58'),
(35, NULL, NULL, NULL, 'pes@pes.cz', '$2y$10$mcXEd/feZDpFnRvFPk7tnO/yOCs1hLfG7lqYzjvJJHc/CwLwJOh/C', '::1', 0, NULL, '2022-04-22 12:03:22'),
(36, NULL, NULL, NULL, 'zebrak@zebrak.cz', '$2y$10$bfprgh9BxFHYog7PDJU1yu3twhSS.oontrj1FF953EpJc/XhAjLJy', '::1', 0, NULL, '2022-04-22 12:03:55'),
(37, NULL, NULL, NULL, 'test32@test.cz', '$2y$10$L1HntVNxa0FapCSCQ6YME.nQZ/OFJlTZR.R8M8yLD9laQQ3UGT036', '::1', 0, NULL, '2022-04-22 13:06:14'),
(38, NULL, NULL, NULL, 'test322@test.cz', '$2y$10$/Obgx0KKV07.oshwwNx.q.DLiQnCBIBSFlVYD0HDFTIFtDAz./TQa', '::1', 0, NULL, '2022-04-22 13:09:08'),
(39, NULL, NULL, NULL, 'test465454@test.cz', '$2y$10$DaHyKGIrJO7PMKH88ULU/u64HowkBMQZK/Sa9As3LBsxmU3t4aaBK', '::1', 0, NULL, '2022-04-22 13:10:04'),
(40, NULL, NULL, NULL, 'te32st@test.cz', '$2y$10$AMdFYMu6Kzp6dfVAzg8mpe/Tu4BdN0W9HX4.e.RY78Swqc0svsO5m', '::1', 0, NULL, '2022-04-22 13:10:39'),
(41, NULL, NULL, NULL, 'test323231@test.cz', '$2y$10$doxcZYSc3JOWcNe36chkreZ1U/16aHbWUBrv9vMk0rijQt7G2/4RW', '::1', 0, NULL, '2022-04-22 13:11:53');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`auction_id`),
  ADD UNIQUE KEY `offer_id` (`offer_id`);

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
  ADD PRIMARY KEY (`offer_id`),
  ADD KEY `user_fk` (`user_id`),
  ADD KEY `book_fk` (`book_id`);

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
-- AUTO_INCREMENT pro tabulku `auctions`
--
ALTER TABLE `auctions`
  MODIFY `auction_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `auctions`
--
ALTER TABLE `auctions`
  ADD CONSTRAINT `offer_fk` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`offer_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `class_room`
--
ALTER TABLE `class_room`
  ADD CONSTRAINT `class_fk` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `book_fk` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;