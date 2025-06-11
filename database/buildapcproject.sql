-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2025 at 02:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `buildapcproject`
--
CREATE DATABASE IF NOT EXISTS `buildapcproject` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `buildapcproject`;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

DROP TABLE IF EXISTS `brand`;
CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `name`) VALUES
(1, 'ASUS'),
(2, 'Corsair'),
(3, 'MSI'),
(4, 'Gigabyte'),
(5, 'ASRock'),
(6, 'EVGA'),
(7, 'NZXT'),
(8, 'Thermaltake'),
(9, 'Cooler Master'),
(10, 'Noctua'),
(11, 'be quiet!'),
(12, 'G.Skill'),
(13, 'ADATA'),
(14, 'Patriot'),
(15, 'Samsung'),
(16, 'Western Digital'),
(17, 'Seagate'),
(18, 'Crucial'),
(19, 'Intel'),
(20, 'AMD'),
(21, 'NVIDIA'),
(22, 'ZOTAC'),
(23, 'PowerColor'),
(24, 'Sapphire'),
(25, 'XFX'),
(26, 'Fractal Design'),
(27, 'Lian Li');

-- --------------------------------------------------------

--
-- Table structure for table `build`
--

DROP TABLE IF EXISTS `build`;
CREATE TABLE `build` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  `created_at` date NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `build`
--

INSERT INTO `build` (`id`, `name`, `is_public`, `created_at`, `user_id`) VALUES
(1, 'Build by leoberghuis@gmail.com', 1, '2025-06-11', 2);

-- --------------------------------------------------------

--
-- Table structure for table `build_products`
--

DROP TABLE IF EXISTS `build_products`;
CREATE TABLE `build_products` (
  `build_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `build_products`
--

INSERT INTO `build_products` (`build_id`, `products_id`) VALUES
(1, 5),
(1, 12),
(1, 18),
(1, 23),
(1, 41),
(1, 55),
(1, 63);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `img`) VALUES
(1, 'cpu', 'cpu_base'),
(2, 'gpu', 'gpu_base'),
(3, 'motherboard', 'motherboard_base'),
(4, 'ram', 'ram_base'),
(5, 'storage', 'storage_base'),
(6, 'power supply', 'power_supply_base'),
(7, 'case', 'case_base');

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250528145118', '2025-05-28 17:04:04', 382),
('DoctrineMigrations\\Version20250602142601', '2025-06-02 16:26:06', 7),
('DoctrineMigrations\\Version20250604215815', '2025-06-10 07:59:31', 88),
('DoctrineMigrations\\Version20250610082343', '2025-06-10 08:24:58', 46),
('DoctrineMigrations\\Version20250610082556', '2025-06-10 08:26:00', 6),
('DoctrineMigrations\\Version20250611101749', '2025-06-11 10:17:58', 48);

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` date NOT NULL,
  `products` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`products`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `brand_id`, `category_id`, `name`, `price`, `stock`, `description`, `image`) VALUES
