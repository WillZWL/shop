<?php
namespace ESG\Panther\Dao;

class InventoryDao extends BaseDao
{
    private $tableName = "inventory";
    private $voClassName = "InventoryVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getBatchInventoryList($where = [], $option = [], $classname = "InventoryVo")
    {

        $this->db->select('inv.*');
        $this->db->from('inventory AS inv');
        $this->db->join('interface_inventory AS iinv', 'inv.log_sku = iinv.log_sku', 'INNER');
        $this->db->where($where);

        $option["limit"] = -1;

        if (empty($option["num_rows"])) {

            $this->include_vo($classname);

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

            $this->db->select('inv.*');

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

    public function getInventoryList($where = [])
    {
        if ($where["sku"] == "") {
            return FALSE;
        }

        $sql = "SELECT warehouse_id, SUM(inventory) as inventory, SUM(git) as git
                FROM inventory
                WHERE prod_sku = ?
                GROUP BY warehouse_id";

        $this->include_vo();

        $rs = [];

        if ($query = $this->db->query($sql, [$where["sku"]])) {
            foreach ($query->result($$this->getVoClassname()) as $obj) {
                $rs[] = $obj;
            }
            return $rs;
        }
        return FALSE;
    }

    public function getListWithProdName($where = [], $option = [], $classname = "Inv_list_w_prod_name_dto")
    {

        $this->db->from('inventory AS i');
        $this->db->join('product AS p', 'p.sku = i.prod_sku', 'LEFT');

        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"])) {
            $this->include_dto($classname);

            $this->db->select('i.*, p.name AS prod_name', FALSE);

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

    public function getStockValuation($where = [], $classname = 'stock_valuation_dto')
    {
        $table_alias = array('inventory' => 'inv', 'product' => 'p', 'category' => 'c',
            'sub_category' => 'sc');

        $replace_arr = array('GBP');

        include_once APPPATH . "helpers/string_helper.php";

        if (is_array($where) && count($where) > 0) {
            $new_where = replace_db_alias($where, $table_alias);
            $where_clause = ' WHERE';
            $count = 0;

            foreach ($new_where as $key => $value) {
                if ($count++ > 0) {
                    $where_clause = ' AND';
                }

                if ($this->db->_has_operator($key)) {
                    $where_clause .= " $key ?";
                } else {
                    $where_clause .= " $key = ?";
                }

                $replace_arr[] = $value;
            }
        }

        $sql = "SELECT
                    c.name cat_name, sc.name sub_cat_name, ssc.name sub_sub_cat_name,
                    p.name prod_name,
                    inv.prod_sku, inv.warehouse_id, SUM(inv.inventory) inventory,
                    ROUND(MIN(sp.cost) * ex.rate, 2) value_per_piece,
                    SUM(inv.inventory) * ROUND(MIN(sp.cost) * ex.rate, 2) total_value
                FROM inventory inv
                JOIN product p
                    ON (p.sku = inv.prod_sku)
                JOIN category c
                    ON (p.cat_id = c.id)
                JOIN category sc
                    ON (p.sub_cat_id = sc.id)
                JOIN category ssc
                    ON (p.sub_sub_cat_id = ssc.id)
                JOIN supplier_prod sp
                    ON (inv.prod_sku = sp.prod_sku AND sp.order_default = 1)
                JOIN exchange_rate ex
                    ON (sp.currency_id = ex.from_currency_id AND ex.to_currency_id = ?)
                $where_clause
                GROUP BY c.name, sc.name, ssc.name,
                    p.name,
                    inv.prod_sku, inv.warehouse_id
                ORDER BY c.name, sc.name, ssc.name,
                    p.name, inv.prod_sku, inv.warehouse_id";

        $result = $this->db->query($sql, $replace_arr);

        $this->include_dto($classname);
        $result_arr = [];

        foreach ($result->result($classname) as $obj) {
            array_push($result_arr, $obj);
        }

        if ($result_arr) {
            return $result_arr;
        } else {
            return [];
        }
    }

    public function getProdSumInvGitByCountry($sku, $country_id)
    {
        $sql = "
                SELECT SUM(inv.inventory + inv.git) AS total
                FROM inventory AS inv
                INNER JOIN warehouse AS wh
                    ON inv.warehouse_id = wh.id
                INNER JOIN country AS c
                    ON c.fc_id = wh.fc_id
                WHERE inv.prod_sku = ?
                AND c.id = ?
                ";
        if ($query = $this->db->query($sql, [$sku, $country_id])) {
            return $query->row()->total;
        } else {
            return FALSE;
        }
    }

    public function getFcPendingByCountry($sku, $country_id)
    {
        $sql = "
                SELECT SUM(soid.outstanding_qty) AS outstanding_qty
                FROM so_item_detail AS soid
                INNER JOIN so
                    ON so.so_no = soid.so_no
                INNER JOIN country AS c
                    ON so.delivery_country_id = c.id
                                AND c.fc_id = (
                                                SELECT fc_id
                                                FROM country
                                                WHERE id = ?
                                                )
                WHERE soid.item_sku = ?
                    AND so.status > 2
                    AND so.hold_status = 0
                    AND so.refund_status = 0
                ";

        if ($query = $this->db->query($sql, [$country_id, $sku])) {
            return $query->row()->outstanding_qty;
        } else {
            return FALSE;
        }
    }

    public function setSurplusQuantity($sku, $qty)
    {
        $sql =
            "
            update `inventory` i
            inner join `sku_mapping` m on m.sku = i.prod_sku and m.status = 1 and ext_sys = 'wms'
                set surplus_qty = ?
            where 1
            and m.ext_sku = ?;
        ";
        $query = $this->db->query($sql, [$qty, (string)$sku]);

        // var_dump($this->db->last_query());

        $sql = "commit";
        $query = $this->db->query($sql);
    }
}


