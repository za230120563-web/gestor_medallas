-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-11-2026 a las 02:09:14
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
-- Base de datos: `gestor_medallas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `medalla_id` int(11) DEFAULT NULL,
  `fecha_limite` date DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `medalla_sugerida_ia` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `titulo`, `descripcion`, `medalla_id`, `fecha_limite`, `creado_por`, `medalla_sugerida_ia`) VALUES
(14, 'taller de investigación aplicada', 'proyecto de diseño curricular', 4, '2026-12-18', 1, 'Oro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evidencias`
--

CREATE TABLE `evidencias` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `actividad_id` int(11) DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `validacion_ia` varchar(50) DEFAULT 'Sin analizar',
  `confianza_ia` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evidencias`
--

INSERT INTO `evidencias` (`id`, `usuario_id`, `actividad_id`, `archivo`, `estado`, `comentario`, `fecha`, `validacion_ia`, `confianza_ia`) VALUES
(14, 3, 14, '1795134520_acuse evaluacion docente.pdf', 'Enviado', NULL, '2026-11-20 00:28:40', 'Pre-aprobado IA', 92.50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medallas`
--

CREATE TABLE `medallas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medallas`
--

INSERT INTO `medallas` (`id`, `nombre`, `descripcion`, `imagen`) VALUES
(2, 'Bronce', 'Participacion en actividades', 'bronce.png'),
(3, 'Plata', 'Compromiso academico', 'plata.png'),
(4, 'oro', 'Reconocimiento destacado', 'oro.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medallas_obtenidas`
--

CREATE TABLE `medallas_obtenidas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `medalla_id` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medallas_obtenidas`
--

INSERT INTO `medallas_obtenidas` (`id`, `usuario_id`, `medalla_id`, `fecha`) VALUES
(1, 1, 2, '2026-05-22 00:53:18'),
(2, 1, 2, '2026-05-22 23:56:05'),
(3, 1, 2, '2026-05-23 00:43:47'),
(4, 1, 2, '2026-05-23 00:43:49'),
(5, 1, 2, '2026-05-23 00:43:51'),
(6, 3, 3, '2026-05-27 01:31:27'),
(7, 3, 4, '2026-08-08 04:35:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'Admin'),
(2, 'Divicion'),
(3, 'Docente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `estado_clasificacion` varchar(50) DEFAULT 'Sin evaluar',
  `semanas_proxima_medalla` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `password`, `rol_id`, `estado_clasificacion`, `semanas_proxima_medalla`) VALUES
(1, 'Admin', 'Admin@gmail.com', '$2y$10$2jQlC1uo6lgzpMhzF128J.e28DYls3BsZU/xDqhGF0Wypn2JLcivO', 1, 'Sin evaluar', 0),
(2, 'Divicion de Sistemas', 'Sistemas@gmail.com', '$2y$10$CsoNZZ2hVEW/DQ6C76.rDOh6z/jIlZgnpJ5Ie.ul/huI1Lm91rssG', 2, 'Sin evaluar', 0),
(3, 'Docente', 'docente@gmail.com', '$2y$10$CsoNZZ2hVEW/DQ6C76.rDOh6z/jIlZgnpJ5Ie.ul/huI1Lm91rssG', 3, 'Sin evaluar', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medalla_id` (`medalla_id`);

--
-- Indices de la tabla `evidencias`
--
ALTER TABLE `evidencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `actividad_id` (`actividad_id`);

--
-- Indices de la tabla `medallas`
--
ALTER TABLE `medallas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `medallas_obtenidas`
--
ALTER TABLE `medallas_obtenidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `medalla_id` (`medalla_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `evidencias`
--
ALTER TABLE `evidencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `medallas`
--
ALTER TABLE `medallas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `medallas_obtenidas`
--
ALTER TABLE `medallas_obtenidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `actividades_ibfk_1` FOREIGN KEY (`medalla_id`) REFERENCES `medallas` (`id`);

--
-- Filtros para la tabla `evidencias`
--
ALTER TABLE `evidencias`
  ADD CONSTRAINT `evidencias_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `evidencias_ibfk_2` FOREIGN KEY (`actividad_id`) REFERENCES `actividades` (`id`);

--
-- Filtros para la tabla `medallas_obtenidas`
--
ALTER TABLE `medallas_obtenidas`
  ADD CONSTRAINT `medallas_obtenidas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `medallas_obtenidas_ibfk_2` FOREIGN KEY (`medalla_id`) REFERENCES `medallas` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
