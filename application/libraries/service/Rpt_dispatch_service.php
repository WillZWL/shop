<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Rpt_sales_service.php";

class Rpt_dispatch_service extends Rpt_sales_service
{
    public function __construct()
    {
        parent::Rpt_sales_service();
    }

    public function get_data($from_date = '', $to_date = '', $where = array())
    {
        $dispatch_string = "so.dispatch_date";
        $where['so.status ='] = 6;

        $where[$dispatch_string . " between '" . $from_date . "' and '" . $to_date . "'"] = null;
        $arr = $this->get_so_service()->get_confirmed_so($where, $from_date, $to_date, $is_light_version = false, $dispatch_report = true);
        $data = $this->process_data_row($arr);

        return $data;
    }

    public function get_no_finance_dispatch_order()
    {
        //get orders that have dispatch logistically but no financially (no finance_dispatch_date)
        $where = $option = array();
        $where['dispatch_date is not null'] = null;
        $option['limit'] = -1;

        return $this->get_so_service()->get_dao()->get_no_finance_dispatch_order($where, $option);

    }
}


