<?php
namespace ESG\Panther\Dao;

class SoItemDetailDao extends BaseDao
{
    private $tableName = "so_item_detail";
    private $voClassName = "SoItemDetailVo";

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

    public function getFulfil($where = [], $option = [], $classname = "FulfilListDto")
    {
        $this->db->from('so_item_detail AS soid');

        $this->db->join('so', 'so.so_no = soid.so_no', 'INNER');

        $this->db->where(["so.status >" => "2"]);
        $this->db->where(["so.status <" => "5"]);
        $this->db->where(["so.hold_status" => "0"]);
        $this->db->where(["so.refund_status" => "0"]);
        $this->db->where(["soid.outstanding_qty >"=>"0"]);
        $this->db->order_by("expect_delivery_date asc, so_no asc");

        if ($option["solist"] != "") {
            $this->db->where_in("so.so_no", $option["solist"]);
        }

        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"])) {

            $this->db->select('soid.*');

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

    public function getListWithProdname($where = [], $option = [], $classname = "SoidProdnameDto")
    {
        $this->db->from('so_item_detail soid');
        $this->db->join('product p', 'soid.item_sku = p.sku', 'INNER');
        $this->db->order_by("line_no asc");
        $this->db->select('soid.gst_total ,soid.profit, soid.margin, soid.so_no, soid.line_no, soid.item_sku, p.name, p.image, soid.qty, soid.discount, soid.unit_price, soid.amount, soid.status, soid.profit_raw, soid.margin_raw');

        if ($where["so_no"] != "") {
            $this->db->where("soid.so_no", $where["so_no"]);
            unset($where["so_no"]);
        }

        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"])) {

            $rs = [];
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

    public function checkOutstanding($so_no)
    {
        $sql = "
                    SELECT SUM(outstanding_qty) AS o_qty
                    FROM so_item_detail
                    WHERE so_no = ?
                    GROUP BY so_no
                ";

        if ($query = $this->db->query($sql, $so_no)) {
            return $query->row()->o_qty;
        } else {
            return FALSE;
        }
    }

    public function getGenSourcingListWithPriority($where = [], $option = [], $classname = "SourcingListDto")
    {
        if (empty($option["num_rows"]))
            $sql = "select normal.*, priority_list.o_qty as prioritized_qty from ";
        else
            $sql = "select count(*) from ";
        $sql .= "
                (
                        select vgsl.*, p.name AS prod_name, p.sourcing_status, sp.supplier_id, i.inventory
                        from v_gen_sourcing_list vgsl
                        inner join (SELECT *
                                                    FROM
                                                    supplier_prod AS s
                                                    WHERE order_default = 1
                                                    GROUP BY supplier_id, prod_sku) sp on vgsl.item_sku = sp.prod_sku
                        inner join product p on vgsl.item_sku = p.sku
                        left join (SELECT prod_sku, SUM(inventory+git) AS inventory
                                                FROM inventory
                                                GROUP BY prod_sku
                                                ) AS i on vgsl.item_sku = i.prod_sku
                        where (i.inventory < vgsl.required_qty OR ISNULL(i.inventory))
                ) as normal
                left join
                (
                        select * from
                        (
                            select soid.item_sku AS item_sku, sum(soid.outstanding_qty) AS o_qty, i.inventory
                            from so_item_detail soid
                            left join so on(soid.so_no = so.so_no)
                            left join (SELECT prod_sku, SUM(inventory+git) AS inventory
                                                    FROM inventory
                                                    GROUP BY prod_sku
                                                    ) AS i on soid.item_sku = i.prod_sku
                            where ((so.status > 2) and (so.status < 5)
                                         and (so.refund_status = 0) and (so.hold_status = 0))
                                        and so.biz_type not in ('ONLINE', 'MOBILE', 'SPECIAL', 'OFFLINE')
                            group by soid.item_sku
                        ) outstanding_qty
                        where ((outstanding_qty.o_qty > outstanding_qty.inventory) OR (ISNULL(outstanding_qty.inventory) AND outstanding_qty.o_qty > 0))
                ) priority_list on priority_list.item_sku=normal.item_sku";

        if (empty($option["num_rows"])) {
            $option_string = "";
            if (empty($option["orderby"])) {
                $option_string .= " order by prod_name ASC ";
            } else
                $option_string .= " order by " . $option["orderby"];

            if (!empty($option["limit"])) {
                $option_string .= " limit " . $option["limit"];
            }

            if (!empty($option["offset"])) {
                $option_string .= " offset " . $option["limit"];
            }

            $rs = [];

            $sql = $sql . $option_string;

            if ($query = $this->db->query($sql)) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            }
        } else {
            if ($query = $this->db->query($sql)) {
                return $query->row()->total;
            }
        }
    }

    public function getGenSourcingList($where = [], $option = [], $classname = "SourcingListDto")
    {
        $this->db->from('v_gen_sourcing_list AS vgsl');
        $this->db->join('(
                            SELECT *
                            FROM
                            supplier_prod AS s
                            WHERE order_default = 1
                            GROUP BY supplier_id, prod_sku
                         ) AS sp
                        ', 'vgsl.item_sku = sp.prod_sku', 'INNER');
        $this->db->join('supplier AS s', 's.id = sp.supplier_id', 'LEFT');
        $this->db->join('product AS p', 'vgsl.item_sku = p.sku', 'INNER');
        $this->db->join('(SELECT prod_sku, SUM(inventory+git) AS inventory
                        FROM inventory
                        GROUP BY prod_sku
                        ) AS i', 'vgsl.item_sku = i.prod_sku', 'LEFT');

        $this->db->where("(i.inventory < vgsl.required_qty OR ISNULL(i.inventory))");

        if (empty($option["num_rows"])) {

            $this->db->select('vgsl.*, p.name AS prod_name, p.sourcing_status, sp.supplier_id, i.inventory');

            if (empty($option["orderby"])) {
                $option["orderby"] = "prod_name ASC";
            }

            if (empty($option["limit"])) {
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

    public function getCpsSourcingList($where = [], $option = [], $classname = "CpsSourcingListDto")
    {
        $this->db->from('v_gen_sourcing_list_by_order AS vgsl');
        $this->db->join('(SELECT prod_sku, SUM(inventory+git) AS inventory
                        FROM inventory
                        GROUP BY prod_sku
                        ) AS i', 'vgsl.item_sku = i.prod_sku', 'LEFT');
        $this->db->where($where);
        if (empty($option["num_rows"])) {

            $this->db->select('vgsl.*, IF(i.inventory > 0, i.inventory, 0) AS inventory');

            if (empty($option["orderby"])) {
                $option["orderby"] = "prod_name ASC";
            }

            if (empty($option["limit"])) {
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
}


