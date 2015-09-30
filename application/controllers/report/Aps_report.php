<?php

include_once "base_report.php";

class aps_report extends Base_report
{
    private $appId = "RPT0041";
    private $lang_id = "en";

    public function aps_report()
    {
        parent::__construct();
        $this->load->model('report/compensation_report_model');
        $this->load->helper(array('url', 'notice'));
        $this->load->library('service/so_service');
        $this->load->library('service/price_service');
        $this->template->set_template('report');
    }

    public function index()
    {
        $data["title"] = "Compensation Report";

        $langfile = $this->getAppId() . "01_" . $this->_get_lang_id() . ".php";
        include_once APPPATH . "language/" . $langfile;
        $data["lang"] = $lang;

        $this->load->view('report/aps_report', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function export_csv()
    {
        $hold_status_list[0] = "No";
        $hold_status_list[1] = "Requested";
        $hold_status_list[2] = "Manager Requested";
        $hold_status_list[3] = "APS need Payment order in Sales - APS area";

        $refund_status_list[0] = "No";
        $refund_status_list[1] = "Requested";
        $refund_status_list[2] = "Logistic Approved";
        $refund_status_list[3] = "CS Approved";
        $refund_status_list[4] = "Refunded";

        if (trim($this->input->post("order_list")) == "") {
            header("Location: /report/aps_report");
        } else {
            $order_list = explode("\n", $this->input->post("order_list"));

            $list = $this->so_service->get_dao()->get_so_aps_report($order_list);

            $content = "SO Number,Platform,Order Create Date,Product Name,SKU,Quantity,Amount,Margin,Hold Status,Refund Status\r\n";
            foreach ($list as $line) {
                $hold_status_id = $line->hold_status;
                $refund_status_id = $line->refund_status;

                if ($hold_status_id == null) $hold_status_id = 0;
                if ($refund_status_id == null) $refund_status_id = 0;

                $hold_status = $hold_status_list[$hold_status_id];
                $refund_status = $refund_status_list[$refund_status_id];

                if ($hold_status == null) $hold_status = "Error";
                if ($refund_status == null) $refund_status = "Error";

                $json = $this->price_service->get_profit_margin_json($line->platform_id, $line->prod_sku, $line->amount, -1, false);
                $info = json_decode($json, true);
                $margin = $info["get_margin"];

                $content .= "{$line->so_no},{$line->platform_id},{$line->create_on},\"{$line->prod_name}\",{$line->prod_sku},{$line->qty},{$line->amount},$margin,{$hold_status},{$refund_status}\r\n";
            }
        }

        $data['output'] = $content;
        $data['filename'] = 'aps_report.csv';
        $this->load->view('output_csv.php', $data);
        return;

        var_dump($result[0]->so_no);
        die();

        if ($this->input->post('is_query')) {


            $data["posted"] = 1;

            if ($_POST["start_date"]["order_create"]) {
                $_SESSION['start_date'] = $_POST["start_date"]["order_create"];
                $where["soc.create_on >="] = $_POST["start_date"]["order_create"] . " 00:00:00";
            }
            if ($_POST["end_date"]["order_create"]) {
                $_SESSION['end_date'] = $_POST["end_date"]["order_create"];
                $where["soc.create_on <="] = $_POST["end_date"]["order_create"] . " 23:59:59";
            }
            $data['output'] = $this->compensation_report_model->get_csv($where);
            $data['filename'] = 'compensation_report.csv';
            $this->load->view('output_csv.php', $data);
        }

// select
// so.so_no,
// so.platform_id,
// so.create_on,
// si.prod_name,
// si.prod_sku,
// si.qty,
// si.amount,
// so.hold_status,
// so.refund_status
// from so
// inner join so_item si on so.so_no = si.so_no
// where so.so_no in (
// 298482,
// 298485,
// 298487,
// 298492,
// 298493,
// 298494,
// 298496
// )

    }
}

