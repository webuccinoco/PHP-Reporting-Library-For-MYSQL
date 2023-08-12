-- Dumping structure for table items
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL,
  `code` varchar(25) DEFAULT 'test',
  `name` varchar(50) DEFAULT NULL,
  `photo` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 20.00,
  `reorder_level` int(11) DEFAULT NULL,
  `units_in_stock` int(11) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `discontinued` bit(1) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_code` (`code`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=COMPACT;
-- Dumping data for table db_sales.items: ~21 rows (approximately)
INSERT INTO `items` (`id`, `code`, `name`, `photo`, `price`, `reorder_level`, `units_in_stock`, `category`, `country`, `rating`, `discontinued`, `date`) VALUES
	(1, 'NWTCFV-100', 'Browline', '09.png', 40.00, 10, 4, 'sunglasses', 'USA', 4.60, b'1', '2023-06-28 22:52:14'),
	(2, 'NWTCFV-140', 'Retro Square', '14.png', 60.00, 10, 4, 'sunglasses', 'USA', 3.50, b'0', '2023-06-17 22:52:41'),
	(3, 'NWTCFV-89', 'Oval', '03.png', 20.00, 5, 2, 'sunglasses', 'USA', 4.50, b'1', '2023-07-15 22:51:32'),
	(4, 'NWTCFV-90', 'Semi-Rimless', '04.png', 35.00, 5, 15, 'sunglasses', 'USA', 5.00, b'1', '2023-05-31 22:51:37'),
	(5, 'NWTCFV-93', 'Cat Eye', '07.png', 100.00, 10, 2, 'sunglasses', 'USA', 3.00, b'1', '2023-06-28 22:51:54'),
	(6, 'NWTCFV-120', 'Butterfly', '12.png', 34.00, 5, 9, 'sunglasses', 'USA', 4.80, b'0', '2023-06-28 22:52:32'),
	(7, 'NWTCFV-101', 'Pilot', '11.png', 75.00, 10, 17, 'sunglasses', 'USA', 4.30, b'1', '2023-05-28 22:52:25'),
	(8, 'NWTCFV-150', 'Square', '10.png', 100.00, 10, 3, 'sunglasses', 'USA', 3.50, b'0', '2023-06-20 22:52:19'),
	(9, 'NWTCFV-17', 'Pilot', '01.png', 40.00, 10, 30, 'sunglasses', 'Canada', 4.80, b'1', '2023-04-18 22:51:15'),
	(10, 'NWTCFV-88', 'ClubMaster', '02.png', 45.00, 5, 15, 'sunglasses', 'Canada', 5.00, b'0', '2023-06-28 22:51:28'),
	(11, 'NWTCFV-92', 'Pilot', '06.png', 200.00, 10, 29, 'sunglasses', 'UK', 4.50, b'0', '2023-07-14 22:51:49'),
	(12, 'NWTCFV-91', 'Aviator', '05.png', 45.00, 10, 12, 'sunglasses', 'UK', 4.20, b'1', '2023-06-15 22:51:42'),
	(13, 'NWTCFV-130', 'Katy', '13.png', 40.00, 10, 19, 'sunglasses', 'UK', 4.50, b'0', '2023-07-28 22:52:37'),
	(14, 'NWTCFV-161', 'Cat Eye', '12.png', 36.00, 10, 10, 'sunglasses', 'UK', 4.20, b'0', '2023-05-31 22:52:50'),
	(15, 'NWTCFV-94', 'Sport', '08.png', 40.00, 10, 10, 'sunglasses', 'Canada', 4.30, b'0', '2023-06-30 22:52:08'),
	(16, 'NWTCFV-160', 'Wirless sport Earbuds-waterproof', '15.png', 35.00, 10, 20, 'Phone Accessories', 'Canada', 5.00, b'0', '2023-06-30 22:52:46'),
	(17, 'PAREGCH-01', 'Regular-Charger', '20.png', 4.00, 4, 6, 'Phone Accessories', 'USA', 4.50, b'0', '2023-07-23 12:51:23'),
	(18, 'PAREGCH-02', 'Regular-Charger', '21.png', 5.00, 4, 10, 'Phone Accessories', 'Canada', 4.50, b'0', '2023-07-23 13:02:29'),
	(19, 'PAWEPCH-09', 'Wireless earbuds', '30.png', 20.00, 10, 9, 'Phone Accessories', 'USA', 5.00, b'0', '2023-07-23 13:02:31'),
	(20, 'PAWEPCH-10', 'Wirless sport Earbuds-waterproof', '31.png', 35.00, 10, 7, 'Phone Accessories', 'USA', 5.00, b'0', '2023-07-23 13:02:32'),
	(21, 'PAWEPCH-18', 'Portable power bank', '32.png', 20.00, 5, 7, 'Phone Accessories', 'USA', 4.50, b'0', '2023-07-23 13:11:47');

