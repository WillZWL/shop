UPDATE application SET url="order/SpecialOrder" WHERE id = 'ORD0011';

CREATE TABLE `order_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason_id` int(11) NOT NULL,
  `reason` varchar(64) NOT NULL,
  `reason_display_name` varchar(128) NOT NULL DEFAULT '',
  `priority` int(11) NOT NULL DEFAULT '0',
  `option_in_special` smallint(1) NOT NULL DEFAULT '0',
  `option_in_manual` smallint(1) NOT NULL DEFAULT '0',
  `option_in_phone` smallint(1) NOT NULL DEFAULT '0',
  `require_payment` smallint(1) NOT NULL DEFAULT '0',
  `Header_TranCode` varchar(16) NOT NULL DEFAULT '',
  `Header_CustCode` varchar(16) NOT NULL DEFAULT '',
  `status` smallint(1) NOT NULL DEFAULT '0',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_reason_id` (`reason_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `order_reason`(
  `reason_id` ,
  `reason` ,
  `reason_display_name` ,
  `priority`,
  `option_in_special` ,
  `option_in_manual` ,
  `option_in_phone`,
  `require_payment` ,
  `Header_TranCode` ,
  `Header_CustCode` ,
  `status`
)  SELECT   `reason_id`,
  `reason`,
  `reason_display_name`,
  `priority`,
  `option_in_special`,
  `option_in_manual`,
  `option_in_phone`,
  `require_payment`,
  `Header_TranCode`,
  `Header_CustCode`,
  `status`
  FROM `order_reason_copy`;

  CREATE TABLE `version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version_id` varchar(2) NOT NULL,
  `desc` varchar(100) DEFAULT NULL COMMENT 'Description to the version code',
  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'A-Active, I - Inactive',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `version` (
  `version_id` ,
  `desc` ,
  `status`
) SELECT
  `id` ,
  `desc` ,
  `status`
FROM  `version_copy`;

INSERT INTO
`template` (`type`, `tpl_id`, `tpl_name`, `platform_id`, `description`, `subject`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
('1', 'special_aps_cs_notification', 'Special aps cs notification', 'WEBAU', 'Special aps cs notification', '[:site_name:] Sales - [:order_reason:] [:so_no:]', '1', NOW(), '2130706433', 'brave', NOW(), '2130706433', 'brave'),
('1', 'special_aps_cs_notification', 'Special aps cs notification', 'WEBES', 'Special aps cs notification', '[:site_name:] Sales - [:order_reason:] [:so_no:]', '1', NOW(), '2130706433', 'brave', NOW(), '2130706433', 'brave'),
('1', 'special_aps_cs_notification', 'Special aps cs notification', 'WEBNZ', 'Special aps cs notification', '[:site_name:] Sales - [:order_reason:] [:so_no:]', '1', NOW(), '2130706433', 'brave', NOW(), '2130706433', 'brave'),
('1', 'special_aps_cs_notification', 'Special aps cs notification', 'WEBGB', 'Special aps cs notification', '[:site_name:] Sales - [:order_reason:] [:so_no:]', '1', NOW(), '2130706433', 'brave', NOW(), '2130706433', 'brave');
('1', 'special_aps_cs_notification', 'Special aps cs notification', 'WEBBE', 'Special aps cs notification', '[:site_name:] Sales - [:order_reason:] [:so_no:]', '1', NOW(), '2130706433', 'brave', NOW(), '2130706433', 'brave');
('1', 'special_aps_cs_notification', 'Special aps cs notification', 'WEBIT', 'Special aps cs notification', '[:site_name:] Sales - [:order_reason:] [:so_no:]', '1', NOW(), '2130706433', 'brave', NOW(), '2130706433', 'brave');
('1', 'special_aps_cs_notification', 'Special aps cs notification', 'WEBPL', 'Special aps cs notification', '[:site_name:] Sales - [:order_reason:] [:so_no:]', '1', NOW(), '2130706433', 'brave', NOW(), '2130706433', 'brave');