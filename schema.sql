-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.17-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.1.0.6116
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for forumboard
CREATE DATABASE IF NOT EXISTS `forumboard` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `forumboard`;

-- Dumping structure for table forumboard.accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `login_token` text NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `primary_role` int(11) DEFAULT NULL,
  `join_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_accounts_accounts_roles` (`primary_role`),
  CONSTRAINT `FK_accounts_accounts_roles` FOREIGN KEY (`primary_role`) REFERENCES `accounts_roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.accounts: ~0 rows (approximately)
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;

-- Dumping structure for table forumboard.accounts_roles
CREATE TABLE IF NOT EXISTS `accounts_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_accounts_roles_accounts` (`account_id`),
  KEY `FK_accounts_roles_roles` (`role_id`),
  CONSTRAINT `FK_accounts_roles_accounts` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  CONSTRAINT `FK_accounts_roles_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.accounts_roles: ~0 rows (approximately)
/*!40000 ALTER TABLE `accounts_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `accounts_roles` ENABLE KEYS */;

-- Dumping structure for table forumboard.javascript
CREATE TABLE IF NOT EXISTS `javascript` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app` varchar(50) DEFAULT NULL,
  `java` varchar(50) DEFAULT NULL,
  `global` tinyint(4) NOT NULL DEFAULT 0,
  `link` tinyint(4) NOT NULL DEFAULT 0,
  `script` text NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.javascript: ~1 rows (approximately)
/*!40000 ALTER TABLE `javascript` DISABLE KEYS */;
INSERT INTO `javascript` (`id`, `app`, `java`, `global`, `link`, `script`) VALUES
	(1, 'jquery', 'core', 1, 1, 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js'),
	(3, 'jquery', 'modal', 1, 1, 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js');
/*!40000 ALTER TABLE `javascript` ENABLE KEYS */;

-- Dumping structure for table forumboard.login_account_links
CREATE TABLE IF NOT EXISTS `login_account_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `handler_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `token` text NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `FK__login_handlers` (`handler_id`),
  KEY `FK_login_account_links_login_accounts` (`account_id`),
  CONSTRAINT `FK__login_handlers` FOREIGN KEY (`handler_id`) REFERENCES `login_handlers` (`id`),
  CONSTRAINT `FK_login_account_links_login_accounts` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.login_account_links: ~0 rows (approximately)
/*!40000 ALTER TABLE `login_account_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_account_links` ENABLE KEYS */;

-- Dumping structure for table forumboard.login_devices
CREATE TABLE IF NOT EXISTS `login_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `device_token` text NOT NULL,
  `login_token` text NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_login_devices_login_accounts` (`account_id`) USING BTREE,
  CONSTRAINT `FK_login_devices_login_accounts` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.login_devices: ~0 rows (approximately)
/*!40000 ALTER TABLE `login_devices` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_devices` ENABLE KEYS */;

-- Dumping structure for table forumboard.login_handlers
CREATE TABLE IF NOT EXISTS `login_handlers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `handler` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `active` tinyint(4) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.login_handlers: ~2 rows (approximately)
/*!40000 ALTER TABLE `login_handlers` DISABLE KEYS */;
INSERT INTO `login_handlers` (`id`, `handler`, `name`, `active`) VALUES
	(1, 'Steam', 'Steam', 1),
	(2, 'Google', 'Google', 0);
/*!40000 ALTER TABLE `login_handlers` ENABLE KEYS */;

-- Dumping structure for table forumboard.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) NOT NULL,
  `app` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.permissions: ~0 rows (approximately)
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` (`id`, `tag`, `app`) VALUES
	(1, 'access_admin', 'core');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;

