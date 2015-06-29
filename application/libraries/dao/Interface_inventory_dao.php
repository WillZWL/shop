<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Interface_inventory_dao extends Base_dao
{
    private $table_name = "interface_inventory";
    private $vo_class_name = "Interface_inventory_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_iinv_list($where = array(), $option = array(), $classname = "")
    {

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

        $vo_classname = $this->get_vo_classname();
        $vo_file = APPPATH . "libraries/vo/" . strtolower($vo_classname) . ".php";
        if (file_exists($vo_file)) {
            include_once($vo_file);
            if ($query = $this->db->get_where($this->get_table_name(), $where, $option["limit"], $option["offset"])) {
                $rs = array();
                if ($classname == "") {
                    $classname = $vo_classname;
                }
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                if ($option["limit"] == 1) {
                    return $rs[0];
                } else {
                    if (empty($option["result_type"])) {
                        return (object)$rs;
                    } else {
                        return $rs;
                    }
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_iinv_num_rows($where = array())
    {
        $this->db->select('COUNT(*) AS total');
        if ($query = $this->db->get_where($this->get_table_name(), $where)) {
            return $query->row()->total;
        } else {
            return FALSE;
        }
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }
}

/* End of file interface_inventory_dao.php */
/* Location: ./system/application/libraries/dao/Interface_inventory_dao.php */