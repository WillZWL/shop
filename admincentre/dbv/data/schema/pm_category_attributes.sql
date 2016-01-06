CREATE TABLE `pm_category_attributes` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `pm_category` varchar(255) NOT NULL,
  `attribute_type` varchar(50) NOT NULL,
  `xml_attribute` varchar(30) NOT NULL,
  `xml_key` varchar(50) DEFAULT NULL,
  `mandatory` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `select_value` tinyint(1) DEFAULT NULL,
  `ordering` int(2) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` date DEFAULT NULL,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8