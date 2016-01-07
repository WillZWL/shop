CREATE TABLE `chargeback_audit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chargeback_id` int(11) NOT NULL,
  `so_no` char(8) NOT NULL,
  `chargeback_status_id` int(11) NOT NULL COMMENT 'refer to lookup_chargeback_status',
  `chargeback_reason_id` int(11) NOT NULL,
  `chargeback_reason` text NOT NULL,
  `chargeback_remark_id` int(11) NOT NULL,
  `chargeback_remark` text NOT NULL,
  `document` text NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_by` text NOT NULL,
  `remarks` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8