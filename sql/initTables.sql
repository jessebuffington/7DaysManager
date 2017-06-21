CREATE DATABASE `7daysManager` /*!40100 DEFAULT CHARACTER SET latin1 */;

CREATE TABLE `bans` (
  `id` int(11) NOT NULL,
  `steam` varchar(17) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `bannedTo` varchar(22) NOT NULL,
  `permanent` tinyint(1) NOT NULL DEFAULT '0',
  `playTime` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `playerKills` int(11) NOT NULL DEFAULT '0',
  `zombies` int(11) NOT NULL DEFAULT '0',
  `country` varchar(2) DEFAULT NULL,
  `belt` varchar(500) DEFAULT NULL,
  `pack` varchar(1000) DEFAULT NULL,
  `equipment` varchar(500) DEFAULT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `botID` varchar(7) DEFAULT NULL,
  `admin` varchar(17) DEFAULT NULL,
  PRIMARY KEY (`id`,`steam`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `chatLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerName` varchar(45) NOT NULL,
  `message` varchar(5000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `donors` (
  `botID` int(11) NOT NULL AUTO_INCREMENT,
  `steam` bigint(17) NOT NULL DEFAULT '0',
  `donor` tinyint(1) NOT NULL DEFAULT '0',
  `donorLevel` int(11) NOT NULL DEFAULT '0',
  `donorExpiry` int(11) DEFAULT NULL,
  `serverGroup` varchar(20) NOT NULL,
  PRIMARY KEY (`botID`,`steam`),
  UNIQUE KEY `steam` (`steam`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `events` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `serverTime` varchar(19) NOT NULL,
  `type` varchar(15) NOT NULL,
  `event` varchar(255) NOT NULL,
  `steam` varchar(17) NOT NULL,
  `server` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `server` (`server`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `gameTime` (
  `id` int(11) NOT NULL,
  `currentTime` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `guides` (
  `guideID` int(11) NOT NULL,
  `Title` varchar(100) NOT NULL,
  `Summary` varchar(255) NOT NULL,
  `guide` text NOT NULL,
  PRIMARY KEY (`guideID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `helpCommands` (
  `commandID` int(11) NOT NULL,
  `command` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  `keywords` varchar(150) NOT NULL,
  `lastUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`commandID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `helpTopicCommands` (
  `topicID` int(11) NOT NULL,
  `commandID` int(11) NOT NULL,
  PRIMARY KEY (`topicID`,`commandID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `helpTopics` (
  `topicID` int(11) NOT NULL AUTO_INCREMENT,
  `topic` varchar(20) NOT NULL,
  `description` varchar(150) NOT NULL,
  PRIMARY KEY (`topicID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `IPBlacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `StartIP` bigint(15) NOT NULL,
  `EndIP` bigint(15) NOT NULL,
  `Country` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`,`StartIP`)
) ENGINE=InnoDB AUTO_INCREMENT=5827 DEFAULT CHARSET=utf8;

CREATE TABLE `messageQueue` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sender` bigint(17) NOT NULL DEFAULT '0',
  `recipient` bigint(20) NOT NULL DEFAULT '0',
  `message` varchar(1000) NOT NULL,
  `fromServer` int(11) NOT NULL,
  `toServer` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` varchar(10) NOT NULL,
  `playerName` varchar(45) NOT NULL,
  `currentPosition` varchar(45) DEFAULT NULL,
  `rotPosition` varchar(45) DEFAULT NULL,
  `remote` varchar(5) DEFAULT NULL,
  `health` varchar(4) DEFAULT NULL,
  `deaths` varchar(10) DEFAULT NULL,
  `zombiesKilled` varchar(20) DEFAULT NULL,
  `playersKilled` varchar(5) DEFAULT NULL,
  `score` varchar(10) DEFAULT NULL,
  `level` varchar(3) DEFAULT NULL,
  `steamid` varchar(20) NOT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `ping` varchar(5) DEFAULT NULL,
  `onlineStatus` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `playerid_UNIQUE` (`playerid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

CREATE TABLE `proxies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scanString` varchar(100) NOT NULL,
  `action` varchar(20) NOT NULL DEFAULT 'nothing',
  `hits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

CREATE TABLE `servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ServerPort` int(11) NOT NULL DEFAULT '0',
  `IP` varchar(15) DEFAULT NULL,
  `botName` varchar(20) NOT NULL DEFAULT '"Botman"',
  `serverName` varchar(50) NOT NULL,
  `playersOnline` int(11) NOT NULL DEFAULT '0',
  `tick` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `serverGroup` varchar(20) DEFAULT NULL,
  `botID` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

