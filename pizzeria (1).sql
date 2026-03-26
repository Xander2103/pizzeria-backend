-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 02 mrt 2026 om 15:23
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pizzeria`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `last_name` varchar(60) DEFAULT NULL,
  `first_name` varchar(60) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `house_number` varchar(10) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `city` varchar(60) DEFAULT NULL,
  `phone_number` varchar(30) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `promo_eligible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `customers`
--

INSERT INTO `customers` (`customer_id`, `last_name`, `first_name`, `street`, `house_number`, `postal_code`, `city`, `phone_number`, `email`, `password_hash`, `remarks`, `promo_eligible`) VALUES
(1, 'Test', 'Customer', 'Teststraat', '1', '3080', 'Tervuren', '0470000000', 'testcustomer@pizzeria.local', 'PASTE_HASH_HERE', 'Test account', 1),
(2, 'Van Malder', 'Xander', 'Rootstraat', '80', '3080', 'Tervuren', '0479701571', 'xander.vanmalder@vdabcampus.be', '$2y$10$2MdWQXG5S5TNiR5fcgs9vOlVOxGnWn3vxDmI27SD1MwDBTfsVKyGa', NULL, 0),
(3, 'Van Malder', 'Xander', 'Rootstraat', '80', '3080', 'Tervuren', '0479701572', 'xander.vanmalder@xander.com', '$2y$10$Uu26XOtCVG6GnMJ0kaAE4unLDx6yXwoEYC94/50N41vC0FsBhofgK', NULL, 1),
(4, 'Van Malder', 'Xander', 'Rootstraat', '80', '3080', 'Tervuren', '0479701571', 'nieuwacctest@gmail.com', '$2y$10$9WjNnmlZJX8ZBwoEwjlbTusCxHSxb1aEJvPnfMwz/vzhECxaLk/4O', NULL, 0),
(5, 'Van Malder', 'Xander', 'Rootstraat', '80', '3080', 'Tervuren', '0479701571', 'kwni@gmail.com', '$2y$10$Vl69zmdCRzg1esKqRg4emuYbmUkq2pp2BEn40NyqmE/iMk7nPE4Aq', NULL, 0),
(6, 'Van Malder', 'Xander', 'Rootstraat', '80', '3080', 'Tervuren', '0479701571', 'whatTheTest@ttets.com', '$2y$10$fReLya6YOGE7ASmZNm0pPufkKOb9E0vnf0decL9c0ZjREqpQADJhu', NULL, 0),
(7, 'Van Malder', 'Xander', 'Rootstraat', '80', '3080', 'Tervuren', '0479701571', 'nogIs@gmail.com', '$2y$10$f0Krkuh5NFDy5.mDOdMQzu/IEcUdurI1VfjFEiuM929N3YDobpdsy', NULL, 0),
(8, 'Van Malder', 'Xander', 'Rootstraat', '80', '3080', 'Tervuren', '0479701571', 'xander.vanmalder@testtttt.com', '$2y$10$Naur2ReHSpJaRapYRqZy0OE4VGBwaBltY1rTPp3UZJ78mR0Er7IW.', NULL, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `delivery_areas`
--

CREATE TABLE `delivery_areas` (
  `postal_code` varchar(10) NOT NULL,
  `city` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `delivery_areas`
--

INSERT INTO `delivery_areas` (`postal_code`, `city`) VALUES
('1000', 'Brussel'),
('2000', 'Antwerpen'),
('3000', 'Leuven'),
('3080', 'Tervuren');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_datetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_price` decimal(10,2) NOT NULL,
  `courier_info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `order_datetime`, `total_price`, `courier_info`) VALUES
(1, 3, '2026-02-24 14:23:34', 10.00, 'Margherita x 1'),
(2, 3, '2026-02-24 14:24:32', 10.00, 'Margherita x 1'),
(3, 3, '2026-02-24 14:25:43', 10.00, 'Margherita x 1'),
(4, 3, '2026-02-24 14:27:43', 10.00, 'Hawai x 1'),
(5, 3, '2026-02-24 14:36:51', 10.00, 'Margherita x 1'),
(6, 3, '2026-02-24 14:39:16', 10.00, 'Margherita x 1'),
(7, 3, '2026-02-24 14:45:09', 10.00, 'Margherita x 1'),
(8, 3, '2026-02-24 15:09:25', 10.00, 'Margherita x 1'),
(9, 3, '2026-02-24 15:14:25', 10.00, 'bel even'),
(10, 3, '2026-02-24 15:15:17', 10.00, 'bel'),
(11, 3, '2026-02-24 15:46:31', 20.00, 'Margherita x 2 (extra kaas) | bel'),
(12, 3, '2026-02-25 12:44:16', 72.50, 'Margherita x 6, Pepperoni x 1'),
(13, 3, '2026-02-25 13:01:55', 52.50, 'Margherita x 4, Pepperoni x 1 | deurbel kapot'),
(14, 3, '2026-02-25 13:45:24', 122.50, 'Margherita x 6, Margherita x 3, Margherita x 2 (extra kaas), Pepperoni x 1'),
(15, 3, '2026-02-27 10:21:44', 118.00, 'Margherita x 6, Pepperoni x 1, Hawai x 2, Quattro Formaggi x 1, Vegetariana x 1'),
(16, 3, '2026-02-27 10:42:10', 30.00, 'Margherita x 3'),
(17, 3, '2026-02-27 10:52:29', 40.00, 'Margherita x 4'),
(18, 3, '2026-02-27 11:08:24', 40.00, 'Margherita x 3, Margherita x 1 (extra kaas)');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price_each` decimal(10,2) DEFAULT NULL,
  `extras` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price_each`, `extras`) VALUES
