<?php
include_once "Base_service.php";

class Category_id_mapping_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Category_id_mapping_dao.php");
        $this->set_dao(new Category_id_mapping_dao());
    }

    public function get_master_cat_id($where = array())
    {
        if ($obj = $this->get_dao()->get($where)) {
            return $obj->get_ext_id();
        } else {
            return false;
        }
    }

    public function get_local_id($master_id)
    {
        $where = array("ext_id" => $master_id);
        if ($obj = $this->get_dao()->get($where)) {
            return $obj->get_id();
        } else {
            return false;
        }
    }
}


