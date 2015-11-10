ALTER TABLE banner DROP FOREIGN KEY `fk_banner_cat`;
ALTER TABLE category_var DROP FOREIGN KEY `fk_cv_cat_id`;
ALTER TABLE display_category_banner DROP FOREIGN KEY `fk_dbcat_catid`;
ALTER TABLE display_qty_factor DROP FOREIGN KEY `fk_dqf_cat_id`;
ALTER TABLE landpage_video_listing DROP FOREIGN KEY `fk_lvl_catid`;
ALTER TABLE category DROP FOREIGN KEY `fk_cat_cat`;



  -- this is for category table, root category "Base" id is 0
SET SESSION SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";