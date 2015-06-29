<?php
class Failed_transaction extends MY_Controller
{

    private $app_id="RPT0011";
    private $lang_id="en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/failed_transaction_model');
        $this->load->helper(array('url'));
        //$this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
    }

    private function _load_parent_lang()
    {
        $sub_app_id = $this->_get_app_id()."00";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");

        return $lang;
    }

    public function query($start_date, $end_date)
    {
        $data['lang'] = $this->_load_parent_lang();
        $data['output'] = $this->failed_transaction_model->get_csv($start_date, $end_date);
        $data['filename'] = 'failed_transaction_report_'.$sku.'.csv';

        $this->load->view('output_csv.php', $data);
    }

    public function index()
    {
        $data['lang'] = $this->_load_parent_lang();
        //$this->model->get_csv($sku, $prod_name);
        if($this->input->post('is_query'))
        {
            $data["posted"] = 1;
            $data["start_date"] = $this->input->post('start_date');
            $data["end_date"] = $this->input->post('end_date');
            $data["display_type"] = $this->input->post('display_type');

            if (!empty($data["start_date"]))
            {
                $where['so.order_create_date >='] = $data["start_date"]." 00:00:00";
            }

            if (!empty($data["end_date"]))
            {
                $where['so.order_create_date <='] = $data["end_date"]." 23:59:59";
            }

            $data["total_attempt"] = $this->rpt_failed_transaction_service->get_so_w_payment($where, array("num_rows"=>1));
            $where["sops.payment_status"] = "F";
            $data["total_fail"] = $this->rpt_failed_transaction_service->get_so_w_payment($where, array("num_rows"=>1));
            $where["sops.payment_status"] = "S";
            $data["total_success"] = $this->rpt_failed_transaction_service->get_so_w_payment($where, array("num_rows"=>1));
        }
        $this->load->view('report/failed_transaction/failed_transaction', $data);
    }

    public function view($start_date, $end_date)
    {

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        if (empty($sort))
        {
            $sort = "so.order_create_date";
        }

        if (empty($order))
        {
            $order = "asc";
        }

        $option["orderby"] = $sort." ".$order;
        $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
        $data["xsort"][$sort] = $order=="asc"?"desc":"asc";
        $data['lang'] = $this->_load_parent_lang();
        $data["objlist"] = $this->rpt_failed_transaction_service->get_data($start_date, $end_date, $option);
        $this->load->view('report/failed_transaction/failed_transaction_v', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
}

/* End of file failed_transaction.php */
/* Location: ./system/application/controllers/report/failed_transaction.php */