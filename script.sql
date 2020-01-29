-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-01-2020 a las 15:20:26
-- Versión del servidor: 10.1.36-MariaDB
-- Versión de PHP: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `educdb`
--
CREATE DATABASE IF NOT EXISTS `educdb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `educdb`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `id` int(11) NOT NULL,
  `id_curso_prerrequisito` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `curso`
--

INSERT INTO `curso` (`id`, `id_curso_prerrequisito`, `nombre`) VALUES
(1, 0, 'Introduccion a la programación'),
(2, 1, 'curso 2'),
(3, 2, 'curso 3'),
(4, 3, 'curso 4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursoestudiante`
--

CREATE TABLE `cursoestudiante` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `estado_curso` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cursoestudiante`
--

INSERT INTO `cursoestudiante` (`id`, `id_estudiante`, `id_curso`, `estado_curso`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 1),
(3, 1, 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`id`, `nombre`) VALUES
(1, 'German'),
(2, 'Alonso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluacion`
--

CREATE TABLE `evaluacion` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_pregunta` int(11) NOT NULL,
  `respuesta_evaluacion` varchar(100) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `evaluacion`
--

INSERT INTO `evaluacion` (`id`, `id_estudiante`, `id_pregunta`, `respuesta_evaluacion`, `estado`) VALUES
(1, 1, 1, '1', 1),
(2, 2, 1, '0', 0),
(3, 2, 2, '1', 1),
(4, 1, 2, '0', 1),
(5, 1, 3, 'A', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `leccion`
--

CREATE TABLE `leccion` (
  `id` int(11) NOT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `id_leccion_prerrequisito` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `puntaje_aprobacion` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `leccion`
--

INSERT INTO `leccion` (`id`, `id_curso`, `id_leccion_prerrequisito`, `nombre`, `puntaje_aprobacion`) VALUES
(1, 1, 0, 'Leccion 1-1', 60),
(2, 1, 1, 'Leccion 2-1', 60),
(3, 2, 0, 'Leccion 1-2', 60),
(5, 2, 3, 'Leccion editada 2-2', 65);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta`
--

