-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pon 21. lis 2022, 15:51
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
-- Struktura tabulky `api_keys`
--

CREATE TABLE `api_keys` (
  `api_key_id` int(11) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `expiration_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `api_keys`
--

INSERT INTO `api_keys` (`api_key_id`, `api_key`, `description`, `expiration_date`) VALUES
(7, 'dghi63rnvq8i0glquy62pq', 'Unity WEBGL\r\n- Spsei Marketplace 3D map in Unity webgl', NULL);

-- --------------------------------------------------------

--
-- Struktura tabulky `auctions`
--

CREATE TABLE `auctions` (
  `auction_id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `top_bid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `auctions`
--

INSERT INTO `auctions` (`auction_id`, `offer_id`, `top_bid`, `user_id`, `start_date`, `end_date`) VALUES
(7, 31, 1697, 19, '2022-05-22 13:09:00', '2022-05-27 13:09:00'),
(9, 54, NULL, NULL, '2022-05-26 14:57:00', '2022-05-27 14:57:00'),
(10, 55, NULL, NULL, '2022-11-26 14:57:00', '2022-05-27 14:58:00'),
(11, 56, NULL, NULL, '2022-05-29 16:28:00', '2022-05-30 16:28:00'),
(12, 57, NULL, NULL, '2022-05-29 17:00:00', '2022-05-30 17:00:00'),
(13, 58, NULL, NULL, '2022-05-29 18:09:00', '2022-05-30 19:09:00'),
(14, 59, NULL, NULL, '2022-05-29 18:19:00', '2022-05-30 19:19:00'),
(15, 60, 7046, 46, '2022-09-05 22:11:06', '2022-09-06 22:52:15'),
(16, 64, 53, 51, '2022-09-15 21:39:00', '2022-09-15 21:50:00'),
(17, 65, NULL, NULL, '2022-09-15 22:42:00', '2022-09-16 23:42:00');

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
  `book_ISBN` varchar(13) NOT NULL,
  `name` varchar(50) NOT NULL,
  `author` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `grade` tinyint(4) NOT NULL,
  `major_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `books`
--

INSERT INTO `books` (`book_ISBN`, `name`, `author`, `category_id`, `grade`, `major_id`) VALUES
('0932-3212-232', 'Jazyk C++', 'Jan Novák', 1, 1, 1),
('1', 'Matematika I', 'Jiřina Petáková', 2, 0, 3),
('2', 'Literatura v kostce I', 'Marie Sochrová', 2, 1, 3),
('978-80-251-49', 'Programovací jazyk C', 'Brian W. Kernighan, Dennis M. Ritchie', 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `value`) VALUES
(1, 'Povinné učebnice', 'povinne_ucebnice'),
(2, 'Doporučené učebnice', 'doporucene_ucebnice'),
(3, 'Povinná četba', 'povinna_cetba'),
(4, 'Sešity', 'sesity');

-- --------------------------------------------------------

--
-- Struktura tabulky `chats`
--

CREATE TABLE `chats` (
  `chat_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `chats`
--

INSERT INTO `chats` (`chat_id`, `user_id`) VALUES
('63178e5f5d87f8.00030854', 19),
('63178e5f5d87f8.00030854', 51);

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
(5, 'I2A'),
(9, 'I4C'),
(10, 'I2B');

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
(23, 3, 'C306'),
(26, 9, 'B306');

-- --------------------------------------------------------

--
-- Struktura tabulky `majors`
--

CREATE TABLE `majors` (
  `major_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `majors`
--

INSERT INTO `majors` (`major_id`, `name`, `value`) VALUES
(1, 'Informační Technologie', 'it'),
(2, 'Elektrotechnika', 'elt'),
(3, 'Vše', 'vse');

-- --------------------------------------------------------

--
-- Struktura tabulky `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `chat_id` varchar(255) NOT NULL,
  `sender` int(11) NOT NULL,
  `text` text NOT NULL,
  `date_sent` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `messages`
--

INSERT INTO `messages` (`message_id`, `chat_id`, `sender`, `text`, `date_sent`) VALUES
(43, '63178e5f5d87f8.00030854', 51, 'Ahoj mám zájem o tvojí nabídku. Ale můžu dát jen 50 kč...', '2022-09-15 21:22:00'),
(45, '63178e5f5d87f8.00030854', 19, 'To by šlo, z jaké jsi třídy?', '2022-09-15 21:23:23'),
(46, '63178e5f5d87f8.00030854', 51, 'I3C', '2022-09-15 21:23:28'),
(47, '63178e5f5d87f8.00030854', 19, 'Tak zítra předání v ŠICU o velké ?', '2022-09-15 21:23:42'),
(48, '63178e5f5d87f8.00030854', 51, 'Domluveno!', '2022-09-15 21:23:49'),
(49, '63178e5f5d87f8.00030854', 51, 'd', '2022-09-15 21:27:11'),
(50, '63178e5f5d87f8.00030854', 51, 'd', '2022-09-15 21:27:16'),
(51, '63178e5f5d87f8.00030854', 51, 'd', '2022-09-15 21:27:17'),
(52, '63178e5f5d87f8.00030854', 51, 'd', '2022-09-15 21:27:17'),
(53, '63178e5f5d87f8.00030854', 51, 'd', '2022-09-15 21:28:09'),
(54, '63178e5f5d87f8.00030854', 51, 'asda', '2022-09-15 21:28:10'),
(55, '63178e5f5d87f8.00030854', 51, 'asdasdd', '2022-09-15 21:28:11'),
(56, '63178e5f5d87f8.00030854', 51, 'asd', '2022-09-15 21:29:07'),
(57, '63178e5f5d87f8.00030854', 51, 'asd', '2022-09-15 21:29:30'),
(58, '63178e5f5d87f8.00030854', 51, 'ca', '2022-09-15 21:30:16'),
(59, '63178e5f5d87f8.00030854', 19, 'd', '2022-09-15 21:30:39'),
(60, '63178e5f5d87f8.00030854', 19, 'asdas', '2022-09-15 21:30:40'),
(61, '63178e5f5d87f8.00030854', 19, 'sddassssssssssss', '2022-09-15 21:30:42'),
(62, '63178e5f5d87f8.00030854', 19, 'das', '2022-09-15 21:33:17'),
(63, '63178e5f5d87f8.00030854', 19, 'asddas', '2022-09-15 21:33:19'),
(64, '63178e5f5d87f8.00030854', 19, 'asdasd', '2022-09-15 21:33:20'),
(65, '63178e5f5d87f8.00030854', 19, 'dasasd', '2022-09-15 21:33:21'),
(66, '63178e5f5d87f8.00030854', 19, ':D', '2022-09-15 21:33:24'),
(67, '63178e5f5d87f8.00030854', 19, 'd', '2022-09-15 21:36:19'),
(68, '63178e5f5d87f8.00030854', 19, 'asdasd', '2022-09-15 21:36:20'),
(69, '63178e5f5d87f8.00030854', 19, 'DS', '2022-09-15 21:36:29'),
(70, '63178e5f5d87f8.00030854', 19, 'ASDASDASDASD', '2022-09-15 21:36:31'),
(71, '63178e5f5d87f8.00030854', 19, 'dsa', '2022-09-15 21:37:07'),
(72, '63178e5f5d87f8.00030854', 19, 'dasasd', '2022-09-15 21:37:48'),
(73, '63178e5f5d87f8.00030854', 19, 'asdaasddasasdasd', '2022-09-15 21:37:49'),
(74, '63178e5f5d87f8.00030854', 19, 'das', '2022-09-15 21:38:28'),
(75, '63178e5f5d87f8.00030854', 19, 'dsa', '2022-09-15 21:38:45'),
(76, '63178e5f5d87f8.00030854', 19, 'dsa', '2022-09-15 21:39:01'),
(77, '63178e5f5d87f8.00030854', 19, 'das', '2022-09-15 21:39:19'),
(78, '63178e5f5d87f8.00030854', 19, 'asd', '2022-09-15 21:39:26'),
(79, '63178e5f5d87f8.00030854', 19, 'dsa', '2022-09-15 21:39:59'),
(80, '63178e5f5d87f8.00030854', 19, 'das', '2022-09-15 21:41:33'),
(81, '63178e5f5d87f8.00030854', 19, 'asdasdasd', '2022-09-15 21:41:35'),
(82, '63178e5f5d87f8.00030854', 51, 'sd', '2022-10-02 12:23:52');

-- --------------------------------------------------------

--
-- Struktura tabulky `notifications`
--

CREATE TABLE `notifications` (
  `not_id` int(11) NOT NULL,
  `target` varchar(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `notifications`
--

INSERT INTO `notifications` (`not_id`, `target`, `content`, `date`) VALUES
(1, '*', 'hello world', '2022-05-11 21:27:40'),
(2, '19', 'hello world', '2022-05-11 21:27:39'),
(3, '*', 'hihihi', '2022-05-11 22:14:26'),
(4, '*', 'cc kidi', '2022-05-11 22:16:44'),
(5, '*', 'cccc', '2022-05-11 22:16:59'),
(6, '*', '<script>alert(\'hey\');</script>', '2022-05-18 20:46:18'),
(7, '*', 'cccc', '2022-05-22 21:59:46'),
(8, '19', 'čauky... máš nevyzvednuté aukce', '2022-09-15 20:49:06');

-- --------------------------------------------------------

--
-- Struktura tabulky `offers`
--

CREATE TABLE `offers` (
  `offer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(100) NOT NULL,
  `book_ISBN` varchar(13) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `price` int(11) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `offers`
--

INSERT INTO `offers` (`offer_id`, `user_id`, `name`, `description`, `book_ISBN`, `category_id`, `price`, `image_path`, `date`) VALUES
(31, 20, NULL, 'Dražba učebnice', '2', 3, NULL, 'offer_u19_628a1a082ad0a', '2022-05-22 13:10:00'),
(38, 19, NULL, 'sadasdsaas', '2', 3, 322, 'offer_u19_628cfbff76a0e', '2022-05-24 17:38:39'),
(39, 19, 'daasd', 'asddsasd', NULL, 3, 333, 'offer_u19_628cfc8892fa8', '2022-05-24 17:40:56'),
(40, 19, 'dsaasd', 'asdasd', NULL, 3, 333, 'offer_u19_628cfcace64aa', '2022-05-24 17:41:32'),
(41, 19, NULL, 'dasasdasd', '2', 3, 333, 'offer_u19_628cfcca1447b', '2022-05-24 17:42:02'),
(42, 19, NULL, 'ddssd', '2', 3, 23, 'offer_u19_628cfd3233e4b', '2022-05-24 17:43:46'),
(43, 19, NULL, 'ddssd', '2', 3, 333, 'offer_u19_628cfd772b73f', '2022-05-24 17:44:55'),
(44, 19, NULL, 'dasddasas', '2', 3, 333, 'offer_u19_628cfdf182744', '2022-05-24 17:46:57'),
(45, 19, NULL, 'dassda', '2', 3, 333, 'offer_u19_628cffe83080d', '2022-05-24 17:55:20'),
(46, 19, NULL, 'dsd', '2', 3, 333, 'offer_u19_628d035b4cbcd', '2022-05-24 18:10:03'),
(47, 19, NULL, 'dsasdasdads', '1', 3, 333, 'offer_u19_628d0409b2431', '2022-05-24 18:12:57'),
(48, 19, NULL, 'dsasdasd', '1', 3, 333, 'offer_u19_628d059f847cb', '2022-05-24 18:19:43'),
(49, 19, NULL, 'dsd', '2', 3, 333, 'offer_u19_628d0ef54bc75', '2022-05-24 18:59:33'),
(52, 19, NULL, 'dsdss', '2', 3, 333, 'offer_u19_628d1052892d4', '2022-05-24 19:05:22'),
(53, 19, NULL, 'dsds', '1', 3, 333, 'offer_u19_628d122aa865b', '2022-05-24 19:13:14'),
(54, 19, NULL, 'dssdsd', '2', 3, NULL, 'offer_u19_628f793de2df3', '2022-05-26 14:57:33'),
(55, 19, NULL, 'sddss', '2', 3, NULL, 'offer_u19_628f79900a2c6', '2022-05-26 14:58:56'),
(56, 19, NULL, 'sdasdasd', '1', 3, NULL, 'offer_u19_629374eb1d06e', '2022-05-29 15:28:11'),
(57, 19, NULL, 'asdasdasd', '1', 3, NULL, 'offer_u19_62937c74b8fa8', '2022-05-29 16:00:20'),
(58, 19, NULL, 'sdsdsd', '1', 3, NULL, 'offer_u19_62937e9f1ff88', '2022-05-29 16:09:35'),
(59, 19, NULL, 'sdsdsddasasdasd', '1', 3, NULL, 'offer_u19_629380e1771da', '2022-05-29 16:19:13'),
(60, 19, 'TEST AUKCE <<<', 'pejsek', NULL, 3, NULL, 'offer_u19_63107f40b6503', '2022-09-01 11:45:36'),
(61, 19, NULL, 'Zcela nová učebnice. Měl jsem jí doma. Používal jsem iPad', '1', 3, 129, 'offer_u19_63236f8b373c6', '2022-09-15 20:31:39'),
(62, 19, NULL, 'Trošku natržená vazba.', '2', 3, 89, 'offer_u19_63237150b00ae', '2022-09-15 20:39:12'),
(64, 19, 'Kompletní sešit do TVP', 'Politý vodou. Vše čitelným písmem. 3. ročník IT', NULL, 3, NULL, 'offer_u19_632371efdbbca', '2022-09-15 20:41:51'),
(65, 19, NULL, 'Nechybí žádný zápis. Ušetří mnoho času a bolesti :D', '1', 3, NULL, 'offer_u19_632372437e301', '2022-09-15 20:43:15'),
(67, 19, NULL, '2.1', '2', 2, 33, 'offer_u19_6339834ea0656', '2022-10-02 14:25:50'),
(68, 19, 'dasasd', 'asdasd', NULL, 4, 3, 'offer_u19_6339885ee38a3', '2022-10-02 14:47:26'),
(69, 19, 'asdasdasd', 'asdasdasd', NULL, 4, 0, 'offer_u19_633988f5d718b', '2022-10-02 14:49:57'),
(70, 19, 'asdasd', 'asdasd', NULL, 4, 1500, 'offer_u19_633ab44be5f14', '2022-10-03 12:07:07');

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
(19, 'Janaaa', 'Nováková', 9, 'test@test.cz', '$2y$10$M./BL.2apvUSH2u71N5Yce0W828hBN05bu.rZkAzHujI.733oIHdO', '::1', 1, '2022-09-11 15:06:00', '2022-02-27 15:13:04'),
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
(41, NULL, NULL, NULL, 'test323231@test.cz', '$2y$10$doxcZYSc3JOWcNe36chkreZ1U/16aHbWUBrv9vMk0rijQt7G2/4RW', '::1', 0, NULL, '2022-04-22 13:11:53'),
(42, NULL, NULL, NULL, 'test1@test.cz', '$2y$10$4LPl936zSe.iOtvr6zg5ieHzBVAAO/euEN5uSmaNpeXaHe/iPWa6S', '::1', 0, NULL, '2022-05-18 21:04:34'),
(43, NULL, NULL, NULL, 'test45@test.cz', '$2y$10$Pr7R7.sE5nlxGxpIemSca.RceXITAnDhxkmoestmbLysM8zaNfS0G', '::1', 0, NULL, '2022-05-20 13:11:36'),
(44, NULL, NULL, NULL, 'tes21t@test.cz', '$2y$10$WaqEh60eNVfM32wY0cP7dOqfwlmgx7c5uLWRNUw8JQsypnvcIG7ae', '::1', 0, NULL, '2022-05-20 13:12:36'),
(45, NULL, NULL, NULL, 'tes99@test.cz', '$2y$10$RxKI3eyoIyaRuhia2JrjTuEmVZyXaOOB20z5WEwIwZrLKZHWW0glW', '::1', 0, NULL, '2022-05-20 13:14:04'),
(46, 'Test', 'rrrr', 2, 'test12@test.cz', '$2y$10$M./BL.2apvUSH2u71N5Yce0W828hBN05bu.rZkAzHujI.733oIHdO', '::1', 0, '2022-05-21 14:24:10', '2022-05-20 13:25:59'),
(47, NULL, NULL, NULL, 'testtest@test.cz', '$2y$10$XuiIK3SJEVa1mYBiGONYRun2JkOKjathhFrojoKVPiXytw6lIjpuS', '::1', 0, NULL, '2022-05-22 13:44:33'),
(48, NULL, NULL, NULL, 'zabanovaeeenej@email.cz', '$2y$10$cqPvfurzzdF/nWsR8gmyZOQJJ8g4CxlDAZ3Dx46KK1TZVhPpDUOkO', '::5', 0, NULL, '2022-04-03 19:39:17'),
(49, NULL, NULL, NULL, 'test1234@test.cz', '$2y$10$hhWECc4.Zgmawy7vWeAX4.X0Fr7fTuRXVX.la8MB/61SsIfJhbivO', '::1', 0, NULL, '2022-09-01 12:43:34'),
(50, NULL, NULL, NULL, 'test12345@test.cz', '$2y$10$y.vky2z0dKUv2dTmtLuJruwNigg7bMg335RHumM6PxWY7pdCwxW6a', '::1', 0, NULL, '2022-09-04 22:12:30'),
(51, 'Jan', 'Novák', 5, 'pes1234@pes.cz', '$2y$10$szETeyH12l7SfcV0Yhb6jeDkg3zutZH6OV4nHgbGhqwjur6lk5h1O', '::1', 0, '2022-09-15 21:16:25', '2022-09-15 21:11:20');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`api_key_id`),
  ADD UNIQUE KEY `api_key` (`api_key`);

--
-- Indexy pro tabulku `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`auction_id`),
  ADD UNIQUE KEY `offer_id` (`offer_id`),
  ADD KEY `user_fk_2` (`user_id`);

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
  ADD PRIMARY KEY (`book_ISBN`),
  ADD UNIQUE KEY `book_ISBN` (`book_ISBN`),
  ADD KEY `major_fk` (`major_id`);

--
-- Indexy pro tabulku `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexy pro tabulku `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`chat_id`,`user_id`) USING BTREE,
  ADD KEY `uc_user_fk` (`user_id`);

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
-- Indexy pro tabulku `majors`
--
ALTER TABLE `majors`
  ADD PRIMARY KEY (`major_id`);

--
-- Indexy pro tabulku `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `m_user_fk` (`sender`);

--
-- Indexy pro tabulku `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`not_id`);

--
-- Indexy pro tabulku `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`),
  ADD KEY `user_fk` (`user_id`),
  ADD KEY `book_fk` (`book_ISBN`),
  ADD KEY `category_fk` (`category_id`);

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
-- AUTO_INCREMENT pro tabulku `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `api_key_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pro tabulku `auctions`
--
ALTER TABLE `auctions`
  MODIFY `auction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pro tabulku `banned_ips`
--
ALTER TABLE `banned_ips`
  MODIFY `bi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pro tabulku `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT pro tabulku `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pro tabulku `class_room`
--
ALTER TABLE `class_room`
  MODIFY `cr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pro tabulku `majors`
--
ALTER TABLE `majors`
  MODIFY `major_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT pro tabulku `notifications`
--
ALTER TABLE `notifications`
  MODIFY `not_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pro tabulku `offers`
--
ALTER TABLE `offers`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `auctions`
--
ALTER TABLE `auctions`
  ADD CONSTRAINT `offer_fk` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`offer_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_fk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `major_fk` FOREIGN KEY (`major_id`) REFERENCES `majors` (`major_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `uc_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `class_room`
--
ALTER TABLE `class_room`
  ADD CONSTRAINT `class_fk` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `chat_fk_2` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`chat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `m_user_fk` FOREIGN KEY (`sender`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `book_fk` FOREIGN KEY (`book_ISBN`) REFERENCES `books` (`book_ISBN`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `category_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
