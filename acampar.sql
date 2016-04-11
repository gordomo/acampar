/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : acampar

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-04-11 16:19:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for calendario
-- ----------------------------
DROP TABLE IF EXISTS `calendario`;
CREATE TABLE `calendario` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `mes` int(2) NOT NULL,
  `ano` int(4) NOT NULL,
  `id_excursiones` text NOT NULL,
  `dias` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for categorias
-- ----------------------------
DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  `foto` text COLLATE utf8_spanish_ci NOT NULL,
  `descripcion_corta` text COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `id_tour` int(11) NOT NULL,
  `cat_superior` int(11) NOT NULL,
  `lat` text COLLATE utf8_spanish_ci NOT NULL,
  `long` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for categorias_bak
-- ----------------------------
DROP TABLE IF EXISTS `categorias_bak`;
CREATE TABLE `categorias_bak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  `foto` text COLLATE utf8_spanish_ci NOT NULL,
  `descripcion_corta` text COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `coordenadas` text COLLATE utf8_spanish_ci NOT NULL,
  `id_tour` int(11) NOT NULL,
  `cat_superior` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for noticias
-- ----------------------------
DROP TABLE IF EXISTS `noticias`;
CREATE TABLE `noticias` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `titulo` text NOT NULL,
  `texto` text NOT NULL,
  `fecha` date NOT NULL,
  `url` text NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for slider_cabecera
-- ----------------------------
DROP TABLE IF EXISTS `slider_cabecera`;
CREATE TABLE `slider_cabecera` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT '1',
  `titulo` text NOT NULL,
  `descripcion` text NOT NULL,
  `categoria_id` int(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for slider_salidas
-- ----------------------------
DROP TABLE IF EXISTS `slider_salidas`;
CREATE TABLE `slider_salidas` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT '1',
  `titulo` text NOT NULL,
  `descripcion` text NOT NULL,
  `categoria_id` int(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for tours
-- ----------------------------
DROP TABLE IF EXISTS `tours`;
CREATE TABLE `tours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  `class_css` varchar(24) COLLATE utf8_spanish_ci NOT NULL,
  `id_css` varchar(24) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `pass` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
