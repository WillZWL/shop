ALTER TABLE `price`
ADD COLUMN `vb_price`  decimal(15,2) NULL AFTER `price`;

ALTER TABLE `price`
MODIFY COLUMN `auto_price`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'N' COMMENT 'N - No action, Y - Auto-price, C - Competitor reprice, M - Manual update, V - Valuebasket price' AFTER `max_order_qty`;

ALTER TABLE `pricing_rules`
ADD COLUMN `min_margin`  double(15,2) NOT NULL DEFAULT 0.00 AFTER `mark_up_type`;


