-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Апр 25 2025 г., 09:47
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `base`
--

-- --------------------------------------------------------

--
-- Структура таблицы `home_appliances`
--

CREATE TABLE `home_appliances` (
  `id` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `power` varchar(50) DEFAULT NULL,
  `features` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `views` int(11) DEFAULT 0,
  `power_numeric` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `home_appliances`
--

INSERT INTO `home_appliances` (`id`, `category`, `brand`, `name`, `price`, `power`, `features`, `image_url`, `created_at`, `views`, `power_numeric`) VALUES
(1, 'Пилосос', 'Samsung', 'Samsung Jet 70', 5999.99, '1500W', 'Бездротовий, HEPA-фільтр', 'samsung_jet70.jpg', '2025-04-24 15:56:34', 0, 1500),
(2, 'Пральна машина', 'Samsung', 'Samsung WW90T554DAW', 9999.00, '1400W', 'EcoBubble, Wi-Fi', 'samsung_ww90t554daw.jpg', '2025-04-24 15:56:34', 0, 1400),
(3, 'Холодильник', 'Samsung', 'Samsung RB30J3000SA', 12499.00, '800W', 'No Frost, 311 л', 'samsung_rb30j.jpg', '2025-04-24 15:56:34', 0, 800),
(4, 'Мікрохвильова піч', 'Samsung', 'Samsung ME83KRW-1', 2299.00, '800W', 'Механічне управління', 'samsung_me83.jpg', '2025-04-24 15:56:34', 0, 800),
(5, 'Посудомийна машина', 'Samsung', 'Samsung DW60M6050FS', 13499.00, '950W', '14 комплектів, A++', 'samsung_dishwasher.jpg', '2025-04-24 15:56:34', 0, 950),
(6, 'Пральна машина', 'LG', 'LG F2J6NS0W', 7999.00, '1200W', 'Парове прання, Wi-Fi', 'lg_f2j6ns0w.jpg', '2025-04-24 15:56:34', 0, 1200),
(7, 'Холодильник', 'LG', 'LG GA-B459SEQZ', 11499.00, '850W', 'Total No Frost', 'lg_b459.jpg', '2025-04-24 15:56:34', 0, 850),
(8, 'Пилососи', 'LG', 'LG VK76A09NTCR', 3499.00, '2000W', 'Циклон, HEPA 11', 'lg_vk76.jpg', '2025-04-24 15:56:34', 0, 2000),
(9, 'Мікрохвильова піч', 'LG', 'LG MH6535GIB', 3099.00, '1000W', 'Гриль, Smart Inverter', 'lg_mh6535.jpg', '2025-04-24 15:56:34', 0, 1000),
(10, 'Плита', 'LG', 'LG LRG4115ST', 14999.00, '2500W', 'Газова, 5 конфорок', 'lg_lrg4115.jpg', '2025-04-24 15:56:34', 0, 2500),
(11, 'Холодильник', 'Bosch', 'Bosch KGN39VW35', 11999.50, '800W', 'No Frost, 366 л', 'bosch_kgn39vw35.jpg', '2025-04-24 15:56:34', 0, 800),
(12, 'Посудомийна машина', 'Bosch', 'Bosch SMS25AW00E', 10999.00, '1050W', '12 комплектів, A+', 'bosch_sms25.jpg', '2025-04-24 15:56:34', 0, 1050),
(13, 'Пральна машина', 'Bosch', 'Bosch WAN24260BY', 8999.00, '1400W', 'EcoSilence Drive', 'bosch_wan24260.jpg', '2025-04-24 15:56:34', 0, 1400),
(14, 'Пилосос', 'Bosch', 'Bosch BGL35MOV20', 4299.00, '2200W', 'Компактний, мішок', 'bosch_bgl35.jpg', '2025-04-24 15:56:34', 0, 2200),
(15, 'Міксер', 'Bosch', 'Bosch MFQ36460', 999.00, '450W', '5 швидкостей, турбо', 'bosch_mfq.jpg', '2025-04-24 15:56:34', 0, 450),
(16, 'Міксер', 'Philips', 'Philips HR3740/00', 899.90, '450W', '5 швидкостей, Турбо режим', 'philips_hr3740.jpg', '2025-04-24 15:56:34', 0, 450),
(17, 'Пилосос', 'Philips', 'Philips FC9174/01', 3799.00, '2200W', 'Технологія AirflowMax', 'philips_fc9174.jpg', '2025-04-24 15:56:34', 0, 2200),
(18, 'Мікрохвильова піч', 'Philips', 'Philips HD9252', 2799.00, '1400W', 'Airfryer, сенсорне управління', 'philips_hd9252.jpg', '2025-04-24 15:56:34', 0, 1400),
(19, 'Плита', 'Philips', 'Philips HD4938', 1999.00, '2100W', 'Індукційна, 10 програм', 'philips_hd4938.jpg', '2025-04-24 15:56:34', 0, 2100),
(20, 'Посудомийна машина', 'Philips', 'Philips DWP7060', 7499.00, '950W', '6 комплектів, компактна', 'philips_dwp7060.jpg', '2025-04-24 15:56:34', 0, 950),
(21, 'Пральна машина', 'Electrolux', 'Electrolux EW6F3R28SI', 9499.00, '1200W', 'SteamCare, Wi-Fi', 'electrolux_ew6f.jpg', '2025-04-24 15:56:34', 0, 1200),
(22, 'Посудомийна машина', 'Electrolux', 'Electrolux ESF9552LOW', 9999.00, '1000W', 'A++, 13 комплектів', 'electrolux_esf9552.jpg', '2025-04-24 15:56:34', 0, 1000),
(23, 'Плита', 'Electrolux', 'Electrolux EKG951108X', 13999.00, '2600W', 'Газова, духовка з грилем', 'electrolux_ekg.jpg', '2025-04-24 15:56:34', 0, 2600),
(24, 'Мікрохвильова піч', 'Electrolux', 'Electrolux EMS21400W', 2399.00, '900W', 'Гриль, електроніка', 'electrolux_ems.jpg', '2025-04-24 15:56:34', 0, 900),
(25, 'Витяжка', 'Electrolux', 'Electrolux LFP316S', 3199.00, '180W', 'Т-подібна, LED', 'electrolux_lfp.jpg', '2025-04-24 15:56:34', 0, 180),
(26, 'Пральна машина', 'Whirlpool', 'Whirlpool FWSG71283BV', 7499.00, '1200W', 'FreshCare+, A+++', 'whirlpool_fwsg.jpg', '2025-04-24 15:56:34', 0, 1200),
(27, 'Мікрохвильова піч', 'Whirlpool', 'Whirlpool MWP339SB', 6499.00, '1000W', 'Гриль, сенсорне управління', 'whirlpool_mwpn339sb.jpg', '2025-04-24 15:56:34', 0, 1000),
(28, 'Посудомийна машина', 'Whirlpool', 'Whirlpool WFC 3C26', 8999.00, '950W', '14 комплектів, 6th Sense', 'whirlpool_wfc.jpg', '2025-04-24 15:56:34', 0, 950),
(29, 'Плита', 'Whirlpool', 'Whirlpool ACMT6533/IX', 9999.00, '2400W', 'Комбінована, електродуховка', 'whirlpool_acmt.jpg', '2025-04-24 15:56:34', 0, 2400),
(30, 'Витяжка', 'Whirlpool', 'Whirlpool AKR 551 IX', 2199.00, '200W', 'Камінна, LED', 'whirlpool_akr.jpg', '2025-04-24 15:56:34', 0, 200),
(31, 'Пральна машина', 'Indesit', 'Indesit IWSC 5105', 5990.00, '1000W', '14 програм прання', 'indesit_iwsc5105.jpg', '2025-04-24 15:56:34', 0, 1000),
(32, 'Холодильник', 'Indesit', 'Indesit DS 316 W', 7499.00, '800W', 'Клас A+, 298 л', 'indesit_ds316.jpg', '2025-04-24 15:56:34', 0, 800),
(33, 'Плита', 'Indesit', 'Indesit IS5G1PMW', 5999.00, '2200W', 'Газова, електрозапалювання', 'indesit_is5g.jpg', '2025-04-24 15:56:34', 0, 2200),
(34, 'Мікрохвильова піч', 'Indesit', 'Indesit MWI 120 GX', 2899.00, '800W', 'Інверторна, LED дисплей', 'indesit_mwi.jpg', '2025-04-24 15:56:34', 0, 800),
(35, 'Пилосос', 'Indesit', 'Indesit CleanPro 2000', 2999.00, '2000W', 'Багатофункціональний', 'indesit_cleanpro.jpg', '2025-04-24 15:56:34', 0, 2000),
(36, 'Холодильник', 'Beko', 'Beko RDSK240M00W', 6999.00, '750W', 'Клас A+, механічне управління', 'beko_rdsk240.jpg', '2025-04-24 15:56:34', 0, 750),
(37, 'Пральна машина', 'Beko', 'Beko WRS 5511 BWW', 5990.00, '1000W', 'Slim, 15 програм', 'beko_wrs.jpg', '2025-04-24 15:56:34', 0, 1000),
(38, 'Плита', 'Beko', 'Beko FSM52330DXT', 8799.00, '2400W', 'Комбінована, газ + електро', 'beko_fsm.jpg', '2025-04-24 15:56:34', 0, 2400),
(39, 'Мікрохвильова піч', 'Beko', 'Beko MGC20100S', 2099.00, '700W', 'Механічне управління', 'beko_mgc.jpg', '2025-04-24 15:56:34', 0, 700),
(40, 'Пилосос', 'Beko', 'Beko VCC34801AR', 2599.00, '1800W', 'Циклон, мішок', 'beko_vcc.jpg', '2025-04-24 15:56:34', 0, 1800),
(41, 'Пилосос', 'Samsung', 'Samsung Jet 90', 7999.00, '2000W', 'Циклонна система, HEPA-фільтр', 'samsung_jet90.jpg', '2025-04-24 16:16:00', 0, 2000),
(42, 'Пральна машина', 'Samsung', 'Samsung WW10T684DLX', 13999.00, '1400W', 'EcoBubble, цифровий інвертор', 'samsung_ww10t684dlx.jpg', '2025-04-24 16:16:00', 0, 1400),
(43, 'Холодильник', 'Samsung', 'Samsung RB37J5020WW', 14999.00, '850W', 'Twin Cooling Plus, No Frost', 'samsung_rb37.jpg', '2025-04-24 16:16:00', 0, 850),
(44, 'Мікрохвильова піч', 'Samsung', 'Samsung MS23K3513AK', 2999.00, '800W', 'Технологія Triple Distribution, гриль', 'samsung_ms23k.jpg', '2025-04-24 16:16:00', 0, 800),
(45, 'Посудомийна машина', 'Samsung', 'Samsung DW60M5040FS', 11999.00, '1000W', 'Технологія WaterWall, A++', 'samsung_dw60.jpg', '2025-04-24 16:16:00', 0, 1000),
(46, 'Пральна машина', 'LG', 'LG F4V5VYP2T', 11999.00, '1400W', 'AI DD, Steam+ технологія', 'lg_f4v5vyp2t.jpg', '2025-04-24 16:16:00', 0, 1400),
(47, 'Холодильник', 'LG', 'LG GBF61PZJZN', 15999.00, '900W', 'No Frost, Multi Air Flow', 'lg_gbf61pzjzn.jpg', '2025-04-24 16:16:00', 0, 900),
(48, 'Пилосос', 'LG', 'LG CordZero A9', 7499.00, '2000W', 'Інверторний мотор, бездротовий', 'lg_cordzero.jpg', '2025-04-24 16:16:00', 0, 2000),
(49, 'Мікрохвильова піч', 'LG', 'LG MS2042DB', 2499.00, '800W', 'Інверторне управління', 'lg_ms2042db.jpg', '2025-04-24 16:16:00', 0, 800),
(50, 'Плита', 'LG', 'LG LSR2015', 14999.00, '2500W', 'Газова, 4 конфорки', 'lg_lsr2015.jpg', '2025-04-24 16:16:00', 0, 2500),
(51, 'Холодильник', 'Bosch', 'Bosch KSV36VWEP', 16999.00, '1000W', 'MultiAirflow, FreshSense', 'bosch_ksv36w.jpg', '2025-04-24 16:16:00', 0, 1000),
(52, 'Посудомийна машина', 'Bosch', 'Bosch SMV46IX00E', 14999.00, '1050W', '16 комплектів, A++', 'bosch_smv46ix00e.jpg', '2025-04-24 16:16:00', 0, 1050),
(53, 'Пральна машина', 'Bosch', 'Bosch WAN24169BY', 8999.00, '1400W', 'EcoSilence Drive', 'bosch_wan24169.jpg', '2025-04-24 16:16:00', 0, 1400),
(54, 'Пилосос', 'Bosch', 'Bosch BGL8ZOOF', 5299.00, '2500W', 'Регулювання потужності', 'bosch_bgl8zoof.jpg', '2025-04-24 16:16:00', 0, 2500),
(55, 'Міксер', 'Bosch', 'Bosch MFQ36460', 1299.00, '450W', '5 швидкостей, турбо', 'bosch_mfq36460.jpg', '2025-04-24 16:16:00', 0, 450),
(56, 'Міксер', 'Philips', 'Philips HR3740/01', 999.00, '450W', '5 швидкостей, турбо', 'philips_hr3740_01.jpg', '2025-04-24 16:16:00', 0, 450),
(57, 'Пилосос', 'Philips', 'Philips FC8762/01', 5999.00, '1800W', 'PowerCyclone 5, HEPA', 'philips_fc8762.jpg', '2025-04-24 16:16:00', 0, 1800),
(58, 'Мікрохвильова піч', 'Philips', 'Philips HD9220', 3599.00, '1400W', 'Airfryer, сенсорне управління', 'philips_hd9220.jpg', '2025-04-24 16:16:00', 0, 1400),
(59, 'Плита', 'Philips', 'Philips HD4911', 4999.00, '2100W', 'Індукційна, 5 програм', 'philips_hd4911.jpg', '2025-04-24 16:16:00', 0, 2100),
(60, 'Посудомийна машина', 'Philips', 'Philips DWP5020', 6499.00, '950W', '6 комплектів, клас A+', 'philips_dwp5020.jpg', '2025-04-24 16:16:00', 0, 950),
(61, 'Пральна машина', 'Electrolux', 'Electrolux EW6F5R06SI', 9499.00, '1400W', 'SteamCare, A++', 'electrolux_ew6f5r06si.jpg', '2025-04-24 16:16:00', 0, 1400),
(62, 'Посудомийна машина', 'Electrolux', 'Electrolux ESF6600RO', 8499.00, '950W', '6 комплектів, компактна', 'electrolux_esf6600ro.jpg', '2025-04-24 16:16:00', 0, 950),
(63, 'Плита', 'Electrolux', 'Electrolux EKI6054AOW', 7999.00, '2000W', 'Газова, 4 конфорки', 'electrolux_eki6054aow.jpg', '2025-04-24 16:16:00', 0, 2000),
(64, 'Мікрохвильова піч', 'Electrolux', 'Electrolux EMS30408OX', 2799.00, '900W', 'Гриль, 20 л', 'electrolux_ems30408ox.jpg', '2025-04-24 16:16:00', 0, 900),
(65, 'Витяжка', 'Electrolux', 'Electrolux LFP516X', 4799.00, '180W', 'Т-подібна, LED', 'electrolux_lfp516x.jpg', '2025-04-24 16:16:00', 0, 180),
(66, 'Пральна машина', 'Whirlpool', 'Whirlpool FWF81053W', 7999.00, '1200W', '6th Sense, FreshCare+', 'whirlpool_fwf81053w.jpg', '2025-04-24 16:16:00', 0, 1200),
(67, 'Мікрохвильова піч', 'Whirlpool', 'Whirlpool MWG122SL', 5499.00, '1000W', 'Гриль, сенсорне управління', 'whirlpool_mwg122sl.jpg', '2025-04-24 16:16:00', 0, 1000),
(68, 'Посудомийна машина', 'Whirlpool', 'Whirlpool WFC 3C34', 10999.00, '1000W', '13 комплектів, 6th Sense', 'whirlpool_wfc3c34.jpg', '2025-04-24 16:16:00', 0, 1000),
(69, 'Плита', 'Whirlpool', 'Whirlpool AKP 201 IX', 7999.00, '2300W', 'Газова, електрична духовка', 'whirlpool_akp201ix.jpg', '2025-04-24 16:16:00', 0, 2300),
(70, 'Витяжка', 'Whirlpool', 'Whirlpool AKR 900 IX', 2299.00, '250W', 'Камінна, LED', 'whirlpool_akr900ix.jpg', '2025-04-24 16:16:00', 0, 250),
(71, 'Пральна машина', 'Indesit', 'Indesit IWSE 61051 C ECO', 6499.00, '1000W', 'EcoTime, 16 програм', 'indesit_iwse61051c.jpg', '2025-04-24 16:16:00', 0, 1000),
(72, 'Холодильник', 'Indesit', 'Indesit BIA 18', 8499.00, '700W', 'А++, 300 л', 'indesit_bia18.jpg', '2025-04-24 16:16:00', 0, 700),
(73, 'Плита', 'Indesit', 'Indesit IS5G4PMW', 6999.00, '2000W', 'Газова, 4 конфорки', 'indesit_is5g4pmw.jpg', '2025-04-24 16:16:00', 0, 2000),
(74, 'Мікрохвильова піч', 'Indesit', 'Indesit MWI 122 IX', 2999.00, '800W', 'Інверторна, LED дисплей', 'indesit_mwi122ix.jpg', '2025-04-24 16:16:00', 0, 800),
(75, 'Пилосос', 'Indesit', 'Indesit CleanPro 2500', 3499.00, '2500W', 'Технологія циклон, мішок', 'indesit_cleanpro2500.jpg', '2025-04-24 16:16:00', 0, 2500),
(76, 'Холодильник', 'Beko', 'Beko RCSA270K20W', 7499.00, '800W', 'А++, 268 л', 'beko_rcsa270k20w.jpg', '2025-04-24 16:16:00', 0, 800),
(77, 'Пральна машина', 'Beko', 'Beko WMB71021', 6499.00, '1000W', 'Slim, 15 програм', 'beko_wmb71021.jpg', '2025-04-24 16:16:00', 0, 1000),
(78, 'Плита', 'Beko', 'Beko FSM67300GXS', 10999.00, '2400W', 'Газова, електрична духовка', 'beko_fsm67300gxs.jpg', '2025-04-24 16:16:00', 0, 2400),
(79, 'Мікрохвильова піч', 'Beko', 'Beko MCB 25331 X', 2299.00, '800W', 'Інверторне управління, 25 л', 'beko_mcb25331x.jpg', '2025-04-24 16:16:00', 0, 800),
(80, 'Пилосос', 'Beko', 'Beko VCM61821A', 1999.00, '1800W', 'Циклон, мішок', 'beko_vcm61821a.jpg', '2025-04-24 16:16:00', 0, 1800);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `ram` int(11) DEFAULT NULL,
  `storage` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `views` int(11) DEFAULT 0,
  `power` varchar(50) DEFAULT NULL,
  `features` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `category`, `brand`, `name`, `price`, `ram`, `storage`, `image_url`, `created_at`, `views`, `power`, `features`) VALUES
