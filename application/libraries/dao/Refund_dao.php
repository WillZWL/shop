<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Refund_dao extends Base_dao
{
    private $table_name = "refund";
    private $vo_class_name = "Refund_vo";
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

    public function get_refund_list($where = array(), $option = array(), $classname = "Refund_so_dto")
    {
        if (!isset($where["rstatus"]) || $where["rstatus"] == "") {
            return false;
        }

        $this->db->from("refund r");

        $this->db->join("so s", "r.so_no = s.so_no", "INNER");

        $this->db->join("refund_reason rr", "rr.id = r.reason", "LEFT");
        $this->db->join("so_payment_status sops", "s.so_no = sops.so_no", "LEFT");
        $this->db->join("payment_gateway pg", "sops.payment_gateway_id = pg.id", "LEFT");
        $this->db->join("so_refund_score sors", "sors.so_no = r.so_no", "LEFT");


        $ri_join = "ri.refund_id = r.id AND ";

        if ($where["check_cb"] != "") {
            if (is_array($where["rstatus"])) {
                $ri_join .= "( ri.status in ('" . implode("','", $where["rstatus"]) . "') OR (ri.status = 'N' AND ri.refund_type='C')) ";
            } else {
                $ri_join .= "( ri.status = '" . $where["rstatus"] . "' OR (ri.status = 'N' AND ri.refund_type='C')) ";
            }
        } else {
            if (is_array($where["rstatus"])) {
                $ri_join .= "ri.status in ('" . implode("','", $where["rstatus"]) . "') ";
            } else {
                $ri_join .= "ri.status = '" . $where["rstatus"] . "' ";
            }
        }

        $this->db->join("refund_item ri", $ri_join, 'INNER');

        $this->db->where("r.status", "I");

        if ($where["refund_type"] != "") {
            $this->db->where("ri.refund_type", $where["refund_type"]);
        }

        if ($where["rid"] != "") {
            $this->db->where("r.id", $where["rid"]);
        }

        if ($where["so"] != "") {
            $this->db->where("r.so_no LIKE", "%" . $where["so"] . "%");
        }

        if ($where["platform_order_id"] != "") {
            $this->db->where("s.platform_order_id", $where["platform_order_id"]);
        }

        if ($where["platform_id"] != "") {
            $this->db->where("s.platform_id", $where["platform_id"]);
        }

        if ($where["payment_gateway"] != "") {
            $this->db->where("pg.name", $where["payment_gateway"]);
        }

        if ($where["txn_id"] != "") {
            $this->db->where("s.txn_id", $where["txn_id"]);
        }

        if ($option["create"] == 1) {
            $this->db->where("(s.refund_status = 0 OR s.refund_status = 4)");
        }

        $this->db->where("(s.hold_status != 15)");

        $pack_date_select_str = '';
        if (isset($option["need_pack_date"]) && $option["need_pack_date"]) {
            $join_sql = "(SELECT a.so_no, b.create_on as pack_date
                          FROM so_allocate a
                          JOIN so_shipment b
                          ON b.sh_no = a.sh_no
                          GROUP BY a.so_no) AS soa";
            $this->db->join($join_sql, 'soa.so_no = s.so_no', 'LEFT');
            $pack_date_select_str = ', soa.pack_date';
        }

        if ($option["num_row"] == "") {
            $this->db->groupby("r.id");

            $this->db->select
            ("
                rr.description as refund_reason,
                (select count(*) from so ss inner join client c on c.id = ss.client_id where ss.biz_type = 'SPECIAL' and ss.client_id = s.client_id) as special_order,
                r.create_by,
                r.id,
                r.so_no,
                s.platform_order_id,
                s.platform_id,
                s.txn_id,
                r.total_refund_amount,
                s.currency_id,
                s.dispatch_date,
                r.create_on,
                pg.name payment_gateway,
                s.create_on as order_date,
                sors.score as refund_score,
                sors.modify_on as refund_score_date
            " . $pack_date_select_str);

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

                return $rs;
            }
        } else {
            $this->db->select("COUNT(DISTINCT r.id) as total");

            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function check_complete($refundid = "")
    {
        if ($refundid == "") {
            return FALSE;
        }

        $sql = "SELECT count(ri.line_no) as total, IFNULL(rip.completed ,0) as complete
                 FROM refund_item ri
                 LEFT JOIN (SELECT refund_id, count(line_no) as completed
                          FROM refund_item
                      WHERE status IN ('C','D')
                      GROUP BY refund_id) as rip
                    ON ri.refund_id = rip.refund_id
                 WHERE ri.refund_id = ?
                 GROUP BY ri.refund_id";

        if ($query = $this->db->query($sql, $refundid)) {
            return array("total" => $query->row()->total, "completed" => $query->row()->complete);
        }
        return FALSE;
    }

    public function get_refund_info_by_period($where = array(), $classname = '')
    {
        if (empty($where['period_start'])
            || empty($where['period_end'])
        ) {
            return FALSE; // period is compulsory
        }

        $data = array($where['period_start'], $where['period_end']);

        $sql = "SELECT rr.item_sku, rr.cnty_id, SUM(rr.refund_qty) refund_qty,
                    SUM(rr.refund_amount) refund_amount
                FROM
                (
                    SELECT DISTINCT soid.item_sku, so.delivery_country_id cnty_id, r.id,
                        ri.qty refund_qty, ri.refund_amount, so.so_no
                    FROM refund r
                    JOIN so
                        ON (so.so_no = r.so_no AND r.status = 'C')
                    JOIN so_item_detail soid
                        ON (soid.so_no = so.so_no)
                    JOIN refund_item ri
                        ON (r.id = ri.refund_id AND soid.item_sku = ri.item_sku
                            AND ri.refund_type = 'R' $additional_clause)
                    WHERE so.status >= 3 AND so.order_create_date > ? AND so.order_create_date <= ?
                ) rr
                GROUP BY rr.item_sku, rr.cnty_id";

        $resultset = $this->db->query($sql, $data);

        foreach ($resultset->result() as $row) {
            $rs[] = $row;
        }

        return $rs;
    }

    public function get_refund_report_content($where = array(), $option = array(), $classname = "refund_report_dto")
    {
        $this->db->from("refund AS r");
        $this->db->join("so", "so.so_no = r.so_no", "INNER");
        $this->db->join("so_item_detail AS soid", "soid.so_no = so.so_no", "INNER");
        $this->db->join("product AS p", "p.sku = soid.item_sku", "INNER");
        $this->db->join("category AS cat", "p.cat_id = cat.id", "INNER");
        //$this->db->join("refund_item AS ri", "ri.refund_id = r.id AND ri.item_sku = soid.item_sku", "INNER");
        $this->db->join("refund_item AS ri", "ri.refund_id = r.id AND (ri.item_sku = soid.item_sku OR ri.item_sku IS NULL)", "INNER");
        $this->db->join("refund_history AS rh", "r.id = rh.refund_id", "INNER");
        $this->db->join("(
                            SELECT refund_id, max(id) max_id
                            FROM refund_history
                            GROUP BY refund_id
                        ) AS a", "a.refund_id = rh.refund_id AND a.max_id = rh.id", "INNER");
        $this->db->join("refund_reason AS rr", "rr.id = r.reason", "INNER");
        $this->db->join("(
                             SELECT refund_id, modify_on as cs_approval_date, create_by as cs_approved_by
                             FROM refund_history
                             WHERE status='CS' and app_status='A'
                        ) AS approve_data", "approve_data.refund_id = rh.refund_id", "LEFT");
        $this->db->join("(
                            SELECT sops.so_no, pm.name
                            FROM so_payment_status sops
                            JOIN payment_gateway pm
                                ON sops.payment_gateway_id = pm.id
                        ) AS pmgw", "pmgw.so_no = so.so_no", "LEFT");
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "r.id refund_id, so.biz_type, so.platform_id, pmgw.name pmgw_name, so.bill_country_id, so.txn_id, so.client_id, so.so_no, p.name prod_name, cat.name cat_name, soid.item_sku, so.dispatch_date, so.order_create_date, so.amount, so.delivery_type_id, r.create_on request_date, if(rh.app_status = 'A', rh.modify_on, null)approve_date, if(rh.app_status = 'A' AND rh.status = 'C', rh.modify_on, null) refund_date, ri.refund_type, so.currency_id, ri.refund_amount, r.create_by request_by, rr.reason_cat, rr.description, rh.notes, rh.status refund_status, cs_approval_date, cs_approved_by");
    }

    public function get_refund_amount_by_pmgw_currency($where = array(), $option = array(), $classname = "refund_amount_by_pmgw_currency_dto")
    {
        $this->db->from('refund r');
        $this->db->join('so', 'so.so_no = r.so_no', 'inner');
        $this->db->join('so_item_detail soid', 'soid.so_no = so.so_no', 'inner');
        $this->db->join('refund_item ri', 'ri.refund_id = r.id and ri.item_sku = soid.item_sku', 'inner');
        $this->db->join('refund_history rh', 'r.id = rh.refund_id', 'inner');
        $this->db->join('(
                          SELECT refund_id, max(id) max_id
                          FROM refund_history
                          GROUP BY refund_id
                         ) AS a', 'a.refund_id = rh.refund_id AND a.max_id = rh.id', 'inner');
        $this->db->join('refund_reason rr', 'rr.id = r.reason', 'inner');
        $this->db->join('(
                          SELECT sops.so_no, pm.name, pm.id
                          FROM so_payment_status sops
                          JOIN payment_gateway pm
                          ON sops.payment_gateway_id = pm.id
                         ) AS pmgw', 'pmgw.so_no = so.so_no', 'left');
        $this->db->where($where);
        $this->db->group_by(array('so.currency_id', 'pmgw.id', 'rr.description'));

        if (isset($option['orderby'])) {
            $this->db->order_by($option['orderby']);
        }

        $this->db->select('count(r.id) as refund_count, sum(ri.refund_amount) as refund_amount, so.currency_id, pmgw.id as payment_gateway_id, pmgw.name pmgw_name, rr.description as refund_reason');

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

    public function get_refund_amount_by_pmgw_currency_with_eur_country($where = array(), $option = array(), $classname = "refund_amount_by_pmgw_currency_dto")
    {
        $this->db->from('refund r');
        $this->db->join('so', 'so.so_no = r.so_no', 'inner');
        $this->db->join('so_item_detail soid', 'soid.so_no = so.so_no', 'inner');
        $this->db->join('refund_item ri', 'ri.refund_id = r.id and ri.item_sku = soid.item_sku', 'inner');
        $this->db->join('refund_history rh', 'r.id = rh.refund_id', 'inner');
        $this->db->join('platform_biz_var pbv', 'so.platform_id = pbv.selling_platform_id', 'inner');
        $this->db->join('(
                          SELECT refund_id, max(id) max_id
                          FROM refund_history
                          GROUP BY refund_id
                         ) AS a', 'a.refund_id = rh.refund_id AND a.max_id = rh.id', 'inner');
        $this->db->join('refund_reason rr', 'rr.id = r.reason', 'inner');
        $this->db->join('(
                          SELECT sops.so_no, pm.name, pm.id
                          FROM so_payment_status sops
                          JOIN payment_gateway pm
                          ON sops.payment_gateway_id = pm.id
                         ) AS pmgw', 'pmgw.so_no = so.so_no', 'left');
        $this->db->where($where);
        $this->db->group_by(array('so.currency_id', 'pmgw.id', 'pbv.platform_country_id', 'rr.description'));

        if (isset($option['orderby'])) {
            $this->db->order_by($option['orderby']);
        }

        $this->db->select('count(r.id) as refund_count, sum(ri.refund_amount) as refund_amount, so.currency_id, pbv.platform_country_id as platform_country_id, pmgw.id as payment_gateway_id, pmgw.name pmgw_name, rr.description as refund_reason');

        $rs = array();
        $this->include_dto($classname);

        //To debug
        /*$this->db->save_queries = true;
        $this->db->get();
        echo "<pre>"; var_dump($this->db->last_query()); var_dump($this->db->_error_message());die();*/

        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }

        return FALSE;
    }

    public function get_refund_reason_top5($where = array(), $option = array(), $classname = "Refund_reason_report_top5_reasons_dto")
    {
        /*
        select R2.id, R2.description as reason, COUNT(*) as frequency
        from refund R1
        inner join refund_reason R2 on R1.reason = R2.id
        inner join so S on S.so_no = R1.so_no
        where S.refund_status >= 3 and R2.id <> 30 and R2.id <> 32 and R1.create_on >= [yyyy-mm-dd hh:mm:ss] and R1.create_on <= [yyyy-mm-dd hh:mm:ss]
        group by R2.id
        order by frequency desc
        limit 0,5
        */
        $this->db->from('refund R1');
        $this->db->join('refund_reason R2', 'R1.reason = R2.id', 'inner');
        $this->db->join('so S', 'S.so_no = R1.so_no', 'inner');
        $this->db->where($where);
        $this->db->group_by(array('R2.id'));
        $this->db->order_by("frequency", "desc");
        $this->db->limit(5, 0);

        $this->db->select('R2.id, R2.description as reason, COUNT(*) as frequency');

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

    public function get_refund_reason_num_rows($where = array(), $option = array(), $classname = "Refund_reason_report_num_rows_dto")
    {
        /*
        select COUNT(*) as num_rows
        from refund R1
        inner join refund_reason R2 on R1.reason = R2.id
        inner join so S on S.so_no = R1.so_no
        where S.refund_status >= 3 and R2.id <> 30 and R2.id <> 32 and R1.create_on >= [yyyy-mm-dd hh:mm:ss] and R1.create_on <= [yyyy-mm-dd hh:mm:ss]
        */
        $this->db->from('refund R1');
        $this->db->join('refund_reason R2', 'R1.reason = R2.id', 'inner');
        $this->db->join('so S', 'S.so_no = R1.so_no', 'inner');
        $this->db->where($where);

        $this->db->select('COUNT(*) as num_rows');

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

    public function get_refund_reason_top5_products($where = array(), $option = array(), $classname = "Refund_reason_report_top5_products_dto")
    {
        /*
        select D.prod_sku, P.name as item_name, count(*) as frequency
        from refund R1
        inner join refund_reason R2 on R1.reason = R2.id
        inner join so S on S.so_no = R1.so_no
        inner join so_item D on S.so_no = D.so_no
        inner join refund_item R3 on R3.refund_id = R1.id
        inner join product P on D.prod_sku = P.sku
        where S.refund_status >= 3 and R2.id = 11 and R1.create_on >= '2013-11-03 00:00:00' and R1.create_on <= '2013-11-09 23:59:59'
        group by D.prod_sku
        order by frequency desc
        limit 0,5;
        */

        $this->db->from('refund R1');
        $this->db->join('refund_reason R2', 'R1.reason = R2.id', 'inner');
        $this->db->join('so S', 'S.so_no = R1.so_no', 'inner');
        $this->db->join('so_item D', 'S.so_no = D.so_no', 'inner');
        $this->db->join('refund_item R3', 'R3.refund_id = R1.id', 'inner');
        $this->db->join('product P', 'D.prod_sku = P.sku', 'inner');
        $this->db->where($where);
        $this->db->group_by(array('D.prod_sku'));
        $this->db->order_by("frequency", "desc");
        $this->db->limit(5, 0);

        $this->db->select('D.prod_sku, P.name as item_name, count(*) as frequency');

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
}