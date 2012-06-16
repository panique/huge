-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 16. Jun 2012 um 13:50
-- Server Version: 5.5.24-log
-- PHP-Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `demo`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name',
  `user_salt` char(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s automaticly generated SALT (key to protect the password)',
  `user_password` char(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and sha256 hashed format',
  `user_email` text COLLATE utf8_unicode_ci COMMENT 'user''s email',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data' AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_salt`, `user_password`, `user_email`) VALUES
(1, 'chris', 'QBtqND625ndKUvuVRyzpIfeTME8GZA3Lx1WCOr40iSog9Pawk7JXYjbFsmlchH', 'ef346424a283027fe39830156e0289769c8d230f7a46143dd77c4cf190d90c23', 'chris@test.com'),
(2, 'tom', 'AOjRJE1qU8730FBKxvTZo94bmQwCcIpfYgMPdrHWnslDXkzGL5NSViey6huat2', 'c6973e81dda5199ac96bd33c514e0bc7d3036e38294d32fae291d717f8ea4e64', 'tom@test.com');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
