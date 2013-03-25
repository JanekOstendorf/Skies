SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `nav`;
CREATE TABLE IF NOT EXISTS `nav` (
		`navID` int(255) NOT NULL AUTO_INCREMENT,
		`navTitle` text NOT NULL,
		`navLoginForm` tinyint(1) NOT NULL DEFAULT '0',
		PRIMARY KEY (`navID`),
		KEY `navID` (`navID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `nav` (`navID`, `navTitle`, `navLoginForm`) VALUES
(1, 'Main navigation, horizontal', 0);

DROP TABLE IF EXISTS `nav-entry`;
CREATE TABLE IF NOT EXISTS `nav-entry` (
		`entryId` int(255) NOT NULL AUTO_INCREMENT,
		`entryOrder` int(255) NOT NULL,
		`entryTitle` text NOT NULL,
		`entryType` int(10) NOT NULL COMMENT '1: page (Name), 2: link',
		`entryPageName` varchar(500) DEFAULT NULL,
		`entryLink` varchar(500) DEFAULT NULL,
		`navId` int(255) DEFAULT NULL,
		PRIMARY KEY (`entryId`),
		KEY `navID` (`navId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `nav-entry` (`entryId`, `entryOrder`, `entryTitle`, `entryType`, `entryPageName`, `entryLink`, `navId`) VALUES
(1, 1, 'Home', 1, 'home', NULL, 1),
(2, 2, 'Login', 1, 'login', NULL, 1);

DROP TABLE IF EXISTS `page`;
CREATE TABLE IF NOT EXISTS `page` (
		`pageID` int(255) NOT NULL AUTO_INCREMENT,
		`pageName` varchar(500) NOT NULL,
		`pageTitle` text NOT NULL,
		`pageClass` varchar(500) NOT NULL,
		PRIMARY KEY (`pageID`),
		KEY `pageID` (`pageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `page` (`pageID`, `pageName`, `pageTitle`, `pageClass`) VALUES
(1, 'home', 'Home', 'HomePage'),
(2, 'Login', 'Login', 'LoginPage');

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
		`sessionId` varchar(40) NOT NULL DEFAULT '',
		`sessionUserId` int(11) DEFAULT NULL,
		`sessionIp` text,
		`sessionLastActivity` bigint(255) NOT NULL,
		`sessionLong` tinyint(1) NOT NULL,
		PRIMARY KEY (`sessionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
		`userId` int(255) NOT NULL AUTO_INCREMENT,
		`userName` text NOT NULL,
		`userMail` text NOT NULL,
		`userPassword` text NOT NULL,
		`userLastActivity` bigint(255) NOT NULL,
		PRIMARY KEY (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `user` (`userId`, `userName`, `userMail`, `userPassword`, `userLastActivity`) VALUES
(1, 'test', 'test@test.com', '$2a$08$QILBz4OkxKnrPvZV8Ma2EOvy278He1df2FNnVBmYma/KYygkHYN9C', 1364224325);

DROP TABLE IF EXISTS `user-data`;
CREATE TABLE IF NOT EXISTS `user-data` (
		`dataID` int(255) NOT NULL AUTO_INCREMENT,
		`dataFieldID` int(255) NOT NULL,
		`dataUserID` int(255) NOT NULL,
		`dataValue` longtext NOT NULL,
		PRIMARY KEY (`dataID`),
		KEY `dataUserID` (`dataUserID`),
		KEY `dataFieldID` (`dataFieldID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `user-data` (`dataID`, `dataFieldID`, `dataUserID`, `dataValue`) VALUES
(1, 1, 1, 'de-DE');

DROP TABLE IF EXISTS `user-fields`;
CREATE TABLE IF NOT EXISTS `user-fields` (
		`fieldId` int(11) NOT NULL DEFAULT '0',
		`fieldName` varchar(500) NOT NULL,
		PRIMARY KEY (`fieldId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user-fields` (`fieldId`, `fieldName`) VALUES
(1, 'language');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
