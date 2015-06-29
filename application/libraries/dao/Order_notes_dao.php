<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Order_notes_dao extends Base_dao
{
    private $table_name="order_notes";
    private $vo_class_name="Order_notes_vo";
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

    public function get_list_w_name($where,$classname="Order_note_username_dto")
    {
        $this->db->from('order_notes n');

        $this->db->join('user u','u.id = n.create_by','LEFT');

        $this->db->where($where);

        $this->db->select('n.note, n.create_on, COALESCE(u.username, n.create_by) AS username', FALSE);

        $this->db->orderby('n.create_on DESC');

        $this->include_dto($classname);

        $rs = array();

        if ($query = $this->db->get())
        {
            foreach ($query->result($classname) as $obj)
            {
                $rs[] = $obj;
            }
            return $rs;
        }
        return FALSE;

    }
}
?>