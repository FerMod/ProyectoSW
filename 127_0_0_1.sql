-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 19-10-2017 a las 20:46:01
-- Versión del servidor: 5.7.19
-- Versión de PHP: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `quiz`
--
CREATE DATABASE IF NOT EXISTS `quiz` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `quiz`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

DROP TABLE IF EXISTS `preguntas`;
CREATE TABLE IF NOT EXISTS `preguntas` (
  `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id de la pregunta',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Correo del autor de la pregunta',
  `enunciado` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Enunciado de la pregunta',
  `respuesta_correcta` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Respuesta correcta a la pregunta',
  `respuesta_incorrecta_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Respuesta incorrecta a la pregunta',
  `respuesta_incorrecta_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Respuesta incorrecta a la pregunta',
  `respuesta_incorrecta_3` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Respuesta incorrecta a la pregunta',
  `complejidad` int(1) NOT NULL COMMENT 'Complejidad de la pregunta (1-5)',
  `tema` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Tema de la pregunta',
  `imagen` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Imagen opcional de la pregunta',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id`, `email`, `enunciado`, `respuesta_correcta`, `respuesta_incorrecta_1`, `respuesta_incorrecta_2`, `respuesta_incorrecta_3`, `complejidad`, `tema`, `imagen`) VALUES