(1, 19, 1, 'Intel Core i5 12400F', 180.00, 15, 'Intel Core i5 12400F', 'images/intel_cpu_base.jpg'),
(2, 19, 1, 'Intel Core i7 12700K', 299.99, 10, 'Intel Core i7 12700K', 'images/intel_cpu_gaming.jpg'),
(3, 20, 1, 'AMD Ryzen 7 5800X', 250.00, 9, 'AMD Ryzen 7 5800X', 'images/amd_cpu_gaming.jpg'),
(4, 20, 1, 'AMD Ryzen 5 5600X', 160.00, 12, 'AMD Ryzen 5 5600X', 'images/amd_cpu_base.jpg'),
(5, 19, 1, 'Intel core I9', 150.00, 5, 'Goeie cpu ouleh', '/private/var/folders/pc/6y90ycgs6y5268zr2lcm_mcr0000gn/T/phph5t5qnh1gnlf7MkHqYG'),
(6, 20, 1, 'AMD Ryzen 9 7950X', 430.00, 6, 'AMD Ryzen 9 7950X', 'images/amd_cpu_flagship.jpg'),
(7, 21, 2, 'NVIDIA GeForce RTX 3060 Ti', 350.00, 8, 'NVIDIA GeForce RTX 3060 Ti', 'images/nvidia_gpu_3060ti.jpg'),
(8, 21, 2, 'NVIDIA GeForce RTX 3070', 500.00, 6, 'NVIDIA GeForce RTX 3070', 'images/nvidia_gpu_3070.jpg'),
(9, 21, 2, 'NVIDIA RTX 4080 Super', 1299.99, 3, 'NVIDIA RTX 4080 Super', 'images/nvidia_gpu_4080.jpg'),
(10, 21, 2, 'NVIDIA RTX 4090', 1599.99, 2, 'NVIDIA RTX 4090', 'images/nvidia_gpu_4090.jpg'),
(11, 24, 2, 'Sapphire RX 6700 XT', 399.99, 5, 'Sapphire RX 6700 XT', 'images/amd_gpu_6700xt.jpg'),
(12, 24, 2, 'Sapphire RX 6800 XT', 579.99, 4, 'Sapphire RX 6800 XT', 'images/amd_gpu_6800xt.jpg'),
(13, 24, 2, 'Sapphire RX 7900 XTX', 850.00, 2, 'Sapphire RX 7900 XTX', 'images/amd_gpu_7900xtx.jpg'),
(14, 23, 2, 'PowerColor RX 6600', 250.00, 6, 'PowerColor RX 6600', 'images/amd_gpu_6600.jpg'),
(15, 22, 2, 'ZOTAC RTX 3060 Twin Edge', 330.00, 5, 'ZOTAC RTX 3060 Twin Edge', 'images/zotac_gpu_3060.jpg'),
(16, 2, 4, 'Corsair Vengeance 16GB DDR4', 60.00, 20, 'Corsair Vengeance 16GB DDR4', 'images/corsair_ram_base.jpg'),
(17, 2, 4, 'Corsair Vengeance RGB 16GB DDR5', 80.00, 18, 'Corsair Vengeance RGB 16GB DDR5', 'images/corsair_ram_rgb.jpg'),
(18, 12, 4, 'G.Skill Ripjaws 16GB DDR4', 65.00, 22, 'G.Skill Ripjaws 16GB DDR4', 'images/gskill_ram_base.jpg'),
(19, 12, 4, 'G.Skill Trident Z 32GB DDR5', 130.00, 10, 'G.Skill Trident Z 32GB DDR5', 'images/gskill_ram_ddr5.jpg'),
(20, 13, 4, 'ADATA XPG 16GB DDR4 RGB', 75.00, 18, 'ADATA XPG 16GB DDR4 RGB', 'images/adata_ram_rgb.jpg'),
(21, 14, 4, 'Patriot Viper Steel 16GB', 55.00, 25, 'Patriot Viper Steel 16GB', 'images/patriot_ram_base.jpg'),
(22, 2, 4, 'Corsair Vengeance 32GB DDR5', 120.00, 12, 'Corsair Vengeance 32GB DDR5', 'images/corsair_ram_32gb.jpg'),
(23, 15, 5, 'Samsung 980 1TB NVMe SSD', 95.00, 30, 'Samsung 980 1TB NVMe SSD', 'images/samsung_ssd_1tb.jpg'),
(24, 16, 5, 'WD Blue 1TB HDD', 50.00, 40, 'WD Blue 1TB HDD', 'images/wd_hdd_1tb.jpg'),
(25, 17, 5, 'Seagate Barracuda 2TB HDD', 70.00, 25, 'Seagate Barracuda 2TB HDD', 'images/seagate_hdd_2tb.jpg'),
(26, 18, 5, 'Crucial MX500 500GB SSD', 45.00, 35, 'Crucial MX500 500GB SSD', 'images/crucial_ssd_500gb.jpg'),
(27, 15, 5, 'Samsung 980 Pro 2TB NVMe', 160.00, 20, 'Samsung 980 Pro 2TB NVMe', 'images/samsung_ssd_2tb.jpg'),
(28, 16, 5, 'WD Black SN770 1TB SSD', 100.00, 18, 'WD Black SN770 1TB SSD', 'images/wd_black_sn770.jpg'),
(29, 2, 6, 'Corsair RM650x 80+ Gold', 85.00, 12, 'Corsair RM650x 80+ Gold', 'images/corsair_psu_650w.jpg'),
(30, 8, 6, 'Thermaltake 750W Toughpower', 95.00, 10, 'Thermaltake 750W Toughpower', 'images/tt_psu_750w.jpg'),
(31, 6, 6, 'EVGA 550 B5 Bronze', 65.00, 15, 'EVGA 550 B5 Bronze', 'images/evga_psu_550w.jpg'),
(32, 1, 6, 'ASUS ROG Thor 850W Platinum', 110.00, 8, 'ASUS ROG Thor 850W Platinum', 'images/asus_psu_850w.jpg'),
(33, 3, 6, 'MSI MPG A750GF 80+ Gold', 100.00, 10, 'MSI MPG A750GF 80+ Gold', 'images/msi_psu_750w.jpg'),
(34, 1, 3, 'ASUS TUF Gaming B550-Plus', 140.00, 10, 'ASUS TUF Gaming B550-Plus', 'images/asus_mobo_b550.jpg'),
(35, 3, 3, 'MSI B550M PRO-VDH', 125.00, 9, 'MSI B550M PRO-VDH', 'images/msi_mobo_b550m.jpg'),
(36, 4, 3, 'Gigabyte Z690 AORUS Elite', 220.00, 6, 'Gigabyte Z690 AORUS Elite', 'images/gigabyte_mobo_z690.jpg'),
(37, 5, 3, 'ASRock B660M Pro RS', 135.00, 11, 'ASRock B660M Pro RS', 'images/asrock_mobo_b660.jpg'),
(38, 1, 3, 'ASUS ROG Crosshair X670E', 350.00, 4, 'ASUS ROG Crosshair X670E', 'images/asus_mobo_x670e.jpg'),
(39, 3, 3, 'MSI MPG X570 GAMING PLUS', 199.00, 7, 'MSI MPG X570 GAMING PLUS', 'images/msi_mobo_x570.jpg'),
(40, 9, 7, 'Cooler Master H500', 100.00, 10, 'Cooler Master H500', 'images/coolermaster_case_h500.jpg'),
(41, 7, 7, 'NZXT H510 Mid Tower', 85.00, 15, 'NZXT H510 Mid Tower', 'images/nzxt_case_h510.jpg'),
(42, 3, 7, 'MSI MAG Forge 100R', 90.00, 8, 'MSI MAG Forge 100R', 'images/msi_case_gungnir.jpg'),
(43, 1, 7, 'ASUS TUF Gaming GT501', 140.00, 6, 'ASUS TUF Gaming GT501', 'images/asus_case_tuf.jpg'),
(44, 4, 7, 'Gigabyte AC300G ATX', 95.00, 9, 'Gigabyte AC300G ATX', 'images/gigabyte_case_ac300g.jpg'),
(45, 19, 1, 'Intel Core i3-12100', 110.00, 13, 'Intel Core i3-12100', 'images/intel_i3.jpg'),
(46, 20, 1, 'AMD Ryzen 5 5500', 130.00, 12, 'AMD Ryzen 5 5500', 'images/amd_5500.jpg'),
(47, 21, 2, 'NVIDIA RTX 4060', 299.00, 10, 'NVIDIA RTX 4060', 'images/nvidia_4060.jpg'),
(48, 23, 2, 'PowerColor RX 6650 XT', 280.00, 10, 'PowerColor RX 6650 XT', 'images/amd_6650xt.jpg'),
(49, 12, 4, 'G.Skill Trident Z 64GB DDR5', 250.00, 5, 'G.Skill Trident Z 64GB DDR5', 'images/gskill_64gb.jpg'),
(50, 13, 4, 'ADATA 32GB DDR4 XPG', 115.00, 7, 'ADATA 32GB DDR4 XPG', 'images/adata_32gb.jpg'),
(51, 16, 5, 'WD Blue 4TB HDD', 90.00, 10, 'WD Blue 4TB HDD', 'images/wd_4tb.jpg'),
(52, 17, 5, 'Seagate 8TB HDD', 140.00, 6, 'Seagate 8TB HDD', 'images/seagate_8tb.jpg'),
(53, 18, 5, 'Crucial P3 Plus 2TB', 170.00, 4, 'Crucial P3 Plus 2TB', 'images/crucial_2tb.jpg'),
(54, 6, 6, 'EVGA 850 GQ', 120.00, 8, 'EVGA 850 GQ', 'images/evga_850.jpg'),
(55, 5, 3, 'ASRock Z790 Pro RS', 240.00, 5, 'ASRock Z790 Pro RS', 'images/asrock_z790.jpg'),
(56, 7, 7, 'NZXT H9 Flow', 160.00, 3, 'NZXT H9 Flow', 'images/nzxt_h9.jpg'),
(57, 9, 7, 'Cooler Master MB511 ARGB', 80.00, 9, 'Cooler Master MB511 ARGB', 'images/coolermaster_mb511.jpg'),
(58, 3, 3, 'MSI Z790 TOMAHAWK', 270.00, 4, 'MSI Z790 TOMAHAWK', 'images/msi_z790.jpg'),
(59, 20, 1, 'AMD Ryzen 7 5700G', 170.00, 11, 'AMD Ryzen 7 5700G', 'images/amd_5700g.jpg'),
(60, 19, 1, 'Intel Core i5-13500', 240.00, 9, 'Intel Core i5-13500', 'images/intel_i5_13500.jpg'),
(61, 22, 2, 'ZOTAC RTX 4070 Twin Edge', 650.00, 6, 'ZOTAC RTX 4070 Twin Edge', 'images/zotac_4070.jpg'),
(62, 24, 2, 'Sapphire RX 6800', 530.00, 3, 'Sapphire RX 6800', 'images/sapphire_6800.jpg'),
(63, 3, 6, 'MSI MPG A1000G 1000W Gold', 170.00, 3, 'MSI MPG A1000G 1000W Gold', 'images/msi_1000w.jpg'),
(64, 2, 4, 'Corsair Vengeance 32GB DDR5', 135.00, 7, 'Corsair Vengeance 32GB DDR5', 'images/corsair_ddr5.jpg'),
(65, 13, 4, 'ADATA XPG DDR5 16GB', 85.00, 10, 'ADATA XPG DDR5 16GB', 'images/adata_ddr5.jpg'),
(66, 1, 7, 'ASUS Prime AP201 mATX', 95.00, 5, 'ASUS Prime AP201 mATX', 'images/asus_ap201.jpg'),
(67, 4, 3, 'Gigabyte B760 AORUS Elite', 160.00, 6, 'Gigabyte B760 AORUS Elite', 'images/gigabyte_b760.jpg'),
(68, 5, 3, 'ASRock B550M Steel Legend', 120.00, 8, 'ASRock B550M Steel Legend', 'images/asrock_b550m.jpg'),
(69, 9, 7, 'Cooler Master TD500 Mesh', 120.00, 4, 'Cooler Master TD500 Mesh', 'images/td500.jpg'),
(70, 7, 7, 'NZXT H7 Flow', 150.00, 3, 'NZXT H7 Flow', 'images/nzxt_h7.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `first_name`, `last_name`) VALUES
(1, 'duncanangel070@gmail.com', '[]', '$2y$13$AOHF2qRJcSXP7KhpbJPF3.ANTx4L3My1ID9/2m8kl.f1ubSn0ckU6', 'Duncan', 'Engelen'),
(2, 'leoberghuis@gmail.com', '[]', '$2y$13$qfEwCVH5CIhifP7jBHxhRO2cIRKMhPsQFNesJzgeV2Yt0VjbG49F.', 'Leo', 'Berghuis');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `build`
--
ALTER TABLE `build`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BDA0F2DBA76ED395` (`user_id`);

--
-- Indexes for table `build_products`
--
ALTER TABLE `build_products`
  ADD PRIMARY KEY (`build_id`,`products_id`),
  ADD KEY `IDX_5799002C17C13F8B` (`build_id`),
  ADD KEY `IDX_5799002C6C8A81A9` (`products_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B3BA5A5A44F5D008` (`brand_id`),
  ADD KEY `IDX_B3BA5A5A12469DE2` (`category_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `build`
--
ALTER TABLE `build`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `build`
--
ALTER TABLE `build`
  ADD CONSTRAINT `FK_BDA0F2DBA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `build_products`
--
ALTER TABLE `build_products`
  ADD CONSTRAINT `FK_5799002C17C13F8B` FOREIGN KEY (`build_id`) REFERENCES `build` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_5799002C6C8A81A9` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_B3BA5A5A12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_B3BA5A5A44F5D008` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
