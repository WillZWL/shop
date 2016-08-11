
Select AUTO_INCREMENT from INFORMATION_SCHEMA.TABLES  Where table_schema = 'panther' AND table_name LIKE 'product_image' into @product_imageid;

update `sequence` set value = @product_imageid where seq_name = 'product_image';