-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 23. Apr 2014 um 07:13
-- Server Version: 5.6.16
-- PHP-Version: 5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `winfo1`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rr_bookings`
--

CREATE TABLE IF NOT EXISTS `rr_bookings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `startdatetime` datetime NOT NULL,
  `enddatetime` datetime NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rr_buildings`
--

CREATE TABLE IF NOT EXISTS `rr_buildings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `short` varchar(10) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rr_groups`
--

CREATE TABLE IF NOT EXISTS `rr_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rr_organizationalunits`
--

CREATE TABLE IF NOT EXISTS `rr_organizationalunits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `short` varchar(10) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `approval_horizon` int(11) DEFAULT '3',
  `approval_automatic` tinyint(4) DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rr_resources`
--

CREATE TABLE IF NOT EXISTS `rr_resources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` int(3) NOT NULL,
  `description` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rr_resources_rooms`
--

CREATE TABLE IF NOT EXISTS `rr_resources_rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int(10) unsigned NOT NULL,
  `room_id` int(10) unsigned NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rr_roomimages`
--

CREATE TABLE IF NOT EXISTS `rr_roomimages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` int(10) unsigned NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `image_small_url` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rr_rooms`
--

CREATE TABLE IF NOT EXISTS `rr_rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organizationalunit_id` int(10) unsigned NOT NULL,
  `building_id` int(10) unsigned NOT NULL,
  `floor` int(11) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `layout_image_url` varchar(255) DEFAULT NULL,
  `layout_image_small_url` varchar(255) DEFAULT NULL,
  `barrier_free` tinyint(4) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rr_semesters`
--

CREATE TABLE IF NOT EXISTS `rr_semesters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `short` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rr_users`
--

CREATE TABLE IF NOT EXISTS `rr_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL,
  `organizationalunit_id` int(10) unsigned NOT NULL DEFAULT '0',
  `organizationalunit_fixed` tinyint(4) DEFAULT '0',
  `organizationalunit_verified` tinyint(4) DEFAULT '0',
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `emailaddress` varchar(50) DEFAULT NULL,
  `phonenumber` varchar(50) DEFAULT NULL,
  `admin_email_every_booking` tinyint(4) DEFAULT NULL,
  `admin_email_every_booking_plan` tinyint(4) DEFAULT '1',
  `user_email_if_active` tinyint(4) DEFAULT NULL,
  `user_email_if_active_gets_rejected` tinyint(4) DEFAULT '1',
  `user_email_if_planned` tinyint(4) DEFAULT NULL,
  `user_email_if_plan_gets_active` tinyint(4) DEFAULT '1',
  `user_email_if_plan_gets_rejected` tinyint(4) DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
