<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class So_credit_chk_dao extends Base_dao
{
    private $table_name = "so_credit_chk";
    private $vo_class_name = "So_credit_chk_vo";
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

    public function get_cc_list()
    {
        $list = $this->get_list(array("t3m_result IS" => ""), array("limit" => "-1"));

        $ret = array();

        foreach ($list as $obj) {
            $ret[$obj->get_so_no()] = 1;
        }

        return $ret;
    }
}


