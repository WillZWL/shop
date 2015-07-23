<?php

include_once "base_report.php";

class product_hs_code_report extends Base_report
{
    private $app_id = "RPT0050";
    private $lang_id = "en";

    public function product_hs_code_report()
    {
        parent::__construct();
        $this->load->model('report/product_hs_code_model');
        $this->load->helper(array('url', 'notice'));
        $this->load->library('service/so_service');
        $this->load->library('service/price_service');
        $this->load->library('service/category_service');
        $this->load->library('template');
        $this->template->set_template('report');
    }

    public function index()
    {
        $data["title"] = "Product HS Code Report";

        $langfile = $this->_get_app_id() . "01_" . $this->_get_lang_id() . ".php";
        include_once APPPATH . "language/" . $langfile;
        $data["lang"] = $lang;

        $data['catoption'] = $this->category_service->get_list_w_key(array('level' => '1'), $option);

        // echo "<pre>"; var_dump($data["catoption"]);die();
        //$catarr= array();
        // for($i=0; $i<$catoption; $i++){
        //  $catarr['id'] = $catoption[$i]['id'];
        //  $catarr['name'] = $catoption[$i]['name'];
        // }


        $this->load->view('report/product_hs_code_report', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
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
        $hold_status_list[3] = "Product HS Code Report";

        $refund_status_list[0] = "No";
        $refund_status_list[1] = "Requested";
        $refund_status_list[2] = "Logistic Approved";
        $refund_status_list[3] = "CS Approved";
        $refund_status_list[4] = "Refunded";

        if (trim($this->input->post("cat_id")) == "") {
            header("Location: /report/product_hs_code_report");
        } else {
            // $sku_list = explode("\n", $this->input->post("sku_list"));
            // $newlist = '';
            // $cntlist = count($sku_list);
            // for($i=0;$i<$cntlist;$i++){
            //  $newlist .= "'".$sku_list[$i];
            //  if($i == $cntlist-1){
            //      $newlist .= "'";
            //  }else{
            //      $newlist .= "',";
            //  }
            // }

            //$list = $this->so_service->get_dao()->get_product_hscode_report($newlist);

            $list = $this->so_service->get_dao()->get_product_hscode_report($this->input->post("cat_id"));

            $content = "MasterSKU,SKU,Product Name,Category, Subcategory,Sub_subcategory,Country ID,HS Code,HS Code Description,Duty Percent\r\n";
            foreach ($list as $line) {
                if ($line->sub_subcategory == 'Base') {
                    $line->sub_subcategory = '--------';
                }
                // $hold_status_id = $line->hold_status;
                // $refund_status_id = $line->refund_status;

                // if ($hold_status_id == null) $hold_status_id = 0;
                // if ($refund_status_id == null) $refund_status_id = 0;

                // $hold_status = $hold_status_list[$hold_status_id];
                // $refund_status = $refund_status_list[$refund_status_id];

                // if ($hold_status == null) $hold_status = "Error";
                // if ($refund_status == null) $refund_status = "Error";

                // $json = $this->price_service->get_profit_margin_json($line->platform_id, $line->prod_sku, $line->amount, -1, false);
                // $info = json_decode($json,true);
                // $margin = $info["get_margin"];

                $content .= "{$line->mastersku},{$line->sku},{$line->product},{$line->category},{$line->subcategory},{$line->sub_subcategory},{$line->country_id},{$line->hscode},{$line->hsdescription},{$line->duty_pcent}\r\n";
            }
        }

        $data['output'] = $content;
        $data['filename'] = 'product_hs_code_report.csv';
        $this->load->view('output_csv.php', $data);
        return;

        //var_dump($result[0]->so_no);
        //die();

        // if($this->input->post('is_query'))
        // {


        //  $data["posted"] = 1;

        //  if($_POST["start_date"]["order_create"])
        //  {
        //      $_SESSION['start_date'] = $_POST["start_date"]["order_create"];
        //      $where["soc.create_on >="] = $_POST["start_date"]["order_create"] . " 00:00:00";
        //  }
        //  if($_POST["end_date"]["order_create"])
        //  {
        //      $_SESSION['end_date'] = $_POST["end_date"]["order_create"];
        //      $where["soc.create_on <="] = $_POST["end_date"]["order_create"] . " 23:59:59";
        //  }
        //  $data['output'] = $this->compensation_report_model->get_csv($where);
        //  $data['filename'] = 'compensation_report.csv';
        //  $this->load->view('output_csv.php', $data);
        // }

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

