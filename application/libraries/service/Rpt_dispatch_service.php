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
        /*
        if (check_finance_role())
        {
            $dispatch_string = "so.finance_dispatch_date";
            $where['so.status >='] = 5;
        }
        else
        {
            $dispatch_string = "so.dispatch_date";
            $where['so.status'] = 6;
        }

        */


        $dispatch_string = "so.finance_dispatch_date";
        $where['so.status >='] = 5;

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
        $where['finance_dispatch_date is null'] = null;
        $option['limit'] = -1;

        return $this->get_so_service()->get_dao()->get_no_finance_dispatch_order($where, $option);

    }
}

/* End of file rpt_dispatch_service.php */
/* Location: ./system/application/libraries/service/Rpt_dispatch_service.php */