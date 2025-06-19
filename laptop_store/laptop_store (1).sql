-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3307
-- Thời gian đã tạo: Th6 19, 2025 lúc 05:19 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `laptop_store`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`) VALUES
(3, 'Acer'),
(2, 'Asus'),
(1, 'Dell'),
(5, 'Hp'),
(4, 'Lenovo'),
(6, 'Macbook');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Laptop Gamming'),
(2, 'Laptop Văn phòng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orderdetails`
--

CREATE TABLE `orderdetails` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantityOrdered` int(11) NOT NULL,
  `priceEach` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orderdetails`
--

INSERT INTO `orderdetails` (`order_id`, `product_id`, `quantityOrdered`, `priceEach`) VALUES
(1, 1, 1, 32000000.00),
(2, 2, 1, 28000000.00),
(3, 3, 1, 29000000.00),
(4, 4, 1, 35000000.00),
(5, 7, 1, 27000000.00),
(6, 11, 1, 25000000.00),
(7, 12, 1, 24000000.00),
(8, 9, 1, 26000000.00),
(9, 6, 1, 31000000.00),
(10, 8, 1, 48000000.00),
(11, 13, 1, 23000000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Chờ xử lý','Đang giao','Hoàn thành','Hủy') NOT NULL DEFAULT 'Chờ xử lý',
  `user_id` int(11) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`order_id`, `order_date`, `total_amount`, `notes`, `status`, `user_id`, `address_id`) VALUES