(1, 1, 4, 1, 10.00, NULL),
(2, 2, 4, 1, 10.00, NULL),
(3, 3, 4, 1, 10.00, NULL),
(4, 4, 6, 1, 10.00, NULL),
(5, 5, 4, 1, 10.00, NULL),
(6, 6, 4, 1, 10.00, NULL),
(7, 7, 4, 1, 10.00, NULL),
(8, 9, 4, 1, 10.00, NULL),
(9, 10, 4, 1, 10.00, NULL),
(10, 11, 4, 2, 10.00, 'extra kaas'),
(11, 12, 4, 6, 10.00, NULL),
(12, 12, 5, 1, 12.50, NULL),
(13, 13, 4, 4, 10.00, NULL),
(14, 13, 5, 1, 12.50, NULL),
(15, 14, 4, 6, 10.00, NULL),
(16, 14, 4, 3, 10.00, NULL),
(17, 14, 4, 2, 10.00, 'extra kaas'),
(18, 14, 5, 1, 12.50, NULL),
(19, 15, 4, 6, 10.00, NULL),
(20, 15, 5, 1, 12.50, NULL),
(21, 15, 6, 2, 10.00, NULL),
(22, 15, 7, 1, 13.50, NULL),
(23, 15, 8, 1, 12.00, NULL),
(24, 16, 4, 3, 10.00, NULL),
(25, 17, 4, 4, 10.00, NULL),
(26, 18, 4, 3, 10.00, NULL),
(27, 18, 4, 1, 10.00, 'extra kaas');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(80) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `promo_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `image`, `price`, `promo_price`) VALUES
(4, 'Margherita', 'Tomaat, mozzarella en oregano.', 'Magharita.png', 10.00, NULL),
(5, 'Pepperoni', 'Pepperoni en mozzarella.', 'Pepperoni.png', 12.50, NULL),
(6, 'Hawai', 'Ham en ananas.', 'Hawaii.png', 12.00, 10.00),
(7, 'Quattro Formaggi', '4 kazen mix.', '4kazen.png', 13.50, NULL),
(8, 'Vegetariana', 'Seizoensgroenten en mozzarella.', 'VegetarischPizza.png', 12.00, NULL);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexen voor tabel `delivery_areas`
--
ALTER TABLE `delivery_areas`
  ADD PRIMARY KEY (`postal_code`);

--
-- Indexen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_customer` (`customer_id`);

--
-- Indexen voor tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexen voor tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT voor een tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT voor een tabel `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Beperkingen voor tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
