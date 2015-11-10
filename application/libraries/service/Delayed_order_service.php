<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";


class Delayed_order_service extends Base_service
{
    private $sohr_dao;


    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Delayed_order_dao.php");
        $this->set_dao(new Delayed_order_dao());
        include_once(APPPATH . "libraries/dao/So_hold_reason_dao.php");
        $this->set_sohr_dao(new So_hold_reason_dao());
    }


    public function get_all_minor_delay_order($where = array(), $option = array())
    {
        return $this->get_dao()->get_all_minor_delay_order($where, $option);
    }


    public function has_oos_status($where = array(), $option = array())
    {
        return $this->get_dao()->has_oos_status($where, $option);
    }

    public function get_delay_order($where = array(), $option = array())
    {
        return $this->get_dao()->get_delay_order($where, $option);
    }

    public function is_delay_order($so_no)
    {
        $where = $option = array();
        $where["deor.so_no"] = $so_no;
        $where["deor.status not in (3, 4)"] = NULL;
        $option["limit"] = 1;
        return $this->get_dao()->is_delay_order($where, $option);
    }


    public function get_sohr_dao()
    {
        return $this->sohr_dao;
    }

    public function set_sohr_dao(Base_dao $dao)
    {
        $this->sohr_dao = $dao;
    }
}

?>