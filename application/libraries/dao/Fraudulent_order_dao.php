<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Fraudulent_order_dao extends Base_dao
{
    private $table_name = "fraudulent_order";
    private $vo_class_name = "Fraudulent_order_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_fraud_order($start_date, $end_date, $where, $classname = 'fraud_order_w_item_dto')
    {
        $sql = "
            SELECT
                fo.id, fo.so_no, date_format(h_modify_on, '%m/%e/%Y') as hold_date, h_modify_by as hold_staff, date_format(order_create_date, '%m/%e/%Y') as order_create_date, payment_gateway_id, prod_name, cat.name as category,
                so.currency_id, temp_tb3.unit_price as item_price, temp_tb3.qty as item_quantity, temp_tb3.order_total_item as order_total_item, so.amount as order_value, c.forename, c.surname,
                so.client_id, c.email, so.bill_name, so.bill_company,
                so.bill_address as bill_address1, so.bill_address as bill_address2, so.bill_address as bill_address3,
                so.bill_city, so.bill_state,
                so.bill_postcode, so.bill_country_id, so.delivery_name, so.delivery_company,
                so.delivery_address as delivery_address1,so.delivery_address as delivery_address2,so.delivery_address as delivery_address3,
                so.delivery_state, so.delivery_postcode, so.delivery_country_id, c.password, c.tel_1, c.tel_2, c.tel_3,
                c.mobile, so.platform_id, sops.card_id, socc.card_type, sor.risk_var1, sor.risk_var2, sor.risk_var3, sor.risk_var4, sor.risk_var5,
                sor.risk_var6, sor.risk_var7, sor.risk_var8, sor.risk_var9, sor.risk_var10,
                socc.card_bin,
                if(sops.card_id='MoneyBookers', sops.risk_ref1, '') as verification_level,
                if(sops.card_id='GlobalCollect', sops.risk_ref2, '') as fraud_result,
                if(sops.card_id='GlobalCollect', sops.risk_ref1, '') as AVS_result,
                if(sops.card_id='PayPal', sops.risk_ref1, '') as protection_eligibility,
                if(sops.card_id='PayPal', sops.risk_ref2, '') as protection_eligibility_type,
                if(sops.card_id='PayPal', sops.risk_ref3, '') as address_status,
                if(sops.card_id='PayPal', sops.risk_ref4, '') as payer_status,
                so.create_at, so.dispatch_date, so.refund_status, temp_rf.create_on as refund_date, rr.description
            FROM fraudulent_order as fo
            INNER JOIN so
                ON fo.so_no = so.so_no
            INNER JOIN
                (SELECT so_no, reason, max(modify_on) as h_modify_on, modify_by as h_modify_by from so_hold_reason group by so_no) as temp_tb1
                ON temp_tb1.so_no = fo.so_no
            LEFT JOIN so_payment_status as sops
                ON so.so_no = sops.so_no
            LEFT JOIN so_credit_chk as socc
                ON socc.so_no = so.so_no
            INNER JOIN (
                SELECT soi.so_no, soi.prod_sku, soi.qty, soi.prod_name, soi.unit_price, order_total_item from so_item as soi
                    INNER JOIN (SELECT soi_2.so_no, MAX(line_no) as order_total_item from so_item as soi_2 GROUP BY so_no) as temp_tb2
                        ON temp_tb2.so_no = soi.so_no
            ) as temp_tb3
                ON temp_tb3.so_no = so.so_no
            INNER JOIN product as p
                ON temp_tb3.prod_sku = p.sku
            INNER JOIN category as cat
                ON cat.id = p.cat_id
            INNER JOIN client as c
                ON c.id = so.client_id
            LEFT JOIN so_risk as sor
                ON sor.so_no = so.so_no
            LEFT JOIN
                (SELECT id, so_no, reason, max(create_on) as create_on from refund group by so_no) as temp_rf
                ON temp_rf.so_no = so.so_no
            LEFT JOIN refund_reason as rr
                ON rr.id = temp_rf.reason
        ";
//one refuned order can get more than one record in the refund table, so select the lastest one
        if ($where == '') {
            $sql .= " WHERE so.order_create_date >= '$start_date 00:00:00' and so.order_create_date <= '$end_date 23:59:59'
                     ORDER BY so.so_no";
        } else {
            $sql .= " WHERE so.order_create_date >= '$start_date 00:00:00' and so.order_create_date <= '$end_date 23:59:59' and $where
                      ORDER BY so.so_no";
        }


        $this->include_dto($classname);
        $result = $this->db->query($sql);
        //var_dump($this->db->last_query());die();
        if (!$result) {
            return FALSE;
        }

        foreach ($result->result("object", $classname) as $row) {
            $rs[] = $row;
        }
        return $rs;
    }


    public function get_all_email_referral_list($where = '', $option = '', $classname = 'email_referral_w_client_dto')
    {
        $this->db->from("email_referral_list erl");
        $this->db->join("client as c", "c.email = erl.email", "LEFT");
        $where['erl.status'] = 1;
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "erl.id, erl.email, c.id as client_id, c.forename, c.surname, c.address_1, c.address_2, c.address_3, c.postcode, c.city, c.country_id, c.tel_1, c.tel_2, c.tel_3, c.create_at");
    }
}


