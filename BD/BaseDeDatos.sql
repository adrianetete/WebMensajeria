-- phpMyAdmin SQL Dump
-- version 3.5.6
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 06-06-2013 a las 05:34:27
-- Versión del servidor: 5.5.29-log
-- Versión de PHP: 5.3.21

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `AdrianCalvo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `amigos`
--

CREATE TABLE IF NOT EXISTS `amigos` (
  `id_amistad` int(10) NOT NULL,
  `usu_1` int(10) NOT NULL,
  `usu_2` int(10) NOT NULL,
  PRIMARY KEY (`id_amistad`),
  UNIQUE KEY `usu_1` (`usu_1`),
  UNIQUE KEY `usu_2` (`usu_2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje_privado`
--

CREATE TABLE IF NOT EXISTS `mensaje_privado` (
  `id_mensaje` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `texto` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id_mensaje`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propiedades`
--

CREATE TABLE IF NOT EXISTS `propiedades` (
  `id_propiedad` int(5) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `carpeta` varchar(10) NOT NULL,
  `perfil` varchar(50) NOT NULL DEFAULT 'defecto.png',
  PRIMARY KEY (`id_propiedad`),
  UNIQUE KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- Volcado de datos para la tabla `propiedades`
--

INSERT INTO `propiedades` (`id_propiedad`, `id_usuario`, `carpeta`, `perfil`) VALUES
(2, 6, '000002', 'defecto.png'),
(3, 3, '000003', 'defecto.png'),
(4, 5, '198283', '198283/2121.png'),
(51, 7, '630667', 'defecto.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `pass` varchar(20) NOT NULL,
  `tipo` int(2) DEFAULT '0',
  `nombre` text NOT NULL,
  `apellido1` text,
  `apellido2` text,
  `provincia` text,
  `codigo_postal` int(5) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `pass`, `tipo`, `nombre`, `apellido1`, `apellido2`, `provincia`, `codigo_postal`, `email`) VALUES
(3, '12345', 1, 'Elena', 'García', 'Diez', 'Valladolid', 34003, 'elena@hotmail.com'),
(5, '12345', 1, 'Adrian', 'Calvo', 'Rojo', 'Palencia', 34003, 'adri_2804@hotmail.com'),
(6, '12345', 0, 'Juan', 'Hernandez', 'Blanco', 'Madrid', 34003, 'juan@hotmail.com'),
(7, '12345', 0, 'Pepe', 'Carrion', 'Gonzalez', 'Valencia', 32001, 'pepe@hotmail.es');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `u_mp`
--

CREATE TABLE IF NOT EXISTS `u_mp` (
  `id_ump` int(11) NOT NULL AUTO_INCREMENT,
  `usu_remite` int(11) NOT NULL,
  `usu_destinatario` int(11) NOT NULL,
  `id_mensaje` int(11) NOT NULL,
  PRIMARY KEY (`id_ump`),
  KEY `id_mensaje` (`id_mensaje`),
  KEY `usu_remite` (`usu_remite`),
  KEY `usu_destinatario` (`usu_destinatario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `amigos`
--
ALTER TABLE `amigos`
  ADD CONSTRAINT `amigos_ibfk_1` FOREIGN KEY (`usu_1`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `amigos_ibfk_2` FOREIGN KEY (`usu_2`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `propiedades`
--
ALTER TABLE `propiedades`
  ADD CONSTRAINT `propiedades_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `u_mp`
--
ALTER TABLE `u_mp`
  ADD CONSTRAINT `u_mp_ibfk_1` FOREIGN KEY (`id_mensaje`) REFERENCES `mensaje_privado` (`id_mensaje`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `u_mp_ibfk_2` FOREIGN KEY (`usu_remite`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `u_mp_ibfk_3` FOREIGN KEY (`usu_destinatario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