(1, 'fostergun123@ikasle.ehu.es', 'aaaaa', 'aaaaaa', 'aaaaaaa', 'aaaaaa', 'aaaaaaa', 3, 'adsad', NULL),
(2, 'fostergun123@ikasle.ehu.es', 'aaaaa', 'aaaaaa', 'aaaaaaa', 'aaaaaa', 'aaaaaaa', 3, 'adsad', NULL),
(3, 'fostergun123@ikasle.ehu.es', 'aaaaa', 'aaaaaa', 'aaaaaaa', 'aaaaaa', 'aaaaaaa', 3, 'adsad', NULL),
(4, 'fostergun123@ikasle.ehu.es', 'aaaaa', 'aaaaaa', 'aaaaaaa', 'aaaaaa', 'aaaaaaa', 3, 'adsad', NULL),
(5, 'fostergun123@ikasle.ehu.es', 'aaaaa', 'aaaaaa', 'aaaaaaa', 'aaaaaa', 'aaaaaaa', 3, 'adsad', NULL),
(6, 'fostergun123@ikasle.ehu.es', 'aaaaa', 'aaaaaa', 'aaaaaaa', 'aaaaaa', 'aaaaaaa', 3, 'adsad', NULL),
(7, 'fostergun123@ikasle.ehu.es', 'aaaaaa', 'aaaaaa', 'aaaaaaaa', 'aaaaaa', 'aaaaaa', 2, 'asfaf', NULL),
(8, 'fostergun123@ikasle.ehu.es', 'aaaaaa', 'aaaaaa', 'aaaaaaaa', 'aaaaaa', 'aaaaaa', 2, 'asfaf', NULL),
(9, 'fostergun123@ikasle.ehu.es', 'aaaaaa', 'aaaaaa', 'aaaaaaaa', 'aaaaaa', 'aaaaaa', 2, 'asfaf', NULL),
(10, 'fostergun123@ikasle.ehu.es', 'aaaaa', 'aaaaaaaa', 'aaaaaaaa', 'aaaaaaaaa', 'aaaaaaaa', 3, 'aaaaaaaaa', NULL),
(11, 'fostergun123@ikasle.ehu.es', 'aaaaa', 'aaaaaaaa', 'aaaaaaaa', 'aaaaaaaaa', 'aaaaaaaa', 3, 'aaaaaaaaa', NULL),
(12, 'foster123@ikasle.ehu.es', 'aaaa', 'aaaa', 'aaaa', 'aaaaaa', 'aaaaa', 2, 'adwas', NULL),
(13, 'foster123@ikasle.ehu.es', 'aaaa', 'aaaa', 'aaaa', 'aaaaaa', 'aaaaa', 2, 'adwas', NULL),
(14, 'fostergun123@ikasle.ehu.es', 'aa', 'a', 'a', 'a', 'a', 3, 'afdas', NULL),
(15, 'fostergun123@ikasle.ehu.es', 'aaaaaaaaaaaaa', 'asfffffff', 'safffff', 'asffff', 'asfsfsfsf', 2, 'assss', NULL),
(16, 'fostergun123@ikasle.ehu.es', 'aaaaaaaaaaaaa', 'asfffffff', 'safffff', 'asffff', 'asfsfsfsf', 2, 'assss', NULL),
(17, 'fostergun123@ikasle.ehu.es', 'aaaaaaaaaaaaa', 'asfffffff', 'safffff', 'asffff', 'asfsfsfsf', 2, 'assss', NULL),
(18, 'fostergun123@ikasle.ehu.es', 'aaaaaaaaaaaaa', 'asfffffff', 'safffff', 'asffff', 'asfsfsfsf', 2, 'assss', NULL),
(19, 'fostergun123@ikasle.ehu.es', 'aaaaaaaaaaaaa', 'asfffffff', 'safffff', 'asffff', 'asfsfsfsf', 2, 'assss', NULL),
(20, 'fostergun123@ikasle.ehu.es', 'aaaaaaaaaaaaa', 'asfffffff', 'safffff', 'asffff', 'asfsfsfsf', 2, 'assss', NULL),
(21, 'fostergun123@ikasle.ehu.es', 'aaaaaaaaaaaaa', 'asfffffff', 'safffff', 'asffff', 'asfsfsfsf', 2, 'assss', NULL),
(22, 'fostergun123@ikasle.ehu.es', 'aaaaaaaaaaaaa', 'asfffffff', 'safffff', 'asffff', 'asfsfsfsf', 2, 'assss', NULL),
(23, 'fostergun123@ikasle.ehu.es', 'aaaaaaaaaaaaa', 'asfffffff', 'safffff', 'asffff', 'asfsfsfsf', 2, 'assss', NULL),
(24, 'fostergun123@ikasle.ehu.es', 'aaaaaaaaaaaaa', 'asfffffff', 'safffff', 'asffff', 'asfsfsfsf', 2, 'assss', NULL),
(25, 'fostergun123@ikasle.ehu.es', 'asdaf', 'fassfa', 'afsfas', 'asfasfasf', 'sfafsaasf', 3, 'as', NULL),
(26, 'fostergun123@ikasle.ehu.es', 'asdaf', 'fassfa', 'afsfas', 'asfasfasf', 'sfafsaasf', 3, 'as', NULL),
(27, 'fostergun123@ikasle.ehu.es', 'asdaf', 'fassfa', 'afsfas', 'asfasfasf', 'sfafsaasf', 3, 'as', NULL),
(28, 'fostergun123@ikasle.ehu.es', 'asdaf', 'fassfa', 'afsfas', 'asfasfasf', 'sfafsaasf', 3, 'as', NULL),
(29, 'fostergun123@ikasle.ehu.es', 'asdaf', 'fassfa', 'afsfas', 'asfasfasf', 'sfafsaasf', 3, 'as', NULL),
(30, 'fostergun123@ikasle.ehu.es', 'asdaf', 'fassfa', 'afsfas', 'asfasfasf', 'sfafsaasf', 3, 'as', NULL),
(31, 'fostergun123@ikasle.ehu.es', 'asdaf', 'fassfa', 'afsfas', 'asfasfasf', 'sfafsaasf', 3, 'as', NULL),
(32, 'fostergun123@ikasle.ehu.es', 'asdaf', 'fassfa', 'afsfas', 'asfasfasf', 'sfafsaasf', 3, 'as', NULL),
(33, 'fostergun123@ikasle.ehu.es', 'asdsaf', 'asf', 'asfdas', 'asaffas', 'asfas', 2, 'aads', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
