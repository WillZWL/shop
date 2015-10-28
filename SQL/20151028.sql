ALTER TABLE `so`
ADD INDEX `idx_create_on` (`create_on`) USING BTREE ;

ALTER TABLE `so_risk`
ADD INDEX `idx_risk_requested` (`risk_requested`) USING BTREE ;

