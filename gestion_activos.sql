-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-05-2025 a las 06:21:55
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


--
-- Base de datos: `gestion_activos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id_admin` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Volcado de datos para la tabla `administradores`


INSERT INTO `administradores` (`id_admin`, `nombre`, `usuario`, `clave`) VALUES

(1, 'Administrador Principal', 'admin1', '123')9G');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bajas`
--

CREATE TABLE `bajas` (
  `id` varchar(200) NOT NULL,
  `numero_activo` varchar(50) NOT NULL,
  `fecha_baja` date NOT NULL,
  `fecha_recepcion` date NOT NULL,
  `oficio_baja` varchar(100) DEFAULT NULL,
  `tipo_baja` varchar(50) DEFAULT NULL,
  `clase_activo` varchar(50) DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `serie` varchar(50) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `checklist_completado` enum('S','N') DEFAULT 'N',
  `ubicacion` varchar(100) DEFAULT NULL,
  `ultima_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Disparadores `bajas`

DELIMITER $$
CREATE TRIGGER `before_insert_bajas` BEFORE INSERT ON `bajas` FOR EACH ROW BEGIN
    DECLARE next_num INT;
    DECLARE next_code VARCHAR(50);
    -- Obtener el número siguiente, extrayendo los últimos 3 dígitos del ID actual
    SELECT COALESCE(
        MAX(CAST(SUBSTRING(id, -3) AS UNSIGNED)), 
        0
    ) + 1 INTO next_num
    FROM bajas
    WHERE id LIKE CONCAT('CBA-INF-', YEAR(CURDATE()), '%');
    -- Generar el nuevo código con formato
    SET next_code = CONCAT('CBA-INF-', YEAR(CURDATE()), '-', LPAD(next_num, 3, '0'));
    -- Asignarlo al nuevo registro
    SET NEW.id = next_code;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `destinatarios`
--

CREATE TABLE `destinatarios` (
  `id` int(11) NOT NULL,
  `correo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `destinatarios`
--

INSERT INTO `destinatarios` (`id`, `correo`) VALUES
(2, 'crayolahpc73@gmail.com'),
(3, 'miguelurea.u@gmail.com'),
(4, 'jelizondoc@castrocarazo.ac.cr');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslados`
--

CREATE TABLE `traslados` (
  `id` varchar(200) NOT NULL,
  `numero_activo` varchar(50) NOT NULL,
  `fecha_aprobacion` date NOT NULL,
  `fecha_traslado` date NOT NULL,
  `canal_comunicacion` varchar(100) DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `tipo_activo` varchar(50) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `serie` varchar(50) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `checklist_completado` enum('S','N') DEFAULT 'N',
  `ubicacion_anterior` varchar(100) DEFAULT NULL,
  `ubicacion_actual` varchar(100) DEFAULT NULL,
  `ultima_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DELIMITER $$
CREATE TRIGGER `before_insert_traslados` BEFORE INSERT ON `traslados` FOR EACH ROW BEGIN
    DECLARE next_num INT;
    DECLARE next_code VARCHAR(50);
    SELECT COALESCE(
        MAX(CAST(SUBSTRING(id, -3) AS UNSIGNED)), 
        0
    ) + 1 INTO next_num
    FROM traslados
    WHERE id LIKE CONCAT('CTA-INF-', YEAR(CURDATE()), '%');
    SET next_code = CONCAT('CTA-INF-', YEAR(CURDATE()), '-', LPAD(next_num, 3, '0'));
    SET NEW.id = next_code;
END
$$
DELIMITER ;

