--
-- Default SQL database for Skies
-- Table prefix: none ("")
--

CREATE TABLE IF NOT EXISTS `language` (
  `langID` int(255) NOT NULL AUTO_INCREMENT,
  `langName` varchar(500) NOT NULL,
  `langTitle` text NOT NULL,
  `langDesc` text NOT NULL,
  PRIMARY KEY (`langID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `language` (`langID`, `langName`, `langTitle`, `langDesc`) VALUES
(1, 'en-UK', 'British English', 'British English'),
(2, 'de-DE', 'Deutsch (Deutschland)', 'Deutsch (Deutschland)');

CREATE TABLE IF NOT EXISTS `language-data` (
  `varID` int(255) NOT NULL AUTO_INCREMENT,
  `varName` varchar(500) NOT NULL,
  `varData` text NOT NULL,
  `langID` int(255) NOT NULL,
  PRIMARY KEY (`varID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

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
(45, 'system.loginform.logout', 'You''re logged in as [[userName]]. [[link-logout]]', 1),
(46, 'system.loginform.logout', 'Du bist als [[userName] eingeloggt. [[link-logout]]', 2);

CREATE TABLE IF NOT EXISTS `nav` (
  `navID` int(255) NOT NULL AUTO_INCREMENT,
  `navTitle` text NOT NULL,
  `navLoginForm` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`navID`),
  KEY `navID` (`navID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `nav` (`navID`, `navTitle`, `navLoginForm`) VALUES
(1, 'Main navigation, horizontal', 0);

CREATE TABLE IF NOT EXISTS `nav-entry` (
  `entryID` int(255) NOT NULL AUTO_INCREMENT,
  `entryOrder` int(255) NOT NULL,
  `entryTitle` text NOT NULL,
  `entryType` int(10) NOT NULL COMMENT '1: pageID, 2: pageName, 3: link',
  `entryLink` int(11) DEFAULT NULL,
  `entryPageID` int(11) DEFAULT NULL,
  `entryPageName` varchar(500) DEFAULT NULL,
  `navID` int(255) NOT NULL,
  PRIMARY KEY (`entryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `nav-entry` (`entryID`, `entryOrder`, `entryTitle`, `entryType`, `entryLink`, `entryPageID`, `entryPageName`, `navID`) VALUES
(1, 1, 'Home', 1, NULL, 1, NULL, 1),
(2, 2, '{{system.page.login.login}}', 2, NULL, NULL, 'login', 1);

CREATE TABLE IF NOT EXISTS `page` (
  `pageID` int(255) NOT NULL AUTO_INCREMENT,
  `pageName` varchar(500) NOT NULL,
  `pageTitle` text NOT NULL,
  `pageType` int(10) NOT NULL,
  `pageFile` text,
  `pageIncFile` text,
  `pageContent` text,
  `pagePHP` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`pageID`),
  KEY `pageID` (`pageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `page` (`pageID`, `pageName`, `pageTitle`, `pageType`, `pageFile`, `pageIncFile`, `pageContent`, `pagePHP`) VALUES
(1, 'home', 'Home', 1, 'home.page.php', NULL, NULL, 1);

CREATE TABLE IF NOT EXISTS `session` (
  `sessionID` varchar(40) NOT NULL,
  `sessionUserID` int(255) NOT NULL,
  `sessionIP` varchar(500) NOT NULL,
  `sessionLastActivity` bigint(255) NOT NULL,
  `sessionLong` tinyint(1) NOT NULL,
  PRIMARY KEY (`sessionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `session` (`sessionID`, `sessionUserID`, `sessionIP`, `sessionLastActivity`, `sessionLong`) VALUES
('0760a6eef47276a2e0fcf9e33ecf0efc21618daf', 0, '::ffff:4d17:fa63', 1344446914, 0),
('079eacadeaf3ec61dfa73963a79ede3bcd5d8687', 0, '::ffff:5b60:a149', 1349203405, 0),
('1a1f76c36132a8b6ade76acd1184d9d67e97249c', 0, '::ffff:5481:8d03', 1345846219, 0),
('218b39c7427c7829352cecb3233de08a288ef604', 0, '::ffff:5c1d:5875', 1345807188, 0),
('23ffc5524339643a0002cbff8346e0558339332d', 0, '::ffff:5481:86ae', 1348999680, 0),
('259f4a16b70300bd2579a3a83507cfe2b47bb4a8', 0, '::ffff:5b60:e82c', 1344461325, 0),
('2d9f5c7cdd047f090a0ac3a834f23a951e8edb1a', 0, '::ffff:bc68:9685', 1344446924, 0),
('2e8cca040577120b1b56603b527a5071a4662eff', 0, '::ffff:d95f:f851', 1349204525, 0),
('3d06bafbf344f97e4c28af9759c28b47b0105222', 0, '::ffff:3e18:fc85', 1345805870, 0),
('3f3db052f6f88a28039602444e642c8d00339878', 0, '::ffff:bc68:93d7', 1344504632, 0),
('749add5be2bfdaf891c33773c31559b537566ed4', 0, '::ffff:5481:8c6b', 1346494787, 0),
('7dc99a0cf1fc1ebf11a1cb189d7f93ee1d62378f', 0, '::ffff:d95f:c4d3', 1344446938, 0),
('7e1e6fb7e6e4f86e5383039b9d4a306db4e610a1', 0, '::ffff:560e:4ca9', 1345805806, 0),
('98f98026a6f2d569c141837a06638345d88cf5fc', 0, '::ffff:5b60:f69d', 1349037421, 0),
('9a53a32f8dd878b478c04f82679740e4a688498b', 0, '::ffff:d95f:c4d3', 1344446932, 0),
('9bdded4afaa815c51dffcf2f876f39dabffbd188', 0, '::ffff:5481:8d03', 1345846061, 0),
('a178ca968fe12bc40ce6cd9735ac318794a9e8e8', 0, '::ffff:d95f:c4d3', 1344429295, 0),
('a45dd8f9ee9ec865d46749538e0b2850c93ba7cd', 0, '::ffff:5481:8390', 1344532208, 0),
('a72314d205805ffce95899f957cf5a8f2a3fea91', 6, '::ffff:5b60:f153', 1349204479, 0),
('a7da1fac467bf8223517780e7694224af059807d', 0, '::ffff:d95f:c4d3', 1344431632, 0),
('b24073cd2f77c6dc0fe68b755b4af55ab75b1f1d', 2, '::ffff:5481:8d03', 1345847581, 0),
('cc0450028fd591c6082d3687e407c2f9df3db010', 0, '::ffff:58c6:b66e', 1349202556, 0),
('d30bfb12afc15ed949970a25b50243c1c76a44d9', 0, '::ffff:5b60:ff2a', 1344588366, 0),
('ef953caf52939938fd49fcd44a5218cae6c2bf0a', 0, '2001:6f8:900:8db8:205b:dea0:57b7:8976', 1344521414, 0),
('f53a453702283fcf8580a045ffc656d6d462d75f', 0, '::ffff:bc68:9e0d', 1349204490, 0),
('fcc40d75917c5a8ea1a3d8879b46ab5a56a05f2d', 0, '::ffff:d95f:f851', 1349204525, 0);

CREATE TABLE IF NOT EXISTS `user` (
  `userID` int(255) NOT NULL AUTO_INCREMENT,
  `userName` text NOT NULL,
  `userMail` text NOT NULL,
  `userSalt` text NOT NULL,
  `userPassword` text NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `user-data` (
  `dataID` int(255) NOT NULL AUTO_INCREMENT,
  `dataFieldID` int(255) NOT NULL,
  `dataUserID` int(255) NOT NULL,
  `dataValue` longtext NOT NULL,
  PRIMARY KEY (`dataID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user-fields` (
  `fieldID` int(255) NOT NULL AUTO_INCREMENT,
  `fieldName` varchar(500) NOT NULL,
  PRIMARY KEY (`fieldID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
