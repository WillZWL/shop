<?php

include_once 'Base_dao.php';

class Ra_group_content_dao extends Base_dao
{
    private $table_name = "ra_group_content";
    private $vo_classname = "Ra_group_content_vo";
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

}


