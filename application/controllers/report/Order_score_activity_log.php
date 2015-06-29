<?php
class Order_score_activity_log extends MY_Controller
{

    private $app_id="RPT0039";
    private $lang_id="en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/order_score_activity_log_model');
        $this->load->helper(array('url'));
        $this->load->library('service/context_config_service');
    }

    private function _load_parent_lang()
    {
        $sub_app_id = $this->_get_app_id()."00";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");

        return $lang;
    }

    public function query()
    {
        $data['lang'] = $this->_load_parent_lang();

        if($this->input->post('is_query'))
        {
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $order_score = $this->input->post('order_score');
            $order_status = $this->input->post('order_status');

            $data['output'] = $this->order_score_activity_log_model->get_csv($start_date, $end_date, $order_score, $order_status);
            //var_dump($data['output']);
            $data['filename'] = 'report.csv';

            $this->load->view('output_csv.php', $data);
        }
    }

    public function index()
    {
        $data['lang'] = $this->_load_parent_lang();

        $data['start_date'] = date("Y-m-d");
        $data['end_date'] = date("Y-m-d");

        $this->load->view('report/order_score_activity_log', $data);
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

/* End of file order_score_activity_log.php */
/* Location: ./system/application/controllers/report/inventory/order_score_activity_log.php */