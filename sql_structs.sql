-- phpMyAdmin SQL Dump
-- version 4.1.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 22. Mrz 2014 um 23:08
-- Server Version: 5.6.16
-- PHP Version: 5.3.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `aiw_admin`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bans`
--

DROP TABLE IF EXISTS `bans`;
CREATE TABLE IF NOT EXISTS `bans` (
  `entry` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(16) NOT NULL,
  `name` varchar(32) NOT NULL,
  `reason` text NOT NULL,
  `banned_by` varchar(32) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`entry`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=68 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chat_logs`
--

DROP TABLE IF EXISTS `chat_logs`;
CREATE TABLE IF NOT EXISTS `chat_logs` (
  `entry` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(16) NOT NULL,
  `name` text NOT NULL,
  `server` varchar(32) NOT NULL,
  `team` varchar(4) NOT NULL,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `message` text NOT NULL,
  PRIMARY KEY (`entry`),
  UNIQUE KEY `entry` (`entry`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11481 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `playerlist`
--

DROP TABLE IF EXISTS `playerlist`;
CREATE TABLE IF NOT EXISTS `playerlist` (
  `slotid` int(11) NOT NULL,
  `guid` varchar(16) NOT NULL,
  `server` text NOT NULL,
  `playtime` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `guid` (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `players`
--

DROP TABLE IF EXISTS `players`;
CREATE TABLE IF NOT EXISTS `players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(16) NOT NULL,
  `name` varchar(32) NOT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `rank` int(11) NOT NULL DEFAULT '0',
  `joined` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `connections` int(11) NOT NULL DEFAULT '0',
  `playtime_sec` bigint(20) DEFAULT '0',
  `fpsboost` tinyint(1) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `guid` (`guid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8189 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `player_stats`
--

DROP TABLE IF EXISTS `player_stats`;
CREATE TABLE IF NOT EXISTS `player_stats` (
  `guid` varchar(16) NOT NULL,
  `bullets` int(11) NOT NULL DEFAULT '0',
  `kills` int(11) NOT NULL,
  `deaths` int(11) NOT NULL,
  `damage` int(11) NOT NULL,
  PRIMARY KEY (`guid`),
  UNIQUE KEY `guid` (`guid`),
  KEY `guid_2` (`guid`),
  KEY `guid_3` (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `positions`
--

DROP TABLE IF EXISTS `positions`;
CREATE TABLE IF NOT EXISTS `positions` (
  `guid` varchar(16) NOT NULL,
  `pos` varchar(32) NOT NULL,
  `slotid` int(11) NOT NULL,
  `team` varchar(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `server` text NOT NULL,
  `hp` int(3) NOT NULL,
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `servers`
--

DROP TABLE IF EXISTS `servers`;
CREATE TABLE IF NOT EXISTS `servers` (
  `log` text NOT NULL,
  `name` text NOT NULL,
  `port` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `weapon_player_stats`
--

DROP TABLE IF EXISTS `weapon_player_stats`;
CREATE TABLE IF NOT EXISTS `weapon_player_stats` (
  `guid` varchar(16) NOT NULL,
  `weapon` varchar(32) NOT NULL,
  `damage` int(11) NOT NULL DEFAULT '0',
  `kills` int(11) NOT NULL DEFAULT '0',
  `bullets` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `weapon_stats`
--

DROP TABLE IF EXISTS `weapon_stats`;
CREATE TABLE IF NOT EXISTS `weapon_stats` (
  `weapon` varchar(64) NOT NULL,
  `damage` bigint(20) NOT NULL,
  `kills` bigint(20) NOT NULL,
  `bullets` bigint(20) NOT NULL,
  UNIQUE KEY `weapon` (`weapon`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
