-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-04-2025 a las 03:59:04
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
-- Base de datos: `bbdd_control_ezeiza`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs_control`
--

CREATE TABLE `logs_control` (
  `id` int(11) NOT NULL,
  `nro_guia` varchar(50) NOT NULL,
  `canal` varchar(20) NOT NULL,
  `accion` varchar(20) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquetes`
--

CREATE TABLE `paquetes` (
  `id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `guiasmad` varchar(255) DEFAULT NULL,
  `nro_guia` varchar(255) DEFAULT NULL,
  `canal` varchar(255) DEFAULT NULL,
  `controlado` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `paquetes`
--

INSERT INTO `paquetes` (`id`, `fecha`, `guiasmad`, `nro_guia`, `canal`, `controlado`) VALUES
(1, '2024-07-28', 'P6097115200', 'MLAR000000031EX', 'rojo', 1),
(2, '2024-07-29', 'P6097115201', 'MLAR000000032EX', 'verde', 0),
(3, '2024-07-30', 'P6097115202', 'MLAR000000033EX', 'rojo', 0),
(4, '2024-07-31', 'P6097115203', 'MLAR000000034EX', 'verde', 1),
(5, '2024-07-28', 'P6097115200', 'MLAR000000031EX', 'rojo', 0),
(6, '2024-07-29', 'P6097115201', 'MLAR000000032EX', 'verde', 0),
(7, '2024-07-30', 'P6097115202', 'MLAR000000033EX', 'rojo', 0),
(8, '2024-07-31', 'P6097115203', 'MLAR000000034EX', 'verde', 0),
(9, '2024-07-28', 'P6097115200', 'MLAR000000031EX', 'rojo', 0),
(10, '2024-07-29', 'P6097115201', 'MLAR000000032EX', 'verde', 0),
(11, '2024-07-30', 'P6097115202', 'MLAR000000033EX', 'rojo', 0),
(12, '2024-07-31', 'P6097115203', 'MLAR000000034EX', 'verde', 0),
(13, '2024-07-28', 'P6097115200', 'MLAR000000031EX', 'rojo', 0),
(14, '2024-07-29', 'P6097115201', 'MLAR000000032EX', 'verde', 0),
(15, '2024-07-30', 'P6097115202', 'MLAR000000033EX', 'rojo', 0),
(16, '2024-07-31', 'P6097115203', 'MLAR000000034EX', 'verde', 0),
(17, '2024-07-28', 'P6097115200', 'MLAR000000031EX', 'rojo', 0),
(18, '2024-07-29', 'P6097115201', 'MLAR000000032EX', 'verde', 0),
(19, '2024-07-30', 'P6097115202', 'MLAR000000033EX', 'rojo', 0),
(20, '2024-07-31', 'P6097115203', 'MLAR000000034EX', 'verde', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `logs_control`
--
ALTER TABLE `logs_control`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `logs_control`
--
ALTER TABLE `logs_control`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
