DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(2) NOT NULL DEFAULT '1' COMMENT '1 = home page, 2 = category, 3 = product',
  `location` int(20) NOT NULL DEFAULT '0' COMMENT 'if type = 1, localtion (1 = top, 2 = bottom left, 3 = bottom right); if type = 2, localtion is catid; if type = 3, localtion is sku',
  `platform_id` varchar(10) NOT NULL DEFAULT '' COMMENT 'platform_id',
  `image` varchar(255) NOT NULL DEFAULT '',
  `image_name` varchar(255) NOT NULL DEFAULT '',
  `image_alt` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT 'target url for image',
  `target_type` int(2) NOT NULL DEFAULT '1' COMMENT '1: open in new window, 2: open in same window',
  `priority` int(2) NOT NULL DEFAULT '1',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '0 = inactive, 1 = active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(255) NOT NULL DEFAULT '127.0.0.1' COMMENT 'IP address',
  `create_by` varchar(32) NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) NOT NULL DEFAULT '127.0.0.1' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;


INSERT INTO `config` (`variable`, `value`, `description`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
    ('banner_img_path', 'images/banner', 'Banner Image path', now(), 2130706433, 'system', now(), 2130706433, 'system');