-- Dumping structure for table forumboard.permissions_roles
CREATE TABLE IF NOT EXISTS `permissions_roles` (
  `role_id` int(11) DEFAULT NULL,
  `permission_id` int(11) DEFAULT NULL,
  KEY `FK_permissions_roles_permissions` (`permission_id`),
  KEY `FK_permissions_roles_roles` (`role_id`),
  CONSTRAINT `FK_permissions_roles_permissions` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  CONSTRAINT `FK_permissions_roles_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.permissions_roles: ~1 rows (approximately)
/*!40000 ALTER TABLE `permissions_roles` DISABLE KEYS */;
INSERT INTO `permissions_roles` (`role_id`, `permission_id`) VALUES
	(2, 1);
/*!40000 ALTER TABLE `permissions_roles` ENABLE KEYS */;

-- Dumping structure for table forumboard.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.roles: ~2 rows (approximately)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`, `active`) VALUES
	(1, 'Member', 1),
	(2, 'Admin', 1);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Dumping structure for table forumboard.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.settings: ~5 rows (approximately)
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` (`id`, `app`, `name`, `value`) VALUES
	(1, '', 'default-app', 'core'),
	(2, '', 'default-theme', '1'),
	(3, 'core', 'site-name', 'Forum Board'),
	(4, 'core', 'steam-api-key', ''),
	(5, 'core', 'default-role', '1');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;

-- Dumping structure for table forumboard.themes
CREATE TABLE IF NOT EXISTS `themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.themes: ~0 rows (approximately)
/*!40000 ALTER TABLE `themes` DISABLE KEYS */;
INSERT INTO `themes` (`id`, `name`, `version`, `author`) VALUES
	(1, 'Default', '1.0.0', 'Jack "Scarso" Farhall');
/*!40000 ALTER TABLE `themes` ENABLE KEYS */;

-- Dumping structure for table forumboard.themes_css
CREATE TABLE IF NOT EXISTS `themes_css` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) DEFAULT NULL,
  `app` varchar(50) NOT NULL DEFAULT '',
  `view` varchar(50) NOT NULL,
  `content` text DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_themes_css_themes` (`theme_id`) USING BTREE,
  CONSTRAINT `FK_themes_css_themes` FOREIGN KEY (`theme_id`) REFERENCES `themes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.themes_css: ~11 rows (approximately)
