-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-04-2025 a las 03:32:04
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
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `usuario` varchar(50) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id`, `accion`, `descripcion`, `usuario`, `fecha`) VALUES
(1, 'Login', 'Usuario logeado', 'admin', '2025-04-22 22:52:48'),
(2, 'Login', 'Usuario logeado', 'admin', '2025-04-22 22:52:57'),
(3, 'Login', 'Usuario logeado', 'jose', '2025-04-22 23:29:54'),
(4, 'Login', 'Usuario logeado', 'admin', '2025-04-22 23:35:50'),
(5, 'create usuario', 'se creo un usuario pedro', 'desconocido', '2025-04-22 23:48:05'),
(6, 'create usuario', 'se creo un usuario juan', 'desconocido', '2025-04-22 23:48:32'),
(7, 'Login', 'Usuario logeado', 'admin', '2025-04-23 00:07:53'),
(8, 'Update guia: Array', 'guia editada.', 'admin', '2025-04-23 00:08:07'),
(9, 'Update guia: Array', 'guia editada.', 'admin', '2025-04-23 00:11:30'),
(10, 'Update guia: ', 'guia editada.', 'admin', '2025-04-23 00:14:23'),
(11, 'Update guia: 138', 'guia editada.', 'admin', '2025-04-23 00:16:20'),
(12, 'Eliminar guia: 138', 'Guia eliminada correctamente', 'admin', '2025-04-23 00:16:35'),
(13, 'controlar paquete', 'paquete controldo', 'admin', '2025-04-23 00:16:56'),
(14, 'Controlar guia: 143', 'paquete controldo', 'admin', '2025-04-23 00:19:16'),
(15, 'Controlar guia: 143', 'paquete controldo', 'admin', '2025-04-23 00:19:27'),
(16, 'delete usuario', 'se elimini el usuario_id 6', 'admin', '2025-04-23 00:19:56');

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
(60, '2025-04-16', '100', '135', 'verde', 0),
(64, '2025-04-22', '100', '139', 'verde', 1),
(66, '2025-04-22', '100', '141', 'verde', 1),
(67, '2025-04-22', '100', '142', 'amarillo', 0),
(68, '2025-04-22', '100', '143', 'rojo', 1),
(69, '2025-04-22', '100', '144', 'verde', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(55) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(20) DEFAULT 'usuario',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `rol`, `fecha_registro`) VALUES
(2, 'cristian', 'admin', '$2y$10$bt1nUDVDdGBzb.mkBZEeGuZRm3nAvqfv3xXUnsDA31E4mg/axOT2K', 'admin', '2025-04-22 15:54:58'),
(3, 'jose', 'jose', '$2y$10$9cUuPJbW9XssvsaP4iI7tu0fgBdA4SYA84RLR.dfAnj6ReYsZGvAm', 'usuario', '2025-04-22 16:06:38'),
(4, 'pedro', 'pedro', '$2y$10$VYrxSSmpKUFIEyv08tMKJeOx5VTfK/4Dy.KoIIel/FaVvD/Mh7gmW', 'usuario', '2025-04-22 23:42:43');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario` (`usuario`),
  ADD KEY `idx_accion` (`accion`);

--
-- Indices de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
