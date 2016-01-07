CREATE TABLE `sequence_tb` (
  `seq_name` varchar(64) NOT NULL,
  `cur_value` bigint(20) unsigned NOT NULL,
  `increment_value` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`seq_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8