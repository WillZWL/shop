<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Warehouse_dao extends Base_dao
{
    private $table_name = "warehouse";
    private $vo_class_name = "Warehouse_vo";
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
}

/* End of file Pricing_tool_dao.php */
/* Location: ./system/application/libraries/dao/Warehouse_dao.php */