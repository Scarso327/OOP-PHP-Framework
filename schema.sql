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
  `join_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.accounts: ~1 rows (approximately)
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;

-- Dumping structure for table forumboard.accounts_roles
CREATE TABLE IF NOT EXISTS `accounts_roles` (
  `account_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  KEY `FK_accounts_roles_accounts` (`account_id`),
  KEY `FK_accounts_roles_roles` (`role_id`),
  CONSTRAINT `FK_accounts_roles_accounts` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  CONSTRAINT `FK_accounts_roles_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.roles: ~2 rows (approximately)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`) VALUES
	(1, 'Member'),
	(2, 'Admin');
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
	(4, 'core', 'steam-api-key', '');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.themes_css: ~2 rows (approximately)
-- Dumping data for table forumboard.themes_css: ~5 rows (approximately)
/*!40000 ALTER TABLE `themes_css` DISABLE KEYS */;
INSERT INTO `themes_css` (`id`, `theme_id`, `app`, `view`, `content`) VALUES
	(1, 1, 'core', 'core', 'body {\r\n    margin: 0;\r\n    color: #c9d1d9;\r\n    font-family: BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif, Apple Color Emoji, Segoe UI Emoji;\r\n    background-color: #282828;\r\n}\r\n\r\np {\r\n    margin: 0;\r\n}\r\n\r\na {\r\n    color: inherit;\r\n    text-decoration: none;\r\n}\r\n\r\nmain.body {\r\n    min-height: calc(100vh - 53px); /* Height of browser view - navbar */\r\n}\r\n\r\n.container {\r\n    max-width: 1320px;\r\n    margin-left: auto;\r\n    margin-right: auto;\r\n}\r\n\r\n.centered-container {\r\n    display: flex;\r\n    align-items: center;\r\n    justify-content: center;\r\n}\r\n\r\n.rounded-box {\r\n    padding: 12px;\r\n    border-radius: 12px;\r\n    background-color:#333333;\r\n}'),
	(2, 1, 'settings', 'main', '#settingsBody {\r\n    display: grid;\r\n    grid-template-columns: 25% 75%;\r\n}\r\n\r\n#settingsBody > div {\r\n    margin-left: 12px;\r\n}'),
	(5, 1, 'core', 'navigation', 'nav.main {\r\n    width: 100%;\r\n    background-color:#333333;\r\n}\r\n\r\nnav.main.no-container {\r\n    display: flex;\r\n}\r\n\r\nnav.main > div.container {\r\n    display: flex;\r\n}\r\n\r\nnav.main a {\r\n    display: inline-block;\r\n    padding: 16px;\r\n}\r\n\r\nnav.main .profile {\r\n    margin-left: auto;\r\n}\r\n\r\nul.tab-links {\r\n    list-style: none;\r\n    padding: 0;\r\n}\r\n\r\nul.tab-links li {\r\n    transition: 0.2s;\r\n}\r\n\r\nul.tab-links li a {\r\n    display: block;\r\n    padding: 6px;\r\n}\r\n\r\nul.tab-links li.active, ul.tab-links li:hover {\r\n    color: white;\r\n}\r\n\r\nul.tab-links li.active {\r\n    background-color: #0f0f0f;\r\n}');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table forumboard.themes_views: ~4 rows (approximately)
/*!40000 ALTER TABLE `themes_views` DISABLE KEYS */;
INSERT INTO `themes_views` (`id`, `theme_id`, `app`, `view`, `content`) VALUES
	(1, 1, 'core', 'template', '<!DOCTYPE html>\r\n<html>\r\n    <head>\r\n        <base href="{$base}"></base>\r\n        <title>{$title} - {$name}</title>\r\n        <meta name="viewport" content="width=device-width, initial-scale=1">\r\n        {$css}\r\n    </head>\r\n    <body>\r\n        <nav class="main">\r\n            <div class="container">\r\n                <a href="/" >Home</a>\r\n                <div class="profile">\r\n                    {{if (\\System\\Session::I()->IsLoggedIn()) }}\r\n                        <a href="/settings">Settings</a><a href="/logout">Logout</a>\r\n                    {{else}}\r\n	     <a href="/login">Login</a>\r\n	 {{endif}}\r\n                </div>\r\n            </div>\r\n        </nav>\r\n        <main class="body">\r\n            <div class="container">\r\n               {$page}\r\n            </div>\r\n        </main>\r\n    </body>\r\n    <script> </script>\r\n</html>'),
	(2, 1, 'core', 'error', '<h3>Error {$error}</h3>\r\n<p>{$info}</p>'),
	(3, 1, 'accounts', 'login', '<main class="body centered-container">\r\n    <div class="rounded-box">\r\n        <form accept-charset="utf8" method="post" action="{$loginurl}">\r\n            <input type="hidden" name="token" value="{$token}"/>\r\n            <div>\r\n                {{foreach $handlers as $handler}}\r\n                    <button type="submit" name="_loginHandler" value="{$handler->id}">Sign in with {$handler->name}</button>\r\n                {{endforeach}}\r\n            </div>\r\n        </form>\r\n    </div>\r\n</main>'),
	(4, 1, 'accounts', 'settings', '<div>\r\n    <div>\r\n        <h1>Settings</h1>\r\n        <p>Manage your account settings, and set up 3rd party integrations.</p>\r\n    </div>\r\n    <div id="settingsBody">\r\n        <ul class="tab-links">\r\n            <li><a href="settings">Overview</a></li>\r\n            <li><a href="settings/name">Display Name</a></li>\r\n            <li><a href="settings/intergrations">Intergrations</a></li>\r\n        </ul>\r\n        <div>\r\n            <div>\r\n                <h2>Settings</h2>\r\n            </div>\r\n            {$page}\r\n        </div>\r\n    </div>\r\n</div>'),
	(5, 1, 'admin', 'template', '<!DOCTYPE html>\r\n<html>\r\n    <head>\r\n        <base href="{$base}"></base>\r\n        <title>{$title} - {$name}</title>\r\n        <meta name="viewport" content="width=device-width, initial-scale=1">\r\n        {$css}\r\n    </head>\r\n    <body>\r\n        <nav class="main no-container">\r\n            <div class="profile">\r\n                {{if (\\System\\Session::I()->IsLoggedIn()) }}\r\n                    <a href="logout">Logout</a>\r\n                {{endif}}\r\n            </div>\r\n        </nav>\r\n        <main class="body cols">\r\n            <div class="sidebar">\r\n                {{foreach $applets as $app}}\r\n                    <a {{if $app["app"] == $current_app && $app["controller"] == $current_controller}}class="active"{{endif}}href="admin/{$app["app"]}/{$app["controller"]}">{$app["title"]}</a>\r\n                {{endforeach}}\r\n            </div>\r\n            <main>\r\n                {$page}\r\n            </main>\r\n        </main>\r\n    </body>\r\n    <script> </script>\r\n</html>');
/*!40000 ALTER TABLE `themes_views` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
