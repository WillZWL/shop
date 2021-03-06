<?php
namespace ESG\Panther\Dao;

class RefundDao extends BaseDao implements HooksInsert
{
    private $tableName = "refund";
    private $voClassName = "RefundVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function triggerAfterInsert($obj)
    {
        $this->tableFieldsHooksInsert($obj);
    }

    public function tableFieldsHooksInsert($obj)
    {
        $table1 = [
                    'table' => 'so',
                    'where' => ['so_no'=>$obj->getSoNo(),],
                    'keyValue'=>['refund_reason' => $this->getRefundReasonById($obj->getReason()),]
                  ];

        $this->updateTables([$table1,]);
    }

    public function getRefundReasonById($id = "")
    {
        if ($id == "") {
            return FALSE;
        }

        $sql = "SELECT reason_cat cat, description reason from refund_reason WHERE id = ?";

        if ($query = $this->db->query($sql, $id)) {
            $cat = $query->row()->cat;
            $reason = $query->row()->reason;
            $rcategory = ["C"=>"Customer","I"=>"Internal","3"=>"3rd Party","M"=>"Miscellaneous","R"=>"RMA","O"=>"Others"];
            $refund_reason = $rcategory[$cat] ." - ". $reason;

            return $refund_reason;
        }

        return FALSE;
    }

    public function getRefundList($where = [], $option = [], $classname = "RefundSoDto")
    {
        if (!isset($where["rstatus"]) || $where["rstatus"] == "") {
            return false;
        }

        $this->db->from("refund r");

        $this->db->join("so s", "r.so_no = s.so_no", "INNER");

        $this->db->join("refund_reason rr", "rr.id = r.reason", "LEFT");
        $this->db->join("so_payment_status sops", "s.so_no = sops.so_no", "LEFT");
        $this->db->join("payment_gateway pg", "sops.payment_gateway_id = pg.payment_gateway_id", "LEFT");
        $this->db->join("so_refund_score sors", "sors.so_no = r.so_no", "LEFT");


        $ri_join = "ri.refund_id = r.id";

        if ($where["check_cb"] != "") {
            if (is_array($where["rstatus"])) {
                $this->db->where("( ri.status in ('" . implode("','", $where["rstatus"]) . "') OR (ri.status = 'N' AND ri.refund_type='C')) ");
            } else {
                $this->db->where("( ri.status = '" . $where["rstatus"] . "' OR (ri.status = 'N' AND ri.refund_type='C')) ");
            }
        } else {
            if (is_array($where["rstatus"])) {
                $this->db->where("ri.status in ('" . implode("','", $where["rstatus"]) . "') ");
            } else {
                $this->db->where("ri.status = '" . $where["rstatus"] . "' ");
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
            $this->db->group_by("r.id");

            // $this->db->select
            // ("
            //     rr.description as refund_reason,
            //     (select count(*) from so ss inner join client c on c.id = ss.client_id where ss.biz_type = 'SPECIAL' and ss.client_id = s.client_id) as special_order,
            //     r.create_by,
            //     r.id,
            //     r.so_no,
            //     s.platform_order_id,
            //     s.platform_id,
            //     s.txn_id,
            //     r.total_refund_amount,
            //     s.currency_id,
            //     s.dispatch_date,
            //     r.create_on,
            //     pg.name payment_gateway,
            //     s.create_on as order_date,
            //     sors.score as refund_score,
            //     sors.modify_on as refund_score_date
            // " . $pack_date_select_str);
             $this->db->select
            ("
                rr.description as refund_reason,
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

            $rs = [];

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

    public function checkComplete($refundid = "")
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
            return ["total" => $query->row()->total, "completed" => $query->row()->complete];
        }
        return FALSE;
    }

    public function getRefundInfoByPeriod($where = [], $classname = '')
    {
        if (empty($where['period_start'])
            || empty($where['period_end'])
        ) {
            return FALSE; // period is compulsory
        }

        $data = [$where['period_start'], $where['period_end']];

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

    public function getRefundReportContent($where = [], $option = [], $classname = "RefundReportDto")
    {
        $this->db->from("refund AS r");
        $this->db->join("so", "so.so_no = r.so_no", "INNER");
        $this->db->join("so_item_detail AS soid", "soid.so_no = so.so_no", "INNER");
        $this->db->join("product AS p", "p.sku = soid.item_sku", "INNER");
        $this->db->join("category AS cat", "p.cat_id = cat.id", "INNER");
        $this->db->join("refund_item AS ri", "ri.refund_id = r.id AND (ri.item_sku = soid.item_sku or ri.item_sku = '')", "INNER", false);
        $this->db->join("refund_history AS rh", "r.id = rh.refund_id", "INNER");
        $this->db->join("(
                            SELECT refund_id, max(id) max_id
                            FROM refund_history
                            GROUP BY refund_id
                        ) AS a", "a.refund_id = rh.refund_id AND a.max_id = rh.id", "INNER");
        $this->db->join("refund_reason AS rr", "rr.id = r.reason", "INNER");
        $this->db->join("payment_gateway AS pmgw", "so.payment_gateway_id = pmgw.payment_gateway_id", "LEFT");
        $this->db->join("refund_history AS rh2", "r.id = rh2.refund_id and rh2.app_status='A' and rh2.status = 'CS'", "LEFT");
        return $this->commonGetList($classname, $where, $option, "r.id refund_id, so.biz_type, so.platform_id, pmgw.name pmgw_name, so.bill_country_id, so.txn_id, so.client_id, so.so_no, p.name prod_name, cat.name cat_name, soid.item_sku, so.dispatch_date, so.order_create_date, so.amount, so.delivery_type_id, r.create_on request_date, if(rh.app_status = 'A', rh.modify_on, null)approve_date, if(rh.app_status = 'A' AND rh.status = 'C', rh.modify_on, null) refund_date, ri.refund_type, so.currency_id, ri.refund_amount, r.create_by request_by, rr.reason_cat, rr.description, rh.notes, rh.status refund_status, rh2.modify_on cs_approval_date, rh2.create_by cs_approved_by");
    }

    public function getRefundAmountByPmgwCurrency($where = [], $option = [], $classname = "RefundAmountByPmgwCurrencyDto")
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
                          SELECT sops.so_no, pm.name, pm.payment_gateway_id
                          FROM so_payment_status sops
                          JOIN payment_gateway pm
                          ON sops.payment_gateway_id = pm.payment_gateway_id
                         ) AS pmgw', 'pmgw.so_no = so.so_no', 'left');
        $this->db->where($where);
        $this->db->group_by(['so.currency_id', 'pmgw.id', 'rr.description']);

        if (isset($option['orderby'])) {
            $this->db->order_by($option['orderby']);
        }

        $this->db->select('count(r.id) as refund_count, sum(ri.refund_amount) as refund_amount, so.currency_id, pmgw.id as payment_gateway_id, pmgw.name pmgw_name, rr.description as refund_reason');

        $rs = [];
        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }

        return FALSE;
    }

