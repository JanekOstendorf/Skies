-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 27. Jan 2013 um 18:40
-- Server Version: 5.1.63-0+squeeze1
-- PHP-Version: 5.4.10-1~dotdeb.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `ozzysql6`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `articleID` int(255) NOT NULL AUTO_INCREMENT,
  `articleTitle` longtext NOT NULL,
  `articleAuthorID` int(255) NOT NULL,
  `articleTime` bigint(255) NOT NULL,
  `articleBlogID` int(255) NOT NULL,
  `articleText` longtext NOT NULL,
  PRIMARY KEY (`articleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `langID` int(255) NOT NULL AUTO_INCREMENT,
  `langName` varchar(500) NOT NULL,
  `langTitle` text NOT NULL,
  `langDesc` text NOT NULL,
  PRIMARY KEY (`langID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `language`
--

INSERT INTO `language` (langId, `langName`, `langTitle`, `langDesc`) VALUES
(1, 'en-UK', 'British English', 'British English'),
(2, 'de-DE', 'Deutsch (Deutschland)', 'Deutsch (Deutschland)');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `language-model`
--

CREATE TABLE IF NOT EXISTS `language-data` (
  `varID` int(255) NOT NULL AUTO_INCREMENT,
  `varName` varchar(500) NOT NULL,
  `varData` text NOT NULL,
  `langID` int(255) NOT NULL,
  PRIMARY KEY (`varID`),
  KEY `langID` (`langID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Daten für Tabelle `language-model`
--

INSERT INTO `language-data` (`varID`, `varName`, `varData`, `langID`) VALUES
(1, 'system.page.login.title', 'Login', 1),
(2, 'system.page.login.title', 'Anmeldung', 2),
(3, 'system.page.login.introduction', 'Welcome to the login and sign up page of {{config.meta.title}}!\n\nOn the left side you can sign in using your user name and password. \nOn the right side you can sign up for a new account. Simply key your desired user name as well as your password in and you''re done.', 1),
(4, 'system.page.login.introduction', 'Willkommen auf der Login- und Registrierungs-Seite von {{config.meta.title}}!\r\n\r\nAuf der linken Seite kannst du dich mit deinem Benutzernamen und deinem Passwort anmelden.\r\nAuf der rechten Seite kannst du einen neuen Account registrieren. Gib einfach den gewünschten Benutzernamen und dein Passwort ein.', 2),
(5, 'system.page.login.login', 'Login', 1),
(6, 'system.page.login.login', 'Anmelden', 2),
(7, 'system.page.login.sign-up', 'Sign up', 1),
(8, 'system.page.login.sign-up', 'Registrieren', 2),
(9, 'system.page.login.username', 'User name', 1),
(10, 'system.page.login.username', 'Benutzername', 2),
(11, 'system.page.login.password', 'Password', 1),
(12, 'system.page.login.password', 'Passwort', 2),
(13, 'system.page.login.register.password-twice', 'Password (twice)', 1),
(14, 'system.page.login.register.password-twice', 'Passwort (zweimal)', 2),
(15, 'system.page.login.welcome-title', 'Howdy, [[userName]]!', 1),
(16, 'system.page.login.welcome-title', 'Howdy, [[userName]]!', 2),
(17, 'system.page.login.logout', 'Logout', 1),
(18, 'system.page.login.logout', 'Abmelden', 2),
(19, 'system.page.login.error.user-pw', 'This combination of username and password does not work. Try again.', 1),
(20, 'system.page.login.error.user-pw', 'Diese Kombination aus Benutzername und Passwort funktioniert nicht. Probier''s nochmal.', 2),
(21, 'system.page.login.login.success', 'You have successfully logged in as [[userName]]!', 1),
(22, 'system.page.login.login.success', 'Du wurdest erfolgreich als [[userName]] angemeldet!', 2),
(23, 'system.page.login.register.mail', 'Mail', 1),
(24, 'system.page.login.register.mail', 'E-Mail', 2),
(25, 'system.page.login.logout.success', 'You have successfully logged out.', 1),
(26, 'system.page.login.logout.success', 'Du wurdest erfolgreich ausgeloggt.', 2),
(27, 'system.page.login.sign-up.error.missing', 'Not all required fields are filled.', 1),
(28, 'system.page.login.sign-up.error.missing', 'Nicht alle erforderlichen Felder sind ausgefüllt.', 2),
(29, 'system.page.login.sign-up.error.pattern', 'The data does not match the required pattern.', 1),
(30, 'system.page.login.sign-up.error.pattern', 'Die eingegebenen Daten haben nicht das vorgegebene Format.', 2),
(31, 'system.page.login.sign-up.error.username-taken', 'This usename is already in use.', 1),
(32, 'system.page.login.sign-up.error.username-taken', 'Dieser Benutzername ist schon vergeben.', 2),
(33, 'system.page.login.sign-up.error.username-pattern', 'The given username is not valid.', 1),
(34, 'system.page.login.sign-up.error.username-pattern', 'Der eingebene Benutzername ist nicht gültig.', 2),
(35, 'system.page.login.sign-up.error.mail-pattern', 'The given mail address is not valid.', 1),
(36, 'system.page.login.sign-up.error.mail-pattern', 'Die eingegebene Mail-Adresse ist nicht gültig.', 2),
(37, 'system.page.login.sign-up.error.passwords-match', 'The entered passwords do not match.', 1),
(38, 'system.page.login.sign-up.error.passwords-match', 'Die eingegebenen Passwörter stimmen nicht überein.', 2),
(39, 'system.page.login.sign-up.success', 'Congratulations! You have successfully registered the account [[userName]]!', 1),
(40, 'system.page.login.sign-up.success', 'Glückwunsch! Du hast den Account [[userName]] erfolgreich registriert!', 2),
(41, 'system.page.login.sign-up.error', 'Error while creating the account. :(', 1),
(42, 'system.page.login.sign-up.error', 'Fehler beim Erstellen des Accounts. :(', 2),
(43, 'system.loginform.login', 'You''re not logged in. [[link-login]]', 1),
(44, 'system.loginform.login', 'Du bist nicht eingeloggt. [[link-login]]', 2),
(45, 'system.loginform.logout', 'You''re logged in as <a href="{{SUBDIR}}/login">[[userName]]</a>. [[link-logout]]', 1),
(46, 'system.loginform.logout', 'Du bist als <a href="{{SUBDIR}}/login">[[userName]]</a> eingeloggt. [[link-logout]]', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nav`
--

CREATE TABLE IF NOT EXISTS `nav` (
  `navID` int(255) NOT NULL AUTO_INCREMENT,
  `navTitle` text NOT NULL,
  `navLoginForm` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`navID`),
  KEY `navID` (`navID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `nav`
--

INSERT INTO `nav` (`navID`, `navTitle`, `navLoginForm`) VALUES
(1, 'Main navigation, horizontal', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nav-entry`
--

CREATE TABLE IF NOT EXISTS `nav-entry` (
  `entryID` int(255) NOT NULL AUTO_INCREMENT,
  `entryOrder` int(255) NOT NULL,
  `entryTitle` text NOT NULL,
  `entryType` int(10) NOT NULL COMMENT '1: pageID, 2: pageName, 3: link',
  `entryLink` int(11) DEFAULT NULL,
  `entryPageID` int(11) DEFAULT NULL,
  `entryPageName` varchar(500) DEFAULT NULL,
  `navID` int(255) DEFAULT NULL,
  `entryNeedAdmin` tinyint(1) NOT NULL,
  `entryNeedLeader` tinyint(1) NOT NULL,
  `entryNeedLogin` tinyint(1) NOT NULL,
  PRIMARY KEY (`entryID`),
  KEY `entryPageID` (`entryPageID`),
  KEY `navID` (`navID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Daten für Tabelle `nav-entry`
--

INSERT INTO `nav-entry` (`entryID`, `entryOrder`, `entryTitle`, `entryType`, `entryLink`, `entryPageID`, `entryPageName`, `navID`, `entryNeedAdmin`, `entryNeedLeader`, `entryNeedLogin`) VALUES
(1, 1, 'Home', 1, NULL, 1, NULL, 1, 0, 0, 0),
(2, 10, '{{system.page.login.login}}', 2, NULL, NULL, 'login', NULL, 0, 0, 0),
(8, 2, 'Infos', 1, NULL, 9, NULL, 1, 0, 0, 1),
(9, 3, 'Regeln', 1, NULL, 10, NULL, 1, 0, 0, 1),
(10, 4, 'Bezahlung', 1, NULL, 8, NULL, 1, 0, 0, 1),
(11, 5, 'Zusagen', 1, NULL, 11, NULL, 1, 0, 0, 1),
(12, 6, 'Admin', 1, NULL, 7, NULL, 1, 1, 0, 1),
(13, 7, 'Leader', 1, NULL, 13, NULL, 1, 0, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `pageID` int(255) NOT NULL AUTO_INCREMENT,
  `pageName` varchar(500) NOT NULL,
  `pageTitle` text NOT NULL,
  `pageType` int(10) NOT NULL,
  `pageFile` text,
  `pageIncFile` text,
  `pageContent` text,
  `pagePHP` tinyint(1) DEFAULT NULL,
  `pageNeedAdmin` tinyint(1) NOT NULL,
  `pageNeedLeader` tinyint(1) NOT NULL,
  `pageNeedLogin` tinyint(1) NOT NULL,
  PRIMARY KEY (`pageID`),
  KEY `pageID` (`pageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Daten für Tabelle `page`
--

INSERT INTO `page` (`pageID`, `pageName`, `pageTitle`, `pageType`, `pageFile`, `pageIncFile`, `pageContent`, `pagePHP`, `pageNeedAdmin`, `pageNeedLeader`, `pageNeedLogin`) VALUES
(1, 'home', 'Home', 1, 'home.page.php', 'home.page.inc.php', NULL, 1, 0, 0, 0),
(7, 'admin', 'Admin', 1, 'admin.page.php', 'admin.inc.page.php', NULL, 1, 1, 0, 1),
(9, 'infos', 'Infos', 1, 'infos.page.php', NULL, NULL, 1, 0, 0, 1),
(11, 'zusagen', 'Zusagen', 1, 'zusagen.page.php', 'zusagen.inc.page.php', NULL, 1, 0, 0, 0),
(12, 'mail', 'Mail', 1, 'mail.page.php', 'mail.inc.page.php', '', 1, 1, 0, 1),
(13, 'leader', 'Leader', 1, 'leader.page.php', 'leader.inc.page.php', NULL, 1, 0, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `sessionID` varchar(40) NOT NULL,
  `sessionUserID` int(255) DEFAULT NULL,
  `sessionIP` varchar(500) NOT NULL,
  `sessionLastActivity` bigint(255) NOT NULL,
  `sessionLong` tinyint(1) NOT NULL,
  PRIMARY KEY (`sessionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `session`
--

INSERT INTO `session` (sessionId, sessionUserId, sessionIp, `sessionLastActivity`, `sessionLong`) VALUES
('e160cac11b7e61875f7777d5b0aa3d655180e0eb', 113, '::ffff:5b60:ab42', 1359312029, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userID` int(255) NOT NULL AUTO_INCREMENT,
  `userName` text NOT NULL,
  `userMail` text NOT NULL,
  `userSalt` text NOT NULL,
  `userPassword` text NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=118 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`userID`, `userName`, `userMail`, `userSalt`, `userPassword`) VALUES
(113, 'Janek Ostendorf', 'janek@der-lan.de', 'efb82997b8e6f523e337c2f0cb5b2e05', 'c09a205beb11d8d47da91a962d363cd3'),
(116, 'Florian Thie', 'florian@der-lan.de', '', ''),
(117, 'Janek Testostendorf', 'ozzy2345de+test@gmail.com', '', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user-model`
--

CREATE TABLE IF NOT EXISTS `user-data` (
  `dataID` int(255) NOT NULL AUTO_INCREMENT,
  `dataFieldID` int(255) NOT NULL,
  `dataUserID` int(255) NOT NULL,
  `dataValue` longtext NOT NULL,
  PRIMARY KEY (`dataID`),
  KEY `dataUserID` (`dataUserID`),
  KEY `dataFieldID` (`dataFieldID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=368 ;

--
-- Daten für Tabelle `user-model`
--

INSERT INTO `user-data` (`dataID`, `dataFieldID`, `dataUserID`, `dataValue`) VALUES
(332, 13, 113, '666888ce658533ee9357ca0719a6c58a470e7436'),
(341, 14, 113, '1359312029'),
(342, 15, 113, 'overview'),
(343, 16, 113, '1356961983'),
(344, 18, 113, 'Champignons, Salami'),
(345, 19, 113, '5'),
(346, 20, 113, '1'),
(347, 21, 113, '1'),
(360, 13, 116, '3b9fd443f5ea6812adeced9668e96bd22f489e1f'),
(361, 21, 116, '1'),
(362, 22, 116, ''),
(363, 13, 117, 'af18f5b67b44c44500039ad564ba5ea219e2cc79'),
(364, 21, 117, ''),
(365, 22, 117, ''),
(366, 14, 117, '1359311996'),
(367, 15, 117, 'overview');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user-fields`
--

CREATE TABLE IF NOT EXISTS `user-fields` (
  `fieldID` int(255) NOT NULL AUTO_INCREMENT,
  `fieldName` varchar(500) NOT NULL,
  PRIMARY KEY (`fieldID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Daten für Tabelle `user-fields`
--

INSERT INTO `user-fields` (`fieldID`, `fieldName`) VALUES
(13, 'regToken'),
(14, 'lastActivity'),
(15, 'acceptPart'),
(16, 'acceptTime'),
(17, 'accept'),
(18, 'pizza'),
(19, 'plugs'),
(20, 'ethernet'),
(21, 'isAdmin'),
(22, 'isLeader');
