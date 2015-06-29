<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Inventory_dao extends Base_dao
{
    private $table_name="inventory";
    private $vo_class_name="Inventory_vo";
    private $seq_name="";
    private $seq_mapping_field="";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_batch_inventory_list($where=array(), $option=array(), $classname="Inventory_vo")
    {

        $this->db->select('inv.*');
        $this->db->from('inventory AS inv');
        $this->db->join('interface_inventory AS iinv', 'inv.log_sku = iinv.log_sku', 'INNER');
        $this->db->where($where);

        $option["limit"] = -1;

        if (empty($option["num_rows"]))
        {

            $this->include_vo($classname);

            if (isset($option["orderby"]))
            {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"]))
            {
                $option["limit"] = $this->rows_limit;
            }

            elseif ($option["limit"] == -1)
            {
                $option["limit"] = "";
            }

            if (!isset($option["offset"]))
            {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "")
            {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $rs = array();

            $this->db->select('inv.*');

            if ($query = $this->db->get())
            {
                foreach ($query->result($classname) as $obj)
                {
                    $rs[] = $obj;
                }
                return (object) $rs;
            }

        }
        else
        {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get())
            {
                return $query->row()->total;
            }
        }
        return FALSE;
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

    public function get_inventory_list($where=array())
    {
        if($where["sku"] == "")
        {
            return FALSE;
        }

        $sql = "SELECT warehouse_id, SUM(inventory) as inventory, SUM(git) as git
                FROM inventory
                WHERE prod_sku = ?
                GROUP BY warehouse_id";

        $this->include_vo();

        $rs =array();

        if($query = $this->db->query($sql, array($where["sku"])))
        {
            foreach($query->result($$this->get_vo_classname()) as $obj)
            {
                $rs[] = $obj;
            }
            return $rs;
        }
        return FALSE;
    }

    public function get_list_w_prod_name($where=array(), $option=array(), $classname="Inv_list_w_prod_name_dto")
    {

        $this->db->from('inventory AS i');
        $this->db->join('product AS p', 'p.sku = i.prod_sku', 'LEFT');

        if ($where)
        {
            $this->db->where($where);
        }

        if (empty($option["num_rows"]))
        {
            $this->include_dto($classname);

            $this->db->select('i.*, p.name AS prod_name', FALSE);

            if (isset($option["orderby"]))
            {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"]))
            {
                $option["limit"] = $this->rows_limit;
            }

            elseif ($option["limit"] == -1)
            {
                $option["limit"] = "";
            }

            if (!isset($option["offset"]))
            {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "")
            {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $rs = array();

            if ($query = $this->db->get())
            {
                foreach ($query->result($classname) as $obj)
                {
                    $rs[] = $obj;
                }
                return (object) $rs;
            }

        }
        else
        {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get())
            {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function get_stock_valuation($where=array(), $classname='stock_valuation_dto')
    {
        $table_alias = array('inventory'=>'inv', 'product'=>'p', 'category'=>'c',
            'sub_category'=>'sc');

        $replace_arr = array('GBP');

        include_once APPPATH . "helpers/string_helper.php";

        if (is_array($where) && count($where) > 0)
        {
            $new_where = replace_db_alias($where, $table_alias);
            $where_clause = ' WHERE';
            $count = 0;

            foreach ($new_where as $key=>$value)
            {
                if ($count++ > 0)
                {
                    $where_clause = ' AND';
                }

                if ($this->db->_has_operator($key))
                {
                    $where_clause .= " $key ?";
                }
                else
                {
                    $where_clause .= " $key = ?";
                }

                $replace_arr[] = $value;
            }
        }

//      $sql = "SELECT
//                  c.name cat_name, sc.name sub_cat_name, ssc.name sub_sub_cat_name,
//                  p.name prod_name,
//                  inv.prod_sku, inv.log_sku, inv.warehouse_id, inv.inventory,
//                  sp.cost value_per_piece,
//                  inv.inventory * sp.cost total_inv_value
//              FROM inventory inv
//              JOIN product p
//                  ON (p.sku = inv.prod_sku)
//              JOIN category c
//                  ON (p.cat_id = c.id)
//              JOIN category sc
//                  ON (p.sub_cat_id = sc.id)
//              JOIN category ssc
//                  ON (p.sub_sub_cat_id = ssc.id)
//              JOIN region r
//                  ON (r.id = inv.source_region_id)
//              JOIN supplier_prod sp
//                  ON (inv.prod_sku = sp.prod_sku
//                      AND sp.order_default = 1)
//              $where_clause
//              ORDER BY c.name, sc.name, ssc.name,
//                  p.name, inv.prod_sku, inv.log_sku, inv.warehouse_id";

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
        $result_arr = array();

        foreach ($result->result("object", $classname) as $obj)
        {
            array_push($result_arr, $obj);
        }

        if ($result_arr)
        {
            return $result_arr;
        }
        else
        {
            return array();
        }
    }

    public function get_prod_sum_inv_git_by_country($sku, $country_id)
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
        if ($query = $this->db->query($sql, array($sku, $country_id)))
        {
            return $query->row()->total;
        }
        else
        {
            return FALSE;
        }
    }

    public function get_fc_pending_by_country($sku, $country_id)
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

        if ($query = $this->db->query($sql, array($country_id, $sku)))
        {
            return $query->row()->outstanding_qty;
        }
        else
        {
            return FALSE;
        }
    }

    public function set_surplus_quantity($sku, $qty)
    {
        $sql =
        "
            update `inventory` i
            inner join `sku_mapping` m on m.sku = i.prod_sku and m.status = 1 and ext_sys = 'wms'
                set surplus_qty = ?
            where 1
            and m.ext_sku = ?;
        ";
        $query = $this->db->query($sql, array($qty, (string)$sku));

        // var_dump($this->db->last_query());

        $sql = "commit";
        $query = $this->db->query($sql);
    }
}

/* End of file inventory_dao.php */
/* Location: ./system/application/libraries/dao/Inventory_dao.php */