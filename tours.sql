-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-04-2016 a las 18:33:21
-- Versión del servidor: 5.6.17
-- Versión de PHP: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `acampar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tours`
--

CREATE TABLE IF NOT EXISTS `tours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  `class_css` varchar(24) COLLATE utf8_spanish_ci NOT NULL,
  `id_css` varchar(24) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `tours`
--

INSERT INTO `tours` (`id`, `nombre`, `class_css`, `id_css`, `descripcion`) VALUES
(1, 'cicloturismo', 'fondoVerde', 'ciclo', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce fermentum maximus dolor, in bibendum tellus. Sed tincidunt, velit vel placerat iaculis, ligula tortor laoreet augue, ut sagittis dolor mi in sem. Pellentesque fermentum dui quis sagittis sodales. Aliquam in luctus quam. Quisque gravida, diam sit amet fringilla eleifend, erat tellus dignissim nisl, a rutrum mi turpis scelerisque eros. Phasellus finibus tempus nulla, sit amet elementum arcu dapibus ut. Vestibulum egestas ex eget sem vestibulum mattis. Sed facilisis tellus elit. Curabitur accumsan est porta neque pulvinar lacinia. Cras sit amet mauris vel erat vehicula pretium vitae ut neque. Mauris eget mi ex. Vivamus eu tortor quis dolor tempor ullamcorper sit amet id massa. Proin accumsan sem nec mi imperdiet, quis faucibus justo fringilla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.'),
(2, 'trekking & expediciones', 'fondoNaranja', 'trekking', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce fermentum maximus dolor, in bibendum tellus. Sed tincidunt, velit vel placerat iaculis, ligula tortor laoreet augue, ut sagittis dolor mi in sem. Pellentesque fermentum dui quis sagittis sodales. Aliquam in luctus quam. Quisque gravida, diam sit amet fringilla eleifend, erat tellus dignissim nisl, a rutrum mi turpis scelerisque eros. Phasellus finibus tempus nulla, sit amet elementum arcu dapibus ut. Vestibulum egestas ex eget sem vestibulum mattis. Sed facilisis tellus elit. Curabitur accumsan est porta neque pulvinar lacinia. Cras sit amet mauris vel erat vehicula pretium vitae ut neque. Mauris eget mi ex. Vivamus eu tortor quis dolor tempor ullamcorper sit amet id massa. Proin accumsan sem nec mi imperdiet, quis faucibus justo fringilla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.'),
(3, 'cabalgatas', 'fondoVioleta', 'cabalgatas', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce fermentum maximus dolor, in bibendum tellus. Sed tincidunt, velit vel placerat iaculis, ligula tortor laoreet augue, ut sagittis dolor mi in sem. Pellentesque fermentum dui quis sagittis sodales. Aliquam in luctus quam. Quisque gravida, diam sit amet fringilla eleifend, erat tellus dignissim nisl, a rutrum mi turpis scelerisque eros. Phasellus finibus tempus nulla, sit amet elementum arcu dapibus ut. Vestibulum egestas ex eget sem vestibulum mattis. Sed facilisis tellus elit. Curabitur accumsan est porta neque pulvinar lacinia. Cras sit amet mauris vel erat vehicula pretium vitae ut neque. Mauris eget mi ex. Vivamus eu tortor quis dolor tempor ullamcorper sit amet id massa. Proin accumsan sem nec mi imperdiet, quis faucibus justo fringilla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
