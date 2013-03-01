-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 01-03-2013 a las 13:34:09
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `bd_encuesta`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciones`
--

CREATE TABLE IF NOT EXISTS `asignaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `encuesta_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `activo` tinyint(1) DEFAULT '0',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_finalizacion` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_encuestas_has_encuestados_encuestas1_idx` (`encuesta_id`),
  KEY `fk_asignaciones_grupos1_idx` (`grupo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestados`
--

CREATE TABLE IF NOT EXISTS `encuestados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `encuestado_nombre` varchar(225) DEFAULT NULL,
  `grado_estudio` enum('PRIMERO','SEGUNDO','TERCERO','CUARTO','QUINTO') DEFAULT NULL,
  `grupo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_encuestados_grupos1_idx` (`grupo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE IF NOT EXISTS `encuestas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `encuesta_titulo` varchar(120) DEFAULT NULL,
  `descripcion` text,
  `fecha_creacion` varchar(45) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_encuestas_usuarios1` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE IF NOT EXISTS `grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_nombre` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones`
--

CREATE TABLE IF NOT EXISTS `opciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `opcion_respuesta` varchar(45) DEFAULT NULL,
  `pregunta_id` int(11) NOT NULL,
  `votos` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_opciones_preguntas1_idx` (`pregunta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE IF NOT EXISTS `preguntas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `formulacion_pregunta` varchar(225) DEFAULT NULL,
  `encuesta_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_preguntas_encuestas_idx` (`encuesta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', NULL);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD CONSTRAINT `fk_encuestas_has_encuestados_encuestas1` FOREIGN KEY (`encuesta_id`) REFERENCES `encuestas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_asignaciones_grupos1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `encuestados`
--
ALTER TABLE `encuestados`
  ADD CONSTRAINT `fk_encuestados_grupos1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD CONSTRAINT `fk_encuestas_usuarios1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `opciones`
--
ALTER TABLE `opciones`
  ADD CONSTRAINT `fk_opciones_preguntas1` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `fk_preguntas_encuestas` FOREIGN KEY (`encuesta_id`) REFERENCES `encuestas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
