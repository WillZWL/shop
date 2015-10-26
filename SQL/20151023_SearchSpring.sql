ALTER TABLE `schedule_job`
MODIFY COLUMN `id`  int(11) NOT NULL AUTO_INCREMENT FIRST ,
MODIFY COLUMN `name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
ADD COLUMN `schedule_job_id`  varchar(64) NOT NULL AFTER `id`;

ALTER TABLE `schedule_job`
ADD UNIQUE INDEX `idx_schedule_job_id` (`schedule_job_id`) USING BTREE ;
/*
ALTER TABLE `product`
ADD INDEX `idx_cat_id` (`cat_id`) USING BTREE ,
ADD INDEX `idx_sub_cat_id` (`sub_cat_id`) USING BTREE ;
*/
ALTER TABLE `category_extend`
ADD UNIQUE INDEX `idx_cat_lang` (`cat_id`, `lang_id`) USING BTREE ;

