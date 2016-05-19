ALTER TABLE `site_config`
ADD COLUMN `api_implemented`  int(11) UNSIGNED NULL DEFAULT 0 COMMENT 'bit 0 = Google Shopping, bit 1 = Google Adwords' AFTER `domain_type`;
