<?php
include_once "base_report.php";

class Us_sourcing_report extends Base_report
{
    private $appId = "RPT0047";
    private $lang_id = "en";

    public function __construct()
    {
        parent::Base_report();
        $this->load->model('report/sourcing_region_report_model');
        $this->load->helper(array('url', 'notice'));
        $this->template->set_template('report');
    }

    public function index()
    {
        $data["title"] = "US sourcing Order Report";
//No content, use the template only, so no need to write into content region
//      $this->template->write_view("content", "report/refund_report", $data, TRUE);
        $this->template->write('_title', $data["title"]);
        $this->template->render();
    }

    public function export_csv()
    {
        if ($this->input->post('is_query')) {
            $data["posted"] = 1;
            if ($_POST["check"]["order_create"]) {
                if ($_POST["start_date"]["order_create"]) {
                    $where["so.order_create_date >="] = $_POST["start_date"]["order_create"] . " 00:00:00";
                }
                if ($_POST["end_date"]["order_create"]) {
                    $where["so.order_create_date <="] = $_POST["end_date"]["order_create"] . " 23:59:59";
                }
            }
            $where["sp.supplier_id"] = 14;

            $data['output'] = $this->sourcing_region_report_model->get_csv($where);
            $data['filename'] = 'us_sourcing_report.csv';
            $this->load->view('output_csv.php', $data);
        }
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
}
