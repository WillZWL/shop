UPDATE `product_image` SET `VB_alt_text` = '' WHERE `VB_alt_text` IS NULL;
ALTER TABLE product_image CHANGE COLUMN `VB_alt_text`  vb_alt_text VARCHAR(255) NOT NULL DEFAULT '';