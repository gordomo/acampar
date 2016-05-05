-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-05-2016 a las 15:49:01
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
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  `foto` text COLLATE utf8_spanish_ci NOT NULL,
  `descripcion_corta` text COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `id_tour` int(11) NOT NULL,
  `cat_superior` int(11) NOT NULL,
  `lat` text COLLATE utf8_spanish_ci NOT NULL,
  `long` text COLLATE utf8_spanish_ci NOT NULL,
  `polylines` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=30 ;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `foto`, `descripcion_corta`, `descripcion`, `id_tour`, `cat_superior`, `lat`, `long`, `polylines`) VALUES
(26, 'Opción 1123123123', 'img/categorias/7ebf0077bf_pink-floyd-11.jpg', '', '', 1, 0, '-32.6517842', '-70.2910596', ''),
(27, 'Martin', 'img/categorias/071568a11a_wdf_1382273.jpg', 'corta3', '“Gastronomía y Alta Cocina” brinda los conocimientos necesarios para alcanzar la excelencia como profesional de la gastronomía. Además obtendrás las herramientas para emprender un negocio culinario y alcanzar así la cima del éxito, haciendo de la cocina tu profesión. Ver mas...', 2, 5, '-32.948920', '-60.646229', ''),
(28, 'Hijo aconca', 'img/categorias/a3f57962ae_pink-floyd-11.jpg', 'corta2', '“Gastronomía y Alta Cocina” brinda los conocimientos necesarios para alcanzar la excelencia como profesional de la gastronomía. Además obtendrás las herramientas para emprender un negocio culinario y alcanzar así la cima del éxito, haciendo de la cocina tu profesión. Ver mas...', 2, 6, '-32.652922', '-70.011727', ''),
(29, 'nuevo', 'img/categorias/fcda90a81d_hqdefault.jpg', 'corta2', '“Gastronomía y Alta Cocina” brinda los conocimientos necesarios para alcanzar la excelencia como profesional de la gastronomía. Además obtendrás las herramientas para emprender un negocio culinario y alcanzar así la cima del éxito, haciendo de la cocina tu profesión. Ver mas...', 2, 1, '-32.949568', '-60.648246', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
