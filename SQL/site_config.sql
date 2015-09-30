# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.26)
# Database: atomv2
# Generation Time: 2015-08-27 11:35:22 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table site_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `site_config`;

CREATE TABLE `site_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  `site_name` varchar(255) NOT NULL DEFAULT '',
  `lang` varchar(5) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `platform` char(5) NOT NULL,
  `domain_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0 = Development / 1 = Production',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `site_config` WRITE;
/*!40000 ALTER TABLE `site_config` DISABLE KEYS */;

INSERT INTO `site_config` (`id`, `domain`, `site_name`, `lang`, `logo`, `email`, `platform`, `domain_type`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
	(1,'digitaldiscount.co.uk','Digital Discount','en_GB','digitaldiscount.png','support@digitaldiscount.co.uk','WEBGB',1,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:25:07',2130706433,'handy'),
	(2,'aheaddigital.co.nz','Ahead Digital','en_GB','aheaddigital.jpg','support@aheaddigital.co.nz','WEBNZ',1,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:25:11',2130706433,'handy'),
	(3,'aheaddigital.net','Ahead Digital','en_GB','aheaddigital.jpg','support@aheaddigital.net','WEBAU',1,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:25:18',2130706433,'handy'),
	(4,'nuovadigitale.it','Nuova Digitale','it_IT','nuovadigitale.jpg','supporto@nuovadigitale.it','WEBIT',1,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:25:25',2130706433,'handy'),
	(5,'numeristock.be','Numeri Stock','fr_FR','numeristock.jpg','support@numeristock.be','WEBBE',1,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:25:29',2130706433,'handy'),
	(6,'numeristock.fr','Numeri Stock','fr_FR','numeristock.jpg','support@numeristock.fr','WEBFR',1,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:25:35',2130706433,'handy'),
	(7,'elektroraj.pl','Elektroraj','pl_PL','elektroraj.jpg','pomoc@elektroraj.pl','WEBPL',1,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:25:39',2130706433,'handy'),
	(8,'buholoco.es','Buholoco','es_ES','buholoco.png','soporte@buholoco.es','WEBES',1,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:25:44',2130706433,'handy'),
	(9,'dduk.dev','Digital Discount','en_GB','digitaldiscount.png','support@digitaldiscount.co.uk','WEBGB',0,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:25:50',2130706433,'handy'),
	(10,'adnz.dev','Ahead Digital','en_GB','aheaddigital.jpg','support@aheaddigital.co.nz','WEBNZ',0,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:25:53',2130706433,'handy'),
	(11,'adau.dev','Ahead Digital','en_GB','aheaddigital.jpg','support@aheaddigital.net','WEBAU',0,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:25:57',2130706433,'handy'),
	(12,'ndit.dev','Nuova Digitale','it_IT','nuovadigitale.jpg','supporto@nuovadigitale.it','WEBIT',0,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:26:04',2130706433,'handy'),
	(13,'nsbe.dev','Numeri Stock','fr_FR','numeristock.jpg','support@numeristock.be','WEBBE',0,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:26:10',2130706433,'handy'),
	(14,'nsfr.dev','Numeri Stock','fr_FR','numeristock.jpg','support@numeristock.fr','WEBFR',0,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:26:18',2130706433,'handy'),
	(15,'bles.dev','Buholoco','es_ES','buholoco.png','soporte@buholoco.es','WEBES',0,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:26:23',2130706433,'handy'),
	(16,'elpl.dev','Elektroraj','pl_PL','elektroraj.jpg','pomoc@elektroraj.pl','WEBPL',0,1,'2015-07-24 13:42:35',2130706433,'handy','2015-08-03 10:26:28',2130706433,'handy');

/*!40000 ALTER TABLE `site_config` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