/*!40000 ALTER TABLE `themes_css` DISABLE KEYS */;
INSERT INTO `themes_css` (`id`, `theme_id`, `app`, `view`, `content`) VALUES
	(1, 1, 'core', 'core', 'body {\r\n    margin: 0;\r\n    color: #c9d1d9;\r\n    font-family: BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif, Apple Color Emoji, Segoe UI Emoji;\r\n    background-color: #282828;\r\n}\r\n\r\np {\r\n    margin: 0;\r\n}\r\n\r\na {\r\n    color: inherit;\r\n    text-decoration: none;\r\n}\r\n\r\na:hover {\r\n    opacity: 0.75;\r\n}\r\n\r\nmain.body {\r\n    min-height: calc(100vh - 53px); /* Height of browser view - navbar */\r\n}\r\n\r\n.container {\r\n    max-width: 1320px;\r\n    margin-left: auto;\r\n    margin-right: auto;\r\n}\r\n\r\n.centered-container {\r\n    display: flex;\r\n    align-items: center;\r\n    justify-content: center;\r\n}\r\n\r\n.rounded-box {\r\n    padding: 12px;\r\n    border-radius: 12px;\r\n    overflow: hidden;\r\n    background-color:#333333;\r\n}\r\n\r\n.rounded-box:not(:first-child) {\r\n    margin-top: 12px;\r\n}\r\n\r\n.rounded-box h2, .rounded-box h3 {\r\n    margin-bottom: 12px;\r\n}\r\n\r\n.rounded-box .header-actions {\r\n    display: flex;\r\n    flex-direction: row;\r\n    align-items: center;\r\n}\r\n\r\n.rounded-box .header-actions > div {\r\n    margin-left: auto;\r\n}'),
	(2, 1, 'settings', 'main', '#settingsBody {\r\n    display: grid;\r\n    grid-template-columns: 25% 75%;\r\n}\r\n\r\n#settingsBody > div {\r\n    margin-left: 12px;\r\n}'),
	(3, 1, 'settings', 'intergrations', '.linked-accounts > div > div > h3 {\r\n    margin: 0;\r\n}'),
	(4, 1, 'admin', 'core', 'h1, h2, h3, h4, h5 {\r\n    margin: 0;\r\n}\r\n\r\n.cols {\r\n    display: flex;\r\n}\r\n\r\n.cols > .sidebar {\r\n    max-width: 200px;\r\n    background-color: #2e2e2e;\r\n}\r\n\r\n.cols > .sidebar.secondary {\r\n    padding: 16px;\r\n    background-color: #383838;\r\n}\r\n\r\n.cols > main {\r\n    width: 100%;\r\n    padding: 16px;\r\n}\r\n\r\n.cols > .sidebar.secondary > div:not(:first-child) {\r\n   margin-top: 12px;\r\n}\r\n\r\n.cols > .sidebar.secondary > div > h3 {\r\n    font-size: 15px;\r\n    padding-bottom: 6px;\r\n}\r\n\r\n.cols > .sidebar a {\r\n    display: block;\r\n    padding: 16px;\r\n    text-align: center;\r\n}\r\n\r\n.cols > .sidebar a.active {\r\n    color: white;\r\n    background: #383838;\r\n}\r\n\r\n.cols > .sidebar.secondary a {\r\n    font-size: 15px;\r\n    padding: 0;\r\n    text-align: left;\r\n}'),
	(5, 1, 'core', 'navigation', 'nav.main {\r\n    width: 100%;\r\n    background-color:#333333;\r\n}\r\n\r\nnav.main.no-container {\r\n    display: flex;\r\n}\r\n\r\nnav.main > div.container {\r\n    display: flex;\r\n}\r\n\r\nnav.main a {\r\n    display: inline-block;\r\n    padding: 16px;\r\n}\r\n\r\nnav.main .profile {\r\n    margin-left: auto;\r\n}\r\n\r\nul.tab-links {\r\n    list-style: none;\r\n    padding: 0;\r\n}\r\n\r\nul.tab-links li {\r\n    transition: 0.2s;\r\n}\r\n\r\nul.tab-links li a {\r\n    display: block;\r\n    padding: 6px;\r\n}\r\n\r\nul.tab-links li.active, ul.tab-links li:hover {\r\n    color: white;\r\n}\r\n\r\nul.tab-links li.active {\r\n    background-color: #0f0f0f;\r\n}'),
	(6, 1, 'core', 'table', 'table {\r\n    width: 100%;\r\n    border-spacing: 0;\r\n}\r\n\r\ntable th {\r\n    padding: 12px;\r\n    text-align: left;\r\n}\r\n\r\ntable tr > td {\r\n    padding: 12px;\r\n    background-color: rgba(15, 15, 15, 0.65);\r\n}\r\n\r\ntable tr > td > a:hover {\r\n    color: white;\r\n}\r\n\r\ntable tr:nth-child(even) > td {\r\n    background-color: rgba(15, 15, 15, 0.5);\r\n}\r\n\r\ntable tr > td.button {\r\n    width: 0%;\r\n    padding: 0;\r\n}\r\n\r\ntable tr > td.button > a {\r\n    display: block;\r\n    padding: 12px;\r\n}\r\n\r\ntable tr > td.button.generic-action {\r\n    color: #e4eaf0;\r\n    background-color: #073763;\r\n}\r\n\r\ntable tr > td.button.negative-action {\r\n    color: #e4eaf0;\r\n    background-color: #660000;\r\n}'),
	(7, 1, 'core', 'profile', 'header {\r\n    position: relative;\r\n    height: 250px;\r\n    background-color: black;\r\n    overflow: hidden;\r\n}\r\n\r\nheader img.banner {\r\n    position: absolute;\r\n    width: 100%;\r\n    height: auto;\r\n}\r\n\r\nheader .user-info {\r\n    position: relative;\r\n    display: flex;\r\n}\r\n\r\nheader .user-info img {\r\n    height: 150px;\r\n    width: auto;\r\n    margin: 50px 0;\r\n    margin-right: 25px;\r\n    border-radius: 10px;\r\n}\r\n\r\nheader .user-info .name-block {\r\n    display: flex;\r\n    flex-direction: column;\r\n    justify-content: center;\r\n    margin: 0;\r\n}\r\n\r\nheader .user-info .name-block h3 {\r\n    font-size: 44px;\r\n    font-weight: 600;\r\n    margin: 0;\r\n}'),
	(8, 1, 'core', 'columns', '.columns {\r\n    display: flex;\r\n}\r\n\r\n.columns .col {\r\n    padding: 5px;\r\n}\r\n\r\n.columns .col.s {\r\n    width: 20%;\r\n}\r\n\r\n.columns .col.m {\r\n    width: 40%;\r\n}\r\n\r\n.columns .col.l {\r\n    width: 60%;\r\n}'),
	(9, 1, 'core', 'member', '.min-banner {\r\n    width: 100%;\r\n    height: 124px;\r\n}\r\n\r\n.role-badge {\r\n    padding: 8px;\r\n    margin-top: 6px;\r\n    border-radius: 8px;\r\n    background-color: black;\r\n}\r\n\r\n.role-badge > span {\r\n    opacity: 0.6;\r\n    font-size: 12px;\r\n}\r\n\r\n.role-badge.first {\r\n    margin-top: 12px;\r\n}'),
	(10, 1, 'core', 'lists', '.flex-list {\r\n    display: flex;\r\n    flex-direction: column;\r\n    justify-content: space-around;\r\n}\r\n\r\n.flex-list .first {\r\n    order: 0;\r\n}\r\n\r\n.flex-list .second {\r\n    order: 1;\r\n}\r\n\r\n.flex-list .third {\r\n    order: 2;\r\n}'),
	(11, 1, 'core', 'forms', 'label {\r\n    display: block;\r\n}\r\n\r\nlabel.inline {\r\n    display: inline-block;\r\n}\r\n\r\nbutton {\r\n    display: block;\r\n}'),
	(12, 1, 'core', 'modals', '.blocker {\r\n  position: fixed;\r\n  top: 0; right: 0; bottom: 0; left: 0;\r\n  width: 100%; height: 100%;\r\n  overflow: auto;\r\n  z-index: 1;\r\n  padding: 20px;\r\n  box-sizing: border-box;\r\n  background-color: rgb(0,0,0);\r\n  background-color: rgba(0,0,0,0.75);\r\n  text-align: center;\r\n}\r\n.blocker:before{\r\n  content: "";\r\n  display: inline-block;\r\n  height: 100%;\r\n  vertical-align: middle;\r\n  margin-right: -0.05em;\r\n}\r\n.blocker.behind {\r\n  background-color: transparent;\r\n}\r\n.modal {\r\n  display: none;\r\n  vertical-align: middle;\r\n  position: relative;\r\n  z-index: 2;\r\n  max-width: 500px;\r\n  box-sizing: border-box;\r\n  width: 90%;\r\n  background: #333333;\r\n  padding: 15px 30px;\r\n  -webkit-border-radius: 8px;\r\n  -moz-border-radius: 8px;\r\n  -o-border-radius: 8px;\r\n  -ms-border-radius: 8px;\r\n  border-radius: 8px;\r\n  -webkit-box-shadow: 0 0 10px #000;\r\n  -moz-box-shadow: 0 0 10px #000;\r\n  -o-box-shadow: 0 0 10px #000;\r\n  -ms-box-shadow: 0 0 10px #000;\r\n  box-shadow: 0 0 10px #000;\r\n  text-align: left;\r\n}\r\n\r\n.modal a.close-modal {\r\n  position: absolute;\r\n  top: -12.5px;\r\n  right: -12.5px;\r\n  display: block;\r\n  width: 30px;\r\n  height: 30px;\r\n  text-indent: -9999px;\r\n  background-size: contain;\r\n  background-repeat: no-repeat;\r\n  background-position: center center;\r\n  background-image: url(\'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAAAXNSR0IArs4c6QAAA3hJREFUaAXlm8+K00Acx7MiCIJH/yw+gA9g25O49SL4AO3Bp1jw5NvktC+wF88qevK4BU97EmzxUBCEolK/n5gp3W6TTJPfpNPNF37MNsl85/vN/DaTmU6PknC4K+pniqeKJ3k8UnkvDxXJzzy+q/yaxxeVHxW/FNHjgRSeKt4rFoplzaAuHHDBGR2eS9G54reirsmienDCTRt7xwsp+KAoEmt9nLaGitZxrBbPFNaGfPloGw2t4JVamSt8xYW6Dg1oCYo3Yv+rCGViV160oMkcd8SYKnYV1Nb1aEOjCe6L5ZOiLfF120EjWhuBu3YIZt1NQmujnk5F4MgOpURzLfAwOBSTmzp3fpDxuI/pabxpqOoz2r2HLAb0GMbZKlNV5/Hg9XJypguryA7lPF5KMdTZQzHjqxNPhWhzIuAruOl1eNqKEx1tSh5rfbxdw7mOxCq4qS68ZTjKS1YVvilu559vWvFHhh4rZrdyZ69Vmpgdj8fJbDZLJpNJ0uv1cnr/gjrUhQMuI+ANjyuwftQ0bbL6Erp0mM/ny8Fg4M3LtdRxgMtKl3jwmIHVxYXChFy94/Rmpa/pTbNUhstKV+4Rr8lLQ9KlUvJKLyG8yvQ2s9SBy1Jb7jV5a0yapfF6apaZLjLLcWtd4sNrmJUMHyM+1xibTjH82Zh01TNlhsrOhdKTe00uAzZQmN6+KW+sDa/JD2PSVQ873m29yf+1Q9VDzfEYlHi1G5LKBBWZbtEsHbFwb1oYDwr1ZiF/2bnCSg1OBE/pfr9/bWx26UxJL3ONPISOLKUvQza0LZUxSKyjpdTGa/vDEr25rddbMM0Q3O6Lx3rqFvU+x6UrRKQY7tyrZecmD9FODy8uLizTmilwNj0kraNcAJhOp5aGVwsAGD5VmJBrWWbJSgWT9zrzWepQF47RaGSiKfeGx6Szi3gzmX/HHbihwBser4B9UJYpFBNX4R6vTn3VQnez0SymnrHQMsRYGTr1dSk34ljRqS/EMd2pLQ8YBp3a1PLfcqCpo8gtHkZFHKkTX6fs3MY0blKnth66rKCnU0VRGu37ONrQaA4eZDFtWAu2fXj9zjFkxTBOo8F7t926gTp/83Kyzzcy2kZD6xiqxTYnHLRFm3vHiRSwNSjkz3hoIzo8lCKWUlg/YtGs7tObunDAZfpDLbfEI15zsEIY3U/x/gHHc/G1zltnAgAAAABJRU5ErkJggg==\');\r\n\r\n}\r\n\r\n.modal-spinner {\r\n  display: none;\r\n  position: fixed;\r\n  top: 50%;\r\n  left: 50%;\r\n  transform: translateY(-50%) translateX(-50%);\r\n  padding: 12px 16px;\r\n  border-radius: 5px;\r\n  background-color: #111;\r\n  height: 20px;\r\n}\r\n\r\n.modal-spinner > div {\r\n  border-radius: 100px;\r\n  background-color: #333333;\r\n  height: 20px;\r\n  width: 2px;\r\n  margin: 0 1px;\r\n  display: inline-block;\r\n\r\n  -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;\r\n  animation: sk-stretchdelay 1.2s infinite ease-in-out;\r\n}\r\n\r\n.modal-spinner .rect2 {\r\n  -webkit-animation-delay: -1.1s;\r\n  animation-delay: -1.1s;\r\n}\r\n\r\n.modal-spinner .rect3 {\r\n  -webkit-animation-delay: -1.0s;\r\n  animation-delay: -1.0s;\r\n}\r\n\r\n.modal-spinner .rect4 {\r\n  -webkit-animation-delay: -0.9s;\r\n  animation-delay: -0.9s;\r\n}\r\n\r\n@-webkit-keyframes sk-stretchdelay {\r\n  0%, 40%, 100% { -webkit-transform: scaleY(0.5) }\r\n  20% { -webkit-transform: scaleY(1.0) }\r\n}\r\n\r\n@keyframes sk-stretchdelay {\r\n  0%, 40%, 100% {\r\n    transform: scaleY(0.5);\r\n    -webkit-transform: scaleY(0.5);\r\n  }  20% {\r\n    transform: scaleY(1.0);\r\n    -webkit-transform: scaleY(1.0);\r\n  }\r\n}');