    public function getRefundAmountByPmgwCurrencyWithEurCountry($where = [], $option = [], $classname = "RefundAmountByPmgwCurrencyDto")
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
                          SELECT sops.so_no, pm.name, pm.payment_gateway_id
                          FROM so_payment_status sops
                          JOIN payment_gateway pm
                          ON sops.payment_gateway_id = pm.payment_gateway_id
                         ) AS pmgw', 'pmgw.so_no = so.so_no', 'left');
        $this->db->where($where);
        $this->db->group_by(['so.currency_id', 'pmgw.id', 'pbv.platform_country_id', 'rr.description']);

        if (isset($option['orderby'])) {
            $this->db->order_by($option['orderby']);
        }

        $this->db->select('count(r.id) as refund_count, sum(ri.refund_amount) as refund_amount, so.currency_id, pbv.platform_country_id as platform_country_id, pmgw.id as payment_gateway_id, pmgw.name pmgw_name, rr.description as refund_reason');

        $rs = [];

        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }

        return FALSE;
    }

    public function getRefundReasonTop5($where = [], $option = [], $classname = "RefundReasonReportTop5ReasonsDto")
    {
        $this->db->from('refund R1');
        $this->db->join('refund_reason R2', 'R1.reason = R2.id', 'inner');
        $this->db->join('so S', 'S.so_no = R1.so_no', 'inner');
        $this->db->where($where);
        $this->db->group_by(['R2.id']);
        $this->db->order_by("frequency", "desc");
        $this->db->limit(5, 0);

        $this->db->select('R2.id, R2.description as reason, COUNT(*) as frequency');

        $rs = [];
        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }

        return FALSE;
    }

    public function getRefundReasonNumRows($where = [], $option = [], $classname = "RefundReasonReportNumRowsDto")
    {
        $this->db->from('refund R1');
        $this->db->join('refund_reason R2', 'R1.reason = R2.id', 'inner');
        $this->db->join('so S', 'S.so_no = R1.so_no', 'inner');
        $this->db->where($where);

        $this->db->select('COUNT(*) as num_rows');

        $rs = [];
        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }

        return FALSE;
    }

    public function getRefundReasonTop5Products($where = [], $option = [], $classname = "RefundReasonReportTop5ProductsDto")
    {
        $this->db->from('refund R1');
        $this->db->join('refund_reason R2', 'R1.reason = R2.id', 'inner');
        $this->db->join('so S', 'S.so_no = R1.so_no', 'inner');
        $this->db->join('so_item D', 'S.so_no = D.so_no', 'inner');
        $this->db->join('refund_item R3', 'R3.refund_id = R1.id', 'inner');
        $this->db->join('product P', 'D.prod_sku = P.sku', 'inner');
        $this->db->where($where);
        $this->db->group_by(['D.prod_sku']);
        $this->db->order_by("frequency", "desc");
        $this->db->limit(5, 0);

        $this->db->select('D.prod_sku, P.name as item_name, count(*) as frequency');

        $rs = [];
        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }

        return FALSE;
    }
}