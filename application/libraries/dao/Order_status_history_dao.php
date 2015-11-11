<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Order_status_history_dao extends Base_dao
{
    private $table_name = "order_status_history";
    private $vo_class_name = "Order_status_history_vo";
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

    public function get_list_w_username($where, $classname = "Order_history_username_dto")
    {
        $this->db->from('order_status_history osh');

        $this->db->join('user u', 'u.id = osh.create_by', 'LEFT');

        $this->db->where($where);

        $this->db->select('osh.status,osh.create_on,u.username');

        $this->db->orderby('osh.create_on ASC');

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
}

?>