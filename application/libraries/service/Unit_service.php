<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Unit_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Unit_dao.php");
        $this->set_dao(new Unit_dao());
        include_once(APPPATH . "libraries/dao/Unit_type_dao.php");
        $this->set_ut_dao(new Unit_type_dao());
    }

    public function set_ut_dao(Base_dao $dao)
    {
        $this->ut_dao = $dao;
    }

    public function get_unit_list($where, $option)
    {
        return $this->get_dao()->get_list($where, $option);
    }

    public function get_unit_type_list($where, $option)
    {
        return $this->get_ut_dao()->get_list($where, $option);
    }

    public function get_ut_dao()
    {
        return $this->ut_dao;
    }
}

/* End of file unit_service.php */
/* Location: ./system/application/libraries/service/Unit_service.php */