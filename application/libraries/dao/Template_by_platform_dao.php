<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Template_by_platform_dao extends Base_dao
{
    private $table_name = "template_by_platform";
    private $vo_class_name = "Template_by_platform_vo";
    private $seq_name = "Template_by_platform";
    private $seq_mapping_field = "id";

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

    public function get_tpl_w_msg($where = "")
    {
        return $this->get($where, "Tpl_msg_w_att_dto");
    }

}


