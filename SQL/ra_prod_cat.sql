CREATE TABLE `ra_prod_cat` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ss_cat_id` bigint(20) unsigned NOT NULL COMMENT 'The category must be at level 3, i.e. sub-sub-category',
  `rcm_ss_cat_id_1` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'The category must be at level 3, i.e. sub-sub-category',
  `rcm_ss_cat_id_2` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'The category must be at level 3, i.e. sub-sub-category',
  `rcm_ss_cat_id_3` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'The category must be at level 3, i.e. sub-sub-category',
  `rcm_ss_cat_id_4` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'The category must be at level 3, i.e. sub-sub-category',
  `rcm_ss_cat_id_5` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'The category must be at level 3, i.e. sub-sub-category',
  `rcm_ss_cat_id_6` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'The category must be at level 3, i.e. sub-sub-category',
  `rcm_ss_cat_id_7` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'The category must be at level 3, i.e. sub-sub-category',
  `rcm_ss_cat_id_8` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'The category must be at level 3, i.e. sub-sub-category',
  `warranty_cat` bigint(20) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0 = Inactive, 1 = Active',
  `create_on` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` INT(10) UNSIGNED NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` VARCHAR(32) NOT NULL DEFAULT 'system',
  `modify_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` INT(10) UNSIGNED NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` VARCHAR(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  unique key idx_ss_cat_id (ss_cat_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;