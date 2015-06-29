<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Freight_category_dao extends Base_dao {
    private $table_name="freight_category";
    private $vo_class_name="Freight_category_vo";
    private $seq_name="";
    private $seq_mapping_field="";

    public function __construct(){
        parent::__construct();
    }

    public function get_vo_classname(){
        return $this->vo_class_name;
    }

    public function get_table_name(){
        return $this->table_name;
    }

    public function get_seq_name(){
        return $this->seq_name;
    }

    public function get_seq_mapping_field(){
        return $this->seq_mapping_field;
    }

    public function get_cat_w_region($where=array(), $option=array(), $classname="")
    {

        $this->include_dto($classname);

        $this->db->select("id AS cat_id, name AS cat_name, weight");

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

        if ($query = $this->db->get_where($this->get_table_name(), $where, $option["limit"], $option["offset"]))
        {
            $rs = array();
            foreach ($query->result($classname) as $obj)
            {
                $rs[] = $obj;
            }
            if ($option["limit"] == 1)
            {
                return $rs[0];
            }
            else
            {
                return (object) $rs;
            }
        }
        else
        {
            return FALSE;
        }
    }

}

/* End of file freight_category_dao.php */
/* Location: ./system/application/libraries/dao/Freight_category_dao.php */