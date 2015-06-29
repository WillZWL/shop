<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Freight_management_report extends MY_Controller
{
    public $app_id = "RPT0049";
    private $lang_id="en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/freight_management_report_model');
        $this->load->helper(array('url','notice'));

        $this->load->library('service/so_service');
        $this->load->library('service/warehouse_service');
        $this->load->library('service/integrated_order_fulfillment_service');


        // $this->_set_export_filename('freight_management_report.csv');
    }

    private function _load_parent_lang()
    {
        $sub_app_id = $this->_get_app_id()."00";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");

        return $lang;
    }

    public function _set_app_id($value)
    {
        $this->app_id = $value;
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }


    public function index()
    {
        $data = array();
        $data['lang'] = $this->_load_parent_lang();

        if($_POST["report"] && ($_POST["report"] == "no_rec" || $_POST["report"] == "rec" || $_POST["report"] == "all")){

            $this->echo_data = false;
            $report = array();
            $objlist = $temp_objlist = array();
            ini_set("memory_limit", "500M");
            // $data["whlist"] = $this->warehouse_service->get_list(array(), array("limit"=>-1, "result_type"=>"array"));
            // echo "<pre>"; var_dump($data["whlist"]);die();
            $where["iof.status >"] = "2";
            $where["iof.status <"] = "5";

            $where["iof.hold_status"]="0";
            $where["iof.refund_status"]="0";

            $option["warehouse_id"] = "ES_HK";  #
            $option["orderby"] = "so_no, expect_delivery_date ASC";
            $option["notes"] = 1;
            // $option["ext_sku"] = 1;
            $option["product_related"] = $reno_option["product_related"] = 1;
            $option["hide_client"] = 1;
            $option["hide_payment"] = 0;
            $option["show_git"] = 1;
            $option["hide_shipped_item"] = 1;
            $option["show_so"] = $reno_option["show_so"] = 1;
            $option["limit"] = $this->so_service->get_dao()->db->rows_limit = 1000;
            $data = array();
            // $total_order =  $this->so_service->get_dao()->get_integrated_fulfillment_list_w_name($where, array("num_rows"=>1, "hide_client"=>1, "hide_payment"=>0, "hide_shipped_item"=>1));

            // retrieving all results at one go will cause time out/ insufficient memory, so we calculate total count of orders
            $total_order_row = $this->so_service->get_dao()->get_integrated_fulfillment_list_w_name($where, array("total_order_row"=>1, "hide_shipped_item"=>1));
            $loop = ceil($total_order_row/1000);
            $i = 0;
            for ($i=0; $i < $loop ; $i++)
            {
                $option["offset"] = $i * 1000;
                $temp_objlist = $this->so_service->get_dao()->get_integrated_fulfillment_list_w_name($where, $option);
                $objlist = $this->integrated_order_fulfillment_service->renovate_data($temp_objlist, $reno_option);

                if(empty($data))
                    $data = $objlist;
                else
                    $data = array_merge($data, $objlist);
            }

            if ($_POST["report"] == "no_rec") {
                foreach ($data as $value) {
                    if($value->get_rec_courier() == null){
                        $no_rec_data[] = $value;
                    }

                }
                $data_array = $no_rec_data;
            }

            if ($_POST["report"] == "rec") {
                foreach ($data as $value) {
                    if($value->get_rec_courier() != null){
                        $no_rec_data[] = $value;
                    }

                }
                $data_array = $no_rec_data;
            }

            if ($_POST["report"] == "all") {

                $data_array = $data;

            }

            if($data_array)
            {
                $report = $this->format_data($data_array, TRUE);
                $this->output_data($report);
            }

        } else {
            $this->load->view("report/freight_management_report", $data);
        }

    }

    private function format_data($objlist = array(), $show_header=false)
    {
        $csv = "";
        $report_data = $header = array();
        if(!empty($objlist))
        {
            $i = 0;
            foreach ($objlist as $obj)
            {
                $amount_usd = number_format($obj->get_amount()*$obj->get_rate(), 2, '.', '');
                $obj->set_amount_usd($amount_usd);

                $so_item_amount_usd = number_format($obj->get_so_item_amount()*$obj->get_rate(), 2, '.', '');
                $obj->set_so_item_amount($so_item_amount_usd);

                $classname = get_class($obj);
                if(!$methods = get_class_methods($classname))
                {
                    $this->error = "freight_management_report ".__LINE__." Unable to get methods from classname <$classname>";
                    return FALSE;
                }

                // fields that you want to add to report (take variable name in so_list_w_name_dto)
                // arrange it in order you want your columns
                $wanted = array("so_no", "sku", "master_sku","product_name", "cat_name", "sub_cat_name", "qty", "amount_usd", "so_item_amount", "weight", "delivery_postcode", "delivery_country_id", "delivery_state", "rec_courier", "note");

                foreach ($methods as $method)
                {
                    if(strpos($method, "get") !== false)
                    {
                        $headername = str_replace("get_", "", $method);
                        if(in_array($headername, $wanted))
                        {
                            $key = array_search($headername, $wanted);
                            if($i == 0 && $show_header)
                            {
                                $header[$key] = $headername;
                            }

                            $report_data[$i][$key] = str_replace(",", " ", $obj->$method());
                        }
                    }
                }

                if($header)
                    ksort($header);

                ksort($report_data[$i]);
                $i++;
            }

            array_unshift($report_data, $header);
        }

        return $report_data;

    }

    private function output_data($report_data)
    {
        if(is_array($report_data))
        {
            $fp = fopen('php://output', 'w');
            header( 'Content-Type: text/csv' );
            header( 'Content-Disposition: attachment;filename=freight_management_report.csv');

            if($report_data)
            {
                foreach ($report_data as $fields)
                {
                    fputcsv($fp, $fields);
                }
            }
            fclose($fp);
        }
    }
}

/* End of file freight_management_report.php */
/* Location: ./system/application/controllers/report/inventory/freight_management_report.php */