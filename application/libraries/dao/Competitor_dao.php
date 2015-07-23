<?php

include_once 'Base_dao.php';

class Competitor_dao extends Base_dao
{
    private $table_name = "competitor";
    private $vo_classname = "Competitor_vo";
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

    public function get_vo_classname()
    {
        return $this->vo_classname;
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_list_index($where = array(), $option = array())
    {
        if (!isset($option["num_row"])) {
            return $this->get_list($where, $option);
        } else {
            $this->db->from('competitor');

            $this->db->where($where);

            $this->db->select("COUNT(*) as total");

            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

}
