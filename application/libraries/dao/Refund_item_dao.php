<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Refund_item_dao extends Base_dao
{
    private $table_name="refund_item";
    private $vo_class_name="Refund_item_vo";
    private $seq_name="";
    private $seq_mapping_field="";

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

    public function get_list_w_name($where=array(),$option=array(),$classname="Refund_item_prod_dto")
    {
        $this->db->from("refund_item ri");

        $this->db->join("product p","p.sku = ri.item_sku","LEFT");

        $this->db->join("user u","u.id = ri.create_by","INNER");

        $this->db->where($where);

        $this->db->select("ri.*, p.name, u.username");

        $this->db->order_by($option["sortby"]);

        $this->include_dto($classname);

        $rs = array();

        if($query = $this->db->get())
        {
            foreach($query->result($classname) as $obj)
            {
                $rs[] = $obj;
            }
            return $rs;
        }
        return FALSE;

    }

    public function get_order_refund_type($so_no = "")
    {
        if($so_no == "")
        {
            return FALSE;
        }

        $sql = "SELECT rfi.refund_type
                FROM refund rf
                JOIN refund_item rfi
                ON rf.id = rfi.refund_id
                WHERE rf.so_no = ?
                ";

        if($query = $this->db->query($sql,array($so_no)))
        {
            return $query->row()->refund_type;
        }
        return FALSE;
    }
}