(1, 'smartphones', 'Apple', 'iPhone 15 Pro 128GB', 39999.00, 6, 128, 'https://example.com/iphone15.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(2, 'smartphones', 'Apple', 'iPhone 15 Pro Max 256GB', 45999.00, 6, 256, 'https://example.com/iphone15max.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(3, 'smartphones', 'Apple', 'iPhone 14 128GB', 29999.00, 4, 128, 'https://example.com/iphone14.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(4, 'smartphones', 'Apple', 'iPhone 14 Plus 128GB', 32999.00, 4, 128, 'https://example.com/iphone14plus.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(5, 'smartphones', 'Apple', 'iPhone SE 2022 64GB', 18999.00, 4, 64, 'https://example.com/iphonese.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(6, 'smartphones', 'Samsung', 'Galaxy S23 Ultra 12/256GB', 36999.00, 12, 256, 'https://example.com/s23ultra.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(7, 'smartphones', 'Samsung', 'Galaxy S23+ 8/256GB', 32999.00, 8, 256, 'https://example.com/s23plus.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(8, 'smartphones', 'Samsung', 'Galaxy S23 8/128GB', 27999.00, 8, 128, 'https://example.com/s23.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(9, 'smartphones', 'Samsung', 'Galaxy Z Fold5 12/512GB', 59999.00, 12, 512, 'https://example.com/zfold5.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(10, 'smartphones', 'Samsung', 'Galaxy Z Flip5 8/256GB', 45999.00, 8, 256, 'https://example.com/zflip5.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(11, 'smartphones', 'Xiaomi', 'Redmi Note 12 Pro 8/256GB', 12999.00, 8, 256, 'https://example.com/redminote12.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(12, 'smartphones', 'Xiaomi', 'Xiaomi 13 Pro 12/256GB', 34999.00, 12, 256, 'https://example.com/xiaomi13pro.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(13, 'smartphones', 'Xiaomi', 'Xiaomi 13 Lite 8/128GB', 17999.00, 8, 128, 'https://example.com/xiaomi13lite.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(14, 'smartphones', 'Xiaomi', 'Redmi Note 11 Pro 6/128GB', 10999.00, 6, 128, 'https://example.com/redminote11.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(15, 'smartphones', 'Xiaomi', 'Poco X5 Pro 8/256GB', 14999.00, 8, 256, 'https://example.com/pocox5.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(16, 'smartphones', 'Huawei', 'P60 Pro 12/512GB', 29999.00, 12, 512, 'https://example.com/p60pro.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(17, 'smartphones', 'Huawei', 'Mate 50 Pro 8/256GB', 32999.00, 8, 256, 'https://example.com/mate50.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(18, 'smartphones', 'Huawei', 'Nova 10 8/128GB', 15999.00, 8, 128, 'https://example.com/nova10.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(19, 'smartphones', 'Huawei', 'P50 Pocket 8/256GB', 37999.00, 8, 256, 'https://example.com/p50pocket.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(20, 'smartphones', 'Huawei', 'Enjoy 50 Pro 6/128GB', 11999.00, 6, 128, 'https://example.com/enjoy50.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(21, 'smartphones', 'OnePlus', '11 5G 16/256GB', 27999.00, 16, 256, 'https://example.com/oneplus11.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(22, 'smartphones', 'OnePlus', '10 Pro 12/256GB', 24999.00, 12, 256, 'https://example.com/oneplus10.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(23, 'smartphones', 'OnePlus', 'Nord 3 5G 8/128GB', 17999.00, 8, 128, 'https://example.com/nord3.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(24, 'smartphones', 'OnePlus', 'Nord CE 3 Lite 8/256GB', 14999.00, 8, 256, 'https://example.com/nordcelite.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(25, 'smartphones', 'OnePlus', 'Ace 2V 12/256GB', 19999.00, 12, 256, 'https://example.com/ace2v.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(26, 'smartphones', 'Google', 'Pixel 7 Pro 12/256GB', 32999.00, 12, 256, 'https://example.com/pixel7pro.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(27, 'smartphones', 'Google', 'Pixel 7 8/128GB', 25999.00, 8, 128, 'https://example.com/pixel7.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(28, 'smartphones', 'Google', 'Pixel 6a 6/128GB', 19999.00, 6, 128, 'https://example.com/pixel6a.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(29, 'smartphones', 'Google', 'Pixel Fold 12/256GB', 49999.00, 12, 256, 'https://example.com/pixelfold.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(30, 'smartphones', 'Google', 'Pixel 5a 6/128GB', 17999.00, 6, 128, 'https://example.com/pixel5a.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(31, 'smartphones', 'Realme', 'GT Neo 5 16/256GB', 18999.00, 16, 256, 'https://example.com/gtneo5.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(32, 'smartphones', 'Realme', 'GT 3 12/256GB', 21999.00, 12, 256, 'https://example.com/gt3.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(33, 'smartphones', 'Realme', '10 Pro+ 8/256GB', 15999.00, 8, 256, 'https://example.com/10proplus.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(34, 'smartphones', 'Realme', 'C55 6/128GB', 8999.00, 6, 128, 'https://example.com/c55.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(35, 'smartphones', 'Realme', 'Narzo 50 Pro 8/128GB', 12999.00, 8, 128, 'https://example.com/narzo50.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(36, 'smartphones', 'Oppo', 'Find X5 Pro 12/256GB', 28999.00, 12, 256, 'https://example.com/findx5pro.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(37, 'smartphones', 'Oppo', 'Reno8 Pro 12/256GB', 24999.00, 12, 256, 'https://example.com/reno8pro.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(38, 'smartphones', 'Oppo', 'A78 5G 8/128GB', 13999.00, 8, 128, 'https://example.com/a78.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(39, 'smartphones', 'Oppo', 'A96 8/256GB', 11999.00, 8, 256, 'https://example.com/a96.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(40, 'smartphones', 'Oppo', 'K10 5G 6/128GB', 10999.00, 6, 128, 'https://example.com/k10.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(41, 'smartphones', 'Vivo', 'X90 Pro 12/256GB', 34999.00, 12, 256, 'https://example.com/x90pro.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(42, 'smartphones', 'Vivo', 'V27 Pro 12/256GB', 27999.00, 12, 256, 'https://example.com/v27pro.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(43, 'smartphones', 'Vivo', 'Y78 5G 8/128GB', 14999.00, 8, 128, 'https://example.com/y78.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(44, 'smartphones', 'Vivo', 'T1 Pro 5G 8/128GB', 16999.00, 8, 128, 'https://example.com/t1pro.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(45, 'smartphones', 'Vivo', 'Y35 4G 6/128GB', 9999.00, 6, 128, 'https://example.com/y35.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(46, 'smartphones', 'Asus', 'ROG Phone 7 16/512GB', 31999.00, 16, 512, 'https://example.com/rogphone7.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(47, 'smartphones', 'Asus', 'Zenfone 10 8/128GB', 26999.00, 8, 128, 'https://example.com/zenfone10.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(48, 'smartphones', 'Asus', 'ROG Phone 6 12/256GB', 28999.00, 12, 256, 'https://example.com/rogphone6.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(49, 'smartphones', 'Asus', 'Zenfone 9 8/128GB', 22999.00, 8, 128, 'https://example.com/zenfone9.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(50, 'smartphones', 'Asus', 'ROG Phone 5s 16/256GB', 24999.00, 16, 256, 'https://example.com/rogphone5s.jpg', '2025-04-10 00:27:00', 0, NULL, NULL),
(51, 'smartphones', 'Xiaomi', 'Redmi 12C 4/64GB', 5999.00, 4, 64, 'https://example.com/redmi12c.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(52, 'smartphones', 'Xiaomi', 'Redmi A2 2/32GB', 4499.00, 2, 32, 'https://example.com/redmia2.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(53, 'smartphones', 'Xiaomi', 'Redmi 10 4/128GB', 8999.00, 4, 128, 'https://example.com/redmi10.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(54, 'smartphones', 'Xiaomi', 'Poco C55 6/128GB', 7999.00, 6, 128, 'https://example.com/pococ55.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(55, 'smartphones', 'Xiaomi', 'Redmi Note 10S 6/64GB', 10499.00, 6, 64, 'https://example.com/note10s.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(56, 'smartphones', 'Samsung', 'Galaxy A04e 3/64GB', 6499.00, 3, 64, 'https://example.com/a04e.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(57, 'smartphones', 'Samsung', 'Galaxy A14 4G 4/128GB', 9499.00, 4, 128, 'https://example.com/a14.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(58, 'smartphones', 'Samsung', 'Galaxy M04 4/64GB', 7999.00, 4, 64, 'https://example.com/m04.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(59, 'smartphones', 'Samsung', 'Galaxy A13 4/64GB', 8999.00, 4, 64, 'https://example.com/a13.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(60, 'smartphones', 'Samsung', 'Galaxy F04 4/64GB', 7599.00, 4, 64, 'https://example.com/f04.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(61, 'smartphones', 'Realme', 'Narzo 60x 5G 6/128GB', 10999.00, 6, 128, 'https://example.com/narzo60x.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(62, 'smartphones', 'Realme', 'Realme C33 4/64GB', 6999.00, 4, 64, 'https://example.com/c33.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(63, 'smartphones', 'Realme', 'Narzo N55 6/128GB', 9999.00, 6, 128, 'https://example.com/narzon55.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(64, 'smartphones', 'Realme', 'Realme 11x 5G 6/128GB', 11499.00, 6, 128, 'https://example.com/realme11x.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(65, 'smartphones', 'Realme', 'C51 4/64GB', 6499.00, 4, 64, 'https://example.com/c51.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(66, 'smartphones', 'Motorola', 'Moto G13 4/128GB', 8499.00, 4, 128, 'https://example.com/g13.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(67, 'smartphones', 'Motorola', 'Moto E13 2/64GB', 5999.00, 2, 64, 'https://example.com/e13.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(68, 'smartphones', 'Motorola', 'Moto G32 6/128GB', 10499.00, 6, 128, 'https://example.com/g32.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(69, 'smartphones', 'Motorola', 'Moto G73 5G 8/128GB', 12999.00, 8, 128, 'https://example.com/g73.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(70, 'smartphones', 'Motorola', 'Moto G14 4/128GB', 7999.00, 4, 128, 'https://example.com/g14.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(71, 'smartphones', 'Infinix', 'Hot 30i 4/64GB', 6999.00, 4, 64, 'https://example.com/hot30i.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(72, 'smartphones', 'Infinix', 'Zero 5G 2023 8/128GB', 12499.00, 8, 128, 'https://example.com/zero5g.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(73, 'smartphones', 'Infinix', 'Smart 7 4/64GB', 5799.00, 4, 64, 'https://example.com/smart7.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(74, 'smartphones', 'Infinix', 'Note 12 6/128GB', 9999.00, 6, 128, 'https://example.com/note12.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(75, 'smartphones', 'Infinix', 'Zero Ultra 8/256GB', 19999.00, 8, 256, 'https://example.com/zeroultra.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(76, 'smartphones', 'Tecno', 'Spark 10 4/64GB', 6499.00, 4, 64, 'https://example.com/spark10.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(77, 'smartphones', 'Tecno', 'Pova 5 Pro 8/128GB', 11499.00, 8, 128, 'https://example.com/pova5pro.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(78, 'smartphones', 'Tecno', 'Pop 7 Pro 4/64GB', 5999.00, 4, 64, 'https://example.com/pop7.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(79, 'smartphones', 'Tecno', 'Camon 20 8/128GB', 11999.00, 8, 128, 'https://example.com/camon20.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(80, 'smartphones', 'Tecno', 'Spark Go 2023 3/64GB', 5599.00, 3, 64, 'https://example.com/sparkgo.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(81, 'smartphones', 'Lava', 'Blaze 5G 4/64GB', 7999.00, 4, 64, 'https://example.com/blaze5g.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(82, 'smartphones', 'Lava', 'Yuva 2 Pro 4/64GB', 6999.00, 4, 64, 'https://example.com/yuva2pro.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(83, 'smartphones', 'Itel', 'A60 2/32GB', 4799.00, 2, 32, 'https://example.com/a60.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(84, 'smartphones', 'Itel', 'P40 4/64GB', 5799.00, 4, 64, 'https://example.com/p40.jpg', '2025-04-10 07:46:25', 0, NULL, NULL),
(85, 'smartphones', 'Itel', 'S23 8/128GB', 8499.00, 8, 128, 'https://example.com/s23itel.jpg', '2025-04-10 07:46:25', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `avatar`) VALUES
(2, 'Dimass', '$2y$10$2JtHq/SYcb2kNoIP9oX//O53DTrFgxTdvtnBrNYdI5LCjCYsJaNXW', '2025-04-04 05:46:27', NULL),
(7, 'Dimasss', '$2y$10$z.9fdsWxUhACufs9GjWQ4.A4ncvNfewhsZ7QD9EuMee2ZF1TxtJuu', '2025-04-04 05:59:42', NULL),
(11, 'Dimonchik14', '$2y$10$SVc.6LhOH.2cUbH.7AksiObx5zSNArvCeiNdPTdP70XL/tomTQ146', '2025-04-04 06:24:46', 'uploads/avatar_11_1744233141.jpg'),
(13, 'Dimons', '$2y$10$UUo4mZ6P5xQ1idSoh4pJruFrQ.JNZ6KqHAdRQn2VfJpZA5zH1wrGC', '2025-04-10 18:44:12', 'uploads/avatar_13_1745560719.jpg');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `home_appliances`
--
ALTER TABLE `home_appliances`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `home_appliances`
--
ALTER TABLE `home_appliances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
