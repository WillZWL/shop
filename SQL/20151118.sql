update application set url='marketing/ext-category-mapping' where id='MKT0074';

/* above is LIVE */

ALTER TABLE `category`
ADD COLUMN `hidden`  tinyint(1) NOT NULL DEFAULT 0 AFTER `status`;

update category set hidden=1 where name like '%Do not%';
update category set hidden=0 where name not like '%Do not%';

ALTER TABLE `google_shopping`
ADD COLUMN `id`  int(11) NULL AUTO_INCREMENT FIRST ,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`),
DROP INDEX `platform_id` ,
ADD INDEX `idx_platform_id` (`platform_id`) USING BTREE ,
ADD INDEX `idx_status` (`status`) USING BTREE ;

