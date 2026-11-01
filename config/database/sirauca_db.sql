-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-11-2026 a las 00:31:51
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sirauca_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT '',
  `capacidad` tinyint(4) NOT NULL DEFAULT 30,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carts`
--

INSERT INTO `carts` (`id`, `nombre`, `descripcion`, `capacidad`, `created_at`) VALUES
(1, 'Carro 1', 'Carro Completo de Notebooks', 30, '2026-11-01 21:48:16'),
(2, 'Carro 2', 'Carro Completo de Notebooks', 30, '2026-11-01 21:48:42'),
(3, 'Carro 3', 'Carro Incompleto de Notebooks', 30, '2026-11-01 21:48:53'),
(4, 'Carro 4', 'Carro Vacio', 30, '2026-11-01 21:49:05'),
(5, 'Carro 5', 'Carro Vacio', 30, '2026-11-01 21:49:17'),
(6, 'Carro 6', 'Carro Vacio', 30, '2026-11-01 21:49:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_delivery_items`
--

CREATE TABLE `cart_delivery_items` (
  `id` int(11) NOT NULL,
  `cart_reservation_id` int(11) NOT NULL,
  `nombre_computadora` varchar(120) NOT NULL,
  `numero_serie` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cart_delivery_items`
--

INSERT INTO `cart_delivery_items` (`id`, `cart_reservation_id`, `nombre_computadora`, `numero_serie`) VALUES
(1, 1, 'Dell Latitude 5420', 'DL5420-0001'),
(2, 1, 'Lenovo ThinkPad T14', 'LNT14-0002'),
(3, 1, 'HP ProBook 440 G8', 'HP440G8-0003'),
(4, 1, 'Acer TravelMate P2', 'ACP2-0004'),
(5, 1, 'Asus ExpertBook B1', 'ASB1-0005'),
(6, 1, 'Dell Latitude 3520', 'DL3520-0006'),
(7, 1, 'Lenovo ThinkPad E14', 'LNE14-0007'),
(8, 1, 'HP EliteBook 840 G7', 'HP840G7-0008'),
(9, 1, 'Acer Aspire 5', 'ACA5-0009'),
(10, 1, 'Asus VivoBook 15', 'ASV15-0010'),
(11, 1, 'Dell Vostro 3400', 'DV3400-0011'),
(12, 1, 'Lenovo IdeaPad 3', 'LNIP3-0012'),
(13, 1, 'HP 250 G8', 'HP250G8-0013'),
(14, 1, 'Acer Extensa 15', 'ACEX15-0014'),
(15, 1, 'Asus Laptop X515', 'ASX515-0015'),
(16, 1, 'Dell Inspiron 3511', 'DI3511-0016'),
(17, 1, 'Lenovo ThinkBook 15', 'LNTB15-0017'),
(18, 1, 'HP Pavilion 15', 'HPP15-0018'),
(19, 1, 'Acer Swift 3', 'ACSW3-0019'),
(20, 1, 'Asus ZenBook 14', 'ASZ14-0020'),
(21, 1, 'Dell Latitude 5410', 'DL5410-0021'),
(22, 1, 'Lenovo ThinkPad L14', 'LNL14-0022'),
(23, 1, 'HP ProBook 450 G7', 'HP450G7-0023'),
(24, 1, 'Acer TravelMate P4', 'ACP4-0024'),
(25, 1, 'Asus ExpertBook P1', 'ASP1-0025'),
(26, 1, 'Dell Vostro 3510', 'DV3510-0026'),
(27, 1, 'Lenovo IdeaPad 5', 'LNIP5-0027'),
(28, 1, 'HP 245 G8', 'HP245G8-0028'),
(29, 1, 'Acer Aspire 3', 'ACA3-0029'),
(30, 1, 'Asus VivoBook 14', 'ASV14-0030'),
(31, 2, 'Dell Latitude 5420', 'DL5420-0001'),
(32, 2, 'Lenovo ThinkPad T14', 'LNT14-0002'),
(33, 2, 'HP ProBook 440 G8', 'HP440G8-0003'),
(34, 2, 'Acer TravelMate P2', 'ACP2-0004'),
(35, 2, 'Asus ExpertBook B1', 'ASB1-0005'),
(36, 2, 'Dell Latitude 3520', 'DL3520-0006'),
(37, 2, 'Lenovo ThinkPad E14', 'LNE14-0007'),
(38, 2, 'HP EliteBook 840 G7', 'HP840G7-0008'),
(39, 2, 'Acer Aspire 5', 'ACA5-0009'),
(40, 2, 'Asus VivoBook 15', 'ASV15-0010'),
(41, 2, 'Dell Vostro 3400', 'DV3400-0011'),
(42, 2, 'Lenovo IdeaPad 3', 'LNIP3-0012'),
(43, 2, 'HP 250 G8', 'HP250G8-0013'),
(44, 2, 'Acer Extensa 15', 'ACEX15-0014'),
(45, 2, 'Asus Laptop X515', 'ASX515-0015'),
(46, 2, 'Dell Inspiron 3511', 'DI3511-0016'),
(47, 2, 'Lenovo ThinkBook 15', 'LNTB15-0017'),
(48, 2, 'HP Pavilion 15', 'HPP15-0018'),
(49, 2, 'Acer Swift 3', 'ACSW3-0019'),
(50, 2, 'Asus ZenBook 14', 'ASZ14-0020'),
(51, 2, 'Dell Latitude 5410', 'DL5410-0021'),
(52, 2, 'Lenovo ThinkPad L14', 'LNL14-0022'),
(53, 2, 'HP ProBook 450 G7', 'HP450G7-0023'),
(54, 2, 'Acer TravelMate P4', 'ACP4-0024'),
(55, 2, 'Asus ExpertBook P1', 'ASP1-0025'),
(56, 2, 'Dell Vostro 3510', 'DV3510-0026'),
(57, 2, 'Lenovo IdeaPad 5', 'LNIP5-0027'),
(58, 2, 'HP 245 G8', 'HP245G8-0028'),
(59, 2, 'Acer Aspire 3', 'ACA3-0029'),
(60, 2, 'Asus VivoBook 14', 'ASV14-0030'),
(61, 3, 'Dell Latitude 5440', 'DL5440-0061'),
(62, 3, 'Lenovo ThinkPad X1 Carbon', 'LNX1C-0062'),
(63, 3, 'HP EliteBook 845 G8', 'HP845G8-0063'),
(64, 3, 'Acer TravelMate Spin P4', 'ACSP4-0064'),
(65, 3, 'Asus ExpertBook B9', 'ASB9-0065'),
(66, 3, 'Dell Latitude 7320', 'DL7320-0066'),
(67, 3, 'Lenovo ThinkPad X13', 'LNX13-0067'),
(68, 3, 'HP ProBook 635 Aero G8', 'HP635G8-0068'),
(69, 3, 'Acer Swift X', 'ACSWX-0069'),
(70, 3, 'Asus ZenBook S13', 'ASZS13-0070'),
(71, 3, 'Dell Inspiron 14 5425', 'DI145425-0071'),
(72, 3, 'Lenovo Yoga Slim 7', 'LNY7-0072'),
(73, 3, 'HP Pavilion Aero 13', 'HPPA13-0073'),
(74, 3, 'Acer Aspire Vero', 'ACAV-0074'),
(75, 3, 'Asus VivoBook S14-1', 'ASVS14-0076'),
(76, 4, 'Dell Latitude 5430', 'DL5430-0031'),
(77, 4, 'Lenovo ThinkPad T15', 'LNT15-0032'),
(78, 4, 'HP ProBook 445 G8', 'HP445G8-0033'),
(79, 4, 'Acer TravelMate P6', 'ACP6-0034'),
(80, 4, 'Asus ExpertBook B5', 'ASB5-0035'),
(81, 4, 'Dell Latitude 5530', 'DL5530-0036'),
(82, 4, 'Lenovo ThinkPad E15', 'LNE15-0037'),
(83, 4, 'HP EliteBook 850 G7', 'HP850G7-0038'),
(84, 4, 'Acer Aspire 7', 'ACA7-0039'),
(85, 4, 'Asus VivoBook 16', 'ASV16-0040'),
(86, 4, 'Dell Vostro 3420', 'DV3420-0041'),
(87, 4, 'Lenovo IdeaPad Slim 3', 'LNIPS3-0042'),
(88, 4, 'HP 255 G8', 'HP255G8-0043'),
(89, 4, 'Acer Extensa 14', 'ACEX14-0044'),
(90, 4, 'Asus Laptop X415', 'ASX415-0045'),
(91, 4, 'Dell Inspiron 3520', 'DI3520-0046'),
(92, 4, 'Lenovo ThinkBook 14', 'LNTB14-0047'),
(93, 4, 'HP Pavilion 14', 'HPP14-0048'),
(94, 4, 'Acer Swift 5', 'ACSW5-0049'),
(95, 4, 'Asus ZenBook 15', 'ASZ15-0050'),
(96, 4, 'Dell Latitude 5520', 'DL5520-0051'),
(97, 4, 'Lenovo ThinkPad L15', 'LNL15-0052'),
(98, 4, 'HP ProBook 455 G8', 'HP455G8-0053'),
(99, 4, 'Acer TravelMate X3', 'ACX3-0054'),
(100, 4, 'Asus ExpertBook L1', 'ASL1-0055'),
(101, 4, 'Dell Vostro 3520', 'DV3520-0056'),
(102, 4, 'Lenovo IdeaPad Flex 5', 'LNIF5-0057'),
(103, 4, 'HP 240 G8', 'HP240G8-0058'),
(104, 4, 'Acer Aspire 1', 'ACA1-0059'),
(105, 4, 'Asus VivoBook Go 15', 'ASVG15-0060'),
(106, 5, 'Dell Latitude 5430', 'DL5430-0031'),
(107, 5, 'Lenovo ThinkPad T15', 'LNT15-0032'),
(108, 5, 'HP ProBook 445 G8', 'HP445G8-0033'),
(109, 5, 'Acer TravelMate P6', 'ACP6-0034'),
(110, 5, 'Asus ExpertBook B5', 'ASB5-0035'),
(111, 5, 'Dell Latitude 5530', 'DL5530-0036'),
(112, 5, 'Lenovo ThinkPad E15', 'LNE15-0037'),
(113, 5, 'HP EliteBook 850 G7', 'HP850G7-0038'),
(114, 5, 'Acer Aspire 7', 'ACA7-0039'),
(115, 5, 'Asus VivoBook 16', 'ASV16-0040'),
(116, 5, 'Dell Vostro 3420', 'DV3420-0041'),
(117, 5, 'Lenovo IdeaPad Slim 3', 'LNIPS3-0042'),
(118, 5, 'HP 255 G8', 'HP255G8-0043'),
(119, 5, 'Acer Extensa 14', 'ACEX14-0044'),
(120, 5, 'Asus Laptop X415', 'ASX415-0045'),
(121, 5, 'Dell Inspiron 3520', 'DI3520-0046'),
(122, 5, 'Lenovo ThinkBook 14', 'LNTB14-0047'),
(123, 5, 'HP Pavilion 14', 'HPP14-0048'),
(124, 5, 'Acer Swift 5', 'ACSW5-0049'),
(125, 5, 'Asus ZenBook 15', 'ASZ15-0050'),
(126, 5, 'Dell Latitude 5520', 'DL5520-0051'),
(127, 5, 'Lenovo ThinkPad L15', 'LNL15-0052'),
(128, 5, 'HP ProBook 455 G8', 'HP455G8-0053'),
(129, 5, 'Acer TravelMate X3', 'ACX3-0054'),
(130, 5, 'Asus ExpertBook L1', 'ASL1-0055'),
(131, 5, 'Dell Vostro 3520', 'DV3520-0056'),
(132, 5, 'Lenovo IdeaPad Flex 5', 'LNIF5-0057'),
(133, 5, 'HP 240 G8', 'HP240G8-0058'),
(134, 5, 'Acer Aspire 1', 'ACA1-0059'),
(135, 5, 'Asus VivoBook Go 15', 'ASVG15-0060'),
(136, 6, 'Dell Latitude 5440', 'DL5440-0061'),
(137, 6, 'Lenovo ThinkPad X1 Carbon', 'LNX1C-0062'),
(138, 6, 'HP EliteBook 845 G8', 'HP845G8-0063'),
(139, 6, 'Acer TravelMate Spin P4', 'ACSP4-0064'),
(140, 6, 'Asus ExpertBook B9', 'ASB9-0065'),
(141, 6, 'Dell Latitude 7320', 'DL7320-0066'),
(142, 6, 'Lenovo ThinkPad X13', 'LNX13-0067'),
(143, 6, 'HP ProBook 635 Aero G8', 'HP635G8-0068'),
(144, 6, 'Acer Swift X', 'ACSWX-0069'),
(145, 6, 'Asus ZenBook S13', 'ASZS13-0070'),
(146, 6, 'Dell Inspiron 14 5425', 'DI145425-0071'),
(147, 6, 'Lenovo Yoga Slim 7', 'LNY7-0072'),
(148, 6, 'HP Pavilion Aero 13', 'HPPA13-0073'),
(149, 6, 'Acer Aspire Vero', 'ACAV-0074'),
(150, 6, 'Asus VivoBook S14-1', 'ASVS14-0076'),
(151, 7, 'Dell Latitude 5440', 'DL5440-0061'),
(152, 7, 'Lenovo ThinkPad X1 Carbon', 'LNX1C-0062'),
(153, 7, 'HP EliteBook 845 G8', 'HP845G8-0063'),
(154, 7, 'Acer TravelMate Spin P4', 'ACSP4-0064'),
(155, 7, 'Asus ExpertBook B9', 'ASB9-0065'),
(156, 7, 'Dell Latitude 7320', 'DL7320-0066'),
(157, 7, 'Lenovo ThinkPad X13', 'LNX13-0067'),
(158, 7, 'HP ProBook 635 Aero G8', 'HP635G8-0068'),
(159, 7, 'Acer Swift X', 'ACSWX-0069'),
(160, 7, 'Asus ZenBook S13', 'ASZS13-0070'),
(161, 7, 'Dell Inspiron 14 5425', 'DI145425-0071'),
(162, 7, 'Lenovo Yoga Slim 7', 'LNY7-0072'),
(163, 7, 'HP Pavilion Aero 13', 'HPPA13-0073'),
(164, 7, 'Acer Aspire Vero', 'ACAV-0074'),
(165, 7, 'Asus VivoBook S14-1', 'ASVS14-0076'),
(166, 8, 'Dell Latitude 5440', 'DL5440-0061'),
(167, 8, 'Lenovo ThinkPad X1 Carbon', 'LNX1C-0062'),
(168, 8, 'HP EliteBook 845 G8', 'HP845G8-0063'),
(169, 8, 'Acer TravelMate Spin P4', 'ACSP4-0064'),
(170, 8, 'Asus ExpertBook B9', 'ASB9-0065'),
(171, 8, 'Dell Latitude 7320', 'DL7320-0066'),
(172, 8, 'Lenovo ThinkPad X13', 'LNX13-0067'),
(173, 8, 'HP ProBook 635 Aero G8', 'HP635G8-0068'),
(174, 8, 'Acer Swift X', 'ACSWX-0069'),
(175, 8, 'Asus ZenBook S13', 'ASZS13-0070'),
(176, 8, 'Dell Inspiron 14 5425', 'DI145425-0071'),
(177, 8, 'Lenovo Yoga Slim 7', 'LNY7-0072'),
(178, 8, 'HP Pavilion Aero 13', 'HPPA13-0073'),
(179, 8, 'Acer Aspire Vero', 'ACAV-0074'),
(180, 8, 'Asus VivoBook S14-1', 'ASVS14-0076'),
(196, 9, 'Dell Latitude 5420', 'DL5420-0001'),
(197, 9, 'Lenovo ThinkPad T14', 'LNT14-0002'),
(198, 9, 'HP ProBook 440 G8', 'HP440G8-0003'),
(199, 9, 'Acer TravelMate P2', 'ACP2-0004'),
(200, 9, 'Asus ExpertBook B1', 'ASB1-0005'),
(201, 9, 'Dell Latitude 3520', 'DL3520-0006'),
(202, 9, 'Lenovo ThinkPad E14', 'LNE14-0007'),
(203, 9, 'HP EliteBook 840 G7', 'HP840G7-0008'),
(204, 9, 'Acer Aspire 5', 'ACA5-0009'),
(205, 9, 'Asus VivoBook 15', 'ASV15-0010'),
(206, 9, 'Dell Vostro 3400', 'DV3400-0011'),
(207, 9, 'Lenovo IdeaPad 3', 'LNIP3-0012'),
(208, 9, 'HP 250 G8', 'HP250G8-0013'),
(209, 9, 'Acer Extensa 15', 'ACEX15-0014'),
(210, 9, 'Asus Laptop X515', 'ASX515-0015'),
(211, 9, 'Dell Inspiron 3511', 'DI3511-0016'),
(212, 9, 'Lenovo ThinkBook 15', 'LNTB15-0017'),
(213, 9, 'HP Pavilion 15', 'HPP15-0018'),
(214, 9, 'Acer Swift 3', 'ACSW3-0019'),
(215, 9, 'Asus ZenBook 14', 'ASZ14-0020'),
(216, 9, 'Dell Latitude 5410', 'DL5410-0021'),
(217, 9, 'Lenovo ThinkPad L14', 'LNL14-0022'),
(218, 9, 'HP ProBook 450 G7', 'HP450G7-0023'),
(219, 9, 'Acer TravelMate P4', 'ACP4-0024'),
(220, 9, 'Asus ExpertBook P1', 'ASP1-0025'),
(221, 9, 'Dell Vostro 3510', 'DV3510-0026'),
(222, 9, 'Lenovo IdeaPad 5', 'LNIP5-0027'),
(223, 9, 'HP 245 G8', 'HP245G8-0028'),
(224, 9, 'Acer Aspire 3', 'ACA3-0029'),
(225, 9, 'Asus VivoBook 14', 'ASV14-0030'),
(226, 10, 'Dell Latitude 5440', 'DL5440-0061'),
(227, 10, 'Lenovo ThinkPad X1 Carbon', 'LNX1C-0062'),
(228, 10, 'HP EliteBook 845 G8', 'HP845G8-0063'),
(229, 10, 'Acer TravelMate Spin P4', 'ACSP4-0064'),
(230, 10, 'Asus ExpertBook B9', 'ASB9-0065'),
(231, 10, 'Dell Latitude 7320', 'DL7320-0066'),
(232, 10, 'Lenovo ThinkPad X13', 'LNX13-0067'),
(233, 10, 'HP ProBook 635 Aero G8', 'HP635G8-0068'),
(234, 10, 'Acer Swift X', 'ACSWX-0069'),
(235, 10, 'Asus ZenBook S13', 'ASZS13-0070'),
(236, 10, 'Dell Inspiron 14 5425', 'DI145425-0071'),
(237, 10, 'Lenovo Yoga Slim 7', 'LNY7-0072'),
(238, 10, 'HP Pavilion Aero 13', 'HPPA13-0073'),
(239, 10, 'Acer Aspire Vero', 'ACAV-0074'),
(240, 10, 'Asus VivoBook S14-1', 'ASVS14-0076');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_reservations`
--

CREATE TABLE `cart_reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `comentario` text DEFAULT NULL,
  `estado` enum('reservado','entregado','devuelto') NOT NULL DEFAULT 'reservado',
  `entregado_at` datetime DEFAULT NULL,
  `devuelto_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cart_reservations`
--

INSERT INTO `cart_reservations` (`id`, `user_id`, `cart_id`, `fecha`, `comentario`, `estado`, `entregado_at`, `devuelto_at`, `created_at`) VALUES
(1, 4, 1, '2026-11-02', 'Necesito que esten cargadas y con XAMPP y VSCode Instalados.', 'reservado', NULL, NULL, '2026-11-01 22:22:10'),
(2, 4, 1, '2026-11-12', 'Necesito que tengan Android Studio instalado.', 'reservado', NULL, NULL, '2026-11-01 22:24:15'),
(3, 4, 3, '2026-11-23', 'Necesito que tengan AUTOCAD instalado.', 'reservado', NULL, NULL, '2026-11-01 22:25:57'),
(4, 3, 2, '2026-11-02', 'Necesito que tenga Google Chrome instalado.', 'reservado', NULL, NULL, '2026-11-01 22:35:54'),
(5, 3, 2, '2026-11-12', 'Necesito que tenga node JS y VSCode instalados.', 'reservado', NULL, NULL, '2026-11-01 22:37:22'),
(6, 3, 3, '2026-11-24', 'Necesito que tenga mysql workbench.', 'reservado', NULL, NULL, '2026-11-01 22:38:27'),
(7, 2, 3, '2026-11-02', 'Necesito que tengan Firefox instalado.', 'reservado', NULL, NULL, '2026-11-01 22:50:31'),
(8, 2, 3, '2026-11-12', 'Necesito que tengan Python instalado.', 'reservado', NULL, NULL, '2026-11-01 22:59:07'),
(9, 2, 1, '2026-11-25', 'Necesito que tengan notepad++ instalado.', 'reservado', NULL, NULL, '2026-11-01 23:00:38'),
(10, 2, 3, '2026-11-01', '---', 'devuelto', '2026-11-01 20:13:06', '2026-11-01 20:14:20', '2026-11-01 23:11:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `classrooms`
--

CREATE TABLE `classrooms` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `classrooms`
--

INSERT INTO `classrooms` (`id`, `nombre`, `capacidad`, `created_at`) VALUES
(1, 'Aula 1 - Planta Baja', 30, '2026-11-01 21:36:21'),
(2, 'Aula 2 - Planta Baja', 45, '2026-11-01 21:36:51'),
(3, 'Auditorio - Planta Baja', 200, '2026-11-01 21:37:11'),
(4, 'Aula 3 - Primer Piso', 30, '2026-11-01 21:37:33'),
(5, 'Aula 4 - Primer Piso', 50, '2026-11-01 21:37:51'),
(6, 'Aula 5 - Primer Piso', 30, '2026-11-01 21:38:10'),
(7, 'Aula 6 - Segundo Piso', 30, '2026-11-01 21:39:59'),
(8, 'Aula 7 - Segundo Piso', 40, '2026-11-01 21:40:19'),
(9, 'Aula 8 - Segundo Piso', 50, '2026-11-01 21:41:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `classroom_schedules`
--

CREATE TABLE `classroom_schedules` (
  `id` int(11) NOT NULL,
  `classroom_id` int(11) NOT NULL,
  `dia_desde` tinyint(4) NOT NULL,
  `dia_hasta` tinyint(4) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `classroom_schedules`
--

INSERT INTO `classroom_schedules` (`id`, `classroom_id`, `dia_desde`, `dia_hasta`, `hora_inicio`, `hora_fin`, `created_at`) VALUES
(1, 1, 1, 5, '18:00:00', '22:00:00', '2026-11-01 21:42:13'),
(2, 2, 1, 6, '18:00:00', '22:00:00', '2026-11-01 21:42:38'),
(3, 3, 1, 7, '17:00:00', '23:00:00', '2026-11-01 21:43:29'),
(4, 5, 1, 5, '19:00:00', '22:00:00', '2026-11-01 21:47:05'),
(5, 8, 1, 5, '19:00:00', '21:00:00', '2026-11-01 21:47:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `computers`
--

CREATE TABLE `computers` (
  `id` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `numero_serie` varchar(120) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `computers`
--

INSERT INTO `computers` (`id`, `nombre`, `numero_serie`, `cart_id`, `created_at`) VALUES
(1, 'Dell Latitude 5420', 'DL5420-0001', 1, '2026-11-01 21:53:39'),
(2, 'Lenovo ThinkPad T14', 'LNT14-0002', 1, '2026-11-01 21:53:39'),
(3, 'HP ProBook 440 G8', 'HP440G8-0003', 1, '2026-11-01 21:53:39'),
(4, 'Acer TravelMate P2', 'ACP2-0004', 1, '2026-11-01 21:53:39'),
(5, 'Asus ExpertBook B1', 'ASB1-0005', 1, '2026-11-01 21:53:39'),
(6, 'Dell Latitude 3520', 'DL3520-0006', 1, '2026-11-01 21:53:39'),
(7, 'Lenovo ThinkPad E14', 'LNE14-0007', 1, '2026-11-01 21:53:39'),
(8, 'HP EliteBook 840 G7', 'HP840G7-0008', 1, '2026-11-01 21:53:39'),
(9, 'Acer Aspire 5', 'ACA5-0009', 1, '2026-11-01 21:53:39'),
(10, 'Asus VivoBook 15', 'ASV15-0010', 1, '2026-11-01 21:53:39'),
(11, 'Dell Vostro 3400', 'DV3400-0011', 1, '2026-11-01 21:53:39'),
(12, 'Lenovo IdeaPad 3', 'LNIP3-0012', 1, '2026-11-01 21:53:39'),
(13, 'HP 250 G8', 'HP250G8-0013', 1, '2026-11-01 21:53:39'),
(14, 'Acer Extensa 15', 'ACEX15-0014', 1, '2026-11-01 21:53:39'),
(15, 'Asus Laptop X515', 'ASX515-0015', 1, '2026-11-01 21:53:39'),
(16, 'Dell Inspiron 3511', 'DI3511-0016', 1, '2026-11-01 21:53:39'),
(17, 'Lenovo ThinkBook 15', 'LNTB15-0017', 1, '2026-11-01 21:53:39'),
(18, 'HP Pavilion 15', 'HPP15-0018', 1, '2026-11-01 21:53:39'),
(19, 'Acer Swift 3', 'ACSW3-0019', 1, '2026-11-01 21:53:39'),
(20, 'Asus ZenBook 14', 'ASZ14-0020', 1, '2026-11-01 21:53:39'),
(21, 'Dell Latitude 5410', 'DL5410-0021', 1, '2026-11-01 21:53:39'),
(22, 'Lenovo ThinkPad L14', 'LNL14-0022', 1, '2026-11-01 21:53:39'),
(23, 'HP ProBook 450 G7', 'HP450G7-0023', 1, '2026-11-01 21:53:39'),
(24, 'Acer TravelMate P4', 'ACP4-0024', 1, '2026-11-01 21:53:39'),
(25, 'Asus ExpertBook P1', 'ASP1-0025', 1, '2026-11-01 21:53:39'),
(26, 'Dell Vostro 3510', 'DV3510-0026', 1, '2026-11-01 21:53:39'),
(27, 'Lenovo IdeaPad 5', 'LNIP5-0027', 1, '2026-11-01 21:53:39'),
(28, 'HP 245 G8', 'HP245G8-0028', 1, '2026-11-01 21:53:39'),
(29, 'Acer Aspire 3', 'ACA3-0029', 1, '2026-11-01 21:53:39'),
(30, 'Asus VivoBook 14', 'ASV14-0030', 1, '2026-11-01 21:53:39'),
(31, 'Dell Latitude 5430', 'DL5430-0031', 2, '2026-11-01 21:53:39'),
(32, 'Lenovo ThinkPad T15', 'LNT15-0032', 2, '2026-11-01 21:53:39'),
(33, 'HP ProBook 445 G8', 'HP445G8-0033', 2, '2026-11-01 21:53:39'),
(34, 'Acer TravelMate P6', 'ACP6-0034', 2, '2026-11-01 21:53:39'),
(35, 'Asus ExpertBook B5', 'ASB5-0035', 2, '2026-11-01 21:53:39'),
(36, 'Dell Latitude 5530', 'DL5530-0036', 2, '2026-11-01 21:53:39'),
(37, 'Lenovo ThinkPad E15', 'LNE15-0037', 2, '2026-11-01 21:53:39'),
(38, 'HP EliteBook 850 G7', 'HP850G7-0038', 2, '2026-11-01 21:53:39'),
(39, 'Acer Aspire 7', 'ACA7-0039', 2, '2026-11-01 21:53:39'),
(40, 'Asus VivoBook 16', 'ASV16-0040', 2, '2026-11-01 21:53:39'),
(41, 'Dell Vostro 3420', 'DV3420-0041', 2, '2026-11-01 21:53:39'),
(42, 'Lenovo IdeaPad Slim 3', 'LNIPS3-0042', 2, '2026-11-01 21:53:39'),
(43, 'HP 255 G8', 'HP255G8-0043', 2, '2026-11-01 21:53:39'),
(44, 'Acer Extensa 14', 'ACEX14-0044', 2, '2026-11-01 21:53:39'),
(45, 'Asus Laptop X415', 'ASX415-0045', 2, '2026-11-01 21:53:39'),
(46, 'Dell Inspiron 3520', 'DI3520-0046', 2, '2026-11-01 21:53:39'),
(47, 'Lenovo ThinkBook 14', 'LNTB14-0047', 2, '2026-11-01 21:53:39'),
(48, 'HP Pavilion 14', 'HPP14-0048', 2, '2026-11-01 21:53:39'),
(49, 'Acer Swift 5', 'ACSW5-0049', 2, '2026-11-01 21:53:39'),
(50, 'Asus ZenBook 15', 'ASZ15-0050', 2, '2026-11-01 21:53:39'),
(51, 'Dell Latitude 5520', 'DL5520-0051', 2, '2026-11-01 21:53:39'),
(52, 'Lenovo ThinkPad L15', 'LNL15-0052', 2, '2026-11-01 21:53:39'),
(53, 'HP ProBook 455 G8', 'HP455G8-0053', 2, '2026-11-01 21:53:39'),
(54, 'Acer TravelMate X3', 'ACX3-0054', 2, '2026-11-01 21:53:39'),
(55, 'Asus ExpertBook L1', 'ASL1-0055', 2, '2026-11-01 21:53:39'),
(56, 'Dell Vostro 3520', 'DV3520-0056', 2, '2026-11-01 21:53:39'),
(57, 'Lenovo IdeaPad Flex 5', 'LNIF5-0057', 2, '2026-11-01 21:53:39'),
(58, 'HP 240 G8', 'HP240G8-0058', 2, '2026-11-01 21:53:39'),
(59, 'Acer Aspire 1', 'ACA1-0059', 2, '2026-11-01 21:53:39'),
(60, 'Asus VivoBook Go 15', 'ASVG15-0060', 2, '2026-11-01 21:53:39'),
(61, 'Dell Latitude 5440', 'DL5440-0061', 3, '2026-11-01 21:53:39'),
(62, 'Lenovo ThinkPad X1 Carbon', 'LNX1C-0062', 3, '2026-11-01 21:53:39'),
(63, 'HP EliteBook 845 G8', 'HP845G8-0063', 3, '2026-11-01 21:53:39'),
(64, 'Acer TravelMate Spin P4', 'ACSP4-0064', 3, '2026-11-01 21:53:39'),
(65, 'Asus ExpertBook B9', 'ASB9-0065', 3, '2026-11-01 21:53:39'),
(66, 'Dell Latitude 7320', 'DL7320-0066', 3, '2026-11-01 21:53:39'),
(67, 'Lenovo ThinkPad X13', 'LNX13-0067', 3, '2026-11-01 21:53:39'),
(68, 'HP ProBook 635 Aero G8', 'HP635G8-0068', 3, '2026-11-01 21:53:39'),
(69, 'Acer Swift X', 'ACSWX-0069', 3, '2026-11-01 21:53:39'),
(70, 'Asus ZenBook S13', 'ASZS13-0070', 3, '2026-11-01 21:53:39'),
(71, 'Dell Inspiron 14 5425', 'DI145425-0071', 3, '2026-11-01 21:53:39'),
(72, 'Lenovo Yoga Slim 7', 'LNY7-0072', 3, '2026-11-01 21:53:39'),
(73, 'HP Pavilion Aero 13', 'HPPA13-0073', 3, '2026-11-01 21:53:39'),
(74, 'Acer Aspire Vero', 'ACAV-0074', 3, '2026-11-01 21:54:33'),
(75, 'Asus VivoBook S14-1', 'ASVS14-0076', 3, '2026-11-01 21:55:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `classroom_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `classroom_id`, `fecha`, `hora_inicio`, `hora_fin`, `created_at`) VALUES
(1, 4, 2, '2026-11-02', '18:00:00', '20:00:00', '2026-11-01 22:19:55'),
(2, 4, 5, '2026-11-12', '20:00:00', '22:00:00', '2026-11-01 22:20:28'),
(3, 4, 8, '2026-11-23', '19:00:00', '21:00:00', '2026-11-01 22:21:07'),
(4, 3, 2, '2026-11-02', '20:00:00', '22:00:00', '2026-11-01 22:32:19'),
(5, 3, 5, '2026-11-12', '19:00:00', '20:00:00', '2026-11-01 22:33:11'),
(6, 3, 8, '2026-11-24', '19:00:00', '21:00:00', '2026-11-01 22:33:44'),
(7, 2, 5, '2026-11-02', '19:00:00', '22:00:00', '2026-11-01 22:43:49'),
(8, 2, 1, '2026-11-12', '18:00:00', '22:00:00', '2026-11-01 22:45:02'),
(9, 2, 1, '2026-11-25', '18:00:00', '19:00:00', '2026-11-01 22:46:37'),
(10, 2, 3, '2026-11-01', '21:00:00', '22:00:00', '2026-11-01 23:05:34'),
(11, 2, 2, '2026-10-30', '18:00:00', '20:00:00', '2026-11-01 23:21:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `email`, `password`, `rol`, `created_at`) VALUES
(1, 'Administracion', 'sirauca.reservas@gmail.com', '$2y$10$JVbJewM6jLKsXePAoHVWyui13p/3NfotCATOSEu9NYTHj31Va5lxq', 'admin', '2026-11-01 21:33:49'),
(2, 'Jonatan Palavecino', 'jnpr1992@gmail.com', '$2y$10$WKeOJMS.E2kFu8vsTlHLv.HiapnenLj8nFsEgFCVzzjb3/s2ffZPe', 'user', '2026-11-01 22:02:44'),
(3, 'Damian Luna', 'damianluna188@gmail.com', '$2y$10$cKAdRFnlZ/ok0j63X9bm2OVJWbl3gAqZGDbPbjYG9ge3PueFx4iUO', 'user', '2026-11-01 22:04:45'),
(4, 'Daniel Lopez', 'isaza8987@gmail.com', '$2y$10$kBF84fXdS.zOj94REvXJY.U9I5Qby1jjVb5f5xCppRR1cATlge7be', 'user', '2026-11-01 22:06:20');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cart_delivery_items`
--
ALTER TABLE `cart_delivery_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_delivery_item_reservation` (`cart_reservation_id`);

--
-- Indices de la tabla `cart_reservations`
--
ALTER TABLE `cart_reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_day` (`cart_id`,`fecha`),
  ADD KEY `fk_cart_reservation_user` (`user_id`);

--
-- Indices de la tabla `classrooms`
--
ALTER TABLE `classrooms`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `classroom_schedules`
--
ALTER TABLE `classroom_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `classroom_id` (`classroom_id`);

--
-- Indices de la tabla `computers`
--
ALTER TABLE `computers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_serie` (`numero_serie`),
  ADD KEY `fk_computer_cart` (`cart_id`);

--
-- Indices de la tabla `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reservation_user` (`user_id`),
  ADD KEY `fk_reservation_classroom` (`classroom_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `cart_delivery_items`
--
ALTER TABLE `cart_delivery_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT de la tabla `cart_reservations`
--
ALTER TABLE `cart_reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `classrooms`
--
ALTER TABLE `classrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `classroom_schedules`
--
ALTER TABLE `classroom_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `computers`
--
ALTER TABLE `computers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de la tabla `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cart_delivery_items`
--
ALTER TABLE `cart_delivery_items`
  ADD CONSTRAINT `fk_delivery_item_reservation` FOREIGN KEY (`cart_reservation_id`) REFERENCES `cart_reservations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cart_reservations`
--
ALTER TABLE `cart_reservations`
  ADD CONSTRAINT `fk_cart_reservation_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`),
  ADD CONSTRAINT `fk_cart_reservation_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `classroom_schedules`
--
ALTER TABLE `classroom_schedules`
  ADD CONSTRAINT `fk_schedule_classroom` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `computers`
--
ALTER TABLE `computers`
  ADD CONSTRAINT `fk_computer_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservation_classroom` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reservation_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
