ALTER TABLE `so_payment_status`
CHANGE COLUMN `risk_ref1` `risk_ref_1`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'PayPal:PROTECTIONELIGIBILITY / GlobalCollect:AVSRESULT / MoneyBookers: VERIFICATIONLEVEL / Adyen:3DAUTHENTICATIONRESULT' AFTER `payer_ref`,
CHANGE COLUMN `risk_ref2` `risk_ref_2`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'PayPal:PROTECTIONELIGIBILITYTYPE / GlobalCollect:FRAUDRESULT, AltaPay:FraudRiskScore, Adyen:FraudScore' AFTER `risk_ref_1`,
CHANGE COLUMN `risk_ref3` `risk_ref_3`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'PayPal:ADDRESSSTATUS, AltaPay:FraudRecommendation, Adyen:AuthorisationCode' AFTER `risk_ref_2`,
CHANGE COLUMN `risk_ref4` `risk_ref_4`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'PayPal:PAYERSTATUS, AltaPay:FraudExplanation, Adyen:RefusalReason' AFTER `risk_ref_3`;

