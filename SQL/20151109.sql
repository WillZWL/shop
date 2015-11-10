ALTER TABLE product_image ADD COLUMN vb_image CHAR(25) NOT NULL DEFAULT '' AFTER image_saved;
ALTER TABLE product_image ADD INDEX idx_vb_image (`vb_image`);