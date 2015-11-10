<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Supplier_dao extends Base_dao
{
    private $table_name = "supplier";
    private $vo_class_name = "Supplier_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
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

    public function get_list_w_name($where = array(), $option = array())
    {

        $this->db->from('supplier AS s');
        $this->db->join('region AS sur', 's.supplier_reg = sur.id', 'LEFT');
        $this->db->join('region AS scr', 's.sourcing_reg = scr.id', 'LEFT');

        if ($where["id"] != "") {
            $this->db->operator_where('s.id', $where["id"]);
        }

        if ($where["name"] != "") {
            $this->db->like('s.name', $where["name"]);
        }

        if ($where["supplier_reg"] != "") {
            $this->db->like('sur.region_name', $where["supplier_reg"]);
        }

        if ($where["sourcing_reg"] != "") {
            $this->db->like('scr.region_name', $where["sourcing_reg"]);
        }

        if ($where["status"] != "") {
            $this->db->like('s.status', $where["status"]);
        }

        if (empty($option["num_rows"])) {

            $this->include_vo();

            $this->db->select('s.*, sur.region_name AS supplier_reg, scr.region_name AS sourcing_reg');

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
                foreach ($query->result($this->get_vo_classname()) as $obj) {
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

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }

    public function get_supplier($prod = "")
    {
        if ($prod == "") {
            return FALSE;
        }
        $sql = "SELECT s.*
                FROM supplier s
                JOIN supplier_prod sp
                    ON sp.supplier_id = s.id
                    AND sp.prod_sku = ?
                    AND sp.order_default = '1'
                LIMIT 1
                ";

        $this->include_vo();

        if ($query = $this->db->query($sql, $prod)) {
            foreach ($query->result($this->get_vo_classname()) as $obj) {
                $tmp = $obj;
            }

            return $tmp;
        }
        return FALSE;
    }

    public function check_valid_supplier_cost($sku)
    {
        if (empty($sku)) {
            return FALSE;
        }

        $this->db->from("supplier AS s");
        $this->db->join("supplier_prod AS sp", "s.id = sp.supplier_id AND sp.order_default = 1", "INNER");
        $this->db->where("sp.prod_sku", $sku);
        $this->db->select("count(1) total");

        if ($query = $this->db->get()) {
            return $query->row()->total;
        }

        return FALSE;
    }

    public function get_supplier_status_dto($where = array(), $option = array(), $classname = 'Supplier_status_dto')
    {
        $this->db->from('product AS p');
        $this->db->join('sku_mapping map', 'p.sku = map.sku and map.status = 1 and map.ext_sys = "WMS"', 'LEFT');
        $this->db->join('supplier_prod sp', 'p.sku = sp.prod_sku and sp.order_default = 1', 'LEFT');
        $this->db->join('supplier s', 'sp.supplier_id = s.id', 'LEFT');

        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'p.sku, map.ext_sku, p.name as prod_name, sp.supplier_id, s.name as supplier_name, sp.supplier_status');
    }

    public function get_supplier_cost_dto($where = array(), $option = array(), $classname = 'Supplier_cost_dto')
    {
        $this->db->from('product AS p');
        $this->db->join('sku_mapping map', 'p.sku = map.sku and map.status = 1 and map.ext_sys = "WMS"', 'LEFT');
        $this->db->join('v_prod_w_platform_biz_var v', 'p.sku = v.sku', 'INNER');

        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'p.sku, map.ext_sku, p.name as prod_name, v.supplier_cost');
    }
}


