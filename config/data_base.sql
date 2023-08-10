-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-08-2023 a las 05:29:16
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistransporte`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductor`
--

CREATE TABLE `conductor` (
  `id_conductor` int(11) NOT NULL,
  `dni` varchar(15) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) NOT NULL,
  `celular` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `conductor`
--

INSERT INTO `conductor` (`id_conductor`, `dni`, `nombre`, `apellido_paterno`, `apellido_materno`, `celular`) VALUES
(1, '79653276', 'Vito', 'Andagua', 'Julca', '997 744 609'),
(2, '77343488', 'Maricielo', 'León', 'Jiménez', '900 773 374'),
(3, '78933434', 'Norma', 'Ferrer', 'Rivera', '974 232 323');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fecha_registro`
--

CREATE TABLE `fecha_registro` (
  `id_registro` int(11) NOT NULL,
  `id_conductor` int(11) NOT NULL,
  `fecha_registro` date NOT NULL,
  `fecha_caducidad` date DEFAULT NULL,
  `fecha_certificado_qr` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `fecha_registro`
--

INSERT INTO `fecha_registro` (`id_registro`, `id_conductor`, `fecha_registro`, `fecha_caducidad`, `fecha_certificado_qr`) VALUES
(1, 1, '2023-07-25', '2025-07-25', '2023-08-09 22:27:05'),
(2, 2, '2023-07-29', '0000-00-00', NULL),
(3, 3, '2023-07-30', '0000-00-00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo`
--

CREATE TABLE `vehiculo` (
  `id_vehiculo` int(11) NOT NULL,
  `id_conductor` int(11) NOT NULL,
  `nro_placa` varchar(10) NOT NULL,
  `tipo_vehiculo` varchar(50) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `vehiculo`
--

INSERT INTO `vehiculo` (`id_vehiculo`, `id_conductor`, `nro_placa`, `tipo_vehiculo`, `marca`, `modelo`) VALUES
(1, 1, 'A1A-760', 'Particular', 'AUDI', 'Q8'),
(2, 2, 'AJG-752', 'Mototaxi', 'YAMAHA', 'YZF-R15'),
(3, 3, 'D10-700', 'Mototaxi', 'RTM', 'FDF-015');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `conductor`
--
ALTER TABLE `conductor`
  ADD PRIMARY KEY (`id_conductor`);

--
-- Indices de la tabla `fecha_registro`
--
ALTER TABLE `fecha_registro`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `id_conductor` (`id_conductor`);

--
-- Indices de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD PRIMARY KEY (`id_vehiculo`),
  ADD KEY `id_conductor` (`id_conductor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `conductor`
--
ALTER TABLE `conductor`
  MODIFY `id_conductor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `fecha_registro`
--
ALTER TABLE `fecha_registro`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  MODIFY `id_vehiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `fecha_registro`
--
ALTER TABLE `fecha_registro`
  ADD CONSTRAINT `fecha_registro_ibfk_1` FOREIGN KEY (`id_conductor`) REFERENCES `conductor` (`id_conductor`);

--
-- Filtros para la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD CONSTRAINT `vehiculo_ibfk_1` FOREIGN KEY (`id_conductor`) REFERENCES `conductor` (`id_conductor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
