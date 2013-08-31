-- phpMyAdmin SQL Dump
-- version 3.5.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 20. Mai 2013 um 22:46
-- Server Version: 5.5.29-log
-- PHP-Version: 5.3.21

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `login`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
 `user_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
 `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name',
 `user_password_hash` char(60) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
 `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email',
 `user_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s activation status',
 `user_activation_hash` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s email verification hash string',
 `user_password_reset_hash` char(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s password reset code',
 `user_password_reset_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of the password reset request',
 `user_rememberme_token` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s remember-me cookie token',
 `user_registration_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `user_registration_ip` varchar(39) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0',
 PRIMARY KEY (`user_id`),
 UNIQUE KEY `user_name` (`user_name`),
 UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
