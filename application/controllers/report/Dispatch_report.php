<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "sales_report.php";

class Dispatch_report extends Sales_report
{
    public $app_id = "RPT0007";
    private $model;

    public function __construct()
    {
        parent::Sales_report();
        $this->load->model('report/dispatch_report_model');
        $this->_set_model($this->dispatch_report_model);
        $this->_set_export_filename('dispatch_report.csv');
    }

    public function no_finance_dispatch_report()
    {
        header("Content-type: text/csv");
        header("Cache-Control: no-store, no-cache");

        $filename = "no_finance_dispatch_report_".date("Ydm_His").".csv";

        header("Content-Disposition: attachment; filename=\"$filename.csv\"");

        echo "so_no, platform_id, order_create_date, dispatch_date, finance_displace_date\r\n";


        if($objs = $this->dispatch_report_model->get_report_service()->get_no_finance_dispatch_order())
        {
            foreach($objs as $obj)
            {
                echo "{$obj->get_so_no()},{$obj->get_platform_id()},{$obj->get_create_on()},{$obj->get_dispatch_date()},{$obj->get_finance_dispatch_date()}\r\n";
            }
        }
    }
}

/* End of file dispatch_report.php */
/* Location: ./system/application/controllers/report/inventory/dispatch_report.php */