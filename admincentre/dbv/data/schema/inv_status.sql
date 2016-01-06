CREATE TABLE `inv_status` (
  `status` varchar(2) NOT NULL,
  `type` varchar(2) NOT NULL COMMENT 'C - Customer, S - Supplier, W - Warehouse',
  `from_inv_dir` tinyint(1) NOT NULL DEFAULT '0',
  `from_git_dir` tinyint(1) NOT NULL DEFAULT '0',
  `to_inv_dir` tinyint(1) NOT NULL DEFAULT '0',
  `to_git_dir` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`status`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8