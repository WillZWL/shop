<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Interface_so_item_dao extends Base_dao
{
    private $table_name="interface_so_item";
    private $vo_class_name="Interface_so_item_vo";
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

}

/* End of file so_item_dao.php */
/* Location: ./system/application/libraries/dao/So_item_dao.php */