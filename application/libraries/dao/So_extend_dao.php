<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class So_extend_dao extends Base_dao
{
    private $table_name = "so_extend";
    private $vo_class_name = "So_extend_vo";
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

    public function get_so_ext_w_reason($where = array(), $option = array(), $classname = 'so_ext_w_reason_dto')
    {
        $this->db->from("so_extend soex");
        $this->db->join('order_reason ore', 'ore.reason_id = soex.order_reason', 'LEFT');
        $this->db->where($where);
        $this->include_dto($classname);
        if (isset($option["limit"])) {
            $this->db->limit($option["limit"]);
            if ($query = $this->db->get()) {
                $result = $query->result($classname);
                if (sizeof($result) == 1)
                    return $result[0];
                else
                    return array();
            }
        } else {
            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            }
        }
    }
}