(1, '2025-06-01 10:00:00', 32000000.00, 'Giao buổi sáng', 'Hoàn thành', 2, 1),
(2, '2025-06-02 11:15:00', 28000000.00, '', 'Hoàn thành', 2, 1),
(3, '2025-06-03 13:00:00', 29000000.00, 'Giao trong giờ hành chính', 'Hoàn thành', 2, 1),
(4, '2025-06-04 14:30:00', 35000000.00, '', 'Hoàn thành', 2, 1),
(5, '2025-06-05 15:45:00', 27000000.00, '', 'Hoàn thành', 2, 1),
(6, '2025-06-06 09:20:00', 25000000.00, '', 'Hoàn thành', 2, 1),
(7, '2025-06-07 16:00:00', 24000000.00, 'Giao ngoài giờ', 'Hoàn thành', 2, 1),
(8, '2025-06-08 17:10:00', 26000000.00, '', 'Hoàn thành', 2, 1),
(9, '2025-06-09 12:00:00', 31000000.00, 'Giao gấp', 'Hoàn thành', 2, 1),
(10, '2025-06-10 10:30:00', 48000000.00, '', 'Hoàn thành', 2, 1),
(11, '2025-06-19 01:21:19', 23000000.00, '', 'Chờ xử lý', 2, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `productreviews`
--

CREATE TABLE `productreviews` (
  `review_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `review_date` datetime DEFAULT current_timestamp(),
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantityInStock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `spec_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `quantityInStock`, `image`, `description`, `brand_id`, `category_id`, `spec_id`) VALUES
(1, 'Asus ROG Strix', 32000000.00, 10, 'Asus_ROG_Strix.jpg', 'Asus ROG Strix là chiếc laptop chơi game mạnh mẽ, được trang bị cấu hình cao với card đồ họa NVIDIA và bộ vi xử lý Intel. Nó mang lại hiệu suất vượt trội cho các game thủ và có thiết kế tối ưu cho việc tản nhiệt, giúp máy hoạt động mượt mà ngay cả trong những trận đấu khốc liệt.', 2, 1, 1),
(2, 'Asus ROG Flow', 28000000.00, 8, 'Asus_ROG_Flow.jpg', 'Asus ROG Flow kết hợp giữa hiệu năng tối ưu và tính di động, là lựa chọn lý tưởng cho game thủ. Chiếc laptop này sở hữu màn hình cảm ứng ấn tượng và khả năng xoay gập, phù hợp cho cả công việc và giải trí. Với thiết kế thời trang, nó dễ dàng thu hút mọi ánh nhìn.', 2, 1, 2),
(3, 'Lenovo Legion 5', 29000000.00, 12, 'Lenovo_Legion_5.jpg', 'Lenovo Legion 5 mang đến trải nghiệm chơi game mượt mà và bền bỉ. Với cấu hình mạnh mẽ và hệ thống tản nhiệt hiệu quả, máy có thể xử lý mọi tựa game hiện đại. Thiết kế sắc nét và tiện nghi, phù hợp cho cả game thủ lẫn dân văn phòng.', 4, 1, 3),
(4, 'Acer Predator Helios', 35000000.00, 6, 'Acer_Predator_Helios.jpg', 'Acer Predator Helios là laptop cao cấp dành cho game thủ, nổi bật với khả năng xử lý đồ họa tuyệt vời và hiệu suất cao. Màn hình sắc nét giúp bạn tận hưởng những hình ảnh sống động nhất. Thiết kế mạnh mẽ, thể hiện sự đẳng cấp trong từng chi tiết.', 3, 1, 4),
(5, 'Dell Alienware M15', 42000000.00, 5, 'Dell_Alienware_M15.jpg', 'Dell Alienware M15 là chiếc laptop gaming với thiết kế ấn tượng và cấu hình khủng. Nó mang lại trải nghiệm chơi game tuyệt vời với hiệu suất cao và khả năng tùy chỉnh. Thiết kế độc đáo, cùng với công nghệ tiên tiến, giúp bạn luôn dẫn đầu trong mọi trận đấu.', 1, 1, 5),
(6, 'Acer Predator Triton', 31000000.00, 10, 'Acer_Predator_Triton.jpg', 'Acer Predator Triton là lựa chọn tuyệt vời cho các game thủ chuyên nghiệp. Với thiết kế siêu mỏng nhẹ, máy dễ dàng mang theo. Hiệu suất cao với card đồ họa mạnh mẽ giúp bạn trải nghiệm mọi tựa game nặng mà không gặp khó khăn gì.', 3, 1, 6),
(7, 'HP Omen 16', 27000000.00, 9, 'HP_Omen_16.jpg', 'HP Omen 16 mang lại hiệu suất cao với hệ thống tản nhiệt tốt, giúp máy luôn hoạt động ổn định. Đây là một sự lựa chọn hoàn hảo cho các game thủ, với thiết kế hiện đại và tính năng gaming mạnh mẽ, mang đến trải nghiệm chơi game tuyệt vời.', 5, 1, 7),
(8, 'Asus Zephyrus M16', 48000000.00, 7, 'Asus_Zephyrus_M16.jpg', 'Asus Zephyrus M16 nổi bật với thiết kế siêu mỏng nhẹ nhưng không kém phần mạnh mẽ. Chiếc laptop này trang bị cấu hình cao, giúp bạn xử lý mọi tác vụ từ chơi game đến làm việc một cách nhanh chóng. Màn hình sắc nét mang đến trải nghiệm hình ảnh tuyệt vời.', 2, 1, 8),
(9, 'Asus TUF Gaming F15', 26000000.00, 15, 'Asus_TUF_Gaming_F15.jpg', 'Asus TUF Gaming F15 là lựa chọn bền bỉ và mạnh mẽ cho game thủ. Với thiết kế chắc chắn và hiệu suất ổn định, nó có thể xử lý nhiều tựa game nặng mà không gặp trục trặc. Đây là sự kết hợp hoàn hảo giữa độ bền và hiệu năng.', 2, 1, 9),
(10, 'Asus TUF Dash F15', 30000000.00, 10, 'Asus_TUF_Dash_F15.jpg', 'Asus TUF Dash F15 mang lại phong cách hiện đại và hiệu năng tốt cho người dùng. Với cấu hình mạnh mẽ, máy có thể dễ dàng xử lý các tác vụ từ chơi game đến làm việc văn phòng. Thiết kế thời trang cùng với hiệu suất ấn tượng là điểm nhấn của sản phẩm.', 2, 1, 10),
(11, 'Dell G15', 25000000.00, 8, 'Dell_G15.jpg', 'Dell G15 là sự lựa chọn phổ thông cho game thủ với mức giá hợp lý. Nó được trang bị cấu hình đủ mạnh để chơi các tựa game phổ biến hiện nay. Thiết kế đơn giản nhưng hiệu quả, phù hợp với nhiều đối tượng người dùng.', 1, 1, 11),
(12, 'Acer Nitro 5', 24000000.00, 14, 'Acer_Nitro_5.jpg', 'Acer Nitro 5 là laptop có giá tốt và hiệu năng ổn định, phù hợp cho các game thủ tầm trung. Với cấu hình hợp lý, bạn có thể chơi các tựa game yêu thích mà không bị lag. Thiết kế thời thượng, đáp ứng nhu cầu người dùng.', 3, 1, 12),
(13, 'Lenovo IdeaPad Gaming 3', 23000000.00, 11, 'Lenovo_IdeaPad_Gaming_3.jpg', 'Lenovo IdeaPad Gaming 3 là lựa chọn phù hợp cho game thủ tầm trung với hiệu suất cao và giá cả phải chăng. Thiết kế gọn nhẹ, dễ dàng mang theo, cùng với khả năng xử lý tốt các tựa game phổ biến, máy sẽ là bạn đồng hành lý tưởng.', 4, 1, 13),
(14, 'HP Victus 16', 26000000.00, 10, 'HP_Victus_16.jpg', 'HP Victus 16 mang đến trải nghiệm gaming nhẹ nhàng và cũng rất phù hợp cho việc học tập. Máy có thiết kế đẹp mắt, hiệu suất ổn định, giúp bạn dễ dàng hoàn thành công việc và tận hưởng những giờ phút giải trí tuyệt vời.', 5, 1, 14),
(15, 'Lenovo LOQ 15', 25500000.00, 9, 'Lenovo_LOQ_15.jpg', 'Lenovo LOQ 15 là chiếc laptop cân bằng giữa học tập và chơi game. Với cấu hình mạnh mẽ, bạn có thể sử dụng cho nhiều mục đích khác nhau. Thiết kế hiện đại và tiện nghi, phù hợp với nhu cầu học sinh, sinh viên.', 4, 1, 15),
(16, 'Dell Inspiron 15', 17000000.00, 12, 'dell_inspiron_15.jpg', 'Dell Inspiron 15 là laptop lý tưởng cho học tập và văn phòng. Với hiệu suất ổn định và thiết kế gọn nhẹ, máy dễ dàng đáp ứng nhu cầu sử dụng hàng ngày, phù hợp cho sinh viên và nhân viên văn phòng.', 1, 2, 16),
(17, 'HP Pavilion 14', 16000000.00, 10, 'HP_Pavilion_14.jpg', 'HP Pavilion 14 nổi bật với thiết kế mỏng nhẹ và giá hợp lý. Đây là lựa chọn hoàn hảo cho những người cần một chiếc laptop để làm việc và giải trí. Hiệu suất tốt giúp bạn hoàn thành công việc một cách nhanh chóng.', 5, 2, 17),
(18, 'Asus VivoBook S14', 18500000.00, 8, 'Asus_VivoBook_S14.jpg', 'Asus VivoBook S14 mang đến hiệu suất tốt cho dân văn phòng. Với thiết kế sang trọng và nhiều màu sắc, máy không chỉ đẹp mà còn mạnh mẽ, giúp bạn thể hiện phong cách cá nhân trong công việc.', 2, 2, 18),
(19, 'Lenovo ThinkBook 15', 19000000.00, 7, 'Lenovo_ThinkBook_15.jpg', 'Lenovo ThinkBook 15 là chiếc laptop bền bỉ và ổn định, phù hợp cho môi trường làm việc. Máy trang bị cấu hình tốt, giúp bạn xử lý công việc hiệu quả, đồng thời dễ dàng mang theo bên mình.', 4, 2, 19),
(20, 'Acer Aspire 5', 15000000.00, 13, 'acer_aspire_5.jpg', 'Acer Aspire 5 là lựa chọn phù hợp cho sinh viên và văn phòng với mức giá hợp lý. Hiệu suất ổn định giúp bạn hoàn thành mọi tác vụ từ học tập đến làm việc, trong khi thiết kế nhẹ nhàng giúp bạn dễ dàng mang theo.', 3, 2, 20),
(21, 'Asus ExpertBook B5', 17000000.00, 9, 'Asus_ExpertBook_B5.jpg', 'Asus ExpertBook B5 mang đến thiết kế thời trang và nhẹ nhàng, lý tưởng cho doanh nhân. Máy có hiệu suất tốt và thời gian sử dụng pin lâu, giúp bạn làm việc hiệu quả mà không lo ngại về thời gian.', 2, 2, 21),
(22, 'HP Envy x360', 20000000.00, 6, 'hp_envy_x360.jpg', 'HP Envy x360 là laptop lai 2 trong 1 tiện lợi, phù hợp cho cả công việc và giải trí. Màn hình cảm ứng giúp bạn thao tác dễ dàng, đồng thời thiết kế đẹp mắt mang đến sự sang trọng cho người dùng.', 5, 2, 22),
(23, 'Lenovo Yoga Slim 7', 21000000.00, 5, 'Lenovo_Yoga_Slim_7.jpg', 'Lenovo Yoga Slim 7 có thiết kế cao cấp và hiệu năng tốt, phù hợp cho người dùng cần một chiếc laptop nhẹ nhàng nhưng mạnh mẽ. Máy có thể xoay 360 độ, cho phép bạn sử dụng dễ dàng trong mọi tình huống.', 4, 2, 23),
(24, 'Asus ZenBook 14', 22000000.00, 8, 'Asus_ZenBook_14.jpg', 'Asus ZenBook 14 nổi bật với hiệu suất ổn định và màn hình đẹp. Đây là lựa chọn hoàn hảo cho những ai cần một chiếc laptop mạnh mẽ để làm việc và giải trí. Thiết kế mỏng nhẹ giúp bạn dễ dàng mang theo.', 2, 2, 24),
(25, 'Dell Latitude 3410', 19500000.00, 7, 'Dell_Latitude_3410.jpg', 'Dell Latitude 3410 là chiếc laptop doanh nhân tin dùng với hiệu suất ổn định và thiết kế chuyên nghiệp. Máy dễ dàng xử lý công việc hàng ngày, giúp bạn hoàn thành mọi nhiệm vụ một cách hiệu quả.', 1, 2, 25),
(26, 'Acer Swift 3', 18000000.00, 9, 'Acer_Swift_3.jpg', 'Acer Swift 3 là lựa chọn gọn nhẹ với hiệu năng khá, phù hợp cho những ai thường xuyên di chuyển. Máy có thiết kế mỏng và thời gian sử dụng pin lâu dài, giúp bạn làm việc liên tục mà không lo ngại.', 3, 2, 26),
(27, 'HP EliteBook 840', 23000000.00, 6, 'HP_EliteBook_840.jpg', 'HP EliteBook 840 là laptop văn phòng cao cấp, mang đến hiệu suất ổn định và thiết kế sang trọng. Máy phù hợp cho doanh nhân, giúp bạn làm việc hiệu quả và thể hiện phong cách chuyên nghiệp.', 5, 2, 27),
(28, 'Macbook Air M2', 29000000.00, 4, 'Macbook_Air_M2.jpg', 'Macbook Air M2 nổi bật với thiết kế sang trọng và thời gian sử dụng pin lâu. Hiệu suất mạnh mẽ giúp bạn xử lý các tác vụ nặng một cách dễ dàng. Đây là lựa chọn hoàn hảo cho những ai yêu thích sự tiện lợi.', 6, 2, 28),
(29, 'Asus X515', 16500000.00, 10, 'Asus_X515.jpg', 'Asus X515 mang đến hiệu năng ổn định và thiết kế đẹp mắt. Đây là chiếc laptop lý tưởng cho nhu cầu sử dụng hàng ngày, từ học tập đến làm việc. Máy dễ dàng đáp ứng nhu cầu của mọi người dùng.', 2, 2, 29),
(30, 'Macbook Pro 14', 34000000.00, 3, 'Macbook_Pro_14.jpg', 'Macbook Pro 14 là chiếc laptop siêu nhẹ với màn hình lớn, phù hợp cho cả công việc và giải trí. Thiết kế sang trọng cùng với hiệu suất mạnh mẽ giúp bạn hoàn thành mọi tác vụ một cách nhanh chóng và dễ dàng.', 6, 2, 30);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `specifications`
--

CREATE TABLE `specifications` (
  `spec_id` int(11) NOT NULL,
  `cpu` varchar(50) DEFAULT NULL,
  `ram` varchar(50) DEFAULT NULL,
  `storage` varchar(50) DEFAULT NULL,
  `screen` varchar(100) DEFAULT NULL,
  `gpu` varchar(50) DEFAULT NULL,
  `os` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `specifications`
--

INSERT INTO `specifications` (`spec_id`, `cpu`, `ram`, `storage`, `screen`, `gpu`, `os`) VALUES
(1, 'Intel Core i7-12700H', '16GB', '512GB SSD', '15.6\" FHD 144Hz', 'NVIDIA RTX 3050', 'Windows 11'),
(2, 'Intel Core i9-12900H', '32GB', '1TB SSD', '17.3\" QHD 165Hz', 'NVIDIA RTX 3070', 'Windows 11'),
(3, 'AMD Ryzen 7 6800H', '16GB', '1TB SSD', '15.6\" FHD 165Hz', 'NVIDIA RTX 3060', 'Windows 11'),
(4, 'Intel Core i7-11800H', '16GB', '512GB SSD', '15.6\" FHD 144Hz', 'NVIDIA GTX 1660 Ti', 'Windows 10'),
(5, 'AMD Ryzen 9 6900HX', '32GB', '1TB SSD', '16\" QHD 165Hz', 'NVIDIA RTX 3080', 'Windows 11'),
(6, 'Intel Core i7-12650H', '16GB', '512GB SSD', '15.6\" FHD 120Hz', 'NVIDIA RTX 3050 Ti', 'Windows 11'),
(7, 'Intel Core i9-13900HX', '32GB', '2TB SSD', '18\" UHD 240Hz', 'NVIDIA RTX 4090', 'Windows 11'),
(8, 'AMD Ryzen 7 5800H', '16GB', '512GB SSD', '15.6\" FHD 144Hz', 'NVIDIA RTX 3060', 'Windows 10'),
(9, 'Intel Core i5-12500H', '16GB', '512GB SSD', '15.6\" FHD 120Hz', 'NVIDIA GTX 1650', 'Windows 11'),
(10, 'AMD Ryzen 7 7735HS', '16GB', '1TB SSD', '16\" FHD 165Hz', 'NVIDIA RTX 4050', 'Windows 11'),
(11, 'Intel Core i7-12700H', '16GB', '1TB SSD', '16.1\" FHD 144Hz', 'NVIDIA RTX 3060', 'Windows 11'),
(12, 'AMD Ryzen 9 7945HX', '32GB', '2TB SSD', '17\" QHD 240Hz', 'NVIDIA RTX 4080', 'Windows 11'),
(13, 'Intel Core i9-11980HK', '32GB', '1TB SSD', '17.3\" FHD 144Hz', 'NVIDIA RTX 3070 Ti', 'Windows 10'),
(14, 'Intel Core i7-12800H', '16GB', '512GB SSD', '15.6\" FHD 165Hz', 'NVIDIA RTX 3060', 'Windows 11'),
(15, 'AMD Ryzen 7 6800H', '16GB', '1TB SSD', '16\" QHD 165Hz', 'NVIDIA RTX 3070', 'Windows 11'),
(16, 'Intel Core i5-1235U', '8GB', '256GB SSD', '14\" FHD', 'Intel Iris Xe', 'Windows 11'),
(17, 'AMD Ryzen 5 5500U', '8GB', '512GB SSD', '15.6\" FHD', 'Radeon Graphics', 'Windows 11'),
(18, 'Intel Core i3-1215U', '8GB', '256GB SSD', '15.6\" FHD', 'Intel UHD', 'Windows 11'),
(19, 'Intel Core i7-1165G7', '16GB', '512GB SSD', '14\" FHD', 'Intel Iris Xe', 'Windows 10'),
(20, 'AMD Ryzen 7 5700U', '16GB', '512GB SSD', '15.6\" FHD', 'Radeon Graphics', 'Windows 11'),
(21, 'Intel Core i5-1135G7', '8GB', '256GB SSD', '13.3\" FHD', 'Intel Iris Xe', 'Windows 11'),
(22, 'Intel Core i3-1115G4', '4GB', '256GB SSD', '14\" HD', 'Intel UHD', 'Windows 10'),
(23, 'AMD Ryzen 3 5300U', '8GB', '256GB SSD', '15.6\" FHD', 'Radeon Graphics', 'Windows 11'),
(24, 'Intel Core i5-10210U', '8GB', '512GB SSD', '15.6\" FHD', 'Intel UHD', 'Windows 10'),
(25, 'AMD Ryzen 5 4500U', '8GB', '512GB SSD', '14\" FHD', 'Radeon Graphics', 'Windows 10'),
(26, 'Intel Core i7-10510U', '16GB', '1TB SSD', '15.6\" FHD', 'Intel UHD', 'Windows 11'),
(27, 'Intel Core i5-1235U', '16GB', '512GB SSD', '14\" FHD', 'Intel Iris Xe', 'Windows 11'),
(28, 'AMD Ryzen 5 7520U', '8GB', '512GB SSD', '15.6\" FHD', 'Radeon 610M', 'Windows 11'),
(29, 'Intel Core i3-10110U', '4GB', '256GB SSD', '15.6\" HD', 'Intel UHD', 'Windows 10'),
(30, 'Intel Core i7-1165G7', '16GB', '1TB SSD', '14\" FHD', 'Intel Iris Xe', 'Windows 11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `useraddresses`
--

CREATE TABLE `useraddresses` (
  `address_id` int(11) NOT NULL,
  `recipient_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_line` text NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `useraddresses`
--

INSERT INTO `useraddresses` (`address_id`, `recipient_name`, `phone`, `address_line`, `city`, `district`, `ward`, `is_default`, `user_id`) VALUES
(1, 'Mạnh Dũng', '0888888888', '123 Đường ABC', 'Hà Nội', 'Hà Đông', 'Yên Nghĩa', 1, 2),
(2, 'Mạnh Dũng', '0395230327', '', 'Hà Nội', 'Hà Đông', 'Yên Nghĩa', 0, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `role` enum('Admin','Khách hàng') NOT NULL DEFAULT 'Khách hàng',
  `is_active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `phone`, `pass`, `role`, `is_active`) VALUES
(1, 'Admin', '0999999999', '111111', 'Admin', 1),
(2, 'Mạnh Dũng', '0888888888', '111111', 'Khách hàng', 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`),
  ADD UNIQUE KEY `brand_name` (`brand_name`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Chỉ mục cho bảng `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `address_id` (`address_id`);

--
-- Chỉ mục cho bảng `productreviews`
--
ALTER TABLE `productreviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `spec_id` (`spec_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `specifications`
--
ALTER TABLE `specifications`
  ADD PRIMARY KEY (`spec_id`);

--
-- Chỉ mục cho bảng `useraddresses`
--
ALTER TABLE `useraddresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `productreviews`
--
ALTER TABLE `productreviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `specifications`
--
ALTER TABLE `specifications`
  MODIFY `spec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `useraddresses`
--
ALTER TABLE `useraddresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Các ràng buộc cho bảng `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `useraddresses` (`address_id`);

--
-- Các ràng buộc cho bảng `productreviews`
--
ALTER TABLE `productreviews`
  ADD CONSTRAINT `productreviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productreviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`spec_id`) REFERENCES `specifications` (`spec_id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Các ràng buộc cho bảng `useraddresses`
--
ALTER TABLE `useraddresses`
  ADD CONSTRAINT `useraddresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