CREATE TABLE `pregunta` (
  `id` int(11) NOT NULL,
  `id_leccion` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `pregunta` varchar(100) NOT NULL,
  `respuesta` varchar(100) NOT NULL,
  `puntaje` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pregunta`
--

INSERT INTO `pregunta` (`id`, `id_leccion`, `id_tipo`, `pregunta`, `respuesta`, `puntaje`) VALUES
(1, 1, 1, 'Un algoritmo es una sucesión de instrucciones detalladas para realizar determinada tarea', '1', 60),
(2, 1, 1, 'la sentencia if es un una sentencia cíclica', '0', 45),
(3, 2, 2, 'El operador mod es:\r\nA %\r\nB ^\r\nC /\r\nD *', 'A', 60),
(5, 1, 1, 'Bulean puede tomar 1 de 2 valores; Verdadero o Falso', '1', 60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pregunta`
--

CREATE TABLE `tipo_pregunta` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipo_pregunta`
--

INSERT INTO `tipo_pregunta` (`id`, `descripcion`) VALUES
(1, 'Booleano'),
(2, 'Opción múltiple donde solo una respuesta es correcta'),
(3, 'Opción múltiple donde más de una respuesta es correcta'),
(4, 'Opción múltiple donde más de una respuesta es correcta y todas deben responderse correctamente');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vwevalua`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vwevalua` (
`id_estudiante` int(11)
,`estudiante` varchar(50)
,`id_leccion` int(11)
,`respuestas_correctas` bigint(21)
,`t_puntaje` double
,`puntaje_aprobacion` double
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vwlistacurso`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vwlistacurso` (
`id_curso` int(11)
,`id_curso_prerrequisito` int(11)
,`id_estudiante` int(11)
,`estado_curso` tinyint(1)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vwpreguntasbyleccion`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vwpreguntasbyleccion` (
`id_curso` int(11)
,`id_leccion` int(11)
,`nombre` varchar(50)
,`id_pregunta` int(11)
,`pregunta` varchar(100)
,`tipo_pregunta` varchar(100)
,`puntaje` double
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vwevalua`
--
DROP TABLE IF EXISTS `vwevalua`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vwevalua`  AS  select `est`.`id` AS `id_estudiante`,`est`.`nombre` AS `estudiante`,`l`.`id` AS `id_leccion`,count(`p`.`id`) AS `respuestas_correctas`,sum(`p`.`puntaje`) AS `t_puntaje`,`l`.`puntaje_aprobacion` AS `puntaje_aprobacion` from ((((`evaluacion` `eva` join `estudiante` `est` on((`eva`.`id_estudiante` = `est`.`id`))) join `pregunta` `p` on((`eva`.`id_pregunta` = `p`.`id`))) join `tipo_pregunta` `tp` on((`p`.`id_tipo` = `tp`.`id`))) join `leccion` `l` on((`p`.`id_leccion` = `l`.`id`))) where (`eva`.`estado` = 1) group by `est`.`id`,`l`.`id` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vwlistacurso`
--
DROP TABLE IF EXISTS `vwlistacurso`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vwlistacurso`  AS  select `c`.`id` AS `id_curso`,`c`.`id_curso_prerrequisito` AS `id_curso_prerrequisito`,`ce`.`id_estudiante` AS `id_estudiante`,`ce`.`estado_curso` AS `estado_curso` from (`curso` `c` left join `cursoestudiante` `ce` on((`ce`.`id_curso` = `c`.`id`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vwpreguntasbyleccion`
--
DROP TABLE IF EXISTS `vwpreguntasbyleccion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vwpreguntasbyleccion`  AS  select `leccion`.`id_curso` AS `id_curso`,`leccion`.`id` AS `id_leccion`,`leccion`.`nombre` AS `nombre`,`pregunta`.`id` AS `id_pregunta`,`pregunta`.`pregunta` AS `pregunta`,`tipo_pregunta`.`descripcion` AS `tipo_pregunta`,`pregunta`.`puntaje` AS `puntaje` from ((`leccion` join `pregunta` on((`pregunta`.`id_leccion` = `leccion`.`id`))) join `tipo_pregunta` on((`pregunta`.`id_tipo` = `tipo_pregunta`.`id`))) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cursoestudiante`
--
ALTER TABLE `cursoestudiante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_curso_cursoestudiante` (`id_curso`),
  ADD KEY `fk_estudiante_cursoestudiante` (`id_estudiante`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `evaluacion`
--
ALTER TABLE `evaluacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_pregunta` (`id_pregunta`);

--
-- Indices de la tabla `leccion`
--
ALTER TABLE `leccion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indices de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_leccion` (`id_leccion`),
  ADD KEY `id_tipo` (`id_tipo`);

--
-- Indices de la tabla `tipo_pregunta`
--
ALTER TABLE `tipo_pregunta`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cursoestudiante`
--
ALTER TABLE `cursoestudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `evaluacion`
--
ALTER TABLE `evaluacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `leccion`
--
ALTER TABLE `leccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo_pregunta`
--
ALTER TABLE `tipo_pregunta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cursoestudiante`
--
ALTER TABLE `cursoestudiante`
  ADD CONSTRAINT `cursoestudiante_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id`),
  ADD CONSTRAINT `cursoestudiante_ibfk_2` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id`),
  ADD CONSTRAINT `cursoestudiante_ibfk_3` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id`);

--
-- Filtros para la tabla `evaluacion`
--
ALTER TABLE `evaluacion`
  ADD CONSTRAINT `evaluacion_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id`),
  ADD CONSTRAINT `evaluacion_ibfk_2` FOREIGN KEY (`id_pregunta`) REFERENCES `pregunta` (`id`);

--
-- Filtros para la tabla `leccion`
--
ALTER TABLE `leccion`
  ADD CONSTRAINT `leccion_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id`);

--
-- Filtros para la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD CONSTRAINT `pregunta_ibfk_1` FOREIGN KEY (`id_leccion`) REFERENCES `leccion` (`id`),
  ADD CONSTRAINT `pregunta_ibfk_2` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_pregunta` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
