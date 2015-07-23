<?php

include_once "Base_service.php";

class So_compensation_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/So_compensation_dao.php");
        $this->set_dao(new So_compensation_dao());
    }

    public function get_orders_eligible_for_compensation($where = array(), $option = array())
    {
        return $this->get_dao()->get_orders_eligible_for_compensation($where, $option);
    }

    public function get_compensation_so_list($where = array(), $option = array())
    {
        return $this->get_dao()->get_compensation_so_list($where, $option);
    }

    public function get_order_compensated_item($where = array(), $option = array())
    {
        return $this->get_dao()->get_order_compensated_item($where, $option);
    }
}


