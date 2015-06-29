<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order_fulfillment_report extends MY_Controller
{
    private $app_id = "RPT0067";
    private $lang_id = "en";

    public function __construct()
    {

        parent::__construct();
        $this->load->model('report/fulfillment_report_model');
        $this->load->helper(array('url'));
        $this->load->library('service/context_config_service');
        $this->_set_export_filename('fulfillment_report.csv');
    }

    public function _set_export_filename($value)
    {
        $this->export_filename = $value;
    }

    public function index()
    {
        $data['lang'] = $this->_load_parent_lang();
        $data['controller'] = strtolower(get_class($this));
        $data['start_date'] = date("Y-m-d");
        $data['end_date'] = date("Y-m-d");
        $this->load->view('report/order_fulfillment_report_v', $data);
    }

    private function _load_parent_lang()
    {
        $sub_app_id = $this->_get_app_id() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        return $lang;
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function query()
    {

        $data['lang'] = $this->_load_parent_lang();
        if ($this->input->post('is_query')) {
            $order_status = $this->input->post('order_status');
            $option['status'] = $order_status;
            switch ($order_status) {
                case '1':
                    $status = 'Fulfilled';
                    $where['so.status'] = 6;
                    break;
                case '0':
                    $status = 'Not_Fulfilled';
                    $where['so.`status` < 6 and so.`status` >= 3'] = null;
                    break;
                case '-1':
                    $status = 'All';
                    break;
                default:
                    $status = 'All';
                    break;
            }
            $start_date = date('Y-m-d 00:00:00', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d 23:59:59', strtotime($this->input->post('end_date')));
            $where['sa.create_on >='] = $start_date;
            $where['sa.create_on <='] = $end_date;
            $where['so.hold_status'] = 0;
            $where['so.refund_status'] = 0;
            $data['output'] = $this->fulfillment_report_model->get_csv($where, $option);
            $data['filename'] = "ORDER__" . $status . '_' . date('Ymd', strtotime($start_date)) . '_' . date('Ymd', strtotime($end_date)) . '.csv';
            $this->load->view('output_csv.php', $data);
        }
    }

    public function _get_export_filename()
    {
        return $this->export_filename;
    }
}

