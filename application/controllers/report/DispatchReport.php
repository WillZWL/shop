<?php defined('BASEPATH') OR exit('No direct script access allowed');

class DispatchReport extends MY_Controller
{
    public $appId = "RPT0007";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'notice', 'image'));
    }

    public function index()
    {
        include_once (APPPATH . "language/" . $this->getAppId() . "00_" . $this->_get_lang_id() . ".php");
        $data['lang'] = $lang;
        $data['countrys'] = $this->sc['Country']->getDao()->getList(array("allow_sell" => "1"), array("orderby" => "name", "limit" => -1));
        $data['currencys'] = $this->sc['Country']->getSellCurrencyList();
        $data['gateways'] = $this->sc['PaymentGateway']->getDao()->getList(array("status" => 1), array("limit" => -1));
        $data['brands'] = $this->sc['Brand']->getDao('Brand')->getList(array('status'=>1), array('limit'=>-1));
        $data["start_date"] = date('Y-m-d', strtotime(date('Y-m-d') . ' - 10 day'));
        $data["end_date"] = date('Y-m-d');
        $this->load->view('report/dispatch_report', $data);
    }

    public function query()
    {
        if ($this->input->post('is_query')) {
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $where["so.dispatch_date BETWEEN '$start_date' AND '$end_date'"] = NULL;

            if ($this->input->post('payment_gateway')) {
                $where['sps.payment_gateway_id'] = $this->input->post('payment_gateway');
            }
            if ($this->input->post('currency')) {
                $where['so.currency_id'] = $this->input->post('currency');
            }
            if ($this->input->post('country')) {
                $where['so.delivery_country_id'] = $this->input->post('country');
            }
            if ($this->input->post('brand')) {
                $where['p.brand_id'] = $this->input->post('brand');
            }

            $data['output'] = $this->sc['RptDispatch']->getCsv($where, $option);

            $data['filename'] = 'dispatch_report.csv';

            $this->load->view('output_csv.php', $data);
        }
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function setAppId($value)
    {
        $this->appId = $value;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

}


