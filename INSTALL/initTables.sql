CREATE DATABASE IF NOT EXISTS 7daysManager;
--
-- Begin table create statements
--

USE 7daysManager;

CREATE TABLE `app_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `configName` varchar(50) NOT NULL,
  `configValue` varchar(255) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configName_UNIQUE` (`configName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `app_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `logLevel` varchar(10) DEFAULT NULL,
  `runName` varchar(45) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `app_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `status` varchar(8) NOT NULL DEFAULT 'Inactive',
  `enabled` varchar(1) NOT NULL DEFAULT '1',
  `description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `badWords` (
  `badWord` varchar(15) NOT NULL,
  `cost` int(11) NOT NULL DEFAULT '10',
  `counter` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `badWord` (`badWord`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `chatLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime DEFAULT NULL,
  `playerName` varchar(45) NOT NULL,
  `message` varchar(5000) DEFAULT NULL,
  `inGame` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `customCommands` (
  `commandID` int(11) NOT NULL AUTO_INCREMENT,
  `command` varchar(50) DEFAULT NULL,
  `serverExecution` varchar(255) DEFAULT NULL,
  `accessLevel` int(11) DEFAULT '2',
  `commandDetails` varchar(255) DEFAULT NULL,
  `price` varchar(10) DEFAULT '0',
  `cooldown` varchar(5) DEFAULT '0',
  `usageExample` varchar(255) DEFAULT NULL,
  `relatedCommand` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`commandID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `memEntities` (
  `entityID` bigint(20) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(30) NOT NULL DEFAULT '',
  `x` int(11) NOT NULL DEFAULT '0',
  `y` int(11) NOT NULL DEFAULT '0',
  `z` int(11) DEFAULT '0',
  `dead` tinyint(1) NOT NULL DEFAULT '0',
  `health` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `entityID` (`entityID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `playerHistory` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `playerid` varchar(10) NOT NULL,
  `playerName` varchar(45) NOT NULL,
  `onlineStatus` varchar(5) DEFAULT '0',
  `currentPosition` varchar(45) DEFAULT NULL,
  `health` varchar(4) DEFAULT NULL,
  `stamina` varchar(45) DEFAULT NULL,
  `deaths` varchar(10) DEFAULT NULL,
  `zombiesKilled` varchar(20) DEFAULT NULL,
  `playersKilled` varchar(5) DEFAULT NULL,
  `score` varchar(10) DEFAULT NULL,
  `experience` varchar(45) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `steamid` varchar(20) NOT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `ping` varchar(5) DEFAULT NULL,
  `lastSeen` varchar(30) DEFAULT NULL,
  `playtime` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `players` (
  `playerid` varchar(10) NOT NULL,
  `playerName` varchar(45) NOT NULL,
  `currentPosition` varchar(45) DEFAULT NULL,
  `remote` varchar(5) DEFAULT NULL,
  `health` varchar(4) DEFAULT NULL,
  `stamina` varchar(45) DEFAULT NULL,
  `deaths` varchar(10) DEFAULT NULL,
  `zombiesKilled` varchar(20) DEFAULT NULL,
  `playersKilled` varchar(5) DEFAULT NULL,
  `score` varchar(10) DEFAULT NULL,
  `experience` varchar(45) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `steamid` varchar(20) NOT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `ping` varchar(5) DEFAULT NULL,
  `onlineStatus` varchar(5) NOT NULL DEFAULT '0',
  `lastSeen` varchar(30) DEFAULT NULL,
  `playtime` varchar(10) DEFAULT NULL,
  `banned` varchar(1) DEFAULT '0',
  PRIMARY KEY (`playerid`,`steamid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `server_announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `message` varchar(400) NOT NULL,
  `interval` int(4) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `server_bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerName` varchar(45) DEFAULT NULL,
  `steamid` varchar(17) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ip` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `reason` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `bannedTo` varchar(22) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `permanent` tinyint(1) NOT NULL DEFAULT '0',
  `playTime` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `playerKills` int(11) NOT NULL DEFAULT '0',
  `zombies` int(11) NOT NULL DEFAULT '0',
  `country` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `belt` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `pack` varchar(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `equipment` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `botID` varchar(7) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `admin` varchar(17) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `manUnban` int(1) NOT NULL DEFAULT '0',
  `unbanReason` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`,`steamid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `server_consoleCommands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `command` varchar(25) DEFAULT NULL,
  `shortCommand` varchar(6) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `server_gameTime` (
  `serverID` int(11) NOT NULL,
  `currentDay` varchar(5) DEFAULT NULL,
  `currentTime` varchar(5) DEFAULT NULL,
  `daysLeft` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`serverID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `server_info` (
  `serverID` int(11) NOT NULL AUTO_INCREMENT,
  `gameType` varchar(5) DEFAULT NULL,
  `gameName` varchar(45) DEFAULT NULL,
  `gameHost` varchar(100) DEFAULT NULL,
  `serverDescription` varchar(255) DEFAULT NULL,
  `serverWebsiteURL` varchar(45) DEFAULT NULL,
  `levelName` varchar(255) DEFAULT NULL,
  `gameMode` varchar(45) DEFAULT NULL,
  `version` varchar(45) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `countryCode` varchar(3) DEFAULT NULL,
  `steamID` varchar(45) DEFAULT NULL,
  `compatibilityVersion` varchar(20) DEFAULT NULL,
  `platform` varchar(45) DEFAULT NULL,
  `serverLoginConfirmationText` varchar(255) DEFAULT NULL,
  `port` int(6) DEFAULT NULL,
  `currentPlayers` int(3) DEFAULT NULL,
  `maxPlayers` int(3) DEFAULT NULL,
  `gameDifficulty` int(2) DEFAULT NULL,
  `dayNightLength` int(5) DEFAULT NULL,
  `zombiesRun` int(1) DEFAULT NULL,
  `dayCount` int(5) DEFAULT NULL,
  `ping` int(5) DEFAULT NULL,
  `dropOnDeath` int(1) DEFAULT NULL,
  `dropOnQuit` int(1) DEFAULT NULL,
  `bloodMoonEnemycount` int(5) DEFAULT NULL,
  `enemyDifficulty` int(2) DEFAULT NULL,
  `playerKillingMode` int(1) DEFAULT NULL,
  `currentServerTime` int(15) DEFAULT NULL,
  `dayLightLength` int(6) DEFAULT NULL,
  `BlockDurabilityModifier` int(6) DEFAULT NULL,
  `airDropFrequency` int(10) DEFAULT NULL,
  `lootAbundance` int(6) DEFAULT NULL,
  `lootRespawnDays` int(6) DEFAULT NULL,
  `maxSpawnedZombies` int(4) DEFAULT NULL,
  `landClaimSize` int(6) DEFAULT NULL,
  `landClaimDeadZone` int(6) DEFAULT NULL,
  `landClaimExpiryTime` int(6) DEFAULT NULL,
  `landClaimDecayMode` int(6) DEFAULT NULL,
  `landClaimOnlineDurabilityModifier` int(1) DEFAULT NULL,
  `landClaimOfflineDurabilityModifier` int(1) DEFAULT NULL,
  `partySharedKillRange` int(10) DEFAULT NULL,
  `maxSpawnedAnimals` int(3) DEFAULT NULL,
  `serverVisibility` int(1) DEFAULT NULL,
  `isDedicated` varchar(6) DEFAULT NULL,
  `isPasswordProtected` varchar(6) DEFAULT NULL,
  `showFriendPlayerOnMap` varchar(6) DEFAULT NULL,
  `buildCreate` varchar(6) DEFAULT NULL,
  `eacEnabled` varchar(6) DEFAULT NULL,
  `architecture64` varchar(6) DEFAULT NULL,
  `stockSettings` varchar(6) DEFAULT NULL,
  `stockFiles` varchar(6) DEFAULT NULL,
  `requiresMod` varchar(6) DEFAULT NULL,
  `airDropMarker` varchar(6) DEFAULT NULL,
  `enemySpawnMode` varchar(6) DEFAULT NULL,
  `isPublic` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`serverID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `server_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `servers` (
  `serverID` int(11) NOT NULL AUTO_INCREMENT,
  `IP` varchar(16) DEFAULT NULL,
  `telnetPort` int(11) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `isEnabled` int(1) DEFAULT NULL,
  `dateUpdated` datetime DEFAULT NULL,
  `game_version` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`serverID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `shop_items` (
  `id` int(11) NOT NULL,
  `itemName` varchar(45) NOT NULL,
  `cost` int(6) DEFAULT NULL,
  `quantity` int(4) DEFAULT '1',
  `quality` int(4) DEFAULT '0',
  `group` varchar(45) DEFAULT NULL,
  `actions` varchar(45) DEFAULT NULL,
  `timesPurchased` int(11) DEFAULT NULL,
  `isEnabled` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `shop_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `datetime` varchar(30) DEFAULT NULL,
  `steamid` int(15) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `item` varchar(45) DEFAULT NULL,
  `amount` int(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `site_accessLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(65) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `page` varchar(20) DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `site_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `siteLocation` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `site_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `configName` varchar(50) NOT NULL,
  `configValue` varchar(255) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configName_UNIQUE` (`configName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `site_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `site_loginAttempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(65) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `failedAttempts` int(11) DEFAULT '0',
  `lastLogin` datetime DEFAULT NULL,
  `lastFailedAttempt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`username`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `site_users` (
  `id` char(23) NOT NULL,
  `username` varchar(65) NOT NULL DEFAULT '',
  `password` varchar(65) NOT NULL DEFAULT '',
  `email` varchar(65) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `lastLogin` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `timeoutEnabled` tinyint(1) NOT NULL DEFAULT '1',
  `timeout` varchar(65) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `zcoin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` varchar(45) DEFAULT NULL,
  `transaction` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `zcoin_wallet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` varchar(45) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `zcoinAmount` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Begin insert statements
--
INSERT INTO `app_config` (configName, configValue, comment) VALUES
('APP_NAME','7DaysManager',NULL),
('APP_ROOT','/usr/local/etc/7daysManager/',''),
('APP_UID','1001',''),
('APP_GID','1001',NULL),
('APP_PID','7dm.lock','\' . APP_ROOT . \'\' . APP_NAME . \'.lock'),
('APP_LOG','var/log/7daysManager.log','\' . APP_ROOT . \'log/7dm_\' . date(\'Ymd\') .\'.log'),
('API_HOST','**CONFIG-ME**',NULL),
('API_PORT','**CONFIG-ME**',NULL),
('API_USER','**CONFIG-ME**',NULL),
('API_PASS','**CONFIG-ME**',NULL),
('TELNET_HOST','**CONFIG-ME**',NULL),
('TELNET_PORT','**CONFIG-ME**',NULL),
('TELNET_PASS','**CONFIG-ME**',NULL),
('interval_syncGameTime','5','Run interval in seconds'),
('interval_syncOnlinePlayers','10','Run interval in seconds'),
('MAX_RESULT','100',NULL),
('MIN_SLEEP','0',NULL),
('MAX_SLEEP','4',NULL),
('APP_FORK','empty($argv[1]) || \'cli\' != $argv[1])',NULL),
('APP_VERSION','0.3',''),
('APP_LOG_LEVEL','3','0=NO LOGS, 1=Crit, 2=Warn, 3=Info, 4=Debug'),
('interval_syncGameVersion','300','Run interval in seconds'),
('interval_syncServerInfo','120','Run interval in seconds'),
('interval_syncLandclaims','300','Run interval in seconds'),
('interval_syncEntities','10','Run interval in seconds'),
('interval_syncAllPlayers','600','Run interval in seconds'),
('interval_syncGameLog','300','Run interval in seconds'),
('PURGE_APP_LOG_DAYS','4','How long to keep the app log in DAYS.'),
('PURGE_PLAYER_HISTORY_ROWS','150',NULL),
('interval_insertPlayerHistory','30','Run interval in seconds'),
('interval_syncGameChat','1','Run interval in seconds -- Best to leave this one at 1 -- Also reads player commands in-game.'),
('APP_SHORTNAME','7DM','The name of the server that will display in game chat at the beginning of messages and command responses'),
('APP_NAME_COLOR','cc0000','The color of the APP_SHORTNAME'),
('APP_CHAT_COLOR','f2f3f4','Default: f2f3f4 - Color of server chat messages'),
('DISCORD_ENABLED','0','0=Disabled, 1=Enabled'),
('DISCORD_LINK','**CONFIG-ME**','URL to join discord server -- Advertisement'),
('DISCORD_WEBHOOK','**CONFIG-ME**','Webhook URL for Discord channel');
('PUSHBULLET_ENABLED','0','0=Disabled, 1=Enabled'),
('PUSHBULLET_TOKEN','**CONFIG-ME**','URL to join discord server -- Advertisement'),
('PUSHBULLET_CHANNELTAG','**CONFIG-ME**','Pushbullet channel tag'),
('NOFITIFICATION_MASTER_SWITCH','0','Master switch for admin notifications -- 0=Disabled, 1=Enabled');

INSERT INTO `site_config` (configName, configValue, comment) VALUES
('SITE_ROOT','/usr/local/etc/7daysManager/public/',NULL),
('SITE_NAME','7DaysManager',NULL),
('SITE_NAME_SHORT','7DM',NULL),
('SITE_VERSION','0.10.0',NULL),
('HEADER_COLOR','red','Color of site'),
('API_HOST','**CONFIG-ME**',NULL),
('API_PORT','**CONFIG-ME**',NULL),
('API_USER','**CONFIG-ME**',NULL),
('API_PASS','**CONFIG-ME**',NULL),
('API_URL',NULL,NULL),
('MAX_RESULT','100',NULL),
('MIN_SLEEP','0',NULL),
('MAX_SLEEP','4',NULL),
('MAIL_SERVER_TYPE','smtp',NULL),
('SMTP_SERVER',NULL,NULL),
('SMTP_PORT',NULL,'465 for ssl, 587 for tls, 25 for other'),
('SMTP_USER',NULL,NULL),
('SMTP_PW',NULL,NULL),
('SMTP_SECURITY',NULL,'ssl/tls/blank'),
('EMAIL_FROM',NULL,NULL),
('EMAIL_NAME',NULL,NULL),
('EMAIL_ADMIN',NULL,NULL),
('EMAIL_MSG_VERIFY','Click this link to verify your new account!','Verify email message'),
('EMAIL_MSG_ACTIVE','Your new account is now active! Click this link to log in!','Active email message'),
('EMAIL_MSG_THANKS','Thank you for signing up! You will receive an email shortly confirming the verification of your account.',NULL),
('EMAIL_MSG_VERIFIED','Your account has been verified! You may now login at <br><a href=\"\'.$signin_url.\'\">\'.$LOGIN_URL.\'</a>\'',NULL),
('EMAIL_MSG_INVALID','$EMAIL_ADMINl is not a valid email address',NULL),
('LOGIN_ERROR_TIMEOUT','300','Timeout (in seconds) after max attempts are reached'),
('LOGIN_ERROR_ATTEMPTS','5','Maximum Login Attempts'),
('BASE_URL','\'http://\' . $_SERVER[\'SERVER_NAME\']  ',NULL),
('SIGNIN_URL','substr($base_url . $_SERVER[\'PHP_SELF\'], 0, -(6 + strlen(basename($_SERVER[\'PHP_SELF\']))))',''),
('IP_ADDRESS','$_SERVER[\'REMOTE_ADDR\']',NULL),
('LOGIN_ATTEMPTS_TABLE','site_loginAttempts',NULL),
('LOGIN_MEMBERS_TABLE','site_users',NULL),
('APP_VERSION','0.5',NULL),
('APP_LOG_LIMIT','50','Lines that will display on site.'),
('SITE_LOGIN_ATTEMPT_LIMIT','20','Lines that will display on site.'),
('SHOP_LOG_LIMIT','50','Lines that will display on site.'),
('GAME_LOG_LIMIT','100','Lines that will display on site.'),
('SITE_ACCESS_LOG_LIMIT','200',NULL),
('SITE_FQDN','**CONFIG-ME**',NULL),
('TELNET_PORT','**CONFIG-ME**',NULL);
