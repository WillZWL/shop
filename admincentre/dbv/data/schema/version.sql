CREATE TABLE `version` (
  `id` varchar(2) NOT NULL,
  `desc` varchar(100) DEFAULT NULL COMMENT 'Description to the version code',
  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'A-Active, I - Inactive',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) NOT NULL,
  `create_by` varchar(32) NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) NOT NULL,
  `modify_by` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8