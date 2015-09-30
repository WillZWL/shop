<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class So_dao extends Base_dao
{
    private $table_name = "so";
    private $vo_class_name = "So_vo";
    private $seq_name = "customer_order";
    private $seq_mapping_field = "so_no";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_surplus_oos($where = array(), $option = array(), $classname = '')
    {
        $this->db->from("product p");
        $where = "(website_status = 'O' or (website_status = 'I' and website_quantity = 0 )) and surplus_quantity > 0";
        return $this->common_get_list($where, $option, $classname, "p.sku,p.name,p.surplus_quantity");
    }

    public function get_so_w_reason($where = array(), $option = array(), $classname = 'so_w_reason_dto')
    {
        $this->db->from("so");
        $this->db->join("so_extend soex", "soex.so_no = so.so_no", 'INNER');
        $this->db->join('order_reason ore', 'ore.reason_id = soex.order_reason', 'LEFT');
        /*
                $this->db->join('(
                    (select * from
                    (select so_no, reason, create_on from so_hold_reason order by create_on desc) a
                    group by so_no)
                as shr)', 'shr.so_no = so.so_no', 'LEFT');
        */
        $this->db->join('refund r', 'r.so_no = so.so_no', 'LEFT');

        $this->db->where($where);
        $this->include_dto($classname);
//      return $this->common_get_list($where, $option, $classname, "so.so_no, so.biz_type, so.platform_id, so.status, so.refund_status, so.hold_status, ore.reason_display_name, shr.reason as hold_reason, r.status as refund_status_progress, soex.notes as order_reason_note");
        return $this->common_get_list($where, $option, $classname, "so.so_no, so.biz_type, so.platform_id, so.status, so.refund_status, so.hold_status, ore.reason_id, ore.reason_display_name, r.status as refund_status_progress, soex.notes as order_reason_note");
    }

    public function get_orders_for_split($where = array(), $option = array())
    {
        $sql = <<<SQL
            SELECT
                # so.status, p.sourcing_status,
                so.so_no,so.platform_id, sops.payment_gateway_id, so.biz_type, so.order_create_date,
                sops.pay_date,
                DATEDIFF(NOW(),sops.pay_date) AS pay_day_diff,
                soprs.score AS priority_score,
                soi.line_no, skum.ext_sku AS master_sku, soi.prod_sku, p.`name`,
                soi.qty,
                so.currency_id, soi.amount,
                so.expect_del_days,
                p.surplus_quantity, p.slow_move_7_days,
                s.origin_country AS sourcing_country,
                p.sourcing_status,

                # complementary accessory
                CASE
                    WHEN p.cat_id = 753 THEN 'Y'
                    ELSE 'N'
                END AS is_CA,

                # recommended accessory
                CASE
                    WHEN ragp.sku IS NULL THEN 'N'
                    ELSE 'Y'
                END AS is_RA,

                coalesce(wmsi.inventory, 0) as ALN_inventory

            FROM so
            INNER JOIN so_item soi on soi.so_no = so.so_no
            LEFT JOIN so_payment_status sops ON sops.so_no = so.so_no
            LEFT JOIN sku_mapping skum ON skum.sku = soi.prod_sku AND skum.ext_sys = 'WMS' AND skum.`status` = 1
            LEFT JOIN so_priority_score soprs ON soprs.so_no = so.so_no
            INNER JOIN product p ON p.sku = soi.prod_sku
            INNER JOIN category c ON c.id = p.cat_id
            LEFT JOIN (SELECT DISTINCT(sku) FROM ra_group_product) AS ragp ON ragp.sku = soi.prod_sku
            LEFT JOIN supplier_prod sp ON sp.prod_sku = soi.prod_sku AND sp.order_default = 1
            LEFT JOIN supplier s ON s.id = sp.supplier_id
            LEFT JOIN wms_inventory wmsi ON wmsi.master_sku = skum.ext_sku AND wmsi.warehouse_id = 'ALN'
            WHERE
                # Orders must be credit checked, no refund, no hold
                so.so_no IN
                (
                    SELECT ssoi.so_no
                    FROM
                    (
                        # Orders that have more than one item after excluding CAs
                        SELECT soitem.so_no, COUNT(*) as soicnt
                        FROM so_item soitem
                        INNER JOIN so AS sso ON sso.so_no = soitem.so_no
                        INNER JOIN product p1 ON p1.sku = soitem.prod_sku AND p1.cat_id != 753
                        where sso.`status` = 3 AND sso.refund_status = 0 AND sso.hold_status = 0
                        GROUP BY soitem.so_no
                        HAVING soicnt > 1
                    ) as ssoi
                )

                OR

                so.so_no IN
                (
                    # Orders that contain items that are OOS or US Sourcing
                    SELECT
                    soi2.so_no
                    FROM so_item soi2
                    INNER JOIN so AS so2 ON so2.so_no = soi2.so_no
                    INNER JOIN product p2 on p2.sku = soi2.prod_sku
                    LEFT JOIN supplier_prod sp2 ON sp2.prod_sku = soi2.prod_sku AND sp2.order_default = 1
                    LEFT JOIN supplier s2 ON s2.id = sp2.supplier_id
                    WHERE
                        (p2.sourcing_status = 'o' OR s2.origin_country = 'US' OR s2.origin_country = 'C1' OR s2.origin_country = 'C2' )
                        AND so2.`status` = 3 AND so2.refund_status = 0 AND so2.hold_status = 0
                )

                # exclude orders that are split
                AND so.split_so_group IS NULL
                #AND so.platform_id LIKE 'WEB%'
            ORDER BY soi.so_no DESC, soi.line_no ASC
SQL;
        $query = $this->db->query($sql);

        $ret = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $ret[] = $row;
            }
        }

        return $ret;
    }

    public function send_daily_order_by_supplier($supplier)
    {
        $this->db->select('id');
        $this->db->from('supplier');
        $this->db->where('origin_country = ', $supplier);
        $this->db->where('status = 1');
        $this->db->where("name != 'VIRTUAL SUPPLIER'");
        if ($query = $this->db->get()) {
            $sourcing = $query->row_array();
            if (isset($sourcing['id'])) {
                $sql = "
                        SELECT
                            so.platform_id as `Platform`,
                            so.so_no as `Order number`,
                            si.line_no as `Line number`,
                            so.order_create_date as `Order creation date`,
                            '{$supplier}' as `Sourcing location`,
                            sm.ext_sku as `Master sku`,
                            si.prod_sku `Retail sku`,
                            p.`name` as `Description`
                        FROM
                            so
                        LEFT JOIN
                            so_item as si
                        ON
                            so.so_no = si.so_no
                        LEFT JOIN
                            sku_mapping as sm
                        ON
                            si.prod_sku = sm.sku
                        LEFT JOIN
                            supplier_prod as sp
                        ON
                            si.prod_sku = sp.prod_sku
                        LEFT JOIN
                            product as p
                        ON
                            si.prod_sku = p.sku
                        WHERE
                            sp.supplier_id = ?
                        AND
                            so.status >= 2
                        AND
                            sp.order_default = 1
                        AND
                            so.order_create_date >= date_sub(curdate(), interval 1 day)
                        AND
                            so.order_create_date <= curdate()
                        ORDER BY
                            so.so_no, si.line_no
                    ";
                if ($query = $this->db->query($sql, array($sourcing['id']))) {
                    $orders = $query->result_array();
                    foreach ($orders as $key => $order) {
                        $result = $this->get_order_item_num($order['Order number'], $sourcing['id']);
                        if ($result['num'] > 1) {
                            $orders[$key]['Boundled'] = 'TRUE';
                        } else {
                            $orders[$key]['Boundled'] = 'FALSE';
                        }
                    }
                    return $orders;
                }
            }
        }
        return FALSE;
    }

    private function get_order_item_num($so_no, $supplier_id)
    {
        $this->db->select('count(si.so_no) as num');
        $this->db->from('so_item as si');
        $this->db->join('supplier_prod as sp', 'si.prod_sku = sp.prod_sku', 'LEFT');
        $this->db->where('si.so_no = ', $so_no);
        $this->db->where('sp.supplier_id = ', $supplier_id);
        $this->db->where('sp.order_default = 1');

        if ($query = $this->db->get()) {
            return $query->row_array();
        }
        return FALSE;
    }

    public function get_order_beforeship()
    {
        $sql = <<<SQL
            SELECT
                so.so_no, so.platform_id, so.biz_type, sops.payment_gateway_id,
                parent.so_no AS parent_so_no, parent.payment_gateway_id AS parent_pmgw,
                CASE
                    WHEN so.status = 2 THEN 'Pending'
                    WHEN so.`status` = 3 THEN 'Paid'
                END AS status,
                 soprs.score, so.order_create_date
                from so
                left join so_priority_score soprs on soprs.so_no = so.so_no
                left join so_payment_status sops on sops.so_no = so.so_no
                left join
                ( select so.so_no, sps.payment_gateway_id from so left join so_payment_status sps on so.so_no = sps.so_no
                    WHERE so.`status` < 4  AND so.`status` > 1 AND so.`status` != 0 and so.refund_status = 0 #and so.hold_status = 0
                ) as parent on parent.so_no = so.parent_so_no
                WHERE so.`status` < 4  AND so.`status` > 1 AND so.`status` != 0 and so.refund_status = 0
                and so.hold_status = 0

SQL;
        $query = $this->db->query($sql);
        $ret = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $ret[] = $row;
            }
        }

        return $ret;
    }

    public function adroll_product_feed_list($where = array(), $option = array())
    {
        $this->db->from("product a");
        $this->db->join("price p", "a.sku = p.sku", "INNER");
        $this->db->join("platform_biz_var d", "d.selling_platform_id = p.platform_id", "INNER");
        $this->db->join("product_content pe", "d.language_id = pe.lang_id and a.sku = pe.prod_sku", "INNER");
        $this->db->join("currency e", "e.id = d.platform_currency_id", "INNER");
        $this->db->join("product_image c", "a.sku = c.sku", "LEFT");
        $this->db->where($where);

        if (isset($option["group_by"])) {
            $this->db->group_by($option["group_by"]);
        }

        $this->db->select("a.sku, pe.prod_name, a.status as url, c.alt_text as pic, p.price, d.platform_currency_id as curr, e.sign, e.sign_pos, e.dec_place, e.dec_point, e.thousands_sep, p.platform_id");
        if ($query = $this->db->get()) {
            return $query->result_array();
        }

        return FALSE;
    }

    public function order_held_for_cc_report($where = array(), $option = array())
    {
        $this->db->from("so_hold_reason shr");
        $this->db->join("so", "shr.so_no=so.so_no", "LEFT");
        $this->db->where($where);

        if (isset($option["group_by"])) {
            $this->db->group_by($option["group_by"]);
        }

        $this->db->select("so.platform_id, so.so_no, so.order_create_date, shr.modify_on, shr.modify_by");
        if ($query = $this->db->get()) {
            return $query->result_array();
        }
        return false;
    }

    public function send_not_chasing_order_report($where = array(), $option = array())
    {
        $this->db->from("so");
        $this->db->join("order_notes as orn", "orn.so_no=so.so_no", "LEFT");
        $this->db->where($where);

        if (isset($option["group_by"])) {
            $this->db->group_by($option["group_by"]);
        }

        $this->db->select("so.platform_id, so.so_no, so.order_create_date, CONCAT(\"'\", so.expect_del_days), so.modify_by as user, GROUP_CONCAT(orn.note) as notes");
        if ($query = $this->db->get()) {
            return $query->result_array();
        }
        return false;
    }

    public function send_not_chasing_order_report_alert2($where = array(), $option = array())
    {
        $this->db->from("so");
        $this->db->join("order_notes as orn", "orn.so_no=so.so_no", "LEFT");
        $this->db->where($where);

        if (isset($option["group_by"])) {
            $this->db->group_by($option["group_by"]);
        }

        $this->db->select("so.platform_id, so.so_no, so.order_create_date, CONCAT(\"'\", so.expect_del_days), so.expect_delivery_date, so.modify_by as user, GROUP_CONCAT(orn.note) as notes");
        if ($query = $this->db->get()) {
            return $query->result_array();
        }
        return false;
    }

    public function get_expect_delivery_date_report($where = array(), $option = array(), $classname = "Expect_delivery_date_report_dto")
    {
        $this->db->from("so");
        //$this->db->join("so_compensation_history as soch", "soch.so_no = soc.so_no and soch.status=2", "INNER");
        $this->db->join('client as c', "so.client_id = c.id", 'LEFT');
        $this->db->join("so_priority_score as `sps`", "so.so_no = sps.so_no", 'LEFT');
        $this->db->join("so_payment_status as `sops`", 'so.so_no = sops.so_no', 'LEFT');
        $this->db->where("(so.expect_delivery_date is not null AND so.expect_delivery_date != '')");
        $this->db->where("so.modify_by != 'system'");

        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "so.so_no, so.platform_id, `sops`.`payment_gateway_id`, so.platform_order_id, c.ext_client_id, so.txn_id, so.amount, so.order_create_date, so.expect_delivery_date, so.bill_name, c.email, CONCAT_WS('', `c`.`tel_1`, `c`.`tel_2`, `c`.`tel_3`) as contact_no, so.dispatch_date, so.status, so.hold_status, so.refund_status, sps.score, so.modify_by");
        //$this->common_get_list($where, $option, $classname, "so.platform_id, so.so_no, p.name prod_name, soc.item_sku,soc.create_by as request_by, soc.modify_on as approval_date, soc.modify_by as approved_by, soch.note as reason, soc.create_on as request_date");
        //var_dump($this->db->last_query());
    }

    public function get_compensation_report($where = array(), $option = array(), $classname = "Compensation_report_dto")
    {
        $this->db->from("so_compensation as soc");
        //$this->db->join("so_compensation_history as soch", "soch.so_no = soc.so_no and soch.status=2", "INNER");
        $this->db->join("(
            select * from so_compensation_history as temp3
            inner JOIN
            (select MAX(id) id_2 from so_compensation_history as temp2 where temp2.`status`=1 group by so_no) temp4 ON temp3.id= temp4.id_2
            )
            AS soch", "soch.so_no = soc.so_no", "INNER");

        $this->db->join("so", "soc.so_no = so.so_no", "INNER");
        $this->db->join("product AS p", "p.sku = soc.item_sku", "INNER");
        $this->db->join("category AS cat", "p.cat_id = cat.id", "INNER");
        $this->include_dto($classname);
        $where['soc.status'] = 2;
        return $this->common_get_list($where, $option, $classname, "so.platform_id, so.so_no, p.name prod_name, soc.item_sku,soc.create_by as request_by, soc.modify_on as approval_date, soc.modify_by as approved_by, soch.note as reason, soc.create_on as request_date");
        //$this->common_get_list($where, $option, $classname, "so.platform_id, so.so_no, p.name prod_name, soc.item_sku,soc.create_by as request_by, soc.modify_on as approval_date, soc.modify_by as approved_by, soch.note as reason, soc.create_on as request_date");
        //var_dump($this->db->last_query());
    }

    public function get_preorder_list($where = array(), $option = array(), $classname = "Preorder_list_dto")
    {
        $this->include_dto($classname);

        $this->db->from("so");
        $this->db->join("so_item as soi", "soi.so_no=so.so_no", "INNER");
        $this->db->join("product p", "p.sku=soi.prod_sku", "INNER");
        $this->db->groupby("so.so_no");
        $this->db->where($where);
        $this->db->select("so.so_no, soi.prod_sku, soi.prod_name, soi.qty, so.expect_delivery_date, so.create_on, p.expected_delivery_date as current_expected_delivery_date, count(1) as multiple_items_count", FALSE);
        if (empty($option["num_rows"])) {
            $list = $this->common_get_list($where, $option, $classname, NULL);
            return $list;
        } else {
            $table = " (" . $this->db->_compile_select() . ") as b";
            $this->db->_reset_select();
            $this->db->_reset_write();
            $this->db->from($table);
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }
        return false;
    }

    public function get_sourcing_region_report($where = array(), $option = array(), $classname = "Sourcing_region_report_dto")
    {
        $this->db->from("so");
        $this->db->join("so_item as soi", "soi.so_no = so.so_no", "INNER");
        $this->db->join("so_item_detail AS soid", "soid.so_no = so.so_no and soi.prod_sku=soid.item_sku", "INNER");
        $this->db->join("so_extend AS soex", "soex.so_no = so.so_no", "INNER");
        $this->db->join("supplier_prod sp", "sp.prod_sku = soi.prod_sku", "INNER");
        $this->include_dto($classname);

        $where['so.status >='] = 2;
        $where['sp.order_default ='] = 1;

        return $this->common_get_list($where, $option, $classname, "so.platform_id, soex.conv_site_id, so.so_no, so.order_create_date, soi.prod_name, soi.prod_sku, soi.qty, (so.amount * so.rate) as order_amount, (soid.unit_price*so.rate) as unit_price, (soid.profit * so.rate) as profit");
    }

    public function get_special_order_report($where = array(), $option = array(), $classname = "Special_order_report_dto")
    {
        $this->db->from("so");
        $this->db->join("order_status_history as osh", "so.so_no = osh.so_no and osh.status=6", "LEFT");
        $this->db->join("so_item_detail AS soid", "soid.so_no = so.so_no", "INNER");
        $this->db->join("so_extend AS sox", "sox.so_no = so.so_no", "INNER");
        $this->db->join("order_reason ore", "sox.order_reason=ore.reason_id", "LEFT");
        $this->db->join("product AS p", "p.sku = soid.item_sku", "INNER");
        $this->db->join("category AS cat", "p.cat_id = cat.id", "INNER");
        $this->db->join("(
                            SELECT sops.so_no, pm.name
                            FROM so_payment_status sops
                            JOIN payment_gateway pm
                                ON sops.payment_gateway_id = pm.id
                        ) AS pmgw", "pmgw.so_no = so.so_no", "LEFT");
        $this->include_dto($classname);
        $where['so.status >='] = 3;
        $where['so.biz_type'] = 'SPECIAL';
        return $this->common_get_list($where, $option, $classname, "so.biz_type, so.platform_id, pmgw.name pmgw_name, so.bill_country_id, so.txn_id, so.client_id, so.so_no, p.name prod_name, cat.name cat_name, soid.item_sku, so.dispatch_date, so.order_create_date, so.amount, so.delivery_type_id, so.currency_id, so.create_by as request_by, osh.create_on as approval_date, osh.create_by as approved_by, ore.reason_display_name as reason");
    }

    public function get_all_orders_report($start_date, $end_date, $so_number = "", $order_type = "", $psp_gateway = "", $hold_reason = "", $currency = "", $classname = "So_screening_dto")
    {
        $current_so_number = "";
        $trace_back = 0;
        $total_quantity = 0;
        $i = 0;
        $sql = "select s.so_no, s.create_on as order_create_date_time
                    , shr.reason as hold_reason, shr.create_on as hold_date_time, shr.create_by as hold_staff
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
                    , sor.risk_var1
                    , sor.risk_var2
                    , sor.risk_var3
                    , sor.risk_var4
                    , sor.risk_var5
                    , sor.risk_var6
                    , sor.risk_var7
                    , sor.risk_var8
                    , sor.risk_var9
                    , sor.risk_var10
                    , scc.t3m_is_sent as t3m_resp
                    , scc.t3m_result as t3m_score
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
                    , rr.description as refund_reason
            from so s
            left join so_hold_reason shr
                on s.so_no=shr.so_no
            left join so_payment_status sps
                on sps.so_no=s.so_no
            left join so_item_detail sid
                on sid.so_no=s.so_no
            inner join product p
                on p.sku=sid.item_sku
            inner join category cat
                on cat.id=p.cat_id
            left join client c
                on c.id=s.client_id
            left join so_credit_chk scc
                on scc.so_no=s.so_no
            left join refund r
                on r.so_no=s.so_no
            left join refund_item ri
                on r.id=ri.refund_id and ri.line_no=sid.line_no
            left join refund_reason rr
                on r.reason=rr.id
            left join so_risk sor on s.so_no=sor.so_no
            where ";

        if ($order_type != "") {
            $sql .= " s.platform_id='" . $order_type . "' and";
        }

        if ($psp_gateway != "") {
            $sql .= " sps.payment_gateway_id='" . $psp_gateway . "' and";
        }

        if ($hold_reason != "") {
            $sql .= " shr.reason='" . $hold_reason . "' and";
        }

        if ($currency != "") {
            $sql .= " s.currency_id='" . $currency . "' and";
        }

        if ($so_number != "") {
            $sql .= " s.so_no='" . $so_number . "' ";
        } else {
            $sql .= " s.create_on >= '" . $start_date . " 00:00:00' and
                        s.create_on <= '" . $end_date . " 23:59:59' ";
        }
        $sql .= " order by s.so_no";

        $this->include_dto($classname);

//      print $sql;
        $rs = array();
        $result = $this->db->query($sql);
        if (!$result) {
            return FALSE;
        }

        foreach ($result->result("object", $classname) as $row) {
            $rs[$i] = $row;
            $rs[$i]->set_order_quantity($rs[$i]->get_item_quantity());
//          $total_quantity += $rs[$i]->get_item_quantity();
            if ($current_so_number == $row->get_so_no()) {
                $trace_back++;
                $total_quantity += $rs[$i]->get_item_quantity();
                for ($j = ($i - $trace_back); $j <= $i; $j++) {
                    $rs[$j]->set_order_quantity($total_quantity);
                }
            } else {
                $trace_back = 0;
                $total_quantity = $rs[$i]->get_item_quantity();
            }
            $current_so_number = $row->get_so_no();
            $i++;
        }
        return $rs;
    }

    public function get_credit_check_list($where = array(), $option = array(), $type = "", $classname = "Credit_check_list_dto")
    {

        $this->db->from('so');

        $this->db->join('client AS c', 'c.id = so.client_id', 'INNER');

        if ($type == "") {
            /*$pw_where = " `so1`.`hold_status` = '0'
                            AND `so1`.`status` = '2'
                            AND `so1`.`refund_status` = '0'";*/
            $pw_where = "so.biz_type <> 'SPECIAL'";
        }

        if ($type == "cs") {
            /*$this->db->join('( SELECT a.so_no, a.reason
                                FROM so_hold_reason a
                                JOIN (SELECT so_no, max(create_on) as create_on
                                      FROM so_hold_reason
                                      GROUP BY so_no) AS c
                                ON a.create_on = c.create_on AND a.so_no = c.so_no
                                ) AS sohr', 'sohr.so_no = so.so_no', 'INNER');*/
			$this->db->join('( SELECT a.so_no, a.reason
                                FROM so_hold_reason a
                                ) AS sohr', 'sohr.so_no = so.so_no', 'INNER');
            $this->db->where("(sohr.reason = 'csvv' OR sohr.reason = 'cscc')");


        }

        if (($type == "oc") || ($type == "comcenter")) {
            /*$this->db->join('( SELECT a.so_no, a.reason
                                FROM so_hold_reason a
                                JOIN (SELECT so_no, max(create_on) as create_on
                                      FROM so_hold_reason
                                      GROUP BY so_no) AS c
                                ON a.create_on = c.create_on AND a.so_no = c.so_no
                                ) AS sohr', 'sohr.so_no = so.so_no', 'INNER');*/
			$this->db->join('( SELECT a.so_no, a.reason
                                FROM so_hold_reason a                               
                                ) AS sohr', 'sohr.so_no = so.so_no', 'INNER');
								
            /*$pw_where = " `so1`.`status` > '2'
                            AND `sol`.`status` < '6'
                            AND `so1`.hold_status` = '1'
                            AND `so1`.`refund_status` = '0'";*/
        }

        if ($type == "ora") {
            /*$this->db->join('( SELECT a.so_no, a.reason
                                FROM so_hold_reason a
                                JOIN (SELECT so_no, max(create_on) as create_on
                                      FROM so_hold_reason
                                      GROUP BY so_no) AS c
                                ON a.create_on = c.create_on AND a.so_no = c.so_no
                                ) AS sohr', 'sohr.so_no = so.so_no', 'LEFT');*/
			$this->db->join('( SELECT a.so_no, a.reason
                                FROM so_hold_reason a
                                ) AS sohr', 'sohr.so_no = so.so_no', 'LEFT');
        }

        if ($type == "log_app") {
            /*$this->db->join('( SELECT a.so_no, a.reason
                                FROM so_hold_reason a
                                JOIN (SELECT so_no, max(create_on) as create_on
                                      FROM so_hold_reason
                                      GROUP BY so_no) AS c
                                ON a.create_on = c.create_on AND a.so_no = c.so_no
                                ) AS sohr', 'sohr.so_no = so.so_no', 'INNER');*/
			$this->db->join('( SELECT a.so_no, a.reason
                                FROM so_hold_reason a
                                ) AS sohr', 'sohr.so_no = so.so_no', 'INNER');
            $this->db->where("sohr.reason LIKE '%_log_app'");

            /*          $pw_where = "   `so1`.`status` > '2'
                                        AND `sol`.`status` < '6'
                                        AND `so1`.hold_status` = '1'
                                        AND `so1`.`refund_status` = '0'";*/

        }

        if ($option["item"]) {
            $this->db->join('(
                        SELECT si.so_no, GROUP_CONCAT(CONCAT_WS("::", si.prod_sku, p.name, CAST(si.qty AS CHAR), CAST(si.unit_price AS CHAR), CAST(si.amount AS CHAR)) ORDER BY si.line_no SEPARATOR "||") AS items
                        FROM so_item AS si
                        INNER JOIN product AS p
                            ON (si.prod_sku = p.sku)
                        GROUP BY so_no
                        ) AS soi', 'so.so_no = soi.so_no', 'LEFT');

            $this->db->select('soi.items');
        }

        if ($option["reason"]) {
            $this->db->join('so_hold_reason AS sohr', 'so.so_no = sohr.so_no', 'LEFT');
        }

        $this->db->join('so_credit_chk AS socc', 'so.so_no = socc.so_no', 'LEFT');
        $this->db->join('so_payment_status AS sops', 'sops.so_no = so.so_no', 'LEFT');
        $this->db->join('risk_ref AS rr', 'rr.payment_gateway_id = sops.payment_gateway_id AND rr.risk_ref = sops.risk_ref_1', 'LEFT');

        //#2309 cybs accept/review/reject, the so_risk_vo is already included in so_service. To enable searching by this field, JOIN the so_risk here
        $this->db->join('so_risk as sor', 'sor.so_no = so.so_no', 'LEFT');

        $this->db->where($where);
        if ($pw_where != "") {
            $this->db->where($pw_where);
        }
        $this->db->where("so.refund_status = '0'");

        if (empty($option["num_rows"])) {
            $this->db->select('so.*, c.id, c.forename, c.surname, c.email, c.password, c.tel_1, c.tel_2, c.tel_3, c.del_tel_1, c.del_tel_2, c.del_tel_3,' . ($option["reason"] ? ', sohr.reason, sohr.create_on AS hold_date' : ', socc.t3m_is_sent, socc.t3m_in_file, socc.t3m_result, socc.fd_status, sops.payment_gateway_id, sops.payment_status, sops.card_id AS card_type, sops.risk_ref_1, sops.risk_ref_2, sops.risk_ref_3, sops.risk_ref_4, sops.pending_action, rr.risk_ref_desc'));
            //$this->db->select('p.pw_count');
            //$this->db->select('soi.items');
            if ($type == "cs" || $type == "log_app" || $type == "oc" || $type == "ora") {
                $this->db->select('sohr.reason');
            }


            $this->include_dto($classname);

            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }


            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }


            $rs = array();

            if ($query = $this->db->get()) {

                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }

                return (object)$rs;
            }

        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function get_order_item_list_done($so_no)
    {
        $sql = "SELECT GROUP_CONCAT(CONCAT_WS('::',soi.prod_sku, COALESCE(soi.prod_name,' '), p.name, CAST(soi.qty as CHAR), CAST(soi.unit_price AS CHAR), CAST(soi.amount AS CHAR),CAST(soi.vat_total AS CHAR),IFNULL(p.image,' '),soi.warranty_in_month) ORDER BY soi.line_no SEPARATOR '||' ) as items
                FROM so_item soi
                JOIN product p
                    ON soi.prod_sku = p.sku
                WHERE soi.so_no = ?
                GROUP BY soi.so_no";

        if ($query = $this->db->query($sql, $so_no)) {
            return $query->row()->items;
        }
        return FALSE;
    }

    public function get_order_item_list($so_no)
    {
        $sql = "
                SELECT GROUP_CONCAT(CONCAT_WS('::',soi.prod_sku, COALESCE(soi.prod_name,' '), p.name, CAST(soi.qty as CHAR), CAST(soi.unit_price AS CHAR), CAST(soi.amount AS CHAR),CAST(soi.vat_total AS CHAR),IFNULL(p.image,' '),soi.warranty_in_month,CAST(o.inventory AS CHAR),CAST(o.outstanding AS CHAR),CAST(o.outorder AS CHAR)) ORDER BY soi.line_no SEPARATOR '||' ) as items
                FROM so_item soi
                JOIN product p
                    ON soi.prod_sku = p.sku
                LEFT JOIN
                (
                    SELECT item_sku, IFNULL(inv.inventory,0) AS inventory, sum(so_item_detail.outstanding_qty) AS outstanding, COUNT(so_item_detail.so_no) AS outorder
                    FROM so_item_detail
                    JOIN so
                        ON so.so_no = so_item_detail.so_no
                        AND so.status BETWEEN 3 AND 5
                        AND so.refund_status = 0
                        AND (so.hold_status = 0 OR so.hold_status = 3)
                    LEFT JOIN ( SELECT prod_sku , SUM(inventory) as inventory
                                FROM inventory
                                GROUP BY prod_sku) AS inv
                        ON inv.prod_sku = so_item_detail.item_sku
                    WHERE so_item_detail.item_sku IN (
                                                        SELECT item_sku
                                                        FROM so_item_detail soid2
                                                        WHERE soid2.so_no = ?
                                                    )
                    GROUP BY so_item_detail.item_sku
                ) AS o
                    ON o.item_sku = soi.prod_sku
                WHERE soi.so_no = ?
                GROUP BY soi.so_no";

        if ($query = $this->db->query($sql, array($so_no, $so_no))) {
            return $query->row()->items;
        }
        return FALSE;
    }

    public function get_pwd_cnt($so_no = "", $cid = "")
    {
        if ($so_no == "" || $cid == "") {
            return FALSE;
        }

        $sql = "SELECT count(1) AS pw_count
                FROM so so1
                INNER JOIN `client` AS c1
                    ON `c1`.`id` = `so1`.`client_id`
                    AND `c1`.`id` = ?
                INNER JOIN `client` AS pwd
                    ON c1.password = pwd.password
                WHERE so1.so_no = ?
                GROUP BY so1.so_no";

        if ($query = $this->db->query($sql, array($cid, $so_no))) {
            return $query->row()->pw_count;
        }

        return FALSE;
    }

    public function get_item_detail_str($so_no = "")
    {
        if ($so_no == "") {
            return FALSE;
        }

        $sql = "SELECT GROUP_CONCAT(CONCAT_WS('::', si.prod_sku, p.name, CAST(si.qty AS CHAR), CAST(si.unit_price AS CHAR), CAST(si.amount AS CHAR)) ORDER BY si.line_no SEPARATOR '||') AS items
                FROM so_item AS si
                INNER JOIN product AS p
                    ON (si.prod_sku = p.sku)
                WHERE si.so_no = ?
                GROUP BY so_no";

        if ($query = $this->db->query($sql, array($so_no))) {
            return $query->row()->items;
        }
        return FALSE;
    }

    public function order_quick_search($where = array(), $option = array(), $classname = "Quick_search_result_dto")
    {
        $this->db->from('so');

        $this->db->join('client c', 'c.id = so.client_id', 'LEFT');

        $this->db->join('so_extend soe', 'soe.so_no = so.so_no', 'LEFT');

        $this->db->join('so_credit_chk socc', 'socc.so_no = so.so_no', 'LEFT');

        $this->db->join('so_payment_status sops', 'sops.so_no = so.so_no', 'LEFT');

        $this->db->join('payment_gateway pmgw', 'pmgw.id = sops.payment_gateway_id', 'LEFT');

        // $this->db->join('so_bank_transfer sbt','sbt.so_no = so.so_no','LEFT');

        if ($option["detail"] != "") {
            $this->db->join('(SELECT si.so_no, GROUP_CONCAT(CONCAT_WS(\'::\', si.prod_sku, p.name, CAST(si.qty AS CHAR), CAST(si.unit_price AS CHAR), CAST(si.amount AS CHAR),CAST(si.vat_total AS CHAR),IFNULL(p.image," "),si.warranty_in_month)
                            ORDER BY si.line_no SEPARATOR \'||\') AS items
                            FROM so_item AS si
                            JOIN product AS p
                                ON (si.prod_sku = p.sku)
                            WHERE si.so_no = \'' . $option["so_no"] . '\'
                            GROUP BY so_no) as soi', 'soi.so_no = so.so_no', 'INNER');
            $this->db->select('soi.items, soe.fulfilled', FALSE);

            $this->db->join('(SELECT sbt.so_no, SUM(sbt.received_amt_localcurr) AS bt_total_received, SUM(sbt.bank_charge) AS bt_total_bank_charge
                            FROM so_bank_transfer AS sbt
                            WHERE sbt.so_no = \'' . $option["so_no"] . '\') as sobt', 'sobt.so_no = so.so_no', 'LEFT');
            $this->db->select('sobt.bt_total_received, sobt.bt_total_bank_charge', FALSE);
        }
        $this->db->join('selling_platform sp', 'sp.id = so.platform_id', 'INNER');

        if ($where["tracking_no"] != "" || $where["tracking_no LIKE "] != "" || isset($option["detail"])) {
            $type = "INNER";

            $join_sql = "(SELECT a.so_no, GROUP_CONCAT(CONCAT_WS('::',a.sh_no,a.item_sku,a.qty, b.courier_id,b.tracking_no,b.create_on) ORDER BY a.sh_no SEPARATOR '||') AS tracking_no
                          FROM so_allocate a
                          JOIN so_shipment b
                            ON b.sh_no = a.sh_no";
            if (isset($option["detail"])) {
                $type = "LEFT";
            }

            if ($where["tracking_no"] != "") {
                $join_sql .= "AND b.tracking_no = '" . $where["tracking_no"] . "' ";
            }

            $this->db->join($join_sql . ' GROUP BY a.so_no) AS soa', 'soa.so_no = so.so_no', $type);

            $this->db->select("soa.tracking_no");
        }

        if ($where["refund_status"] != "") {
            if ($where["refund_status"] === "0") {
                $where["so.refund_status"] = 0;
            } else if ($where["refund_status"] === "1") {
                $where["so.refund_status >"] = 0;
                $where["so.refund_status <"] = 4;
            } else {
                $where["so.refund_status"] = 4;
            }
            unset($where["refund_status"]);
        }

        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"])) {
            $dispatch_string = check_finance_role(true);
            $this->db->select("
                    IF(ISNULL(so.split_so_group), so.so_no, CONCAT_WS('/',so.so_no,so.split_so_group)) AS concat_so_no,
                    so.so_no, so.platform_order_id, so.txn_id,so.delivery_type_id as delivery_mode,so.biz_type, so.delivery_name, so.order_create_date,so.expect_delivery_date, so.platform_id, so.amount, so.currency_id, so.cost,sp.name, sops.payment_status, so.status, so.refund_status, so.hold_status, so.delivery_charge, so.client_id, so.platform_id ,so.currency_id, " . $dispatch_string . ", c.forename, c.email, c.`password`, c.surname,  c.email, CONCAT_WS(' ',c.tel_1,c.tel_2,c.tel_3) AS tel,
                    sops.payment_gateway_id, pmgw.name AS payment_gateway_name, socc.fd_status, c.ext_client_id,
                    so.expect_ship_days, so.expect_del_days, so.cs_customer_query, so.split_so_group", FALSE);

            $this->include_dto($classname);

            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $rs = array();

            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            }

        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function cc_get_order_detail($so_no)
    {

    }

    public function get_so_w_pmgw($where = array(), $option = array())
    {

        $this->db->from('so');
        $this->db->join('so_payment_status AS sops', 'so.so_no = sops.so_no', 'INNER');

        $this->db->where($where);

        if (empty($option["num_rows"])) {

            $this->include_vo();
            $this->db->select('so.*');

            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }

            $rs = array();

            if ($query = $this->db->get()) {
                $rs = array();
                foreach ($query->result($this->get_vo_classname()) as $obj) {
                    $rs[] = $obj;
                }
                if ($option["limit"] == 1) {
                    return $rs[0];
                } else {
                    return (object)$rs;
                }
            }

        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }

    public function get_orders_for_dm($where = array(), $option = array(), $classname = "So_w_client_and_item_dto")
    {
        $this->db->from("so");
        $this->db->join("so_payment_status as sps", "sps.so_no=so.so_no and sps.payment_status='S'", 'INNER');
        $this->db->join("client as c", "c.id=so.client_id and so.status in (2, 3)", 'INNER');
        $this->db->join("so_item as si", "si.so_no=so.so_no", 'INNER');
        $this->db->join("so_risk as sr", "sr.so_no=so.so_no and sr.risk_requested=0", 'INNER');
        $this->db->where($where);
        $this->db->select("so.so_no, so.currency_id, so.amount, so.create_at, so.lang_id, so.fingerprint_id, sps.payment_gateway_id, sps.risk_ref3, sps.risk_ref4, sps.payer_email,
        si.line_no, si.prod_sku, si.prod_name, si.qty, si.unit_price,
        c.email, c.companyname, c.del_company, c.address_1, c.address_2, c.address_3, c.postcode, c.city, c.state, c.country_id, c.del_address_1, c.del_address_2, c.del_address_3, c.del_postcode, c.del_city, c.del_state, c.del_country_id, c.forename, c.surname, c.tel_1, c.tel_2, c.tel_3");
        $this->include_dto($classname);

        if (isset($option["orderby"])) {
            $this->db->order_by($option["orderby"]);
        }

        if (empty($option["limit"])) {
            $option["limit"] = $this->rows_limit;
        } elseif ($option["limit"] == -1) {
            $option["limit"] = "";
        }

        if (!isset($option["offset"])) {
            $option["offset"] = 0;
        }

        if ($this->rows_limit != "") {
            $this->db->limit($option["limit"], $option["offset"]);
        }
        $rs = array();

//      print $this->db->_compile_select();
        if ($query = $this->db->get()) {
//          print $this->db->last_query();
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        }
    }

    public function get_list_w_name($where = array(), $option = array(), $classname = "So_list_w_name_dto")
    {
        $this->db->from('so');
//      $select_str = "so.*, so.delivery_type_id AS delivery_courier";
        $select_str = "so.so_no, so.platform_id, so.order_create_date, so.delivery_name, so.delivery_country_id, ore.reason, ore.require_payment, so.currency_id, so.amount, so.create_by";
        if ($option["so_item"]) {
            $this->db->join('(
                            SELECT si.so_no, GROUP_CONCAT(CONCAT_WS("::", si.prod_sku, p.name, CAST(si.qty AS CHAR), CAST(si.unit_price AS CHAR), CAST(si.amount AS CHAR)) ORDER BY si.line_no SEPARATOR "||") AS items
                            FROM so_item AS si
                            LEFT JOIN product AS p
                                ON (si.prod_sku = p.sku)
                            GROUP BY so_no
                            ) AS soi', 'so.so_no = soi.so_no', 'INNER');
            $select_str .= ", soi.items";
        } elseif (empty($option["num_rows"]) || isset($where["multiple"])) {
            $where_str = $option["hide_shipped_item"] ? "WHERE sid.status = 0" : "";
            $this->db->join("(
                            SELECT sid.so_no, IF(COUNT(sid.item_sku)>1 OR SUM(sid.outstanding_qty)>1, 'Y', 'N') AS multiple, SUM(sid.outstanding_qty) AS sum_oqty, GROUP_CONCAT(CONCAT_WS('::', sid.item_sku, p.name, CAST(sid.outstanding_qty AS CHAR), CAST(COALESCE(i.inventory,0) AS CHAR), CAST(COALESCE(i.git,0) AS CHAR)) ORDER BY sid.line_no SEPARATOR '||') AS items, SUM(COALESCE(i.inventory,0)<sid.outstanding_qty) AS o_items
                            FROM so_item_detail AS sid
                            LEFT JOIN product AS p
                                ON (sid.item_sku = p.sku)
                            LEFT JOIN
                                (SELECT prod_sku, COALESCE(SUM(inventory),0) AS inventory, COALESCE(SUM(git),0) AS git
                                FROM inventory
                                WHERE warehouse_id = '{$option["warehouse_id"]}'
                                GROUP BY prod_sku
                                ) AS i
                                ON (sid.item_sku = i.prod_sku)
                            {$where_str}
                            GROUP BY so_no
                            ) AS soid", 'so.so_no = soid.so_no', 'INNER');
            $select_str .= ", soid.multiple, soid.items, soid.o_items ";
        }

        if (!$option["hide_client"]) {
            $this->db->join('client AS c', 'c.id = so.client_id', 'INNER');
            $select_str .= ", CONCAT(c.forename, ' ', c.surname) AS client_name, c.email";
        }

        if (!$option["hide_payment"]) {
            $this->db->join('so_payment_status AS sops', 'sops.so_no = so.so_no', 'LEFT');
            $select_str .= ", sops.payment_gateway_id";
        }

        if ($option["notes"]) {
            $this->db->join('(
                                SELECT so_no, note, MAX(create_on) AS mco
                                FROM order_notes
                                GROUP BY so_no
                            ) AS so_n', 'so_n.so_no = so.so_no', 'LEFT');
            $select_str .= ", so_n.note";
        }

        if ($option["extend"]) {
            $this->db->join('so_extend AS soe', 'soe.so_no = so.so_no', 'LEFT');
            $select_str .= ", soe.order_reason, soe.offline_fee";
        }

        $this->db->join("order_reason ore", "soe.order_reason=ore.reason_id", "INNER");

        if ($option["credit_chk"]) {
            $this->db->join('so_credit_chk socc', 'socc.so_no = so.so_no', 'LEFT');
            $select_str .= ", socc.t3m_result";
        }

        if ($option["solist"] != "") {
            $so_list = "(" . implode(",", $option["solist"]) . ")";
            $where["so.so_no in " . $so_list] = null;
            unset($option["solist"]);
        }

        if ($option["solist"] != "") {
            $this->db->where_in("so.so_no", $option["solist"]);
        }

        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"]) && empty($option["total_items"])) {

            $this->db->select($select_str, FALSE);

            $this->include_dto($classname);

            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $rs = array();

            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            }

        } else {
            if ($where["si.website_status"]) {
                $this->db->join("so_item AS si", "si.so_no = so.so_no", "LEFT");
            }
            $this->db->select(($option["num_rows"] ? 'COUNT(*)' : 'SUM(soid.sum_oqty)') . ' AS total');

            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function get_list_by_item_sku($where = array(), $option = array(), $classname = "So_list_w_name_dto")
    {
        $this->db->from('so_item_detail AS soid');
        $this->db->join('so', 'so.so_no = soid.so_no', 'INNER');
//      $select_str = "so.*, so.delivery_type_id AS delivery_courier";
        $select_str = "so.so_no, so.platform_id, so.order_create_date, so.delivery_name, so.delivery_country_id";

        if (empty($option["num_rows"]) && empty($option["total_items"])) {
            $this->db->join('product AS p', 'soid.item_sku = p.sku', 'LEFT');
            //#2214
//          $this->db->join('so_item AS si', 'soid.so_no = si.so_no and soid.line_no = si.line_no and soid.item_sku = si.prod_sku', 'INNER');
            $this->db->join("(
                            SELECT prod_sku, COALESCE(SUM(inventory),0) AS inventory, COALESCE(SUM(git),0) AS git
                            FROM inventory
                            WHERE warehouse_id = '{$option["warehouse_id"]}'
                            GROUP BY prod_sku
                            ) AS i", 'soid.item_sku = i.prod_sku', 'LEFT');
//          $select_str .= ", CONCAT_WS('::', soid.item_sku, p.name, CAST(soid.outstanding_qty AS CHAR), CAST(COALESCE(i.inventory,0) AS CHAR), CAST(COALESCE(i.git,0) AS CHAR), si.website_status) AS items";
            $select_str .= ", CONCAT_WS('::', soid.item_sku, p.name, CAST(soid.outstanding_qty AS CHAR), CAST(COALESCE(i.inventory,0) AS CHAR), CAST(COALESCE(i.git,0) AS CHAR)) AS items";
        }

        if ((empty($option["num_rows"]) && empty($option["total_items"])) || isset($where["multiple"])) {
            $this->db->join("(
                            SELECT so_no, IF(COUNT(item_sku)>1 OR SUM(outstanding_qty)>1, 'Y', 'N') AS multiple, SUM(COALESCE(inv.inventory,0)<sid2.outstanding_qty) AS o_items, IFNULL(inv.git,0) AS git
                            FROM so_item_detail AS sid2
                            LEFT JOIN
                                (SELECT prod_sku, COALESCE(SUM(inventory),0) AS inventory, COALESCE(SUM(git),0) AS git
                                FROM inventory
                                WHERE warehouse_id = '{$option["warehouse_id"]}'
                                GROUP BY prod_sku
                                ) AS inv
                                ON (sid2.item_sku = inv.prod_sku)
                            GROUP BY so_no
                            ) AS sid", 'so.so_no = sid.so_no', 'INNER');
            $select_str .= ", sid.multiple, sid.o_items, sid.git";
        }

        if ($option["notes"]) {
            $this->db->join('(SELECT so_no, note, MAX(create_on) AS mco
                                    FROM order_notes
                                    GROUP BY so_no
                            ) AS so_n', 'so_n.so_no = so.so_no', 'LEFT');
            $select_str .= ", so_n.note";
        }

        //#2214
        if (!$option["hide_payment"]) {
            $this->db->join('so_payment_status AS sops', 'sops.so_no = soid.so_no', 'LEFT');
            $select_str .= ", sops.payment_gateway_id";
        }

        if ($option["hide_shipped_item"]) {
            $where["soid.status"] = 0;
        }

        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"]) && empty($option["total_items"])) {

            $this->db->select($select_str, FALSE);

            $this->include_dto($classname);

            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $rs = array();

            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            }

        } else {
            if ($where["si.website_status"]) {
                $this->db->join('so_item AS si', 'soid.so_no = si.so_no and soid.line_no = si.line_no and soid.item_sku = si.prod_sku', 'INNER');
            }
            $this->db->select(($option["num_rows"] ? 'COUNT(*)' : 'SUM(soid.outstanding_qty)') . ' AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function get_refundable_order($where = array(), $option = array())
    {
        $this->db->from($this->get_table_name());

        //$this->db->where("status > 1 AND (refund_status = 0 OR refund_status > 3)");

        $this->db->where($where);

        // don't allow parent of split orders
        $this->db->where(array("so.hold_status != 15 " => NULL));

        if ($option["create"] == 1) {
            $this->db->where("(refund_status = 0 AND status > 2)");
        }

        if ($option["num_row"] == "") {

            $this->db->select("*");

            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $this->include_vo();

            $rs = array();

            if ($query = $this->db->get()) {
                foreach ($query->result($$this->get_vo_classname()) as $obj) {
                    $rs[] = $obj;
                }

                return $rs;
            }
            return FALSE;
        } else {
            $this->db->select("COUNT(*) AS total");

            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
            return FALSE;
        }
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_quantity_in_orders($sku = "", $num_of_days = "7")
    {
        if ($sku == "") {
            return FALSE;
        }

        $from_date = date("Y-m-d", strtotime("-" . $num_of_days . " days")) . " 00:00:00";

        $sql = "SELECT item_sku, IFNULL(sum(qty),0) as qty
                FROM so_item_detail soid
                JOIN so
                    ON so.so_no = soid.so_no
                    AND so.status > '2'
                    AND so.order_create_date >= ?
                WHERE soid.item_sku = ?
                GROUP BY item_sku";

        if ($query = $this->db->query($sql, array($from_date, $sku))) {
            $cnt = $query->row()->qty;

            return $cnt ? $cnt : 0;
        }
        return FALSE;
    }

    public function get_ack_list($platform = "", $classname = "Amazon_ack_dto")
    {
        if ($platform == "") {
            return FALSE;
        }

        $this->db->from('so');

        $this->db->join('so_item soi', 'so.so_no = soi.so_no', 'LEFT');

        $this->db->where(array("so.status" => 1, "so.platform_id" => $platform, "so.biz_type" => "AMAZON"));

        $this->db->select('so.platform_order_id, so.so_no, \'Success\' as status_code, soi.ext_item_cd, soi.prod_sku', FALSE);

        $this->include_dto($classname);

        $rs = array();

        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }

        return FALSE;
    }

    public function get_fulfillment_list($platform = "", $classname = "Amazon_fulfillment_dto")
    {
        if ($platform == "") {
            return FALSE;
        }

        $this->db->from('so');

        $this->db->join('so_item soi', 'so.so_no = soi.so_no', 'LEFT');

        $this->db->join('so_extend soext', 'so.so_no = soext.so_no AND soext.fulfilled=\'N\'', 'INNER');

        $this->db->join('(SELECT soa.so_no, soa.line_no, sosh.courier_id, sosh.tracking_no
                            FROM so_shipment sosh
                            JOIN so_allocate soa
                                ON soa.sh_no = sosh.sh_no
                            WHERE soa.status = \'3\'
                            AND sosh.status=\'2\') AS soash', 'soash.so_no = soi.so_no ', 'INNER');

        $this->db->where(array("so.status" => 6, "so.platform_id" => $platform));

        $this->db->select('so.platform_order_id, so.so_no, so.dispatch_date as shipdate, soi.ext_item_cd, soi.prod_sku, soi.qty, soash.courier_id, soash.tracking_no', FALSE);

        $this->include_dto($classname);

        $this->db->group_by("soi.ext_item_cd");

        $this->db->order_by("so.so_no", "ASC");

        $rs = array();

        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }

        return FALSE;
    }

    public function get_fnac_fulfillment_list($platform = "FNACFR", $classname = "Fnac_fulfillment_dto")
    {
        if ($platform == "") {
            return FALSE;
        }

        $this->db->from('so');

        $this->db->join('so_item soi', 'so.so_no = soi.so_no', 'LEFT');

        $this->db->join('so_extend soext', 'so.so_no = soext.so_no AND soext.fulfilled=\'N\'', 'INNER');

        $this->db->join('so_allocate soa', "soa.so_no = so.so_no AND soa.line_no = soi.line_no AND soa.item_sku = soi.prod_sku", 'INNER');

        $this->db->join('so_shipment sosh', "sosh.sh_no = soa.sh_no", 'INNER');

        // $this->db->join('(SELECT soa.so_no, soa.line_no, sosh.courier_id, sosh.tracking_no
        //                  FROM so_shipment sosh
        //                  JOIN so_allocate soa
        //                      ON soa.sh_no = sosh.sh_no
        //                  WHERE soa.status = \'3\'
        //                  AND sosh.status=\'2\') AS soash','soash.so_no = soi.so_no ','INNER');

        $this->db->where(array("so.status" => 6, "so.refund_status" => 0, "so.platform_id" => $platform, "sosh.status" => 2, "soa.status" => 3));

        $this->db->select('so.platform_order_id, so.so_no, so.dispatch_date as shipdate, sosh.courier_id, sosh.tracking_no', FALSE);

        $this->include_dto($classname);

        $this->db->group_by("so.so_no");

        $this->db->order_by("so.so_no", "ASC");

        $rs = array();

        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }

        return FALSE;
    }

    public function get_amazon_order_number($platform = "AMUK")
    {
        $ret = array();

        $this->db->from('so');

        $this->db->where('platform_id', $platform);

        $this->db->where('platform_order_id IS NOT NULL');

        $this->db->select('platform_order_id');

        if ($query = $this->db->get()) {
            foreach ($query->result("object") as $obj) {
                $ret[$obj->platform_order_id] = 1;
            }
            return $ret;
        }

        return FALSE;
    }

    public function get_confirmed_so($where = array(), $from_date = '', $to_date = '', $is_light_version = false, $dispatch_report = false)
    {

        $table_alias = array('platform_biz_var' => 'pbz');
        include_once APPPATH . "helpers/string_helper.php";
        include_once APPPATH . "helpers/array_helper.php";
        $new_where = replace_db_alias($where, $table_alias);

        //$value_list = array($from_date . ' 00:00:00', $to_date . ' 23:59:59');
        $value_list = array();

        if (!isset($new_where['so.status'])) {
            $new_where['so.status >='] = 2;
        }

        // exclude parent of split orders
        $new_where['so.hold_status !='] = 15;

        if (!$dispatch_report) {
            if (!array_similar_key_exists($where, 'pay_date')) {
                #sbf #4530 use so_payment_status.pay_date
                $new_where['sps.pay_date'] = ' AND sps.pay_date BETWEEN ? AND ?';
            }
        }

        if ($new_where && count($new_where) > 0) {
            $where_clause = '';

            foreach ($new_where as $key => $value) {
                if (strlen($where_clause) > 0) {
                    $where_clause .= ' AND ';
                } else {
                    $where_clause = ' WHERE ';
                }

                if (strpos($key, 'modify_on') === FALSE && strpos($key, "pay_date") === FALSE) {
                    if (is_array($value)) {
                        $where_clause .= "$key IN (";
                        $temp_clause = '';

                        foreach ($value as $element) {
                            if (strlen($temp_clause) > 0) {
                                $temp_clause .= ', ';
                            }

                            $temp_clause .= '?';
                            array_push($value_list, $value);
                        }

                        $where_clause .= $temp_clause . ')';
                    } else {
                        $no_value = false;
                        if (is_null($value)) {
                            //$where_clause .= "$key ";

                            if ($this->db->_has_operator($key)) {
                                $where_clause .= "$key ";
                                $no_value = true;
                            } else {
                                $where_clause .= "$key is ?";
                            }
                        } else {
                            if ($this->db->_has_operator($key)) {

                                $where_clause .= "$key ?";
                            } else {
                                $where_clause .= "$key = ?";
                            }
                        }

                        if (!$no_value) {
                            array_push($value_list, $value);
                        }
                    }
                } else {
                    // # sbf#3870 need date parameter to be applied to different fields based on order success (not refund and payment_status = S)
                    // if(strpos($key, "sps.modify_on") !== FALSE)
                    //  $where_clause .= "(so.refund_status = 0 AND sps.payment_status = 'S' AND $key BETWEEN ? AND ?)";

                    // if(strpos($key, "so.modify_on") !== FALSE)
                    //  $where_clause .= "( (so.refund_status != 0 OR sps.payment_status != 'S') AND so.order_create_date BETWEEN ? AND ?)";

                    $where_clause .= "$key BETWEEN ? AND ?";

                    if (is_array($value) && count($value) >= 2) {
                        array_push($value_list, $value[0] . ' 00:00:00');
                        array_push($value_list, $value[1] . ' 23:59:59');
                    } else {
                        array_push($value_list, $from_date . ' 00:00:00');
                        array_push($value_list, $to_date . ' 23:59:59');
                    }
                }

                $counter++;
            }
        }

        /*
                $sql = "SELECT
                            so.platform_id, sps.payment_gateway_id, so.biz_type, so.txn_id, so.so_no, so.platform_order_id, soex.conv_site_id, sa.warehouse_id,
                            cat.name cat_name, sc.name sub_cat_name, b.brand_name, p.name prod_name,
                            p.sku, soid.qty, so.dispatch_date, so.order_create_date, so.currency_id,
                            IF(rf.refund_status='C', (so.amount - if(rf.total_refund_amount IS NULL,'0',rf.total_refund_amount)),so.amount) as amount,
                            ROUND(IF(rf.refund_status='C', pbz.payment_charge_percent * (so.amount - if(rf.refund_amount IS NULL,0,rf.total_refund_amount)) / 100, pbz.payment_charge_percent * so.amount / 100),2) AS fee,
                            ROUND(IF(rf.refund_status='C', 0, (so.amount - pbz.payment_charge_percent * so.amount / 100)),2) AS receivable,
                            ROUND(soid.vat_total, 2)vat_total,
                            ROUND(IF(rf.refund_status='C', (soid.amount - if(rf.refund_amount is NULL,'0',rf.refund_amount) - ROUND((soid.cost / soid.qty * (soid.qty - if(rf.refund_qty is NULL,'0',rf.refund_qty))),2)), (soid.amount - soid.cost)),2) AS profit,
                            ROUND(IF(rf.refund_status='C', ((soid.amount - if(rf.refund_amount is NULL,'0',rf.refund_amount) - (soid.cost / soid.qty * (soid.qty - if(rf.refund_qty is NULL,'0',rf.refund_qty)))) * 100 / (soid.amount - pbz.payment_charge_percent * soid.amount / 100)), ((soid.amount - soid.cost) * 100 / (soid.amount - pbz.payment_charge_percent * soid.amount / 100))),2) AS margin,
                            soid.unit_price, so.delivery_country_id,
                            ROUND(IF(rf.refund_status='C', (soid.amount - if(rf.refund_amount is NULL,'0',rf.refund_amount)) * so.rate, soid.amount * so.rate),2) AS amount_usd,
                            ROUND(IF(rf.refund_status='C', (soid.amount - if(rf.refund_amount is NULL,'0',rf.refund_amount) - ROUND((soid.cost / soid.qty * (soid.qty - if(rf.refund_qty is NULL,'0',rf.refund_qty))),2))* so.rate, (soid.amount - soid.cost) * so.rate),2) AS profit_usd,
                            so.promotion_code, so.delivery_type_id shipment_type, sosh.courier_id, sosh.tracking_no, if(soid.line_no = 1, so.delivery_charge, 0) as delivery_charge,
                            rf.refund_type, rf.refund_status, rf.refund_qty, rf.refund_amount
                        FROM
                            so
                        LEFT JOIN so_extend soex
                            ON so.so_no = soex.so_no
                        INNER JOIN so_item_detail soid
                            ON(soid.so_no = so.so_no)
                        LEFT JOIN so_allocate sa
                            ON(sa.so_no = so.so_no AND sa.item_sku = soid.item_sku AND sa.line_no = soid.line_no)
                        LEFT JOIN so_payment_status sps
                            ON(sps.so_no = so.so_no)
                        INNER JOIN product p
                            ON(soid.item_sku = p.sku)
                        INNER JOIN category cat
                            ON(cat.id = p.cat_id)
                        INNER JOIN category sc
                            ON(sc.id = p.sub_cat_id)
                        INNER JOIN brand b
                            ON(b.id = p.brand_id)
                        INNER JOIN platform_biz_var pbz
                            ON(pbz.selling_platform_id = so.platform_id)
                        LEFT JOIN
                        (
                            SELECT r.so_no, r.reason, r.total_refund_amount,
                            GROUP_CONCAT(r.status SEPARATOR ',') as refund_status,
                            (ri.refund_amount * ri.qty) as refund_amount , ri.item_sku, ri.qty as refund_qty, ri.refund_type
                            FROM refund_item as ri
                            LEFT JOIN refund as r
                                ON r.id = ri.refund_id
                            WHERE ri.status <> 'D'
                            GROUP BY r.so_no, r.reason, r.status, r.total_refund_amount, ri.item_sku, ri.refund_type
                        ) rf
                        ON (rf.item_sku = soid.item_sku and rf.so_no = so.so_no)
                        LEFT JOIN so_allocate soal
                            ON (soal.so_no = soid.so_no AND soal.line_no = soid.line_no AND soal.item_sku = soid.item_sku)
                        LEFT JOIN so_shipment sosh
                            ON (sosh.sh_no = soal.sh_no AND sosh.status = '2')
                        $where_clause
                        ORDER BY so.so_no, p.sku";
        */
        $dispatch_string = check_finance_role(true);

        if ($is_light_version) {
            $select_str = "so.platform_id, sps.payment_gateway_id, sps.modify_on, sps.payment_status, so.biz_type, ore.reason as order_reason, soid.line_no, so.platform_order_id, so.split_so_group, soex.conv_site_id,
                    cat.name cat_name, sc.name sub_cat_name, b.brand_name, p.name prod_name,
                    p.sku, soid.qty, so.order_create_date, so.currency_id,
                    null amount, null fee, null receivable, soid.vat_total,  soid.profit profit, soid.margin margin,
                    soid.unit_price, soid.cost,
                    so.promotion_code, rf.refund_status,rf.total_refund_amount,pbz.payment_charge_percent, sp.type,soid.gst_total soid_gst_total,
                    soid.amount soid_amount,so.rate, so.amount so_amount ";

            /*  $select_str = "so.platform_id, sps.payment_gateway_id, sps.modify_on, sps.payment_status, so.biz_type, ore.reason as order_reason, so.txn_id, so.so_no, soid.line_no, so.platform_order_id, soex.conv_site_id, sa.warehouse_id,
                        cat.name cat_name, sc.name sub_cat_name, b.brand_name, p.name prod_name,
                        p.sku, soid.qty, " . $dispatch_string . ", so.order_create_date, so.currency_id,
                        null amount, null fee, null receivable, soid.vat_total,  soid.profit profit, soid.margin margin,
                        soid.unit_price, soid.cost, so.delivery_country_id, null amount_usd, null profit_usd,
                        so.promotion_code, rf.refund_status,rf.total_refund_amount,pbz.payment_charge_percent, sp.type,soid.gst_total soid_gst_total,
                        soid.amount soid_amount,so.rate, so.amount so_amount";
            */

        } else {
            $select_str = "so.platform_id, sps.payment_gateway_id, sps.pay_date, sps.payment_status, so.biz_type, ore.reason as order_reason, so.txn_id, so.so_no, so.split_so_group, soid.line_no, so.platform_order_id, soex.conv_site_id, sa.warehouse_id,
                    cat.name cat_name, sc.name sub_cat_name, b.brand_name, p.name prod_name,
                    p.sku, skum.ext_sku, sup.name, soid.qty, " . $dispatch_string . ", so.order_create_date, so.currency_id,
                    null amount, null fee, null receivable, soid.vat_total,  soid.profit profit, soid.margin margin,
                    soid.unit_price, soid.cost, so.delivery_country_id, null amount_usd, null profit_usd,
                    so.promotion_code, so.delivery_type_id shipment_type, sosh.courier_id, sosh.tracking_no, so.delivery_charge,
                    rf.refund_type, rf.refund_status, rf.refund_qty, rf.refund_amount,
                    so.amount so_amount, so.rate, so.delivery_charge,
                    soid.amount soid_amount, rf.refund_qty,
                    soid.gst_total soid_gst_total,
                    rf.total_refund_amount, pbz.payment_charge_percent,
                    sp.type, sosh.create_on,
                    sbt.payment_received_date,
                    p.clearance,cl.email ";
        }


        $sql = "SELECT
                    {$select_str}
                FROM
                    so
                LEFT JOIN so_extend soex
                    ON so.so_no = soex.so_no
                LEFT JOIN client cl
                    ON so.client_id = cl.id
                LEFT JOIN order_reason ore
                    ON(ore.reason_id = soex.order_reason)
                INNER JOIN so_item_detail soid
                    ON(soid.so_no = so.so_no)
                LEFT JOIN so_allocate sa
                    ON(sa.so_no = so.so_no AND sa.item_sku = soid.item_sku AND sa.line_no = soid.line_no)
                LEFT JOIN so_payment_status sps
                    ON(sps.so_no = so.so_no)
                LEFT JOIN sku_mapping skum
                    ON(skum.sku = soid.item_sku and skum.status=1 and skum.ext_sys='WMS')
                INNER JOIN product p
                    ON(soid.item_sku = p.sku)
                INNER JOIN supplier_prod sp_prod
                        on sp_prod.prod_sku=p.sku and sp_prod.order_default=1
                INNER JOIN supplier sup
                        on sup.id=sp_prod.supplier_id and sup.status=1
                INNER JOIN category cat
                    ON(cat.id = p.cat_id)
                INNER JOIN category sc
                    ON(sc.id = p.sub_cat_id)
                INNER JOIN brand b
                    ON(b.id = p.brand_id)
                INNER JOIN platform_biz_var pbz
                    ON(pbz.selling_platform_id = so.platform_id)
                INNER JOIN selling_platform sp on pbz.selling_platform_id = sp.id
                LEFT JOIN
                (
                    SELECT r.so_no, r.reason, r.total_refund_amount,
                    GROUP_CONCAT(r.status SEPARATOR ',') as refund_status,
                    (ri.refund_amount * ri.qty) as refund_amount , ri.item_sku, ri.qty as refund_qty, ri.refund_type
                    FROM refund_item as ri
                    LEFT JOIN refund as r
                        ON r.id = ri.refund_id
                    WHERE ri.status <> 'D'
                    GROUP BY r.so_no, r.reason, r.status, r.total_refund_amount, ri.item_sku, ri.refund_type
                ) rf
                    ON (rf.item_sku = soid.item_sku and rf.so_no = so.so_no)
                LEFT JOIN so_allocate soal
                    ON (soal.so_no = soid.so_no AND soal.line_no = soid.line_no AND soal.item_sku = soid.item_sku)
                LEFT JOIN so_shipment sosh
                    ON (sosh.sh_no = soal.sh_no)
                LEFT JOIN
                (
                    SELECT sobt.*, MAX(sobt.received_date_localtime) AS payment_received_date
                    FROM so_bank_transfer sobt
                    INNER JOIN so so1 ON so1.so_no = sobt.so_no
                    GROUP BY sobt.so_no
                ) sbt ON (so.so_no = sbt.so_no)
                $where_clause
                ORDER BY so.so_no, soid.amount DESC, p.sku";
        $result = $this->db->query($sql, $value_list);
        if (!$result) {
            return FALSE;
        }

        $array = $result->result_array();

        return $array;
    }

    public function get_split_so_report($where = array(), $option = array(), $from_date = "", $to_date = "")
    {
        $where["sops.pay_date >="] = $from_date;
        $where["sops.pay_date <="] = $to_date;
        $where["so.split_so_group IS NOT NULL"] = null;
        $where["so.hold_status != 15"] = null;

        foreach ($where as $key => $value) {
            if ($value === NULL || $value == "") {
                # value = 0 should not be here
                $where_clause .= " AND $key";
            } else {
                if (strpos(strtolower($key), "like") !== false) {
                    # if key contains "LIKE", then escape differently
                    $where_clause .= " AND $key " . $this->db->escape_like_str($value);
                } else {
                    $where_clause .= " AND $key " . $this->db->escape($value);
                }
            }
        }

        $sql = <<<SQL
            SELECT so.so_no, so.split_so_group, so.platform_id, so.status AS order_status, so.refund_status, so.hold_status, so.dispatch_date, so.biz_type, so.order_create_date,
                sops.pay_date, soprs.score AS priority_score, soid.line_no, skum.ext_sku AS master_sku, soid.item_sku, p.name, soid.qty, so.currency_id, soid.amount, (soid.profit*soid.qty) as item_total_profit, so.expect_del_days
            FROM so
            INNER JOIN so_item_detail soid ON soid.so_no = so.so_no
            LEFT JOIN sku_mapping skum ON (skum.sku = soid.item_sku and skum.status=1 and skum.ext_sys='WMS')
            INNER JOIN product p ON (soid.item_sku = p.sku)
            LEFT JOIN so_priority_score soprs ON soprs.so_no = so.so_no AND soprs.status = 1
            LEFT JOIN so_payment_status sops ON sops.so_no = so.so_no
            WHERE 1=1
                $where_clause
            ORDER BY so.so_no, soid.line_no DESC
SQL;
        $query = $this->db->query($sql);

        $result = array();
        if (!$query)
            return FALSE;
        else {
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $result[] = $row;
                }
            }
            return $result;
        }


    }

    public function get_dispatch_data($where = array(), $from_date = '', $to_date = '')
    {

        $where_clause2 = 'where so.dispatch_date > "' . $from_date . ' 00:00:00" and so.dispatch_date < "' . $to_date . ' 23:59:59' . '"';

        $where_clause = "";
        if ($where['so.currency_id']) {
            $where_clause .= 'And so.currency_id = "' . $where['so.currency_id'] . '"';
        }
        if ($where['so.delivery_country_id']) {
            $where_clause .= ' And so.delivery_country_id = "' . $where['so.delivery_country_id'] . '"';
        }


        $sql = "SELECT
                so.so_no, soa.warehouse_id, smm.ext_sku, soi.prod_name, soi.qty, pcc.`code`, so.order_create_date, soh.create_on pack_date,
                so.dispatch_date, so.amount, fc.country_id fc_country, so.delivery_country_id, soh.courier_id, soh.tracking_no, so.rate, so.currency_id
                from so so
                INNER JOIN so_item soi on so.so_no = soi.so_no
                INNER JOIN so_allocate soa on soa.so_no = so.so_no and soi.line_no = soa.line_no
                INNER JOIN so_shipment soh on soa.sh_no = soh.sh_no
                LEFT JOIN warehouse wh on wh.id = soa.warehouse_id
                LEFT JOIN fulfillment_centre fc on fc.id = wh.fc_id
                LEFT JOIN product_custom_classification pcc on soi.prod_sku = pcc.sku and so.delivery_country_id = pcc.country_id
                LEFT JOIN sku_mapping smm on soi.prod_sku = smm.sku and smm.status ='1' and smm.ext_sys ='WMS'
                $where_clause2
                $where_clause";

        $result = $this->db->query($sql, $value_list);
        if (!$result) {
            return FALSE;
        }

        $array = $result->result_array();
        return $array;
    }

    public function get_altapay_captured_amount($start_date, $end_date)
    {
        $sql = "select sum(amount * ref_1) as total_amount
                from so
                inner join so_payment_status sops on sops.so_no=so.so_no and sops.payment_gateway_id='altapay'
                where order_create_date between ? and ? and sops.payment_status = 'S'
                group by currency_id";
        $result = $this->db->query($sql, array($start_date, $end_date));
        if (!$result) {
            return FALSE;
        }
//        error_log($this->db->last_query());
        return $result->result_array();
    }

    public function get_paid_affiliate_orders($where = array(), $start_date = "", $end_date = "")
    {
        $array = array();

        # construct the where clause as a string with escape
        $where_clause = "";
        if ($start_date && $end_date) {
            $where_clause .= " AND so.order_create_date BETWEEN " . $this->db->escape($start_date) . " AND " . $this->db->escape($end_date);
        }

        foreach ($where as $key => $value) {
            if (strpos(strtolower($key), "like") !== FALSE) {
                # if key contains "LIKE", then escape differently
                $where_clause .= " AND $key " . $this->db->escape_like_str($value);
                // $where_clause .= " AND $key '$value'";
            } else {
                $where_clause .= " AND $key " . $this->db->escape($value);
            }
        }

        $sql = <<<SQL
                    SELECT
                        so.platform_id,
                        soex.conv_site_id AS affiliate,
                        so.so_no,
                        so.order_create_date,
                        sosh.modify_on AS ship_date,
                        p.name prod_name, p.sku, soid.qty,
                        c.email, CONCAT(c.forename, c.surname) AS name,
                        so.status AS so_status,
                        so.refund_status,
                        soal.status AS allocate_status
                    FROM so
                    LEFT JOIN so_extend soex ON so.so_no = soex.so_no
                    LEFT JOIN order_reason ore ON(ore.reason_id = soex.order_reason)
                    INNER JOIN so_item_detail soid ON(soid.so_no = so.so_no)
                    INNER JOIN client c ON c.id = so.client_id
                    LEFT JOIN so_allocate sa ON(sa.so_no = so.so_no AND sa.item_sku = soid.item_sku AND sa.line_no = soid.line_no)
                    LEFT JOIN so_payment_status sps ON(sps.so_no = so.so_no)
                    INNER JOIN product p ON(soid.item_sku = p.sku)
                    INNER JOIN platform_biz_var pbz ON(pbz.selling_platform_id = so.platform_id)
                    INNER JOIN selling_platform sp on pbz.selling_platform_id = sp.id
                    LEFT JOIN
                            (
                                SELECT
                                    r.so_no, r.reason, r.total_refund_amount,
                                    GROUP_CONCAT(r.status SEPARATOR ',') as refund_status,
                                    (ri.refund_amount * ri.qty) as refund_amount ,
                                    ri.item_sku, ri.qty as refund_qty, ri.refund_type
                                FROM refund_item as ri
                                LEFT JOIN refund as r ON r.id = ri.refund_id
                                WHERE ri.status <> 'D'
                                GROUP BY r.so_no, r.reason, r.status, r.total_refund_amount, ri.item_sku, ri.refund_type
                            ) rf ON (rf.item_sku = soid.item_sku and rf.so_no = so.so_no)
                    LEFT JOIN so_allocate soal ON (soal.so_no = soid.so_no AND soal.line_no = soid.line_no AND soal.item_sku = soid.item_sku)
                    LEFT JOIN so_shipment sosh ON (sosh.sh_no = soal.sh_no AND sosh.status = '2')
                    WHERE
                        so.status >= 2
                        $where_clause
                    ORDER BY so.so_no, soid.amount DESC, p.sku
SQL;
        $query = $this->db->query($sql);

        if (!$query) {
            return FALSE;
        }
        $array = $query->result_array();

        return $array;
    }

    public function get_delay_affiliate_orders($where = array(), $diff_days = "")
    {

        $array = array();
        # construct the where clause as a string with escape
        $where_clause = "";
        if ($diff_days != '') {
            //$where_clause .= " AND DATE_FORMAT(NOW(),'%Y-%m-%d') = DATE_ADD(DATE_FORMAT(so.create_on,'%Y-%m-%d'), INTERVAL ".$diff_days." day)";
            $where_clause .= " AND DATE_ADD(DATE_FORMAT(so.create_on,'%Y-%m-%d'), INTERVAL  " . $diff_days . " day) = DATE_FORMAT(NOW(),'%Y-%m-%d')";
        }

        foreach ($where as $key => $value) {
            if (strpos(strtolower($key), "like") === false) {
                # if key contains "LIKE", then escape differently
                $where_clause .= " AND $key " . $this->db->escape_like_str($value);
                // $where_clause .= " AND $key '$value'";
            } else {
                $where_clause .= " AND $key " . $this->db->escape($value);
            }
        }

        $sql = <<<SQL
                    SELECT
                        so.platform_id, soex.conv_site_id, so.so_no, so.create_on, si.prod_name,
                        si.prod_sku, si.qty, c.email, c.forename, c.surname,so.refund_status, so.hold_status, so.status
                    FROM so
                    inner join so_extend soex on soex.so_no = so.so_no
                    inner join so_hold_reason sr on sr.so_no = so.so_no
                    inner join so_item si on si.so_no = so.so_no
                    inner join client c on c.id = so.client_id
                    WHERE
                        so.status >= '2' AND so.status <= '5' AND so.hold_status = '0' AND so.refund_status = '0'
                        $where_clause
                    GROUP BY so_no, prod_sku
SQL;
        $query = $this->db->query($sql);
        if (!$query) {
            return FALSE;
        }
        $array = $query->result_array();
        return $array;
    }

    public function get_platform_client_delivery_orders($where = array(), $diff_days = "")
    {

        $array = array();

        # construct the where clause as a string with escape
        $where_clause = "";
        if ($diff_days != '') {
            //$where_clause .= " AND DATE_FORMAT(NOW(),'%Y-%m-%d') = DATE_ADD(DATE_FORMAT(so.create_on,'%Y-%m-%d'), INTERVAL ".$diff_days." day)";
            $where_clause .= "AND DATE_ADD(DATE_FORMAT(so.dispatch_date,'%Y-%m-%d'), INTERVAL " . $diff_days . " day) = DATE_FORMAT(NOW(),'%Y-%m-%d')";
        }

        foreach ($where as $key => $value) {
            if (strpos(strtolower($key), "like") === true) {
                # if key contains "LIKE", then escape differently
                $where_clause .= " AND $key " . $this->db->escape_like_str($value);
                // $where_clause .= " AND $key '$value'";
            } else {
                $where_clause .= " AND $key " . $this->db->escape($value);
            }
        }

        $sql = <<<SQL
                    SELECT
                        so.so_no, so.platform_id, so.status, so.order_create_date, so.dispatch_date , c.forename, c.surname,
                        c.email, c.city, c.country_id, CONCAT_WS(' ',c.tel_1,c.tel_2,c.tel_3) telephone
                    FROM so
                    INNER JOIN client c on so.client_id = c.id
                    WHERE
                        so.status = '6'
                        $where_clause
                    order by so.dispatch_date desc
SQL;
        $query = $this->db->query($sql);
        //var_dump($this->db->last_query()); die();
        if (!$query) {
            return FALSE;
        }
        $array = $query->result_array();
        return $array;
    }

    public function get_product_hscode_report($where = array(), $option = array(), $classname = "Product_hscode_report_dto")
    {
        $sql = "SELECT smm.ext_sku AS mastersku, p.sku AS sku, p.name AS product,
            c.name category, sc.name subcategory, ssc.name sub_subcategory,
            pcc.country_id, pcc.code AS hscode, pcc.description AS hsdescription, pcc.duty_pcent
                FROM  product p
                INNER JOIN product_custom_classification pcc on p.sku = pcc.sku
                INNER JOIN category c on c.id = p.cat_id
                INNER JOIN category sc on sc.id = p.sub_cat_id
                LEFT JOIN category ssc on ssc.id = p.sub_sub_cat_id
                LEFT JOIN sku_mapping smm on p.sku = smm.sku and smm.status ='1' and smm.ext_sys ='WMS'
                WHERE c.id = $where
                ORDER BY p.sku";

        if ($query = $this->db->query($sql)) {
            return $query->result();
        }
        return FALSE;
    }

    public function get_client_stat($client_id = "")
    {
        if ($client_id == "") {
            return FALSE;
        }

        $this->db->from("so");

        $this->db->select('COUNT(so_no) as times, SUM(amount) as  total, min(order_create_date) as first', FALSE);

        $this->db->where(array("client_id" => $client_id, "status >" => 2));

        $this->db->group_by("client_id");

        $this->db->limit(1);

        if ($query = $this->db->get()) {
            return array("times" => $query->row()->times, "total" => $query->row()->total, "first" => $query->row()->first);
        }
        return FALSE;
    }

    public function get_ilg_dispatch_feed()
    {
        $this->db->from("so_item_detail soid");

        $this->db->join("so", "so.so_no = soid.so_no", "INNER");

        $this->db->join("(  SELECT b.so_no, COUNT(b.item_sku) AS cnt
                            FROM so_item_detail b
                            GROUP BY b.so_no) AS socnt", "socnt.so_no = soid.so_no", "INNER");

        $this->db->join("product p", "p.sku = soid.sku", "INNER");

        $this->db->join("client c", "c.id = so.client_id", "INNER");

        /*
SELECT so.platform_id,  p.name, soid.qty, so.so_no, soid.line_no, socnt.cnt AS listings, soid.item_sku, so.delivery_name,c.email, so.delivery_address,so.delivery_city,so.delivery_postcode, so.delivery_country_id, CONCAT_WS(" ",c.tel_1, c.tel_2,c.tel_3) as BuyerPhone, soid.unit_price as unitprice, soid.amount as productprice, IF(soid.line_no = 1,(so.amount- so.delivery_charge),0)  as subtotal, IF(soid.line_no = 1,so.delivery_charge,0) as delivery_charge, IF(soid.line_no = 1,so.amount,0) as amount, so.order_create_date as purchasedon, '' as packeddate, '' as shipdate, '' as trackingnumber,' NA' as prefcourier
FROM so_item_detail soid
JOIN so
    ON so.so_no = soid.so_no
JOIN (
    SELECT b.so_no, count(b.item_sku) as cnt
    FROM so_item_detail b
    GROUP BY b.so_no) AS socnt
    ON socnt.so_no = soid.so_no
JOIN product p
    ON p.sku = soid.item_sku
JOIN client c
    ON c.id = so.client_id
JOIN so_allocate soal
    ON soal.so_no = soid.so_no
    AND soal.line_no = soid.line_no
    AND soal.item_sku = soid.item_sku
JOIN so_shipment sosh
    ON sosh.sh_no = soal.sh_no
    AND sosh.status = '2'
WHERE so.status> 2
AND so.hold_status = 0
AND so.refund_status= 0
ORDER BY so.so_no, soid.line_no

        */
    }

    public function get_shipment_delivery_info($so_no = '',
                                               $classname = 'shipment_info_to_courier_dto')
    {
        $sql = "SELECT
                    so.platform_id, soa.sh_no, so.so_no, so.platform_order_id,
                    so.order_create_date, so.bill_name, so.bill_company,
                    so.bill_address, so.bill_postcode, so.bill_city,
                    so.bill_state, so.bill_country_id, c.email,
                    concat_ws('', c.tel_1, c.tel_2, c.tel_3) tel,
                    so.delivery_name, so.delivery_company,
                    so.delivery_address, so.delivery_postcode,
                    so.delivery_city, so.delivery_state, so.delivery_country_id,
                    soa.line_no, soa.item_sku sku, p.name prod_name,
                    so.currency_id, soid.amount unit_price, soa.qty, so.delivery_charge,
                    so.amount, sos.courier_id, so.promotion_code, soe.offline_fee,
                    vpo.cc_desc, vpo.cc_code
                FROM so
                INNER JOIN client c
                    ON (c.id = so.client_id)
                INNER JOIN so_item_detail soid
                    ON (soid.so_no = so.so_no)
                INNER JOIN so_allocate soa
                    ON (soa.so_no = soid.so_no AND soa.line_no = soid.line_no AND  soa.item_sku = soid.item_sku)
                INNER JOIN so_shipment sos
                    ON (sos.sh_no = soa.sh_no)
                INNER JOIN v_prod_overview_wo_shiptype vpo
                    ON (vpo.sku = soid.item_sku AND so.platform_id = vpo.platform_id)
                INNER JOIN product p
                    ON (p.sku = soid.item_sku)
                LEFT JOIN so_extend soe
                    ON (so.so_no = soe.so_no)
                WHERE so.so_no = ?";

        $this->include_dto($classname);

        $result = $this->db->query($sql, array($so_no));

        if (!$result) {
            return FALSE;
        }

        foreach ($result->result("object", $classname) as $obj) {
            $array[] = $obj;
        }

        return $array;
    }

    public function get_shipment_delivery_info_dhl($so_no = '', $classname = 'shipment_info_to_courier_dhl_dto')
    {
        $sql = "
                SELECT
                        so.amount - ifnull(soe.offline_fee,0) order_cost,
                        'DDP' added_service,
                        CONCAT_WS('', c.tel_1, c.tel_2, c.tel_3, '/', c.mobile) AS tel, so.delivery_name,
                        so.delivery_company, so.delivery_address, so.delivery_postcode,
                        so.delivery_city, so.delivery_state, so.delivery_country_id,
                        soid.qty, vpo.prod_weight, so.amount amount, so.rate, vpo.cc_desc,
                        vpo.cc_code, so.so_no, vpo.price, vpo.free_delivery_limit,
                        vpo.delivery_charge, vpo.platform_id, vpo.declared_pcent, so.currency_id, soid.item_sku AS prod_sku,
                        c.email as client_email, cat.name as category_name
                FROM so
                inner join so_extend soe on soe.so_no = so.so_no
                INNER JOIN client c
                    ON (c.id = so.client_id)
                INNER JOIN so_item_detail soid
                    ON (soid.so_no = so.so_no)
                INNER JOIN v_prod_overview_wo_shiptype vpo
                    ON (vpo.sku = soid.item_sku AND so.platform_id = vpo.platform_id)
                INNER JOIN product p
                    ON(soid.item_sku = p.sku)
                INNER JOIN category cat
                    ON(cat.id = p.cat_id)
                WHERE so.so_no = ?
                ORDER BY soid.line_no LIMIT 1
                ";

        $this->include_dto($classname);

        $result = $this->db->query($sql, array($so_no));
        if (!$result) {
            return FALSE;
        }

        foreach ($result->result("object", $classname) as $obj) {
            $array[] = $obj;
        }

        return $array;
    }

    public function get_shipment_delivery_info_courier($so_no = '',
                                                       $classname = 'shipment_info_to_courier_dhl_dto')
    {
        $sql = "
                SELECT CONCAT_WS('', c.tel_1, c.tel_2, c.tel_3) AS tel, so.delivery_name, so.delivery_company, so.delivery_address, so.delivery_postcode, so.delivery_city, so.delivery_state, so.delivery_country_id, soid.qty, vpo.prod_weight, soid.unit_price amount, so.rate, vpo.cc_desc, vpo.cc_code, so.so_no, vpo.price, vpo.free_delivery_limit, vpo.delivery_charge, vpo.platform_id, vpo.declared_pcent, so.currency_id, soid.item_sku prod_sku
                FROM so
                INNER JOIN client c
                    ON (c.id = so.client_id)
                INNER JOIN so_item_detail soid
                    ON (soid.so_no = so.so_no)
                INNER JOIN v_prod_overview_wo_shiptype vpo
                    ON (vpo.sku = soid.item_sku AND so.platform_id = vpo.platform_id)
                WHERE so.so_no = ?
                ";

        $this->include_dto($classname);

        $result = $this->db->query($sql, array($so_no));

        if (!$result) {
            return FALSE;
        }

        foreach ($result->result("object", $classname) as $obj) {
            $array[] = $obj;
        }

        return $array;
    }

    public function get_shipment_delivery_info_courier_for_tnt($so_no = '', $classname = 'shipment_info_to_courier_dhl_dto')
    {

        $sql = "
                SELECT so.so_no, so.delivery_name, so.delivery_address, so.delivery_city, so.delivery_state, so.delivery_postcode, so.delivery_country_id, so.delivery_name, CONCAT_WS('', c.tel_1, c.tel_2, c.tel_3) AS tel, vpo.prod_weight, soid.unit_price amount, so.currency_id, cat.name cat_name, cat2.name subcat, cat3.name subsubcat
                FROM so
                INNER JOIN client c
                    ON (c.id = so.client_id)
                INNER JOIN so_item_detail soid
                    ON (soid.so_no = so.so_no)
                INNER JOIN product pdt
                    ON (soid.item_sku = pdt.sku)
                INNER JOIN category cat
                    ON (cat.id = pdt.cat_id)
                INNER JOIN category cat2
                    ON (cat2.id = pdt.sub_cat_id)
                INNER JOIN category cat3
                    ON (cat3.id = pdt.sub_sub_cat_id)
                INNER JOIN v_prod_overview_wo_shiptype vpo
                    ON (vpo.sku = soid.item_sku AND so.platform_id = vpo.platform_id)
                WHERE so.so_no = ?
                ";

        $this->include_dto($classname);

        $result = $this->db->query($sql, array($so_no));

        if (!$result) {
            return FALSE;
        }

        foreach ($result->result("object", $classname) as $obj) {
            $array[] = $obj;
        }

        //echo '<pre>'; var_dump($array); die();

        return $array;
    }

    public function get_dispatch_email_list($now_access_time = "", $last_access_time = "")
    {
        $sql = "SELECT DISTINCT c.email,so.bill_name
                FROM so
                LEFT JOIN client c
                    ON so.client_id = c.id
                WHERE so.dispatch_date < ? AND so.dispatch_date >= ? AND so.status ='6'";

        $result = $this->db->query($sql, array($now_access_time, $last_access_time));

        if (!$result) {
            return FALSE;
        }

        foreach ($result->result("array") as $obj) {
            $array[] = $obj;
        }

        return $array;
    }

    public function get_wow_mail_list($where = array(), $option = array(), $classname = "wow_mail_list_dto")
    {
        $this->db->from('so');
        $this->db->join('so_payment_status sps', 'so.so_no = sps.so_no', 'LEFT');
        $this->db->join('(
                            SELECT DISTINCT soal.sh_no, soal.so_no, sosh.courier_id
                            FROM so_allocate soal
                            JOIN so_shipment sosh
                                ON soal.sh_no = sosh.sh_no
                            GROUP BY soal.sh_no, soal.so_no
                        )a', 'a.so_no = so.so_no', 'LEFT');

        $this->db->group_by(array("a.so_no", "a.sh_no"));

        #SBF #4020 - going forward will be using so.expect_ship_days (dynamic) instead of hard code of less than 6 days
        // $this->db->where(array('so.delivery_type_id' => 'STD', 'HOUR(TIMEDIFF(dispatch_date, order_create_date))/24 <= ' => '6', 'a.sh_no IS NOT NULL'=>NULL));
        $this->db->where(array('so.delivery_type_id' => 'STD', 'a.sh_no IS NOT NULL' => NULL));

        $this->db->where($where);

        $this->db->select('so.so_no, so.bill_name, HOUR(TIMEDIFF(dispatch_date, order_create_date))/24 AS date_to_delivery, count(*) as no_of_partial_shipment, a.courier_id,
                            so.expect_ship_days, so.expect_del_days, so.dispatch_date, so.order_create_date, sps.pay_date', FALSE);

        $this->include_dto($classname);

        $rs = array();
        // $query = $this->db->get();
        // var_dump($this->db->last_query());die();
        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                # SBF #4020 - wow logic to change depending on so.expect_ship_days

                $found = false;
                $dispatch_date = $obj->get_dispatch_date();
                $pay_date = $obj->get_pay_date();
                $order_create_date = $obj->get_order_create_date();
                $expect_ship_days = $obj->get_expect_ship_days();

                if (!empty($expect_ship_days)) {
                    $expect_ship_days = $obj->get_expect_ship_days();
                    $max_ship_day = trim(substr($expect_ship_days, strpos($expect_ship_days, '-') + 1));

                    if (ctype_digit($max_ship_day)) {
                        # Expected date of shipment based on upper limit of expect_ship_days
                        // $expect_max_ship_date = strtotime($order_create_date) + ($max_ship_day*24*60*60);
                        $loopdate = $pay_date;
                        $c = $max_ship_days;

                        for ($i = 0; $i < $c; $i++) {
                            $checkday = date('D', $loopdate);
                            if (($checkday == 'Sat') or ($checkday == 'Sun')) {
                                $max_ship_day = $max_ship_day + (1 * 24 * 60 * 60);
                                $loopdate = $loopdate + (1 * 24 * 60 * 60);
                            }
                        }

                        // $day = date('D', $expect_day);
                        // var_dump($day); die();
                        if (strtotime($dispatch_date) <= (strtotime($pay_date) + ($max_ship_day * 24 * 60 * 60) - 1 * 24 * 60 * 60)) {
                            // is wow because dispatch date is <= expected ship date - 1
                            $found = true;
                        } else {
                            // fail wow; set $obj to null
                            $found = true;
                            $obj = null;
                        }
                    }
                }

                # (transitioning period) if above does not pass through, go back to using old logic
                if (!$found) {
                    # fail wow because > 6 days
                    //if( (strtotime($dispatch_date) - strtotime($order_create_date))/(24*60*60) > $expect_day)
                    if ((strtotime($dispatch_date) - strtotime($pay_date)) / (24 * 60 * 60) > $max_ship_day) {
                        $obj = null;
                    }

                }
                if ($obj) {
                    $rs[] = $obj;
                }
            }
            return $rs;
        }

        return FALSE;

        /*
        $sql = "
                SELECT so.so_no, HOUR(TIMEDIFF(dispatch_date, order_create_date))/24 AS date_to_delivery, count(*) as is_split
                FROM so
                LEFT JOIN
                (
                    SELECT DISTINCT sh_no, so_no
                    FROM so_allocate
                    GROUP BY sh_no, so_no
                )a
                ON a.so_no = so.so_no
                WHERE so.delivery_type_id = 'STD' AND a.sh_no IS NOT NULL AND HOUR(TIMEDIFF(dispatch_date, order_create_date))/24 <= 2 AND so.so_no = ?
                GROUP BY a.so_no, a.sh_no
                ";
        */
    }

    public function get_so_w_payment($where = array(), $option = array(), $classname = "So_w_payment_dto")
    {

        $this->db->from('so');
        $this->db->join('so_payment_status AS sops', 'so.so_no = sops.so_no', 'INNER');

        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"])) {

            $this->db->join('ipligence AS ip', 'ip.ip_from <= INET_ATON(so.create_at) AND ip.ip_to >= INET_ATON(so.create_at)', 'INNER');

            $this->include_dto($classname);

            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $rs = array();

            $this->db->select('so.so_no, so.order_create_date, so.platform_id, so.bill_country_id, so.delivery_country_id, so.amount, sops.card_id AS card_type, sops.remark AS fail_reason, ip.country_code AS country_by_ip, create_at', FALSE);

            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return $rs ? (object)$rs : $rs;
            }

        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function get_cc_list($where = array(), $option = array(), $classname = '')
    {
        $this->db->from('so');

        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"])) {

            $this->include_dto($classname);

            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $rs = array();

            $this->db->select('DISTINCT delivery_country_id', FALSE);

            if ($query = $this->db->get()) {
                foreach ($query->result() as $obj) {
                    $rs[] = $obj->delivery_country_id;
                }
                return $rs;
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function get_skype_report_list($start_date = "", $end_date = "", $where = array(), $classname = "Skype_report_dto")
    {
        $join_clause = $where_clause = '';
        if (!empty($where)) {
            foreach ($where as $id => $value) {
                if (!empty($value)) {
                    switch ($id) {
                        case 'sku':
                            $where_clause .= " AND soid.item_sku " . $value;
                            break;
                        case 'conv_site_id':
                            $join_clause .= "JOIN so_extend soex
                                            ON (so.so_no = soex.so_no) ";
                            $where_clause .= " AND soex.conv_site_id " . $value;
                            break;
                        case 'promotion_code':
                            $where_clause .= " AND so.promotion_code " . $value;
                            break;
                        default:
                    }
                }
            }
        }
        $sql =
            "
                    SELECT
                        c.bill_country_id,
                        c.order_date AS period,
                        SUM(c.num_of_orders) number_of_orders,
                        SUM(c.items_ordered) items_ordered,
                        SUM(c.subtotal) subtotal,
                        SUM(c.tax) tax,
                        SUM(c.discounts) discounts,
                        SUM(c.shipping) shipping,
                        SUM(c.total) total,
                        SUM(c.invoiced) invoiced,
                        SUM(c.refund) refunded
                    FROM
                    (
                        SELECT
                            b.bill_country_id, b.order_date,
                            b.num_of_orders,
                            b.items_ordered,
                            b.subtotal,
                            b.tax,
                            b.discounts,
                            b.shipping,
                            b.total,
                            b.invoiced,
                            b.oldamount,
                            b.currency_id,
                            b.rate,
                            SUM(ROUND(IFNULL(ra.refund_amount * b.rate, 0), 2)) refund
                        FROM
                        (
                            SELECT
                                a.bill_country_id,
                                date_format(a.order_create_date, '%Y-%m-%d') order_date,
                                COUNT(1) num_of_orders,
                                SUM(a.items_ordered) items_ordered,
                                SUM(a.amount - a.tax) subtotal,
                                SUM(a.tax) tax,
                                SUM(ROUND(a.discount * a.rate, 2)) discounts,
                                SUM(ROUND(a.delivery_charge * a.rate, 2)) shipping,
                                SUM(a.amount + ROUND(a.discount * a.rate, 2)) total,
                                SUM(a.amount) invoiced,
                                SUM(a.oldamount) oldamount,
                                a.currency_id,
                                a.rate
                            FROM
                            (
                                SELECT
                                    so.bill_country_id,
                                    so.order_create_date,
                                    so.so_no,
                                    so.ref_1 AS rate,
                                    ROUND(so.amount * so.ref_1, 2) amount,
                                    so.amount oldamount,
                                    so.delivery_charge,
                                    SUM(ROUND(so.amount * pbv.vat_percent / (100 + pbv.vat_percent) * so.ref_1, 2)) tax,
                                    SUM(soid.qty) items_ordered,
                                    SUM(ROUND(soid.discount * so.ref_1, 2)) discount,
                                    so.currency_id
                                FROM so
                                JOIN platform_biz_var pbv
                                    ON (pbv.platform_country_id = so.bill_country_id)
                                JOIN so_item soi
                                    ON (soi.so_no = so.so_no)
                                JOIN so_item_detail soid
                                    ON (soid.so_no = soi.so_no AND soid.line_no = soi.line_no)" .
            $join_clause .
            "WHERE so.status >= 2 AND so.biz_type = 'SKYPE' AND (so.order_create_date > ? AND so.order_create_date < ?)" .
            $where_clause .
            "GROUP BY so.bill_country_id, so.order_create_date, so.ref_1, so.so_no, so.amount,so.delivery_charge
                            ) a
                            GROUP BY a.bill_country_id, date_format(a.order_create_date, '%Y-%m-%d'),a.currency_id,a.rate
                        ) b
                        LEFT JOIN
                        (
                            SELECT
                                date_format(so1.order_create_date, '%Y-%m-%d') order_date,
                                so1.bill_country_id,
                                SUM(r.total_refund_amount) refund_amount
                            FROM so so1
                            JOIN refund r
                                ON (r.so_no = so1.so_no)
                            WHERE so1.status >= 2 AND so1.biz_type = 'SKYPE'
                            GROUP BY so1.bill_country_id, date_format(so1.order_create_date, '%Y-%m-%d')
                        ) ra
                        ON (ra.order_date = b.order_date AND ra.bill_country_id = b.bill_country_id)
                        GROUP BY
                            b.bill_country_id,
                            b.order_date,
                            b.num_of_orders,
                            b.subtotal,
                            b.tax,
                            b.discounts,
                            b.shipping,
                            b.total,
                            b.invoiced,
                            b.oldamount,
                            b.currency_id,
                            b.rate
                    ) c
                    GROUP BY
                    c.bill_country_id, c.order_date
                ";

        $this->include_dto($classname);
        $resultp = $this->db->query($sql, array($start_date, $end_date));
        foreach ($resultp->result("object", $classname) as $row) {
            $rs[] = $row;
        }
        return $rs;
    }

    public function get_delay_report_item_list($start_date, $end_date, $where, $classname = 'Delay_report_item_list_dto')
    {
        $sql = "
            SELECT
                s.platform_id, s.so_no as order_no, s.bill_country_id,
                DATE_FORMAT(s.order_create_date,'%Y-%m-%d') as order_date,
                p.name as product_name, p.sku,
                IF(s.hold_status>0,'Y','N') as hold_status,
                IF(m.mult>1,'Y','N') as mult,
                DATE_FORMAT(ss.create_on,'%Y-%m-%d') as packed_date,
                DATE_FORMAT(s.dispatch_date,'%Y-%m-%d') as dispatched_date,
                ss.courier_id,
                CONCAT('''',trim(ss.tracking_no),'''') as tracking_no,
                sa.warehouse_id as fulfillment_centre,
                REPLACE(REPLACE(c.note, CHAR(9), ''), CHAR(10), '') as cs_comment,
                ROUND(HOUR(TIMEDIFF(s.dispatch_date,s.order_create_date))/24, 2) as fulfillment_day,
                IF(s.dispatch_date IS NULL, FLOOR(HOUR(TIMEDIFF(CURRENT_TIMESTAMP(),s.order_create_date))/24+1),'') as unfulfilled_day,
                rf.refund_type, rf.refund_status, rf.refund_qty, rf.refund_amount
            FROM so as s
            JOIN so_item_detail as soid
              ON s.so_no = soid.so_no
            JOIN product as p
              on soid.item_sku = p.sku
            LEFT JOIN so_allocate as sa
              on s.so_no = sa.so_no
            LEFT JOIN so_shipment as ss
              on sa.sh_no = ss.sh_no
            LEFT JOIN
            (
                SELECT *
                FROM order_notes
                GROUP BY so_no DESC
                ORDER BY modify_on DESC
            ) as c
              ON ( s.so_no = c.so_no )
            LEFT JOIN
            (
                SELECT so_no, count(1) as mult
                FROM so_item
                GROUP BY so_no
            ) as m
              on ( s.so_no = m.so_no )
            LEFT JOIN
            (
                SELECT r.so_no, r.reason,
                GROUP_CONCAT(r.status SEPARATOR ',') as refund_status,
                SUM(ri.refund_amount * ri.qty) as refund_amount,
                ri.item_sku,
                SUM(ri.qty) as refund_qty,
                GROUP_CONCAT(ri.refund_type SEPARATOR ',') as refund_type
                FROM refund_item as ri
                LEFT JOIN refund as r
                    ON r.id = ri.refund_id
                WHERE ri.status <> 'D'
                GROUP BY r.so_no, r.reason, r.status, r.total_refund_amount, ri.item_sku, ri.refund_type
            ) rf
            ON ( rf.so_no = s.so_no )
        ";
        if ($where == '') {
            $sql .= " WHERE s.order_create_date >= '$start_date' and s.order_create_date <= '$end_date' and s.status >= 2 $where
                      ORDER BY s.so_no";
        } else {
            $sql .= " WHERE s.dispatch_date >= '$start_date' and s.dispatch_date <= '$end_date' and s.status >= 2
                      ORDER BY s.dispatch_date, s.so_no";
        }

        $this->include_dto($classname);

        $result = $this->db->query($sql);
        //echo $this->db->last_query();
        if (!$result) {
            return FALSE;
        }

        foreach ($result->result("object", $classname) as $row) {
            $rs[] = $row;
        }
        return $rs;
    }

    public function get_customer_extraction_item_list($where, $where1, $where2, $curr, $classname = 'Customer_extraction_item_list_dto')
    {
        $sql = "
                SELECT
                    c.title, c.forename, c.surname, c.companyname, c.email, REPLACE(c.del_address_1, '|', ', ') as address, c.postcode,
                    c.country_id, GROUP_CONCAT(c.tel_1, c.tel_2, c.tel_3) as phone_no, c.mobile, c.transaction_date, c.transaction_item, c.transaction_category, c.transaction_value, c.transaction_profit
                FROM
                    (
                    SELECT
                        b.platform_id, b.cat_id, b.sub_cat_id, b.sub_sub_cat_id, b.client_id, b.title, b.forename, b.surname, b.companyname,
                        b.email, b.del_address_1, b.postcode, b.country_id, b.tel_1, b.tel_2, b.tel_3, b.mobile, SUM(order_amount) as order_value, SUM(frequency) as frequency,
                        GROUP_CONCAT(CAST(b.order_date as CHAR) SEPARATOR '||') as transaction_date,
                        GROUP_CONCAT(CAST(b.p_name as CHAR) SEPARATOR '||') as transaction_item,
                        GROUP_CONCAT(CAST(b.c_name as CHAR) SEPARATOR '||') as transaction_category,
                        GROUP_CONCAT(CAST(b.order_amount as CHAR) SEPARATOR '||') as transaction_value,
                        GROUP_CONCAT(CAST(b.profit as CHAR) SEPARATOR '||') as transaction_profit
                    FROM
                    (
                        SELECT
                            a.platform_id, a.cat_id, a.sub_cat_id, a.sub_sub_cat_id, a.c_name, a.p_name, a.client_id, a.title, a.forename, a.surname, a.companyname, a.email, a.del_address_1, a.postcode,
                            a.country_id, a.tel_1, a.tel_2, a.tel_3, a.mobile, a.currency_id, a.order_amount, a.frequency, a.order_date, SUM(a.profit) as profit
                        FROM
                        (
                            SELECT
                                so.so_no, so.platform_id, sp.name, so.client_id, c.title, c.forename, c.surname, c.companyname, c.email, del_address_1, c.postcode,
                                c.country_id, c.tel_1, c.tel_2, c.tel_3, c.mobile, so.currency_id,
                                p.cat_id, p.sub_cat_id, p.sub_sub_cat_id, cat.name as c_name, p.name as p_name,
                                (so.amount*ex.rate) as order_amount, DATE_FORMAT(so.order_create_date, '%d/%m/%Y') as order_date, so.status,
                                ROUND(soid.profit*ex.rate,2) as profit,
                                SUM(soid.qty) as quantity, count(*) as frequency
                            FROM so
                            LEFT JOIN so_item_detail as soid
                                ON (soid.so_no = so.so_no)
                            INNER JOIN client as c
                                ON (so.client_id = c.id)
                            INNER JOIN product as p
                                ON (soid.item_sku = p.sku)
                            LEFT JOIN category as cat
                                ON (cat.id = p.cat_id)
                            LEFT JOIN category sc
                                ON (sc.id = p.sub_cat_id)
                            LEFT JOIN category ssc
                                ON (ssc.id = p.sub_sub_cat_id)
                            INNER JOIN selling_platform as sp
                                ON (so.platform_id = sp.id)
                            INNER JOIN exchange_rate as ex
                                ON (so.currency_id = ex.from_currency_id AND ex.to_currency_id = '$curr')
                            WHERE so.status >= 2 AND so.biz_type <> 'SPECIAL' $where
                            GROUP BY so.so_no, so.client_id, c.forename, c.surname, c.companyname, c.email
                        )a
                        GROUP BY a.so_no, a.client_id, a.forename, a.surname, a.companyname, a.email
                        ORDER BY a.client_id, a.order_date ASC
                    )b
                    $where1
                    GROUP BY b.client_id, b.forename, b.surname, b.companyname, b.email
                )c
                $where2
                GROUP BY c.client_id, c.forename, c.surname, c.companyname, c.email
                ";
        /*

                SELECT
                    c.platform_id, c.name, c.client_id, c.forename, c.surname, c.companyname, c.email, c.order_value, c.frequency, c.order_create_date
                FROM
                    (
                    SELECT
                        b.platform_id, b.name, b.cat_id, b.sub_cat_id, b.sub_sub_cat_id, b.client_id, b.forename, b.surname, b.companyname, b.email, SUM(order_amount) as order_value, SUM(frequency) as frequency, b.order_create_date
                    FROM
                    (
                        SELECT
                            a.platform_id, a.name, a.cat_id, a.sub_cat_id, a.sub_sub_cat_id, a.client_id, a.forename, a.surname, a.companyname, a.email, a.currency_id, a.order_amount, a.frequency, a.order_create_date
                        FROM
                        (
                            SELECT
                                so.so_no, so.platform_id, sp.name, so.client_id, c.forename, c.surname, c.companyname, c.email, so.currency_id,
                                p.cat_id, p.sub_cat_id, p.sub_sub_cat_id,
                                (so.amount*ex.rate) as order_amount, SUM(soi.qty) as quantity, count(*) as frequency, so.order_create_date, so.status, so.refund_status
                            FROM so
                            INNER JOIN so_item as soi
                                ON so.so_no = soi.so_no
                            INNER JOIN client as c
                                ON so.client_id = c.id
                            INNER JOIN product as p
                                ON soi.prod_sku = p.sku
                            LEFT JOIN category as cat
                                ON cat.id = p.cat_id
                            LEFT JOIN category sc
                                ON sc.id = p.sub_cat_id
                            LEFT JOIN category ssc
                                ON ssc.id = p.sub_sub_cat_id
                            INNER JOIN selling_platform as sp
                                ON so.platform_id = sp.id
                            INNER JOIN exchange_rate as ex
                                ON so.currency_id = ex.from_currency_id
                            WHERE so.status >= 2 AND ex.to_currency_id = 'GBP' $where
                            GROUP BY so.so_no, so.client_id, c.forename, c.surname, c.companyname, c.email
                        )a
                    )b
                    WHERE 1=1 $where1
                    GROUP BY b.client_id, b.forename, b.surname, b.companyname, b.email
                )c
                WHERE 1=1 $where2
                GROUP BY c.client_id, c.forename, c.surname, c.companyname, c.email

            ";
*/

        $this->include_dto($classname);

        $result = $this->db->query($sql);
        //$this->db->last_query();

        if (!$result) {
            return FALSE;
        }
        foreach ($result->result("object", $classname) as $row) {
            $rs[] = $row;
        }
        return $rs;
    }

    public function get_sales_comparison_data_by_period($where = array(),
                                                        $classname = '')
    {
        if (empty($where['period_start1'])
            || empty($where['period_end1'])
            || empty($where['period_start2'])
            || empty($where['period_end2'])
        ) {
            return FALSE; // period is compulsory
        }

        $data = array($where['period_start1'], $where['period_end1'],
            $where['period_start2'], $where['period_end2'],
            $where['period_start1'], $where['period_end1'],
            $where['period_start2'], $where['period_end2']);

        $raw_sql = "SELECT soid.item_sku, p.name prod_name, so.delivery_country_id cnty_id, cnty.name cnty_name,
                        so.promotion_code, count(1) sales_count
                    FROM so
                    JOIN so_item_detail soid
                        ON (soid.so_no = so.so_no)
                    JOIN country cnty
                        ON (so.delivery_country_id = cnty.id)
                    JOIN product p
                        ON (soid.item_sku = p.sku)
                    WHERE so.status >= 3 AND so.order_create_date > ?
                        AND so.order_create_date <= ?
                        $additional_clause
                    GROUP BY soid.item_sku, p.name, so.delivery_country_id, cnty.name, so.promotion_code";

        $combined_sql = "SELECT a.item_sku item_sku1, a.prod_name prod_name1,
                            a.cnty_id cnty_id1, a.cnty_name cnty_name1,
                            a.promotion_code promotion_code1, a.sales_count sales_count1,
                            b.item_sku item_sku2, b.prod_name prod_name2,
                            b.cnty_id cnty_id2, b.cnty_name cnty_name2,
                            b.promotion_code promotion_code2, b.sales_count sales_count2
                        FROM
                        ($raw_sql) a
                        LEFT JOIN
                        ($raw_sql) b
                            ON (a.item_sku = b.item_sku AND a.cnty_name = b.cnty_name
                                AND (a.promotion_code = b.promotion_code
                                    OR (a.promotion_code IS NULL AND b.promotion_code IS NULL)))
                        UNION
                        SELECT a.item_sku item_sku1, a.prod_name prod_name1,
                            a.cnty_id cnty_id1, a.cnty_name cnty_name1,
                            a.promotion_code promotion_code1, a.sales_count sales_count1,
                            b.item_sku item_sku2, b.prod_name prod_name2,
                            b.cnty_id cnty_id2, b.cnty_name cnty_name2,
                            b.promotion_code promotion_code2, b.sales_count sales_count2
                        FROM
                        ($raw_sql) a
                        RIGHT JOIN
                        ($raw_sql) b
                            ON (a.item_sku = b.item_sku AND a.cnty_name = b.cnty_name
                                AND (a.promotion_code = b.promotion_code
                                    OR (a.promotion_code IS NULL AND b.promotion_code IS NULL)))";

        $resultset = $this->db->query($combined_sql, $data);

        foreach ($resultset->result() as $row) {
            $rs[] = $row;
        }

        return $rs;
    }

    public function get_voucher_report_item_list($start_date, $end_date, $where, $classname = 'Voucher_report_item_list_dto')
    {
        $sql = "SELECT so.platform_id, so.biz_type, so.so_no, so.create_on order_date, c.email, soex.voucher_code
                FROM so
                JOIN so_extend soex
                    ON so.so_no = soex.so_no
                JOIN client c
                    ON so.client_id = c.id
                WHERE soex.voucher_code IS NOT NULL
                    AND so.order_create_date >= '$start_date' and so.order_create_date <= '$end_date' and so.status >= 2
                ORDER BY so.so_no";

        $this->include_dto($classname);

        $result = $this->db->query($sql);
        //echo $this->db->last_query();
        if (!$result) {
            return FALSE;
        }

        foreach ($result->result("object", $classname) as $row) {
            $rs[] = $row;
        }
        return $rs;
    }

    public function get_reevoo_customer_feed_dto($last_access_time = "", $classname = "Reevoo_customer_feed_dto")
    {
        $option = array("limit" => -1);
        $this->db->from("so");
        $this->db->join("so_item_detail soid", "so.so_no = soid.so_no", "INNER");
        $this->db->join("client c", "c.id = so.client_id", "INNER");
        $this->db->where(array("so.status >=" => 2, "so.biz_type IN ('ONLINE', 'MOBILE')" => null));

        if ($last_access_time) {
            $this->db->where("so.order_create_date >= ", $last_access_time);
        } else {
            // generate one month record if last accessed time is unavailable
            $startdate = date("Y-m-d", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")));
            $this->db->where("so.order_create_date >= ", $startdate . " 00:00:00");
        }
        $this->include_dto($classname);
        return
            // $data =
            $this->common_get_list($where, $option, $classname, 'so.dispatch_date, c.forename, c.surname, c.email, so.order_create_date purchase_date, c.id client_id, c.postcode, so.delivery_country_id, soid.so_no, soid.item_sku, so.currency_id, soid.amount');
        // var_dump($this->db->last_query()); return $data;
    }

    public function get_order_info_for_dynamic_shipment_status($where)
    {
        $this->db->from("so");
        $this->db->join("so_payment_status sops", "sops.so_no=so.so_no", "INNER");
        $this->db->join("so_extend soext", "soext.so_no=so.so_no", "LEFT");
        $this->include_dto("dynamic_shipment_status_dto");
        $select_str = "
                    so.so_no as so_no, if(sops.pay_date, sops.pay_date, so.order_create_date) as pay_date,
                    so.status as order_status,
                    soext.aftership_status as aftership_status,
                    soext.aftership_checkpoint as last_update_time,
                    so.dispatch_date,
                    so.delivery_country_id
            ";

        $this->db->select($select_str);
        $this->db->where($where);
        $this->db->limit(1);

        $classname = "dynamic_shipment_status_dto";
        $this->include_dto($classname);


        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                return $obj;
            }
        }

        return FALSE;
    }

    public function get_so_item_total_qty_by_sku($so_no, $sku, $where = array(), $option = array())
    {
        $select_str = "soi.so_no, soi.prod_sku, sum(soi.qty) as soi_qty, sum(soid.qty) as soid_qty";
        $this->db->from("so_item AS soi");
        $this->db->join("so_item_detail AS soid", "soid.so_no = soi.so_no AND soid.line_no = soi.line_no AND soid.item_sku = soi.prod_sku", "LEFT");

        $where["soi.so_no"] = $so_no;
        $where["soi.prod_sku"] = $sku;
        $this->db->select($select_str);
        $this->db->where($where);

        $query = $this->db->get();
        if ($query) {
            foreach ($query->result() as $row) {
                return $row;
            }
        }
        // select
        // soi.so_no, soi.prod_sku, sum(soi.qty) as soi_qty, sum(soid.qty) as soid_qty
        // from so_item soi
        // left join so_item_detail soid on soid.so_no = soi.so_no AND
        // soid.line_no = soi.line_no
        // AND soid.item_sku = soi.prod_sku
        // where soi.so_no = 380861 and soi.prod_sku = '10258-AA-NA'

        return FALSE;
    }

    public function get_order_history($client_id, $classname = "Order_history_dto")
    {
        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->get_accessory_catid_arr());

        $option = array("limit" => -1);
        $this->db->from("so");
        $this->db->join("so_item AS soi", "soi.so_no = so.so_no", "INNER");
        $this->db->join("so_payment_status as sops", "so.so_no = sops.so_no", "LEFT");
        $this->db->join("client AS c", "c.id = so.client_id", "INNER");
        $this->db->join("product AS p", "p.sku = soi.prod_sku", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = so.platform_id", "INNER");

        # so.hold_status = 15 means this is parent of order with split. We exclude parent and use each split child with concatenated so_no with split_so_group
        $this->db->where(array("so.client_id" => $client_id, "so.status >= 2" => null, "so.hold_status != 15" => null, "p.cat_id NOT IN ($ca_catid_arr)" => null));

        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "
                pbv.platform_currency_id currency_id,
                so.so_no,
                IF(ISNULL(so.split_so_group), so.so_no, CONCAT_WS('/',so.split_so_group,so.so_no)) AS join_split_so_no,
                so.split_so_group,
                c.id client_id,
                so.order_create_date, so.delivery_name, p.sku, soi.prod_name, soi.amount,
                so.status, so.refund_status, so.hold_status, so.dispatch_date, sops.payment_gateway_id");
        // var_dump($this->db->last_query());die();
    }

    public function get_accessory_catid_arr()
    {
        /* ======================================================================
            IMPORTANT!
            This will get all current category IDs that below to Complementary Accessories
            if there are more accessory cat ids in future,
            add it to this array $ca_catid_arr.
        ========================================================================= */

        return $accessory_catid_arr = array("753");
    }

    public function get_unpaid_order_history($client_id, $payment_gateway_arr = array(), $classname = "Order_history_dto")
    {
        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->get_accessory_catid_arr());

        # active but unpaid so; not on hold
        $option = array("limit" => -1);
        $this->db->from("so");
        $this->db->join("so_item AS soi", "soi.so_no = so.so_no", "INNER");
        $this->db->join("so_payment_status AS sops", "so.so_no = sops.so_no", "INNER");
        $this->db->join("so_bank_transfer AS sbt", "so.so_no = sbt.so_no", "LEFT");
        $this->db->join("client AS c", "c.id = so.client_id", "INNER");
        $this->db->join("product AS p", "p.sku = soi.prod_sku", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = so.platform_id", "INNER");
        $this->db->where(array("so.client_id" => $client_id, "so.status" => 1, "so.hold_status" => 0, "p.cat_id NOT IN ($ca_catid_arr)" => null));

        if (!empty($payment_gateway_arr)) {
            $this->db->where_in("sops.payment_gateway_id", $payment_gateway_arr);
        }
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "pbv.platform_currency_id currency_id, so.so_no, so.status, so.hold_status, so.refund_status, c.id client_id, so.order_create_date, so.delivery_name, p.sku, soi.prod_name, soi.amount, sbt.net_diff_status, so.status, so.refund_status, sops.payment_gateway_id");

    }

    public function get_fnac_pending_payment_orders($where = array(), $option = array())
    {
        $this->db->from("so");
        $this->db->join("so_extend soex", "so.so_no = soex.so_no", "INNER");
        $this->db->where(array("so.status" => 1, "soex.acked" => "Y"));
        $this->include_vo();
        return $this->common_get_list($where, $option, $this->get_vo_classname(), "so.*");
    }

    public function get_ebay_feedback_email_content($where = array(), $option = array(), $classname = "ebay_feedback_email_dto")
    {
        if (!isset($option["limit"])) {
            $option["limit"] = -1;
        }
        $this->db->from("so");
        $this->db->join("so_item soi", "so.so_no = soi.so_no", "INNER");
        $this->db->join("client c", "so.client_id = c.id", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = so.platform_id", "INNER");
        $this->db->group_by("so.so_no");
        $this->db->where(array("so.biz_type" => 'EBAY', "so.dispatch_date > NOW() - INTERVAL 1 WEEK" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "so.so_no, so.platform_id, c.email, so.delivery_name, GROUP_CONCAT(CONCAT(soi.ext_item_cd,',',soi.prod_name) SEPARATOR '||')item_list, pbv.language_id");
    }

    public function get_flex_sales_invoice($where, $classname = "sales_invoice_dto")
    {
        $option['limit'] = -1;
        $this->db->from("(select so.so_no, so.biz_type, so.parent_so_no, so.status, 
        so.client_id, so.currency_id, so.platform_id,so.client_promotion_code,so.dispatch_date,so.order_create_date,so.delivery_charge,so.split_so_group,soex.order_reason,
        sum(fr.amount) as amount, fr.txn_id, fr.txn_time, fr.flex_batch_id, fr.gateway_id from so
        INNER JOIN so_extend soex on so.so_no = soex.so_no LEFT JOIN flex_ria fr ON so.so_no = fr.so_no group by so.so_no) tbl_1");

        $this->db->join("so_payment_status sops", "sops.so_no = tbl_1.so_no", "LEFT");
        $this->db->join("so_item_detail soid", "soid.so_no = tbl_1.so_no", "LEFT");
        $this->db->join("client c", "c.id = tbl_1.client_id", "INNER");

        $this->db->join("flex_gateway_mapping gm", "gm.gateway_id = tbl_1.gateway_id AND gm.currency_id = tbl_1.currency_id", "LEFT");
        $this->db->join("sku_mapping map", "map.sku = soid.item_sku AND map.ext_sys = 'WMS' AND map.status = 1", "LEFT");
        $this->db->orderby("gm.gateway_code desc, tbl_1.txn_time DESC, sops.payment_gateway_id, tbl_1.currency_id, soid.item_sku");
        $start_date = $where["start_date"];
        $end_date = $where["end_date"];

        unset($where["start_date"]);
        unset($where["end_date"]);

//      if (check_finance_role())
        $dispatch_string = "tbl_1.finance_dispatch_date";
//      else
//          $dispatch_string = "tbl_1.dispatch_date";
        $where[$dispatch_string . " >="] = $start_date . ' 00:00:00';
        $where[$dispatch_string . " <="] = $end_date . ' 23:59:59';
        $this->include_dto($classname);

        //$this->common_get_list($where, $option, $classname, 'tbl_1.txn_id, RIGHT(tbl_1.platform_id,2) as sm_code, SUBSTR(tbl_1.platform_id, 1, CHAR_LENGTH(tbl_1.platform_id) - 2) as contain_size, tbl_1.client_promotion_code as promotion_code, CONCAT(gm.gateway_code, "I") tran_type,date_format(tbl_1.dispatch_date, "%Y-%m-%d") dispatch_date, date_format(tbl_1.txn_time, "%Y-%m-%d") txn_time, map.ext_sku product_code, tbl_1.platform_id, tbl_1.flex_batch_id, gm.gateway_code AS report_pmgw, if(tbl_1.gateway_id !="", tbl_1.gateway_id, sops.payment_gateway_id) as gateway_id, tbl_1.currency_id, map.ext_sku AS master_sku, soid.qty AS qty, soid.amount AS amount, tbl_1.order_create_date, c.email AS customer_email, tbl_1.so_no, if(soid.line_no = 1,tbl_1.delivery_charge,0) AS delivery_charge');
        //var_dump($this->db->last_query());die();
        return $this->common_get_list($where, $option, $classname, 'tbl_1.txn_id, tbl_1.biz_type, tbl_1.order_reason, tbl_1.parent_so_no, tbl_1.split_so_group, RIGHT(tbl_1.platform_id,2) as sm_code, SUBSTR(tbl_1.platform_id, 1, CHAR_LENGTH(tbl_1.platform_id) - 2) as contain_size, tbl_1.client_promotion_code as promotion_code, CONCAT(gm.gateway_code, "I") tran_type,date_format(tbl_1.finance_dispatch_date, "%Y-%m-%d") dispatch_date, date_format(tbl_1.txn_time, "%Y-%m-%d") txn_time, map.ext_sku product_code, tbl_1.platform_id, tbl_1.flex_batch_id, gm.gateway_code AS report_pmgw, if(tbl_1.gateway_id !="", tbl_1.gateway_id, sops.payment_gateway_id) as gateway_id, tbl_1.currency_id, map.ext_sku AS master_sku, soid.qty AS qty, soid.amount AS amount, tbl_1.order_create_date, c.email AS customer_email, tbl_1.so_no, if(soid.line_no = 1,tbl_1.delivery_charge,0) AS delivery_charge, soid.line_no as line_index');
    }

    public function get_flex_refund_invoice($where, $classname = "refund_invoice_dto")
    {
        $option['limit'] = -1;
        $this->db->from("flex_refund frf");
        $this->db->join("so", "frf.so_no = so.so_no", "INNER");
        $this->db->join("so_item_detail soid", "so.so_no = soid.so_no", "INNER");
        $this->db->join("sku_mapping map", " map.sku = soid.item_sku AND map.ext_sys = 'WMS' AND map.status = 1", "LEFT");
        $this->db->join("flex_gateway_mapping gm", "gm.gateway_id = frf.gateway_id AND gm.currency_id = frf.currency_id", "LEFT");
        $this->db->join("so_payment_status sops", "sops.so_no = so.so_no", "LEFT");
        $this->db->orderby("gm.gateway_code desc, frf.flex_batch_id, frf.txn_time");
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'map.ext_sku master_sku, CONCAT(gm.gateway_code,frf.status) tran_type, frf.flex_batch_id, frf.txn_time, frf.currency_id, gm.gateway_code report_pmgw, soid.qty, soid.amount unit_price, so.so_no, frf.txn_id, sops.payment_gateway_id gateway_id');
    }

    public function get_orders_by_sku_and_status($sku, $so_status = 2, $where = array(), $option = array())
    {
        $this->db->from("so");
        $this->db->join("so_item soi", "soi.so_no = so.so_no", "INNER");
        $where["soi.prod_sku"] = $sku;
        $where["so.status"] = $so_status;
        $this->db->where($where);
        $select_str = "so.so_no, so.platform_order_id, so.platform_id, so.txn_id, so.client_id, so.biz_type, so.amount,
                        so.status as so_status, so.refund_status, so.hold_status, soi.prod_sku, soi.prod_name";

        if (isset($option["num_row"])) {
            $this->db->select("COUNT(so.so_no) AS total, COUNT(soi.qty) as total_qty");
            if ($query = $this->db->get()) {
                $rs["total_orders"] = $query->row()->total;
                $rs["total_qty"] = $query->row()->total_qty;
                return $rs;
            }
        } else {
            $this->db->select($select_str);
            $query = $this->db->get();
            // echo "<pre>"; var_dump($this->db->last_query()); var_dump($this->db->_error_message()); die();
            if ($query) {
                $rs = array();
                if ($query->num_rows() > 0) {
                    foreach ($query->result_array() as $row) {
                        $rs[] = $row;
                    }
                }
                return $rs;
            }
        }


        return FALSE;
    }

    public function get_ebay_pending_shipment_update_orders($where = array(), $option = array(), $classname = "ebay_pending_shipment_orders_dto")
    {
        $option['limit'] = -1;
        $option['array_list'] = 1;
        $this->db->from("so");
        $this->db->join("so_extend AS soex", "so.so_no = soex.so_no", "INNER");
        $this->db->join("so_item AS soi", "so.so_no = soi.so_no", "INNER");
        $this->db->join("so_item_detail AS soid", "soid.so_no = soi.so_no and soid.line_no = soi.line_no and soid.item_sku = soi.prod_sku", "INNER");
        $this->db->join("so_allocate AS soal", "soal.so_no = soid.so_no and soal.item_sku = soid.item_sku and soal.line_no = soid.line_no", "INNER");
        $this->db->join("so_shipment AS sosh", "sosh.sh_no = soal.sh_no", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = so.platform_id", "INNER");
        $this->db->where(array("so.biz_type" => "EBAY", "soex.fulfilled" => "N", "sosh.status" => 2, "so.status" => 6, "soid.amount >" => 0)); // soid.amount > 0 is to exclude compensation item
        $this->db->groupby("so.so_no");
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'so.so_no, so.platform_order_id, soi.ext_item_cd , count(*) item_count, sosh.courier_id, sosh.tracking_no, so.dispatch_date, pbv.platform_country_id');
    }

    public function get_qoo10_pending_shipment_update_orders($where = array(), $option = array(), $classname = "qoo10_pending_shipment_orders_dto")
    {
        $option['limit'] = -1;
        $option['array_list'] = 1;
        $this->db->from("so");
        $this->db->join("so_extend AS soex", "so.so_no = soex.so_no", "INNER");
        $this->db->join("so_item AS soi", "so.so_no = soi.so_no", "INNER");
        $this->db->join("so_item_detail AS soid", "soid.so_no = soi.so_no and soid.line_no = soi.line_no and soid.item_sku = soi.prod_sku", "INNER");
        $this->db->join("so_allocate AS soal", "soal.so_no = soid.so_no and soal.item_sku = soid.item_sku and soal.line_no = soid.line_no", "INNER");
        $this->db->join("so_shipment AS sosh", "sosh.sh_no = soal.sh_no", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = so.platform_id", "INNER");
        $this->db->join("courier", "sosh.courier_id = courier.id", "INNER"); #get full courier name
        $this->db->where(array("so.biz_type" => "QOO10", "soex.fulfilled" => "N", "sosh.status" => 2, "so.status" => 6, "so.refund_status" => 0, "soid.amount >" => 0)); // soid.amount > 0 is to exclude compensation item
        $this->db->groupby("so.so_no");
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'so.so_no, so.platform_order_id, so.txn_id, courier.courier_name, soi.ext_item_cd , count(*) item_count, sosh.courier_id, sosh.tracking_no, so.dispatch_date, pbv.platform_country_id');
    }

    public function get_rakuten_pending_shipment_update_orders($where = array(), $option = array(), $classname = "rakuten_pending_shipment_orders_dto")
    {
        $option['limit'] = -1;
        $option['array_list'] = 1;
        $this->db->from("so");
        $this->db->join("so_extend AS soex", "so.so_no = soex.so_no", "INNER");
        $this->db->join("so_item AS soi", "so.so_no = soi.so_no", "INNER");
        $this->db->join("so_item_detail AS soid", "soid.so_no = soi.so_no and soid.line_no = soi.line_no and soid.item_sku = soi.prod_sku", "INNER");
        $this->db->join("so_allocate AS soal", "soal.so_no = soid.so_no and soal.item_sku = soid.item_sku and soal.line_no = soid.line_no", "INNER");
        $this->db->join("so_shipment AS sosh", "sosh.sh_no = soal.sh_no", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = so.platform_id", "INNER");
        $this->db->join("courier", "sosh.courier_id = courier.id", "INNER"); #get full courier name
        $this->db->where(array("(so.biz_type = 'RAKUTEN' OR so.biz_type = 'WEBSITE' OR so.biz_type = 'MANUAL')" => NULL, "soex.fulfilled" => "N", "sosh.status" => 2, "so.status" => 6, "so.refund_status" => 0, "soid.amount >" => 0)); // soid.amount > 0 is to exclude compensation item
        $this->db->groupby("so.so_no");
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'so.so_no, so.platform_order_id, so.txn_id, courier.courier_name, soi.ext_item_cd , count(*) item_count, sosh.courier_id, sosh.tracking_no, so.dispatch_date, pbv.platform_country_id');
    }

    public function get_automated_feedback_email_content($where = array(), $option = array(), $classname = "Feedback_email_dto")
    {
        $option['limit'] = -1;
        $this->db->from("so");
        $this->db->join("so_item_detail AS soid", "soid.so_no = so.so_no", "INNER");
        $this->db->join("so_allocate AS soal", "soal.so_no = soid.so_no AND soal.line_no = soid.line_no AND soal.item_sku = soid.item_sku", "INNER");
        $this->db->join("so_shipment AS sosh", "sosh.sh_no = soal.sh_no", "INNER");
        $this->db->join("so_extend AS soex", "soex.so_no = so.so_no", "LEFT");
        $this->db->join("client AS cl", "cl.id = so.client_id", "INNER");
        $this->db->where(array("so.order_create_date + INTERVAL IF(DATE_FORMAT(so.dispatch_date, '%w') = 5, 5, 7) DAY > so.dispatch_date" => null,
            "DATEDIFF(now(), so.dispatch_date) = (IF(DATE_FORMAT(now(), '%w') = 5, 4, 6))" => null,
            "so.biz_type IN ('ONLINE', 'MOBILE', 'EBAY', 'MANUAL', 'OFFLINE')" => null,
            "so.status" => 6, "sosh.status" => 2));
        $this->db->groupby("so.so_no, soal.warehouse_id");
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'so.so_no, so.biz_type, so.platform_id, so.delivery_country_id, soal.warehouse_id, sosh.courier_id, cl.forename, cl.email, soex.conv_site_id');
    }

    public function get_profit_margin($so_no)
    {
        $this->db->from("so_item_detail soid");
        $this->db->where(array("soid.so_no" => $so_no, "soid.amount > 0" => null));
        $this->db->groupby("soid.so_no");
        $this->db->limit(1);
        $this->db->select('soid.so_no, count(1) as number_of_items, sum(profit*qty)/sum(amount) as order_margin');

        if ($query = $this->db->get()) {
            foreach ($query->result("array", null) as $obj) {
                $rs = $obj;
            }
            return $rs;
        }
    }

    public function get_priority_score($so_no)
    {
        $option['limit'] = 1;
        $this->db->from("so");
        $this->db->join("so_extend AS soext", "soext.so_no = so.so_no", "INNER");
//      $this->db->join("so_item_detail AS soid", "soid.so_no = so.so_no", "LEFT");
//      $this->db->join("exchange_rate AS fx", "fx.from_currency_id = so.currency_id AND fx.to_currency_id = 'USD'", "INNER");
        $this->db->where(array("so.so_no" => $so_no));
        $this->db->groupby("so.so_no");
//      $this->db->select('so.biz_type, so.order_create_date, SUM(soid.profit * fx.rate) AS usd_profit, so.delivery_country_id');
        $this->db->select('so.biz_type, so.order_create_date, so.delivery_country_id, soext.conv_site_id');

        if ($query = $this->db->get()) {
            foreach ($query->result("array", $classname) as $obj) {
                $rs = $obj;
            }
            return $rs;
        }
    }

    public function get_last_ten_transaction_info_by_client_id($client_id)
    {
        $option['limit'] = 10;
        $this->db->from("so");
        $this->db->join("so_item_detail soid", "so.so_no = soid.so_no", "INNER");
        $this->db->join("product p", "p.sku = soid.item_sku", "INNER");
        $this->db->join("category cat", "p.cat_id = cat.id", "INNER");
        $this->db->join("category sc", "p.sub_cat_id = sc.id", "INNER");
        $this->db->join("category ssc", "p.sub_sub_cat_id = ssc.id", "LEFT");
        $this->db->join("brand br", "p.brand_id = br.id", "INNER");
        $this->db->where(array("so.status" => 6, "so.refund_status" => 0, "so.client_id" => $client_id));
        $this->db->order_by("so.order_create_date", "desc");
        $this->db->select('CONCAT_WS(";", trim(cat.name), trim(sc.name), trim(ssc.name), trim(br.brand_name)) trans_product, so.order_create_date, soid.qty');

        if ($query = $this->db->get()) {
            foreach ($query->result("array", $classname) as $obj) {
                $rs[] = $obj;
            }
            return $rs;
        }
    }

    public function get_lifetime_transaction_by_client_id($client_id)
    {
        $this->db->from("so");
        $this->db->join("so_item_detail soid", "so.so_no = soid.so_no", "INNER");
        $this->db->join("product p", "p.sku = soid.item_sku", "INNER");
        $this->db->where(array("so.status" => 6, "so.refund_status" => 0, "so.hold_status" => 0, "so.client_id" => $client_id));
        $this->db->group_by("so.client_id");
        $this->db->select('SUM(soid.qty) total');

        if ($query = $this->db->get()) {
            foreach ($query->result("array", $classname) as $obj) {
                $rs = $obj['total'];
            }
            return $rs;
        }
    }

    public function get_distinct_client_id_list($where = array())
    {
        $this->db->from("so");
        $this->db->where($where);
        $this->db->select('DISTINCT client_id', FALSE);

        if ($query = $this->db->get()) {
            foreach ($query->result("array", $classname) as $obj) {
                $rs[] = $obj['client_id'];
            }
            return $rs;
        }
    }

    public function get_pending_order_info($where = array(), $option = array(), $classname = "Pending_order_dto")
    {
        $option['limit'] = -1;
        $this->db->from("flex_ria AS fr");
        $this->db->join("so", "so.so_no = fr.so_no", "INNER");
        $this->db->join("so_item_detail soid", "soid.so_no = fr.so_no", "INNER");
        $this->db->join("sku_mapping map", "map.sku = soid.item_sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("flex_gateway_mapping gm", "gm.gateway_id = fr.gateway_id AND gm.currency_id = fr.currency_id", "LEFT");
        //$this->db->where(array("fr.status"=>'RIA'));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'map.ext_sku, fr.flex_batch_id, fr.txn_time, fr.currency_id, gm.gateway_code gateway_id, soid.qty, soid.amount, fr.so_no, fr.txn_id platform_order_id');
    }

    public function get_rma_customer_email_address($past_day)
    {

        // turning $past_day to integer is enough for any sanitization
        $past_day = intval($past_day);
        $sql = "
            # emails of customers with updated RMA tickets within the past 7 days
            select distinct c.email
            from
            client c inner join rma r on c.id = r.client_id
            where r.modify_on > date_Sub(NOW(), interval ? day)
        ";

        $result = $this->db->query($sql, array($past_day));
        foreach ($result->result() as $row) {
            $rs[] = $row->email;
        }

        return $rs;
    }

    public function get_sales_summary($start_date, $end_date)
    {
        if ($start_date == $end_date) {
            $start_date .= " 00:00:00";
            $end_date .= " 23:59:59";
        }

        $sql = "
            select sm.ext_sku master_sku, sum(sb.amount*ex.rate) total_amount_hkd, sum(sb.qty) total_quantity from so
            inner join so_item sb on so.so_no = sb.so_no and so.biz_type <> 'SPECIAL' and so.status >= 2 and so.hold_status = 0
            and so.order_create_date >= ? and so.order_create_date <= ?
            inner join sku_mapping sm on sm.ext_sys = 'WMS' and sm.sku = sb.prod_sku
            inner join exchange_rate ex on ex.from_currency_id = so.currency_id and ex.to_currency_id = 'HKD'
            group by sb.prod_sku
        ";

        // turning $past_day to integer is enough for any sanitization
        $past_day = intval($past_day);

        // $result = $this->db->query($sql, array($past_day));
        $result = $this->db->query($sql, array($start_date, $end_date));
        if ($result == null) $rs = array();
        foreach ($result->result() as $row) {
            $rs[] = $row;
        }
        return $rs;
    }

    public function get_aftership_data($where = array(), $option = array(), $classname = "aftership_data_dto")
    {
        $this->include_dto($classname);

        $option['limit'] = -1;
        $this->db->from("so");
        $this->db->join("so_allocate soa", "soa.so_no = so.so_no AND line_no = 1", "LEFT");
        $this->db->join("client c", "c.id = so.client_id", "INNER");
        $this->db->join("so_shipment sosh", "soa.sh_no = sosh.sh_no", "LEFT");
        $this->db->join("country cy", "cy.id = so.bill_country_id", "INNER");
        $this->db->group_by("so.so_no");
        return $this->common_get_list($where, $option, $classname, 'sosh.courier_id courier, sosh.tracking_no trackingno, c.email clientemail, CONCAT_WS(" ",c.tel_1, c.tel_2,c.tel_3) as buyertel, so.so_no so_no, so.bill_name bill_name, cy.id_3_digit country_code, so.dispatch_date');
    }

    public function get_aftership_report_for_ftp($where = array(), $option = array(), $classname = "aftership_data_dto")
    {
        $this->include_dto($classname);

        $option['limit'] = -1;
        $this->db->from("so");
        $this->db->join("so_allocate soa", "soa.so_no = so.so_no AND line_no = 1", "LEFT");
        $this->db->join("client c", "c.id = so.client_id", "INNER");
        $this->db->join("so_shipment sosh", "soa.sh_no = sosh.sh_no", "LEFT");
        $this->db->join("country cy", "cy.id = so.bill_country_id", "INNER");
        $this->db->group_by("so.so_no");

        return $this->common_get_list($where, $option, $classname, 'sosh.tracking_no trackingno, if(sosh.courier_id="toll-global-expr","toll-global-express" , sosh.courier_id) courier, so.so_no so_no, so.bill_name bill_name, c.email clientemail, cy.id_3_digit country_code, so.dispatch_date');
    }


    public function get_wow_email_list_data($where = array(), $option = array(), $classname = "aftership_data_dto")
    {
        $this->db->from('so');
        $this->db->join('(
                            SELECT soal.sh_no, soal.so_no, sosh.courier_id, sosh.tracking_no
                            FROM so_allocate soal
                            JOIN so_shipment sosh
                                ON soal.sh_no = sosh.sh_no
                            GROUP BY soal.sh_no, soal.so_no
                        )a', 'a.so_no = so.so_no', 'LEFT');
        $this->db->join("client c", "c.id = so.client_id", "INNER");
        $this->db->join("country cy", "cy.id = so.bill_country_id", "INNER");
        $this->db->group_by(array("a.so_no", "a.sh_no"));
        $this->db->where(array('so.delivery_type_id' => 'STD', 'HOUR(TIMEDIFF(dispatch_date, order_create_date))/24 <= ' => '6', 'a.sh_no IS NOT NULL' => NULL));
        $this->db->where($where);
        $this->db->select('a.courier_id courier, a.tracking_no trackingno, c.email clientemail, CONCAT_WS(" ",c.tel_1, c.tel_2,c.tel_3) as buyertel, so.so_no so_no, c.forename bill_name, cy.id_3_digit country_code', FALSE);
        $this->include_dto($classname);
        $rs = array();
        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return $rs;
        }

        return FALSE;
    }

    public function get_thank_you_mail_list($where = array(), &$replace_last_update = Null, $option = array(), $classname = "aftership_mail_list_dto")
    {
        $this->db->from('so');

        $this->db->join('so_extend soext', 'so.so_no = soext.so_no', 'LEFT');
        $this->db->join('so_payment_status sps', 'so.so_no = sps.so_no', 'LEFT');

        $this->db->join('(
                            SELECT DISTINCT soal.sh_no, soal.so_no, sosh.courier_id
                            FROM so_allocate soal
                            JOIN so_shipment sosh
                                ON soal.sh_no = sosh.sh_no
                            GROUP BY soal.sh_no, soal.so_no
                        )a', 'a.so_no = so.so_no', 'LEFT');

        $this->db->group_by(array("a.so_no", "a.sh_no"));

        $this->db->where(array('so.delivery_type_id' => 'STD', 'a.sh_no IS NOT NULL' => NULL));

        $this->db->where($where);

        $this->db->select('so.so_no, so.bill_name, soext.aftership_status, soext.aftership_checkpoint, HOUR(TIMEDIFF(dispatch_date, order_create_date))/24 AS date_to_delivery, count(*) as no_of_partial_shipment, a.courier_id,
                            so.expect_ship_days, so.expect_del_days, so.dispatch_date, so.order_create_date, sps.pay_date', FALSE);

        $this->include_dto($classname);


        $rs = array();

        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $found = false;
                $dispatch_date = $obj->get_dispatch_date();
                //$finance_dispatch = $obj->get_finance_dispatch_date();
                $pay_date = $obj->get_pay_date();
                $order_create_date = $obj->get_order_create_date();
                $expect_ship_days = $obj->get_expect_ship_days();
                $expect_del_days = $obj->get_expect_del_days();
                $aftership_stat = $obj->get_aftership_status();
                $aftership_cp = $obj->get_aftership_checkpoint();
                //$replace_last_update = date_format(date($aftership_cp), 'F j, Y, g:i a'); //format without time - 'l jS F Y'
                $replace_last_update = date('d/m/Y H:i:s', strtotime($aftership_cp));

                if (!empty($expect_del_days)) {
                    $max_del_day = trim(substr($expect_del_days, strpos($expect_del_days, '-') + 1));

                    if (ctype_digit($max_del_day)) {
                        # Expected date of shipment based on upper limit of expect_ship_days
                        // $expect_max_ship_date = strtotime($order_create_date) + ($max_ship_day*24*60*60);
                        //$loopdate = $dispatch_date;
                        $loopdate = $pay_date;
                        $c = $max_del_days;

                        for ($i = 0; $i < $c; $i++) {
                            $checkday = date('D', $loopdate);
                            if (($checkday == 'Sat') or ($checkday == 'Sun')) {
                                //$max_del_day = $max_del_day + (1*24*60*60);
                                $max_del_day = $max_del_day + 1;
                                $loopdate = $loopdate + (1 * 24 * 60 * 60);
                            }
                        }

                        if (strtotime($aftership_cp) <= (strtotime($pay_date) + ($max_del_day * 24 * 60 * 60))) {
                            // is delivered on time because dispatch date is <= expected ship date - 1
                            $delivered_on_time = 1;
                        } else {
                            // fail wow; set $obj to nul
                            $delivered_on_time = 0;
                            //$obj = null;
                        }
                    }
                }

                // # (transitioning period) if above does not pass through, go back to using old logic
                // if(!$delivered_on_time)
                // {
                //  # fail wow because > 6 days
                //  //if( (strtotime($dispatch_date) - strtotime($order_create_date))/(24*60*60) > $expect_day)
                //  if( (strtotime($dispatch_date) - strtotime($order_create_date))/(24*60*60) > $max_ship_day)
                //  {
                //      $obj = null;
                //  }

                // }
                if ($obj) {
                    $rs[] = $obj;
                    $rs['delivered_on_time'] = $delivered_on_time;
                }
            }
            //var_dump($rs); die();
            return $rs;
        }
        return FALSE;

    }

    public function get_so_amount_by_pmgw_currency($where = array(), $option = array(), $classname = "so_amount_by_pmgw_currency_dto")
    {
        $this->db->from('so');
        $this->db->join('so_payment_status sops', 'so.so_no = sops.so_no', 'left');
        $this->db->join('payment_gateway pmgw', 'pmgw.id = sops.payment_gateway_id', 'left');
        $this->db->where($where);
        $this->db->group_by(array('so.currency_id', 'sops.payment_gateway_id'));

        if (isset($option['orderby'])) {
            $this->db->order_by($option['orderby']);
        }

        $this->db->select('count(so.so_no) as so_count, sum(so.amount) as so_amount, so.currency_id, sops.payment_gateway_id, pmgw.name pmgw_name');

        $rs = array();
        $this->include_dto($classname);
        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }

        return FALSE;
    }

    public function get_so_amount_by_pmgw_currency_with_eur_country($where = array(), $option = array(), $classname = "so_amount_by_pmgw_currency_dto")
    {
        $this->db->from('so');
        $this->db->join('so_payment_status sops', 'so.so_no = sops.so_no', 'left');
        $this->db->join('payment_gateway pmgw', 'pmgw.id = sops.payment_gateway_id', 'left');
        $this->db->join('platform_biz_var pbv', 'so.platform_id = pbv.selling_platform_id', 'left');
        $this->db->where($where);
        $this->db->group_by(array('so.currency_id', 'sops.payment_gateway_id', 'pbv.platform_country_id'));

        if (isset($option['orderby'])) {
            $this->db->order_by($option['orderby']);
        }

        $this->db->select('count(so.so_no) as so_count, sum(so.amount) as so_amount, so.currency_id, sops.payment_gateway_id, pmgw.name pmgw_name, pbv.platform_country_id');

        $rs = array();
        $this->include_dto($classname);

        //To debug
        /*$this->db->save_queries = true;
        $this->db->get();
        echo "<pre>"; var_dump($this->db->last_query()); var_dump($this->db->_error_message());die();
        */

        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }

        return FALSE;
    }

    public function get_integrated_fulfillment_list_w_name($where = array(), $option = array(), $classname = "So_list_w_name_dto")
    {
        $this->db->from('integrated_order_fulfillment as iof');
        $select_str = "iof.*,
        sm.ext_sku as master_sku
        ";

        $where_str = '';

        /*$this->db->join("(
                select prod_sku, warehouse_id, coalesce(sum(inventory), 0) as inventory, coalesce(sum(git),0) as git
                from inventory
                where warehouse_id = '{$option["warehouse_id"]}'
                group by prod_sku
        ) as i", "iof.sku = i.prod_sku", "left");*/

        $this->db->join("sku_mapping sm", "sm.sku = iof.sku and ext_sys = 'WMS'", 'INNER');

        if ($option["solist"] != "") {
            $this->db->where_in("iof.so_no", $option["solist"]);
        }

        // if($option["ext_sku"] != "")
        // {
        //  $this->db->join("sku_mapping map", "map.sku = iof.sku", "LEFT");
        //  $select_str .= ", map.ext_sku AS ";
        // }

        if ($option["product_related"] != "") {
            # display category names, freight weight
            $this->db->join("product p", "p.sku = iof.sku", "INNER");
            $this->db->join("freight_category fc", "fc.id = p.freight_cat_id", "LEFT");
            $this->db->join("category cat", "cat.id = p.cat_id", "LEFT");
            $this->db->join("category scat", "scat.id = p.sub_cat_id", "LEFT");
            $select_str .= ", cat.name AS cat_name, scat.name AS sub_cat_name, fc.weight";
        }

        if ($option["show_so"] != "") {
            $this->db->join("so so", "so.so_no = iof.so_no", "INNER");
            $this->db->join("exchange_rate ex", "ex.from_currency_id = so.currency_id AND to_currency_id = 'USD'", "INNER");
            $this->db->join("so_item soi", "soi.so_no = iof.so_no AND soi.prod_sku = iof.sku", "INNER");
            $select_str .= ", ex.rate AS rate, so.currency_id, so.delivery_state, soi.amount AS so_item_amount";
        }

        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"]) && empty($option["total_items"]) && empty($option["total_order_row"])) {
            $this->db->select($select_str, FALSE);

            $this->include_dto($classname);

            if (isset($option["orderby"])) {
                // sequence of ORDER BY so_no is important; may cause problem
                if (strpos($option["orderby"], "so_no") !== FALSE) {
                    $this->db->order_by($option["orderby"]);
                } else {
                    $this->db->order_by($option["orderby"]);
                    $this->db->order_by("so_no");
                }
            }
            $this->db->order_by("iof.split_so_group desc");

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }
            $rs = array();

            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            }

        } elseif (!empty($option["total_order_row"])) {
            $this->db->select('COUNT(iof.so_no) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        } else {
            $this->db->select('SUM(iof.outstanding_qty) AS total');
            // $this->db->select(($option["num_rows"] ? 'COUNT(distinct(iof.so_no))' : 'COALESCE(SUM(iof.outstanding_qty), 0)').' AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function get_so_by_txn_id($txn_id, $where = "", $option = "", $classname = "")
    {
        $this->db->from("so");
        $this->db->where(array("txn_id LIKE '%$txn_id%'" => null));
        $option["limit"] = 1;
        $this->include_dto($classname);

        return $this->common_get_list($where, $option, $classname, '*');

    }

    public function get_so_aps_report($list)
    {
        // $query = "
        //  select
        //  so.so_no,
        //  so.platform_id,
        //  so.create_on,
        //  si.prod_name,
        //  si.prod_sku,
        //  si.qty,
        //  si.amount,
        //  so.hold_status,
        //  so.refund_status
        //  from so
        //  inner join so_item si on so.so_no = si.so_no
        //  where so.so_no in ()
        // ";

        $list = array_filter($list);

        $this->db->select("
            so.so_no,
            so.platform_id,
            so.create_on,
            si.prod_name,
            si.prod_sku,
            si.qty,
            si.amount,
            so.hold_status,
            so.refund_status
            ");
        $this->db->from("so");
        $this->db->join("so_item si", "si.so_no = so.so_no", "inner");
        $this->db->where_in("so.so_no", $list);

        if ($query = $this->db->get()) {
            // var_dump($this->db->last_query()); die();
            foreach ($query->result() as $obj)
                $rs[] = $obj;

            return $rs;
        }
    }

    // gets the relevant information to compute priority score
    public function get_so_priority_score_info($so_no_list)
    {
        $sql = "
            select
                s.so_no,
                s.delivery_country_id,
                s.order_create_date,
                s.biz_type,
                e.conv_site_id,
                (select sum(profit*qty)/sum(amount) from so_item_detail d where d.amount>0 and d.so_no = s.so_no) as order_margin
            from so s
            inner join so_extend e on e.so_no = s.so_no
            where s.so_no in
            ($so_no_list)
            order by s.so_no
        ";

        $result = $this->db->query($sql);
        return $result->result_array();
    }


    public function get_order_score_activity_log_report($where = array(), $option = array(), $classname = "So_w_margin_dto")
    {

        $this->db->from("so as s");
        $selectStr = "s.create_on as create_on, s.so_no as so_no, s.platform_id as platform_id, s.status as status, sps.modify_on as modify_on, sps.modify_by as modify_by, s.dispatch_date as dispatch_date, temp.margin as margin, sps.score as score";
        $this->db->join("so_priority_score sps", "sps.so_no  = s.so_no", "INNER");
        $this->db->join("(select round(sum(profit*qty)/sum(amount)*100,2) as margin, so_no from so_item_detail group by so_no) temp", "temp.so_no = s.so_no", "INNER");
        $this->db->select($selectStr);
        $this->db->orderby("modify_on ASC");
        $this->db->where($where);

        $this->include_dto($classname);
        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }

        return FALSE;
    }

    public function get_duplicate_purchase()
    {
        // The cron job may not run at the scheduled time sharp, so add 20 seconds in the SQL to cover this lag

        $sqlTimeBuffer = '00:00:20';
        $searchFromDayBefore = 3;

        $sql =
            "
            select distinct so.client_id, soi.prod_sku, p.name, so.so_no, so.order_create_date, so.currency_id, soi.unit_price
                from so inner join so_item soi on so.so_no = soi.so_no left join product p on soi.prod_sku = p.sku
            inner join
            (
                select so_join.client_id, soi_join.prod_sku from so so_join inner join so_item soi_join on so_join.so_no = soi_join.so_no
                where so_join.status in (2, 3)
                and so_join.order_create_date >= subdate(addtime(now(), '{$sqlTimeBuffer}'), {$searchFromDayBefore})
                group by so_join.client_id, soi_join.prod_sku
                having count(distinct so_join.so_no) > 1
            ) filterDuplicate on filterDuplicate.client_id = so.client_id and filterDuplicate.prod_sku = soi.prod_sku
            where 1
            and so.status >= 2          # only paid orders
            and so.order_create_date >= subdate(addtime(now(), '{$sqlTimeBuffer}'), {$searchFromDayBefore})
            and concat(so.client_id,'|',soi.prod_sku) in
            (
                select concat(so_today.client_id,'|',soi_today.prod_sku) from so so_today inner join so_item soi_today on so_today.so_no = soi_today.so_no
                where so_today.status in (2, 3)
                and so_today.order_create_date >= subdate(addtime(now(), '{$sqlTimeBuffer}'), 1)
                group by so_today.client_id, soi_today.prod_sku
            )
            order by so.client_id, soi.prod_sku, so.order_create_date desc, so.so_no
        ";

        $result = $this->db->query($sql);
        return $result->result_array();
    }

    public function get_chargeback_info($so_no)
    {
        $sql = <<<SQL
            select
                so.so_no,
                so.lang_id,
                c.courier_name,
                ss.tracking_no courier_tracking_number,
                so.currency_id,

                max(r.create_on) refund_date,
                sum(r.total_refund_amount) refund_amount,

                so.delivery_address,
                so.delivery_postcode,
                so.delivery_city,
                so.delivery_state,
                so.delivery_country_id

            from so
            left join refund r          on r.so_no                      = so.so_no
            left join so_shipment ss    on SUBSTRING_INDEX(sh_no,'-',1) = so.so_no
            left join courier c         on c.id                         = ss.courier_id
            where so.so_no = {$so_no}

            group by so.so_no
            #order by so.so_no desc limit 10
SQL;

        $result = $this->db->query($sql);
        if ($result)
            return $result->result_array();
        else
            return FALSE;
    }

    public function cancel_order($age_in_days = 10)
    {
        $age_in_days = intval($age_in_days);
        $sql = <<<SQL
            UPDATE `so` SET
                `status`    = "0",
                `modify_by` = "system"
            WHERE 1
            and biz_type    = "OFFLINE"
            and `status`    = 1
            and hold_status = 0
            and order_create_date < DATE_SUB(CONCAT, " 00:00:00"), INTERVAL {$age_in_days} DAY);
SQL;

        var_dump($sql);

        $result = $this->db->query($sql);
        $result = $this->db->query("commit");

        return true;
    }

    public function get_no_finance_dispatch_order($where, $option)
    {
        $this->db->from("so");
        $this->include_vo();
        return $this->common_get_list($where, $option, "so_vo", "so.so_no, so.platform_id, so.create_on, so.dispatch_date, so.finance_dispatch_date");

    }

    public function get_order_not_in_ria_report($where = array(), $option = array(), $classname = 'Order_not_in_ria_report_dto')
    {
        $this->db->from('so');
        $this->db->join('flex_ria ria', 'so.so_no = ria.so_no', 'LEFT');
        $this->db->join('so_payment_status sps', 'so.so_no = sps.so_no', 'LEFT');
        $this->db->where('ria.so_no IS NULL');
        $this->db->where('so.status >= 2');
        $this->db->order_by('so.currency_id', 'ASC');
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'sps.payment_gateway_id, so.order_create_date, so.currency_id, so.so_no, so.amount');
    }

    public function get_rakuten_shipped_order($where = array(), $option = array(), $classname = 'Rakuten_shipped_order_dto')
    {
        $this->db->from('so');
        $this->db->join('interface_flex_ria ifr', 'so.so_no = ifr.so_no', 'LEFT');
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'so.so_no, so.platform_order_id, so.platform_id, so.txn_id, so.currency_id, so.amount, so.order_create_date, so.dispatch_date, ifr.status');
    }

    public function get_rakuten_shipped_order_from_interface($where, $option, $classname = 'Rakuten_shipped_order_dto')
    {
        $this->db->from('interface_flex_ria ifr');
        $this->db->join('flex_ria fr', 'fr.so_no = ifr.so_no', 'LEFT');
        $this->db->join('so', 'ifr.so_no = so.so_no', 'INNER');
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'so.so_no, so.platform_order_id, so.platform_id, so.txn_id, so.currency_id, so.amount, so.order_create_date, so.dispatch_date, ifr.status');
    }

    public function get_sales_order($where = array(), $option = array(), $classname = 'Order_not_in_ria_report_dto')
    {
        $option['limit'] = -1;

        $this->db->from('so');
        $this->db->join('integrated_order_fulfillment iof', 'iof.so_no = so.so_no', 'INNER');
        $this->db->join('selling_platform sp', 'so.platform_id = sp.id', 'INNER');
        $this->db->join('so_item_detail soid', 'so.so_no = soid.so_no AND soid.item_sku = iof.sku and soid.line_no=iof.line_no', 'INNER');
        $this->db->join('product p', 'p.sku = soid.item_sku', 'INNER');
        $this->db->join('sku_mapping sm', 'sm.sku = p.sku', 'LEFT');
        $this->db->join('so_payment_status sops', 'sops.so_no = so.so_no', 'LEFT');
        $this->db->join('client c', 'so.client_id = c.id', 'INNER');
        $this->db->join('country', 'so.delivery_country_id = country.id', 'LEFT');
//      $this->db->join('country_state cs', 'so.delivery_state = cs.state_id and so.delivery_country_id = cs.country_id', 'LEFT');
        $this->db->join('so_priority_score sps', 'sps.so_no = so.so_no and sps.status=1', 'LEFT');
        $this->db->where($where);
        $select_str = 'so.so_no,
                       so.biz_type,
                       so.platform_id,
                       soid.item_sku as merchant_sku,
                       so.order_create_date,
                       concat_ws(" ", c.title, c.forename, c.surname) as name,
                       so.delivery_address,
                       so.delivery_postcode,
                       so.delivery_city,
                       so.delivery_state,
                       so.delivery_country_id,
                       so.currency_id,
                       so.delivery_charge,
                       so.amount,
                       so.rate,
                       soid.amount as item_sub_amount,
                       soid.amount as price,
                       soid.item_sku as prod_sku,
                       sm.ext_sku,
                       soid.qty,
                       soid.item_unit_cost,
                       p.clearance,
                       p.sub_cat_id,
                       so.hold_status,
                       so.refund_status,
                       so.status,
                       iof.rec_courier,
                       sps.score
                       ';


        $this->db->select($select_str);

        if (isset($option["orderby"])) {
            $this->db->order_by($option["orderby"]);
        }

        if (empty($option["limit"])) {
            $option["limit"] = $this->rows_limit;
        } elseif ($option["limit"] == -1) {
            $option["limit"] = "";
        }

        if (!isset($option["offset"])) {
            $option["offset"] = 0;
        }

        if ($this->rows_limit != "") {
            $this->db->limit($option["limit"], $option["offset"]);
        }

        if ($query = $this->db->get()) {

            $rs = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $rs[] = $row;
                }
            }
            return $rs;
        }

    }

    public function get_order_fulfillment_csv($where)
    {
        $first_line = array(
            'order no.',
            'Platform',
            'Tracking no.',
            'warehouse',
            'Order created date',
            'Dispatched date'
        );
        $first_line = implode(',', $first_line);
        $sql = "select iof.so_no,iof.platform_id,ssh.tracking_no,sa.warehouse_id as warehouse,so.create_on,so.dispatch_date
                from integrated_order_fulfillment as iof
                INNER JOIN so ON iof.so_no = so.so_no
                INNER JOIN so_allocate as sa ON sa.so_no = so.so_no and iof.sku = sa.item_sku
                INNER JOIN so_shipment ssh ON ssh.sh_no = sa.sh_no
                #INNER JOIN courier_info ci ON ci.courier_id = ssh.courier_id
                $where";

        $result = $this->db->query($sql);
        $result = $result->result_array();

        $data = '';
        foreach ($result as $key => $value) {
            $data .= $value['so_no'] . ',' . $value['platform_id'] . ',' . $value['tracking_no'] . ',' . $value['warehouse'] . ',' . $value['create_on'] . ',' . $value['dispatch_date'];
            $data .= "\r\n";
        }
        $data = $first_line . "\r\n" . $data;
        return $data;
    }

    public function get_aps_direct_order_csv($where)
    {
        $first_line = array(
            'order no.',
            'Platform',
            'Tracking no.',
            'Courier ID',
            'Dispatched date'
        );
        $first_line = implode(',', $first_line);
        $sql = "select soe.so_no, so.platform_id, sosh.tracking_no, sosh.courier_id, so.dispatch_date
                from so_shipment sosh
                LEFT JOIN so_extend soe ON SUBSTR(sosh.sh_no FROM -9 FOR 6) = soe.so_no
                LEFT JOIN so ON so.so_no=soe.so_no
                $where";

        $result = $this->db->query($sql);
        $result = $result->result_array();

        $data = '';
        foreach ($result as $key => $value) {
            $data .= $value['so_no'] . ',' . $value['platform_id'] . ',' . $value['tracking_no'] . ',' . $value['courier_id'] . ',' . $value['dispatch_date'];
            $data .= "\r\n";
        }
        $data = $first_line . "\r\n" . $data;
        return $data;
    }

    public function update_empty_so_item_cost()
    {
        $sql = "update so_item_detail soid
                inner join so on soid.so_no=so.so_no
                inner join supplier_prod sp on sp.prod_sku=soid.item_sku and sp.order_default=1
                set soid.item_unit_cost=sp.cost
                where so.status >= 2 and soid.item_unit_cost is null and so.order_create_date >= '2015-03-06 00:00:00'";

        if ($query = $this->db->query($sql)) {
            $this->db->query("commit;");
            return true;
        }
        return false;
    }

    public function get_fulfillment_so($where, $option, $classname = 'fulfillment_report_dto')
    {
        $option['limit'] = -1;
        $this->db->from('integrated_order_fulfillment iof');
        $this->db->join('so so', 'so.so_no = iof.so_no', 'INNER');
        $this->db->join('so_allocate sa', 'sa.so_no = so.so_no and iof.sku = sa.item_sku', 'INNER');
        $this->db->join('so_shipment ssh', 'ssh.sh_no = sa.sh_no', 'INNER');
        $this->db->group_by('so.so_no');
        $this->include_dto($classname);
        $select_str = 'iof.so_no,iof.platform_id,ssh.tracking_no,ssh.courier_id,sa.warehouse_id,sa.create_on,so.dispatch_date';
        return $this->common_get_list($where, $option, $classname, $select_str);
    }

    public function get_sales_volume_so($where, $option, $classname = 'sales_volume_report_dto')
    {
        $option['limit'] = -1;
        $this->db->from('so_item si');
        $this->db->join("so", "si.so_no = so.so_no", "INNER");
        $this->db->join("sku_mapping sm", "si.prod_sku = sm.sku", "LEFT");
        $this->db->join("product pr", "si.prod_sku = pr.sku ", "LEFT");
        $this->db->join("category c", "pr.cat_id = c.id ", "LEFT");
        $this->db->join("category sc", "pr.sub_cat_id = sc.id ", "LEFT");
        $this->db->join("category ssc", "pr.sub_sub_cat_id = ssc.id ", "LEFT");
        $this->include_dto($classname);
        $select_str = 'so.platform_id,so.create_on ,c.name  cat_name,
                sc.name sub_cat_name ,ssc.name sub_sub_cat_name,
                si.prod_sku ,si.qty ,sm.ext_sku, pr.name ,pr.create_on as sku_create_on';
        return $this->common_get_list($where, $option, $classname, $select_str);
    }
}
