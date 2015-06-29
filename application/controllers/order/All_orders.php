<?php
class All_orders extends MY_Controller
{
    private $_app_id="ORD0021";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/all_orders_model');
/*
        $this->load->helper(array('url', 'notice', 'object', 'operator'));
        $this->load->library('service/pagination_service');
        $this->load->library('service/event_service');
        $this->load->library('service/delivery_option_service');
        $this->load->library('encrypt');
*/
    }

    public function index()
    {
        $sub_app_id = $this->_get_app_id() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->get_lang_id() . ".php");

        #SBF #2768 add search filters
        $data["pmgw_list"] = $this->all_orders_model->get_pmgw_list(array(), array("ORDER BY id ASC")); #payment_gateway
        $data["order_type_list"] = $this->all_orders_model->get_selling_platform_list(); #from platform_biz_var
        $data["currency_list"] = $this->all_orders_model->get_currency_list(); #from platform_biz_var
        $data["so_hold_reason_list"] = $this->all_orders_model->get_so_hold_reason_list();
        // echo "<pre>"; var_dump($data["currency_list"]);die();

        if ($_POST["post"])
        {
            $order_type = trim($_POST["order_type"]);
            $psp_gateway = trim($_POST["psp_gateway"]);
            $hold_reason = trim($_POST["hold_reason"]);
            $currency = trim($_POST["currency"]);
            $start_date = trim($_POST["start_date"]);
            $end_date = trim($_POST["end_date"]);
            $so_number = trim($_POST["so_number"]);
            if (!empty($so_number))
            {
                $so_number = strtoupper($so_number);
            }
        }

        $data["lang"] = $lang;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['so_number'] = $so_number;

        # if we already have a POST value, then let value stay in option
        $data['select_order_type'] = $order_type;
        $data['select_psp_gateway'] = $psp_gateway;
        $data['select_hold_reason'] = $hold_reason;
        $data['select_currency'] = $currency;


        if($_POST["exportSubmit"])
        {
//          $data['output'] = $this->all_orders_model->get_report_heading();
            $data['output'] = $this->all_orders_model->get_all_orders_export_report($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency);
            $data['filename'] = $this->all_orders_model->get_export_filename($start_date, $end_date);
            $this->load->view('output_csv.php', $data);
        }
        else
        {
            $data['heading'] = explode(",", $this->all_orders_model->get_report_heading());
            if ($_POST["post"])
            {
                $data['orders'] = $this->all_orders_model->get_all_orders_report($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency);
            }
            $this->load->view('order/all_orders/index', $data);
        }
    }

    public function _get_app_id()
    {
        return $this->_app_id;
    }

}
