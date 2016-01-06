CREATE TABLE `t3m_filelog` (
  `file_num` varchar(7) NOT NULL,
  `uploaded` char(1) NOT NULL DEFAULT 'N',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) NOT NULL,
  `create_by` varchar(32) NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) NOT NULL,
  `modify_by` varchar(32) NOT NULL,
  PRIMARY KEY (`file_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT