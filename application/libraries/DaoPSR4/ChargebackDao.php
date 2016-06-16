<?php
namespace ESG\Panther\Dao;

class ChargebackDao extends BaseDao
{
    private $table_name = "chargeback_dao";
    private $vo_classname = "ChargebackVo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getVoClassname()
    {
        return $this->vo_classname;
    }


    public function getChargebackReasonList()
    {
        $sql = "select id, name, details from lookup_chargeback_reason lk_cb_reason";
        $query = $this->db->query($sql);

        foreach ($query->result() as $tmp) {
            $obj[] = $tmp;
        }

        return $obj;
    }

    public function getChargebackStatusList()
    {
        $sql = "select id, name, details from lookup_chargeback_status lk_cb_status";
        $query = $this->db->query($sql);

        foreach ($query->result() as $tmp) {
            $obj[] = $tmp;
        }

        return $obj;
    }

    public function getChargebackRemarkList()
    {
        $sql = "select id, name, details from lookup_chargeback_remark lk_cb_remark";
        $query = $this->db->query($sql);

        foreach ($query->result() as $tmp) {
            $obj[] = $tmp;
        }

        return $obj;
    }

    public function getChargebackData($filter = array(), $classname = "ChargebackOrdersDto")
    {
        $rs = array();

        $where = [];

        if (!empty($filter)) {
            if ($filter["platform_id"] != "") {
                $where["s.platform_id"] = $filter["platform_id"];
            }

            if ($filter["payment_gateway_id"] != "") {
                $where["sps.payment_gateway_id"] = $filter["payment_gateway_id"];
            }

            if ($filter["hold_reason"] != "") {
                $where["shr.reason"] = $filter["hold_reason"];
            }

            if ($filter["chargeback_reason"] != "") {
                $where["cb.chargeback_reason_id"] = $filter["chargeback_reason"];
            }

            if ($filter["chargeback_status"] != "") {
                $where["cb.chargeback_status_id"] = $filter["chargeback_status"];
            }

            if ($filter["chargeback_remark"] != "") {
                $where["cb.chargeback_remark_id"] = $filter["chargeback_remark"];
            }
            if ($filter["so_no"] != "") {
                $where["s.so_no"] = $filter["so_no"];
            }
            if ($filter["currency_id"] != "") {
                $where["s.currency_id"] = $filter["currency_id"];
            }

            if ($filter["order_start_date"] != "") {
                $order_start_date = "{$filter["order_start_date"]} 00:00:00";
                if ($filter["order_end_date"] != "") {
                    $order_end_date = "{$filter["order_end_date"]} 23:59:59";
                } else {
                    $order_end_date = date('Y-m-d') . " 23:59:59";
                }

                $where["s.create_on >= '$order_start_date' AND s.create_on <= '$order_end_date'"] = null;
            }


            if ($filter["chargeback_start_date"] != "") {
                $chargeback_start_date = "{$filter["chargeback_start_date"]} 00:00:00";
                if ($filter["chargeback_end_date"] != "") {
                    $chargeback_end_date = "{$filter["chargeback_end_date"]} 23:59:59";
                } else {
                    $chargeback_end_date = date('Y-m-d') . " 23:59:59";
                }

                $where["cb.create_on >= '$chargeback_start_date' AND cb.create_on <= '$chargeback_end_date'"] = null;

            }

            $this->db->where($where);
            $this->db->from("so s");
            $this->db->select('s.so_no, s.create_on as order_create_date_time
                                , cb.create_on as chargeback_create_date
                                , lk_cb_reason.name AS chargeback_reason
                                , lk_cb_remark.name AS chargeback_remark
                                , lk_cb_status.name as chargeback_status
                                , shr.reason as hold_reason, MAX(shr.create_on) as hold_date_time, shr.create_by as hold_staff
                                , s.txn_id as payment_transaction_id
                                , sps.payment_gateway_id
                                , s.amount as order_value
                                , sid.qty as item_quantity
                                , sid.unit_price as item_value
                                , s.currency_id as currency
                                , p.name as product_name
                                , cat.name as category_name
                                , sps.payment_status
                                , c.forename as client_forename
                                , c.surname as client_surname
                                , c.id as client_id
                                , c.email
                                , s.bill_name
                                , s.bill_company
                                , s.bill_address
                                , s.bill_city
                                , s.bill_state
                                , s.bill_postcode
                                , s.bill_country_id
                                , s.delivery_name
                                , s.delivery_company
                                , s.delivery_address
                                , s.delivery_city
                                , s.delivery_state
                                , s.delivery_postcode
                                , s.delivery_country_id
                                , c.`password`
                                , c.tel_1, c.tel_2, c.tel_3
                                , c.mobile
                                , s.platform_id as order_type
                                , s.delivery_type_id as delivery_mode
                                , s.delivery_charge as delivery_cost
                                , s.promotion_code
                                , sps.card_id as payment_type
                                , sor.risk_var_1
                                , sor.risk_var_2
                                , sor.risk_var_3
                                , sor.risk_var_4
                                , sor.risk_var_5
                                , sor.risk_var_6
                                , sor.risk_var_7
                                , sor.risk_var_8
                                , sor.risk_var_9
                                , sor.risk_var_10
                                , scc.card_bin
                                , scc.card_type
                                , sps.pay_to_account
                                , sps.risk_ref_1
                                , sps.risk_ref_2
                                , sps.risk_ref_3
                                , sps.risk_ref_4
                                , s.create_at as ip_address
                                , s.status as order_status
                                , s.dispatch_date
                                , ri.`status` as refund_status
                                , ri.create_on as refund_date
                                , rr.description as refund_reason');

            $this->db->join("so_hold_reason shr", "s.so_no=shr.so_no", "left");
            $this->db->join("so_payment_status sps", "sps.so_no=s.so_no", "left");
            $this->db->join("so_item_detail sid", "sid.so_no=s.so_no", "left");
            $this->db->join("product p", "p.sku=sid.item_sku", "inner");
            $this->db->join("category cat", "cat.id=p.cat_id", "inner");
            $this->db->join("client c", "c.id=s.client_id", "left");
            $this->db->join("so_credit_chk scc", "scc.so_no=s.so_no", "left");
            $this->db->join("refund r", "r.so_no=s.so_no", "left");
            $this->db->join("refund_item ri", "r.id=ri.refund_id and ri.line_no=sid.line_no", "left");
            $this->db->join("refund_reason rr", "r.reason=rr.id", "left");
            $this->db->join("so_risk sor", "s.so_no=sor.so_no", "left");
            $this->db->join("chargeback cb", "s.so_no=cb.so_no", "inner");
            $this->db->join("lookup_chargeback_status lk_cb_status", "lk_cb_status.id = cb.chargeback_status_id", "left");
            $this->db->join("lookup_chargeback_reason lk_cb_reason", "lk_cb_reason.id = cb.chargeback_reason_id", "left");
            $this->db->join("lookup_chargeback_remark lk_cb_remark", "lk_cb_remark.id = cb.chargeback_remark_id", "left");
            $this->db->group_by("s.so_no", "sid.item_sku");

            $current_so_number = "";
            $trace_back = $total_quantity = $i = 0;

            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $row) {
                    // add up all the item qty for the same so_no
                    $rs[$i] = $row;
                    $rs[$i]->setOrderQuantity($rs[$i]->getItemQuantity());
                    if ($current_so_number == $row->getSoNo()) {
                        $trace_back++;
                        $total_quantity += $rs[$i]->getItemQuantity();
                        for ($j = ($i - $trace_back); $j <= $i; $j++) {
                            $rs[$j]->setOrderQuantity($total_quantity);
                        }
                    } else {
                        $trace_back = 0;
                        $total_quantity = $rs[$i]->getItemQuantity();
                    }
                    $current_so_number = $row->getSoNo();
                    $i++;
                }
            }
            return $rs;
        } else {
            return $rs;
        }
    }
}