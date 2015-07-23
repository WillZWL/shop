<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Delivery_option_dao extends Base_dao
{
    private $table_name = "delivery_option";
    private $vo_class_name = "Delivery_option_vo";
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

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function display_name_of($courier_id, $lang_id = "en")
    {
        $this->db->select('display_name');
        if ($query = $this->db->get_where($this->get_table_name(), array("courier_id" => $courier_id, "lang_id" => $lang_id), 1)) {
            return $query->row()->display_name;
        } else {
            return FALSE;
        }
    }

    public function get_table_name()
    {
        return $this->table_name;
    }
}


