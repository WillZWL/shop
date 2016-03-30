SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE `arc_order_notes`
MODIFY COLUMN `so_no`  bigint(20) NOT NULL FIRST ;

ALTER TABLE `arc_order_status_history`
MODIFY COLUMN `so_no`  bigint(20) NOT NULL AFTER `id`;

ALTER TABLE `auto_refund`
MODIFY COLUMN `so_no`  bigint(20) NOT NULL AFTER `refund_id`;

ALTER TABLE `arc_so_payment_log`
MODIFY COLUMN `so_no`  bigint(20) NOT NULL AFTER `id`;

ALTER TABLE `chargeback`
MODIFY COLUMN `so_no`  bigint(20) NOT NULL AFTER `id`;

ALTER TABLE `chargeback_audit`
MODIFY COLUMN `so_no`  bigint(20) NOT NULL AFTER `chargeback_id`;

ALTER TABLE `cps_allocated_so`
MODIFY COLUMN `so_no`  bigint(20) NOT NULL AFTER `date`;

ALTER TABLE `delayed_order`
MODIFY COLUMN `so_no`  bigint(20) NOT NULL FIRST ;

ALTER TABLE `flex_pmgw_transactions`
MODIFY COLUMN `so_no` bigint(20) NOT NULL;

ALTER TABLE flex_refund	 MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE flex_ria MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE flex_rolling_reserve MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE flex_so_fee MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE fraudulent_order MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE general_purpose MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE integrated_order_fulfillment MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE interface_finance_dispatch MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE interface_flex_pmgw_transactions MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE interface_flex_refund MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE interface_flex_ria MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE interface_flex_rolling_reserve MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE interface_flex_so_fee MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE interface_so MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE interface_so_item MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE interface_so_item_detail MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE interface_so_payment_status MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE interface_t3m_score MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE interface_tracking_feed MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE interface_tracking_info MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE ls_transactions MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE order_notes MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE order_status_history MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE refund MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE release_order_history MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE replace_so_credit_chk MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE rma MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_allocate MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_bank_transfer MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_compensation MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_compensation_history MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_credit_chk MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_extend MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_hold_reason MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_hold_status_history MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_item_detail MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_payment_log MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_payment_query_log MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_payment_status MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_priority_score MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_priority_score_history MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_refund_score MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_refund_score_history MODIFY COLUMN `so_no` bigint(20) NOT NULL;
ALTER TABLE so_risk MODIFY COLUMN `so_no` bigint(20) NOT NULL;
