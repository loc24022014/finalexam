-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for webbanxe
CREATE DATABASE IF NOT EXISTS `webbanxe` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `webbanxe`;

-- Dumping structure for table webbanxe.category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanxe.category: ~5 rows (approximately)
INSERT INTO `category` (`id`, `name`, `description`, `slug`, `created_at`) VALUES
	(1, 'Xe SUV', 'Xe thể thao đa dụng, địa hình mạnh mẽ', 'xe-suv', '2026-05-27 01:27:26'),
	(2, 'Xe Sedan', 'Xe sedan sang trọng, tiện nghi', 'xe-sedan', '2026-05-27 01:27:26'),
	(3, 'Xe Thương mại', 'Xe tải, van phục vụ kinh doanh', 'xe-thuong-mai', '2026-05-27 01:27:26'),
	(4, 'Xe Điện', 'Xe điện thân thiện môi trường', 'xe-dien', '2026-05-27 01:27:26'),
	(5, 'Xe Thể thao', 'Supercar và xe thể thao hiệu năng cao', 'xe-the-thao', '2026-05-27 01:27:26');

-- Dumping structure for table webbanxe.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total_amount` decimal(15,0) NOT NULL DEFAULT '0',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'cash',
  `payment_status` enum('pending','completed','failed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `status` enum('pending','confirmed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `appointment_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanxe.orders: ~12 rows (approximately)
INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `payment_method`, `payment_status`, `status`, `appointment_date`, `created_at`) VALUES
	(1, 1, 59500000, 'qr', 'pending', 'pending', '2026-05-28', '2026-05-27 03:10:00'),
	(2, 1, 67500000, 'momo', 'pending', 'pending', '2026-05-28', '2026-05-27 03:11:15'),
	(3, 1, 59500000, 'shopeepay', 'completed', 'pending', '2026-05-28', '2026-05-27 03:11:41'),
	(4, 1, 67500000, 'bank', 'completed', 'pending', '2026-05-28', '2026-05-27 03:12:02'),
	(5, 1, 224950000, 'qr', 'pending', 'pending', '2026-05-28', '2026-05-27 03:17:48'),
	(6, 1, 67500000, 'cash', 'pending', 'pending', '2026-05-28', '2026-05-27 03:19:33'),
	(7, 1, 62500000, 'momo', 'pending', 'pending', '2026-05-28', '2026-05-27 03:35:29'),
	(8, 1, 480000000, 'momo', 'completed', 'pending', '2026-05-28', '2026-05-27 03:40:03'),
	(9, 1, 62500000, 'momo', 'pending', 'pending', '2026-05-28', '2026-05-27 03:40:22'),
	(10, 1, 62500000, 'qr', 'pending', 'pending', '2026-05-28', '2026-05-27 03:40:26'),
	(11, 1, 675000000, 'qr', 'pending', 'pending', '2026-05-28', '2026-05-27 03:45:29'),
	(12, 1, 675000000, 'qr', 'completed', 'pending', '2026-05-28', '2026-05-27 03:48:48');

-- Dumping structure for table webbanxe.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `price` decimal(15,0) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanxe.order_items: ~12 rows (approximately)
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `price`, `created_at`) VALUES
	(1, 1, 1, 1190000000, '2026-05-27 03:10:00'),
	(2, 2, 2, 1350000000, '2026-05-27 03:11:15'),
	(3, 3, 1, 1190000000, '2026-05-27 03:11:41'),
	(4, 4, 2, 1350000000, '2026-05-27 03:12:02'),
	(5, 5, 5, 4499000000, '2026-05-27 03:17:48'),
	(6, 6, 2, 1350000000, '2026-05-27 03:19:33'),
	(7, 7, 7, 1250000000, '2026-05-27 03:35:29'),
	(8, 8, 9, 9600000000, '2026-05-27 03:40:03'),
	(9, 9, 7, 1250000000, '2026-05-27 03:40:22'),
	(10, 10, 7, 1250000000, '2026-05-27 03:40:26'),
	(11, 11, 8, 13500000000, '2026-05-27 03:45:29'),
	(12, 12, 8, 13500000000, '2026-05-27 03:48:48');