/*!40000 ALTER TABLE `themes_css` ENABLE KEYS */;

-- Dumping structure for table forumboard.themes_views
CREATE TABLE IF NOT EXISTS `themes_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) DEFAULT NULL,
  `app` varchar(50) NOT NULL DEFAULT '',
  `view` varchar(50) NOT NULL,
  `content` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_themes_views_themes` (`theme_id`),
  CONSTRAINT `FK_themes_views_themes` FOREIGN KEY (`theme_id`) REFERENCES `themes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.themes_views: ~9 rows (approximately)
/*!40000 ALTER TABLE `themes_views` DISABLE KEYS */;
INSERT INTO `themes_views` (`id`, `theme_id`, `app`, `view`, `content`) VALUES
	(1, 1, 'core', 'template', '<!DOCTYPE html>\r\n<html lang="en">\r\n    <head>\r\n        <base href="{$base}"></base>\r\n        <title>{$title} - {$name}</title>\r\n        <meta name="viewport" content="width=device-width, initial-scale=1">\r\n        {$css}\r\n        {$javascript}\r\n    </head>\r\n    <body>\r\n        <nav class="main">\r\n            <div class="container">\r\n                <a href="/" >Home</a>\r\n                <div class="profile">\r\n                    {{if (\\System\\Session::I()->IsLoggedIn()) }}\r\n                        {{if (\\System\\Admin\\Admin::HasAccess()) }}\r\n                            <a href="/{$admincpdir}">Admin CP</a>\r\n                        {{endif}}\r\n                        <a href="/settings">Settings</a>\r\n	      <a href="/logout">Logout</a>\r\n                    {{else}}\r\n	     <a href="/login">Login</a>\r\n	 {{endif}}\r\n                </div>\r\n            </div>\r\n        </nav>\r\n        <main class="body">\r\n            <div class="container">\r\n               {$page}\r\n            </div>\r\n        </main>\r\n    </body>\r\n    <script> </script>\r\n</html>'),
	(2, 1, 'core', 'error', '<h3>Error {$error}</h3>\r\n<p>{$info}</p>'),
	(3, 1, 'accounts', 'login', '<main class="body centered-container">\r\n    <div class="rounded-box">\r\n        <form accept-charset="utf8" method="post" action="{$loginurl}">\r\n            <input type="hidden" name="token" value="{$token}"/>\r\n            <div>\r\n                {{foreach $handlers as $handler}}\r\n                    <button type="submit" name="_loginHandler" value="{$handler->id}">Sign in with {$handler->name}</button>\r\n                {{endforeach}}\r\n            </div>\r\n        </form>\r\n    </div>\r\n</main>'),
	(4, 1, 'accounts', 'settings', '<div>\r\n    <div>\r\n        <h1>Settings</h1>\r\n        <p>Manage your account settings, and set up 3rd party integrations.</p>\r\n    </div>\r\n    <div id="settingsBody">\r\n        <ul class="tab-links">\r\n            <li><a href="settings">Overview</a></li>\r\n            <li><a href="settings/name">Display Name</a></li>\r\n            <li><a href="settings/intergrations">Intergrations</a></li>\r\n        </ul>\r\n        <div>\r\n            <div>\r\n                <h2>Settings</h2>\r\n            </div>\r\n            {$page}\r\n        </div>\r\n    </div>\r\n</div>'),
	(5, 1, 'admin', 'template', '<!DOCTYPE html>\r\n<html lang="en">\r\n    <head>\r\n        <base href="{$base}"></base>\r\n        <title>{$title} - {$name}</title>\r\n        <meta name="viewport" content="width=device-width, initial-scale=1">\r\n        {$css}\r\n        {$javascript}\r\n    </head>\r\n    <body>\r\n        <nav class="main no-container">\r\n            <div class="profile">\r\n                {{if (\\System\\Session::I()->IsLoggedIn()) }}\r\n                    <a href="logout">Logout</a>\r\n                {{endif}}\r\n            </div>\r\n        </nav>\r\n        <main class="body cols">\r\n            <div class="sidebar">\r\n                {{foreach $applets as $app}}\r\n                    <a {{if $app["app"] == $current_app && $app["controller"] == $current_controller}}class="active"{{endif}} href="admin/{$app["app"]}/{$app["controller"]}">{$app["title"]}</a>\r\n                {{endforeach}}\r\n            </div>\r\n            {{if ($sidebar) }}\r\n                <div class="sidebar secondary">\r\n                    {{foreach $sidebar as $name =>$bars}}\r\n                        <div>\r\n                            <h3>{$name}</h3>\r\n                            {{foreach $bars as $bar}}\r\n                                <a {{if $bar["link"] == $current_function || ($bar["link"] == "" && $current_function == "home")}}class="active"{{endif}} href="admin/{$current_app}/{$current_controller}/{$bar["link"]}">{$bar["title"]}</a>\r\n                            {{endforeach}}\r\n                       </div>\r\n                   {{endforeach}}\r\n                </div>\r\n            {{endif}}\r\n            <main>\r\n                {$page}\r\n            </main>\r\n        </main>\r\n    </body>\r\n    <script> </script>\r\n</html>'),
	(6, 1, 'admin', 'members_listview', '<h2>Members</h2>\r\n<div>\r\n    <table>\r\n        <tr>\r\n            <th>Username</th>\r\n            <th>Join Date</th>\r\n            <th colspan=2></th>\r\n        </tr>\r\n        {{foreach $members as $member}}\r\n            <tr>\r\n                <td><a style="display: block;" target="_blank" href="/profile/{$member->id}">{$member->name}</a></td>\r\n                <td>{$member->join_date}</td>\r\n                <td class="button generic-action"><a href="/admin/core/members/home/{$member->id}">View</a></td>\r\n                <td class="button negative-action"><a href="">Delete</a></td>\r\n            </tr>\r\n        {{endforeach}}\r\n    </table>\r\n</div>'),
	(7, 1, 'admin', 'members_roles', '<h2>Roles</h2>'),
	(8, 1, 'core', 'profile', '<header>\r\n    <img class="banner" src="/img/banner.png"/>\r\n    <div class="user-info container">\r\n        <img src="" alt=""/>\r\n        <div class="name-block">\r\n            <h3>{$member->name}</h3>\r\n            <span>{$member->PrimaryRole()->name}</span>\r\n        </div>\r\n    </div>\r\n</header>'),
	(9, 1, 'admin', 'profile', '<div class="columns">\r\n    <div class="col s">\r\n        <div class="rounded-box" style="padding: 0;">\r\n             <img class="min-banner" src="/img/banner.png"/>\r\n             <div style="padding: 12px; padding-bottom: 18px;">\r\n                 {$member->name}\r\n             </div>\r\n        </div>\r\n        <div class="rounded-box">\r\n             <h3>Identifiers</h3>\r\n             {{foreach $identifiers as $id}}\r\n                 <p>{$id}</p>\r\n             {{endforeach}}\r\n        </div>\r\n        <div class="rounded-box">\r\n             <div class="header-actions">\r\n                 <h3 style="margin: 0;">Roles</h3>\r\n                 <div>\r\n                     <a href="/admin/core/members/home/{$member->id}/roles?action=primary" rel="modal:open">Edit Primary</a>\r\n                     <a href="/admin/core/members/home/{$member->id}/roles?action=roles" rel="modal:open">Edit Roles</a>\r\n                 </div>\r\n             </div>\r\n             {{$pRole = $member->PrimaryRole()->id;}}\r\n             <div class="flex-list">\r\n                 {{foreach $member->GetRoles(true) as $role}}\r\n                     <div class="role-badge{{if $role->id == $pRole}} first{{else}} second{{endif}}">{$role->name}{{if $role->id == $pRole}}</br><span>Primary Role</span>{{endif}}</div>\r\n                 {{endforeach}}\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <div class="col l">\r\n        <div class="rounded-box">\r\n            <h3>Statistics</h3>\r\n        </div>\r\n    </div>\r\n    <div class="col s">\r\n        <div class="rounded-box">\r\n             <h3>History</h3>\r\n        </div>\r\n    </div>\r\n</div>');
/*!40000 ALTER TABLE `themes_views` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
