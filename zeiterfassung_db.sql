SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `zeiterfassung_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `zeiterfassung_db`;

DROP TABLE IF EXISTS `krank`;
CREATE TABLE IF NOT EXISTS `krank` (
  `account` text NOT NULL,
  `aktuellkrank` bit(1) DEFAULT b'0',
  `bis` date DEFAULT NULL,
  KEY `account` (`account`(10)) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `mitarbeiter`;
CREATE TABLE IF NOT EXISTS `mitarbeiter` (
  `account` varchar(35) NOT NULL DEFAULT '',
  `passwort` varchar(128) DEFAULT NULL,
  `sysadmlevel` int(11) DEFAULT NULL,
  `personaladmlevel` int(11) DEFAULT NULL,
  `vorname` text DEFAULT NULL,
  `name` text DEFAULT NULL,
  `vertragsbeginn` date DEFAULT NULL,
  `vertragsende` date DEFAULT NULL,
  `vertragsart` text DEFAULT NULL,
  `urlaubsanspruch` int(11) DEFAULT NULL,
  `resturlaub` int(11) DEFAULT NULL,
  `milo` tinyint(1) DEFAULT NULL,
  `wochenstunden` decimal(5,2) DEFAULT NULL,
  `korrektur` decimal(5,2) DEFAULT NULL,
  `reststunden` decimal(5,2) DEFAULT NULL,
  `jahresstunden` decimal(6,2) DEFAULT NULL,
  `stamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`account`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `stundenzettel`;
CREATE TABLE IF NOT EXISTS `stundenzettel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` text DEFAULT NULL,
  `datum` date DEFAULT NULL,
  `aufgabe` text DEFAULT NULL,
  `bereich` text DEFAULT NULL,
  `von` time DEFAULT NULL,
  `bis` time DEFAULT NULL,
  `zeitsumme` int(11) DEFAULT NULL,
  `AU` bit(1) DEFAULT b'0',
  `bemerkungen` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account` (`account`(10))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