-- Dumping structure for table webbanxe.product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(15,0) NOT NULL DEFAULT '0',
  `category_id` int DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanxe.product: ~9 rows (approximately)
INSERT INTO `product` (`id`, `name`, `description`, `price`, `category_id`, `image`, `brand`, `created_at`) VALUES
	(1, 'Toyota Camry 2024', 'Xe sedan hạng D sang trọng với động cơ hybrid tiết kiệm nhiên liệu. Nội thất cao cấp, an toàn 5 sao.', 1190000000, 2, 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=800&q=80', 'Toyota', '2026-05-27 01:27:26'),
	(2, 'Ford Everest Titanium', 'SUV 7 chỗ mạnh mẽ với hệ dẫn động 4x4, phù hợp địa hình khó. Trang bị hiện đại nhất phân khúc.', 1350000000, 1, 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=800&q=80', 'Ford', '2026-05-27 01:27:26'),
	(3, 'VinFast VF 8', 'SUV điện thuần túy Made in Vietnam. Phạm vi di chuyển 400km/sạc, công nghệ ADAS tiên tiến.', 969000000, 4, 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=800&q=80', 'VinFast', '2026-05-27 01:27:26'),
	(4, 'Mercedes-Benz C300', 'Xe sang hạng C đẳng cấp châu Âu với động cơ turbo 258HP, nội thất siêu cao cấp.', 2259000000, 2, 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=800&q=80', 'Mercedes-Benz', '2026-05-27 01:27:26'),
	(5, 'BMW X5 M Sport', 'SUV hạng sang với hiệu suất thể thao M Sport, màn hình curved 12.3 inch, hệ thống âm thanh Harman Kardon.', 4499000000, 1, 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=800&q=80', 'BMW', '2026-05-27 01:27:26'),
	(6, 'Lamborghini Urus', 'Super SUV với động cơ V8 twin-turbo 650HP, tốc độ tối đa 305 km/h. Biểu tượng sang trọng tối thượng.', 18000000000, 5, 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=800&q=80', 'Lamborghini', '2026-05-27 01:27:26'),
	(7, 'VinFast VF8 Plus 2024', 'Mẫu SUV thuần điện thông minh cỡ D với thiết kế thời thượng, công nghệ tự lái tiên tiến (ADAS) và trợ lý ảo thông minh ViVi. Xe được trang bị hệ dẫn động 2 cầu toàn thời gian và nội thất cao cấp.', 1250000000, 4, 'vinfast_vf8_new.png', 'VinFast', '2026-05-27 03:29:00'),
	(8, 'Porsche 911 GT3 RS', 'Siêu xe thể thao hiệu năng cao đích thực với khối động cơ hút khí tự nhiên boxer 4.0L mạnh 525 mã lực. Thiết kế khí động học hoàn hảo với cánh gió lớn và các chi tiết carbon tối ưu trọng lượng.', 13500000000, 5, 'porsche_911_new.png', 'Porsche', '2026-05-27 03:29:00'),
	(9, 'Lexus LX 600 VIP', 'Mẫu SUV hạng sang cỡ lớn (Full-size Luxury SUV) mang đẳng cấp thương gia. Trang bị động cơ V6 3.5L Twin-Turbo mạnh mẽ, thiết kế nội thất sang trọng với ghế thương gia massage, làm mát.', 9600000000, 1, 'lexus_lx600_new.png', 'Lexus', '2026-05-27 03:29:00');

-- Dumping structure for table webbanxe.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanxe.users: ~0 rows (approximately)
INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `role`, `created_at`) VALUES
	(1, 'admin', 'admin@banxe.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', '2026-05-27 01:27:26'),
	(2, 'ShaMi', 'nguyenthanhloi727@gmail.com', '$2y$10$HsHGVYuI1C4VbShI8RLjfuPUFXntKpc5vwUcSfExS8kXX43hKplPm', 'Nguyễn Thành Lợi', 'user', '2026-05-27 01:46:53');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
