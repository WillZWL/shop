<?php
include_once "base_report.php";
class Refund_reason_report extends Base_report
{
    private $app_id = "RPT0048";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/refund_reason_report_model');
        $this->load->helper(array('url','notice'));
//      $this->load->library('input');
//      $this->load->library('service/context_config_service');
        // if use view template
        //$this->load->library('template');
        //$this->template->set_template('report');
    }

    public function index()
    {
        $data["title"] = "Refund Reason Report";
        $this->load->view("report/refund_reason_report", $data);
        // if use view template
        //$this->template->write('_title', $data["title"]);
        //$this->template->render();
    }

    public function export_csv()
    {
        if($this->input->post('is_query'))
        {
            $data["posted"] = 1;

            if($_POST['startD'] && $_POST['endD'])
            {
                // where S.refund_status >= 3 and R2.id <> 30 and R2.id <> 32 and R1.create_on >= [yyyy-mm-dd hh:mm:ss] and R1.create_on < [yyyy-mm-dd hh:mm:ss]
                $where["R1.create_on >="] = $_POST['startD'] . " 00:00:00";
                $where["R1.create_on <="] = $_POST['endD'] . " 23:59:59";
                $where["S.refund_status >="] = 3;
                $where["R2.id <>"] = 30;
                $where["R2.id <> "] = 32;

                $data['output'] = $this->refund_reason_report_model->get_csv($where);
                $data['filename'] = 'refund_reason_report_'.$_POST['startD'].'-'.$_POST['endD'].'.csv';
                $this->load->view('output_csv.php', $data);
            }
        }
    }

    public function send_email()
    {
        $data["posted"] = 1;
        $where["R1.create_on >="] = date('Y-m-d',strtotime("-7 days")) . " 00:00:00";
        $where["R1.create_on <="] = date('Y-m-d',strtotime("-1 days")) . " 23:59:59";
        $where["S.refund_status >="] = 3;
        $where["R2.id <>"] = 30;
        $where["R2.id <> "] = 32;

        $data['output'] = $this->refund_reason_report_model->get_csv($where);
        $data['filename'] = 'refund_reason_report_'.date('Y-m-d',strtotime("-7 days")).'-'.date('Y-m-d',strtotime("-1 days")).'.csv';
        //$this->load->view('output_csv.php', $data);
        $csv = $data['output'];
        //var_dump($csv);

        $msg = "Attached is last week's (".date('Y-m-d',strtotime("-7 days")).'-'.date('Y-m-d',strtotime("-1 days")).") ValueBasket Refund Reason Report. Thank you for your attention.";
        $this->refund_reason_report_model->send_email($data['filename'], $csv, $msg);
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