-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-02-2026 a las 05:01:33
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `taxi_app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('cliente','conductor','admin') DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `placa` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `email`, `password`, `rol`, `creado_en`, `placa`) VALUES
(1, 'Administrador', 'admin@taxiapp.com', '$2y$10$8wWjUV5aopfPi9nsP1VrTOCv6BmDn.p6xnR6x7Y6cxJl6OXer/Rxm', 'admin', '2026-02-04 00:45:50', NULL),
(8, 'Christian', 'christian29@hotmail.com', '$2y$10$gmzmAr1I5j2wteq1SXdyr.TbxvRGQ//JVt/hT1qB0L/d5YAitQ6nO', 'conductor', '2026-02-04 02:05:09', 'pb2364'),
(9, 'Jose', 'jose@hotmail.com', '$2y$10$yFDukIuPSQYaqLKDgawz7uInvmxT.rC2R9wwZSIf.D2EsqPzQ99gG', 'cliente', '2026-02-04 02:11:20', NULL),
(10, 'laura', 'laura@hotmail.com', '$2y$10$l9JTqCZbjLnvM0BZX0JsZ./Iz1bkdEG2pPAsojSOdlul3nVTwhX76', 'conductor', '2026-02-04 02:11:49', 'pb2368'),
(11, 'soria', 'soria@hotmail.com', '$2y$10$uXXjtObhuMw1ZlMV6CsPfe8WN/AoqrDHWNi.RL3WXIN3CQ1K2.hOe', 'cliente', '2026-02-04 02:12:09', NULL);

--
-- Índices para tablas volcadas
--

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
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
