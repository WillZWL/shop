# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.25)
# Database: atomv2
# Generation Time: 2015-09-01 02:43:42 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table fulfillment_centre
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fulfillment_centre`;

CREATE TABLE `fulfillment_centre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fulfillment_centre_id` varchar(20) NOT NULL DEFAULT '',
  `country_id` char(2) NOT NULL COMMENT 'International country code (2 characters)',
  `name` varchar(50) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) NOT NULL DEFAULT '127.0.0.1' COMMENT 'IP address',
  `create_by` varchar(32) NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) NOT NULL DEFAULT '127.0.0.1' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_fc_country_id` (`country_id`),
  KEY `idx_fulfillment_centre_id` (`fulfillment_centre_id`),
  CONSTRAINT `fk_fc_country_id` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `fulfillment_centre` WRITE;
/*!40000 ALTER TABLE `fulfillment_centre` DISABLE KEYS */;

INSERT INTO `fulfillment_centre` (`id`, `fulfillment_centre_id`, `country_id`, `name`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
	(1,'HK_FC','HK','HK Fulfillment Center','2011-02-25 17:34:50','127.0.0.1','system','2012-02-01 19:33:50','127.0.0.1','system'),
	(2,'UK_FC','GB','UK Fulfillment Center','2011-02-25 17:34:51','127.0.0.1','system','2012-02-01 19:33:50','127.0.0.1','system'),
	(3,'US_FC','US','US Fulfillment Center','2011-02-25 17:34:51','127.0.0.1','system','2012-02-01 19:33:50','127.0.0.1','system');

/*!40000 ALTER TABLE `fulfillment_centre` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
