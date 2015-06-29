<?php
class Flex extends MY_Controller
{
    private $app_id="ACC0002";
    private $lang_id="en";

    function __construct()
    {
        parent::__construct();
        $this->load->model('account/flex_model');
        $this->load->model('account/order_not_in_ria_model');
        $this->load->helper(array('url','notice','object','image'));
        $this->load->library('service/context_config_service');
        $this->load->library('service/pagination_service');
        $this->load->library('service/country_service');
        $this->load->library('service/payment_gateway_service');
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    function create_zip($files = array(),$destination = '', $location = '', $overwrite = true)
    {
        if(file_exists($location.$destination) && !$overwrite)
        {
            return false;
        }
        $valid_files = array();
        if(is_array($files))
        {
            foreach($files as $file)
            {
                if(file_exists($location.$file))
                {
                    $valid_files[] = $file;
                }
            }
        }
        if(count($valid_files))
        {
            $zip = new ZipArchive();
            if($zip->open($location.$destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true)
            {
                return false;
            }
            foreach($valid_files as $file)
            {
                $zip->addFile($location.$file,$file);
            }
            $zip->close();
            return file_exists($location.$destination);
        }
        else
        {
            return false;
        }
    }

    public function sales($start_date = "", $end_date = "")
    {
        $query = 0;
        $sub_app_id = $this->_get_app_id()."01";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;
        DEFINE('FLEX_REPORT_PATH', $this->context_config_service->value_of("flex_report_path"));

        if($this->input->post("is_query") == 1)
        {
            $data["start_date"] = $this->input->post("start_date");
            $data["end_date"] = $this->input->post("end_date");
        }
        elseif($start_date && $end_date)
        {
            $data["start_date"] = $start_date;
            $data["end_date"] = $end_date;
            $query = 1;
        }
        else
        {
            $data["start_date"] = $date = date('Y-m-d', strtotime(date('Y-m-d'). ' - 10 day'));
            $data["end_date"] = date('Y-m-d');
        }
        $start_date = $date = date("Ymd", strtotime("+1 day", strtotime($data["start_date"])));
        $end_date = date("Ymd", strtotime("+1 day", strtotime($data["end_date"])));
        if($this->input->post("search") == 1 || $query)
        {
            $result_array = array();
            $i = 0;
            while (strtotime($date) <= strtotime($end_date) && $i < 30)
            {
                $result = array();
                $result["date"] = date('Y-m-d', strtotime($date));

                $file_path = FLEX_REPORT_PATH.$date."/sales/";
                $file_info = $this->get_dir_file_info($file_path);

                if(array_key_exists("zip",$file_info))
                {
                    $result["type"] = "settled";
                    foreach($file_info["zip"] as $zip_name)
                    {
                        $result["zip"][] = $zip_name;
                    }
                    @asort($result["zip"]);
                }

                if(is_file(FLEX_REPORT_PATH.$date."/sales/Exception.csv"))
                {
                    $result["type"] = "waiting_confirm";
                    $result["filepath"] = FLEX_REPORT_PATH.$date."/sales/";
                    $result["filename"] = "Exception.csv";
                    $result["process_url"] = "";
                    $result["regen_url"] = "";
                }
                elseif(!is_file(FLEX_REPORT_PATH.$date."/sales/Exception.csv"))
                {
                    $result["type"] = "gen_exception";
                }
                else
                {
                    $result["type"] = "error";
                    $result["msg"] = "Exception status on ".$date.". Please contact IT.";
                }

                $result_array[$date] = $result;
                $date = date ("Ymd", strtotime("+1 day", strtotime($date)));
                $i++;
            }
            $data["result_array"] = $result_array;
        }
        $data["notice"] = notice($lang);
        $data["status"] = 'sales';

        $this->load->view('account/flex/index_v', $data);
    }

    public function refund($start_date = "", $end_date = "")
    {
        $query = 0;
        $sub_app_id = $this->_get_app_id()."02";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;
        DEFINE('FLEX_REPORT_PATH', $this->context_config_service->value_of("flex_report_path"));

        if($this->input->post("is_query") == 1)
        {
            $data["start_date"] = $this->input->post("start_date");
            $data["end_date"] = $this->input->post("end_date");
        }
        elseif($start_date && $end_date)
        {
            $data["start_date"] = $start_date;
            $data["end_date"] = $end_date;
            $query = 1;
        }
        else
        {
            $data["start_date"] = $date = date('Y-m-d', strtotime(date('Y-m-d'). ' - 10 day'));
            $data["end_date"] = date('Y-m-d');
        }

        $start_date = $date = date("Ymd", strtotime("+1 day", strtotime($data["start_date"])));
        $end_date = date("Ymd", strtotime("+1 day", strtotime($data["end_date"])));
        if($this->input->post("search") == 1 || $query)
        {
            $result_array = array();
            $i = 0;
            while (strtotime($date) <= strtotime($end_date) && $i < 30)
            {
                $result = array();
                $result["date"] = date('Y-m-d', strtotime($date));

                $file_path = FLEX_REPORT_PATH.$date."/refund/";
                $file_info = $this->get_dir_file_info($file_path);

                if(array_key_exists("zip",$file_info))
                {
                    foreach($file_info["zip"] as $zip_name)
                    {
                        $result["zip"][] = $zip_name;
                    }
                    asort($result["zip"]);
                }


                if(is_file(FLEX_REPORT_PATH.$date."/refund/refund_invoice.csv"))
                {
                    $result["r_invoice"] = "invoice";
                    $result["r_invoice_filepath"] = FLEX_REPORT_PATH.$date."/refund/";
                    $result["r_invoice_filename"] = "refund_invoice.csv";
                }
                if(is_file(FLEX_REPORT_PATH.$date."/refund/refund_exception.csv"))
                {
                    $result["r_exception"] = "exception";
                    $result["r_exception_filepath"] = FLEX_REPORT_PATH.$date."/refund/";
                    $result["r_exception_filename"] = "refund_exception.csv";
                }
                if(is_file(FLEX_REPORT_PATH.$date."/refund/chargeback_invoice.csv"))
                {
                    $result["cb_invoice"] = "invoice";
                    $result["cb_invoice_filepath"] = FLEX_REPORT_PATH.$date."/chargeback/";
                    $result["cb_invoice_filename"] = "chargeback_invoice.csv";
                }
                if(is_file(FLEX_REPORT_PATH.$date."/refund/chargeback_exception.csv"))
                {
                    $result["cb_exception"] = "exception";
                    $result["cb_exception_filepath"] = FLEX_REPORT_PATH.$date."/chargeback/";
                    $result["cb_exception_filename"] = "chargeback_exception.csv";
                }

                $result_array[$date] = $result;
                $date = date ("Ymd", strtotime("+1 day", strtotime($date)));
                $i++;
            }
            $data["result_array"] = $result_array;
        }
        $data["notice"] = notice($lang);
        $data["status"] = 'refund';

        $this->load->view('account/flex/refund_index_v', $data);
    }

    public function pending_order_report()
    {
        $query = 0;
        $sub_app_id = $this->_get_app_id()."04";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;
        DEFINE('FLEX_REPORT_PATH', $this->context_config_service->value_of("flex_report_path"));

        if($this->input->post("is_query") == 1)
        {
            $data["ship_date"] = $this->input->post('ship_date');
            $this->get_pending_order_report($data["ship_date"]);
        }

        $this->load->view('account/flex/pending_order_report_index_v', $data);
    }

    public function order_not_in_ria_report()
    {
        $sub_app_id = $this->_get_app_id()."05";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data['lang'] = $lang;

        if ($this->input->post('is_query') == 1) {
            $where = array();
            $where['so.order_create_date >= '] = $this->input->post('start_date') . ' 00:00:00';
            $where['so.order_create_date <= '] = $this->input->post('end_date') . ' 23:59:59';

            if ($this->input->post('payment_gateway') != -1) {
                $where['sps.payment_gateway_id'] = $this->input->post('payment_gateway');
            } else {
                $where['sps.payment_gateway_id is not null'] = null;
            }
            if ($this->input->post('currency') != -1) {
                $where['so.currency_id'] = $this->input->post('currency');
            }

            $data['output'] = $this->order_not_in_ria_model->get_csv($where);
            $data['filename'] = 'order_not_in_ria_report.csv';
            $this->load->view('output_csv.php', $data);
        } else {
            $data['currencys'] = $this->country_service->get_sell_currency_list();
            $data['gateways'] = $this->payment_gateway_service->get_list(array('status' => 1), array('limit' => -1));

            $this->load->view('account/flex/order_not_in_ria_report', $data);
        }
    }

    public function fee($start_date = "", $end_date = "")
    {
        $query = 0;
        $sub_app_id = $this->_get_app_id()."03";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;
        DEFINE('FLEX_REPORT_PATH', $this->context_config_service->value_of("flex_report_path"));

        if($this->input->post("is_query") == 1)
        {
            $data["start_date"] = $this->input->post("start_date");
            $data["end_date"] = $this->input->post("end_date");
            $data["payment_gateway"] = $this->input->post("payment_gateway");
            $query = 1;
        }
        elseif($start_date && $end_date)
        {
            $data["start_date"] = $start_date;
            $data["end_date"] = $end_date;
            $query = 1;
        }
        else
        {
            $data["start_date"] = $date = date('Y-m-d', strtotime(date('Y-m-d'). ' - 10 day'));
            $data["end_date"] = date('Y-m-d');
        }
        if($query)
        {
            $this->gen_fee_invoice($data["start_date"], $data["end_date"], $data["payment_gateway"]);
        }

        $this->load->view('account/flex/fee_index_v', $data);
    }

    public function generate_zip_file($file_path, $zip_name)
    {
        $files_to_zip = array();
        if (is_dir($file_path) && ($handle = opendir($file_path)))
        {
            while (false !== ($entry = readdir($handle)))
            {
                if ($entry != "." && $entry != "..")
                {
                    $ext = PATHINFO($entry, PATHINFO_EXTENSION);

                    if($ext != "zip")
                    {
                        $file_to_delete[] = $entry;

                        if($ext != "txt")
                        {
                            $files_to_zip[] = $entry;
                        }
                    }
                }
            }
            closedir($handle);
        }
        $result = $this->create_zip($files_to_zip, $zip_name, $file_path);
        //after create the zip file, delete the original ones

        if(isset($file_to_delete))
        {
            foreach($file_to_delete as $file)
            {
                @unlink($file_path.$file);
            }
        }
    }

    public function download_file($date, $status, $filename = "")
    {
        DEFINE('FLEX_REPORT_PATH', $this->context_config_service->value_of("flex_report_path"));
        $file_path = FLEX_REPORT_PATH.$date."/".$status."/".$filename;
        $this->download($file_path, $filename);
    }

    public function download($file_path, $filename)
    {
        if(is_file($file_path))
        {
            header("Expires: 0");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header("Content-Type: application/octet-stream");
            header('Content-length: '.filesize($file_path));
            header('Content-disposition: attachment; filename="'.$filename.'"');
            readfile($file_path);
            exit;
        }
    }

    public function generate_exception($date, $start_date="", $end_date="", $folder_name="")
    {
        //for more than one time confirm, need to reverse the status from SALES TO RIA again.
        //should put it before gen the sales invoice

        $file_path = $this->context_config_service->value_of("flex_report_path").$date."/sales/";
        $flag_file = "processing.txt";

        if(!$this->is_gen_sales_invoice_running($file_path,$flag_file))
        {
            $this->flex_model->reverse_sales_invoice_status($date);
            $this->flex_model->get_sales_invoice($date, $date, $folder_name, TRUE, FALSE);

            if(!file_exists($file_path."Exception.csv"))
            {
                $this->gen_sales_invoice($date, $start_date, $end_date, $folder_name);
            }
        }
        else
        {
            unset($_SESSION["NOTICE"]);
            $_SESSION["NOTICE"] = "sales invoice is generating. Please try later";
        }

        redirect(base_url()."account/flex/sales/".$start_date."/".$end_date);
    }

    public function regenerate_exception($date, $start_date="", $end_date="", $folder_name="")
    {
        //prevent regenerate_exception function if the confirm function - gen_sales_invoice is running
        $file_path = $this->context_config_service->value_of("flex_report_path").$date."/sales/";
        $flag_file = "processing.txt";
        //unlink($file_path.$flag_file);die();
        if(!$this->is_gen_sales_invoice_running($file_path,$flag_file))
        {
            $this->flex_model->reverse_sales_invoice_status($date);
            $this->flex_model->get_sales_invoice($date, $date, $folder_name, TRUE, FALSE);

            //if no exception file, zip the files directly. just as auto confirm the exception in order to generate a zip file
            if(!file_exists($file_path."Exception.csv"))
            {
                $this->gen_sales_invoice($date, $start_date, $end_date, $folder_name);
            }
        }
        else
        {
            unset($_SESSION["NOTICE"]);
            $_SESSION["NOTICE"] = "sales invoice is generating. Please try later";
        }

        redirect(base_url()."account/flex/sales/".$start_date."/".$end_date);
    }

    public function gen_sales_invoice($date, $start_date="", $end_date="", $folder_name="")
    {
        $file_path = $this->context_config_service->value_of("flex_report_path").$date."/sales/";
        $file_info = $this->get_dir_file_info($file_path);

        //create a file to indicate if the process is undergoing
        $flag_file = "processing.txt";

        if($this->is_gen_sales_invoice_running($file_path,$flag_file))
        {
            unset($_SESSION["NOTICE"]);
            $_SESSION["NOTICE"] = "sales invoice is generating. Please try later";
        }
        else
        {
            $this->flex_model->reverse_sales_invoice_status($date);
            $result = $this->flex_model->get_sales_invoice($date, $date, $folder_name, FALSE, FALSE);
            if(file_exists($file_path))
            {
                file_put_contents($file_path.$flag_file, "processing..");
            }

            if($result)
            {
                $next_zip_file_no = count($file_info["zip"]) + 1;
                $zip_name = "sales".$date."_". $next_zip_file_no .".zip";
                //$this->flex_model->get_sales_invoice($date, $date, $folder_name, TRUE, FALSE);
                $this->generate_zip_file($file_path, $zip_name);
            }
            @unlink($file_path.$flag_file);
        }

        redirect(base_url()."account/flex/sales/".$start_date."/".$end_date);

    }

    public function gen_sales_invoice_ignore_status($start_date, $end_date, $folder_name)
    {
        $result = $this->flex_model->get_sales_invoice($start_date, $end_date, $folder_name, FALSE, TRUE);
        if($result)
        {
            $file_path = $this->context_config_service->value_of("flex_report_path").$folder_name."/sales/";
            $zip_name = "sales".$folder_name.".zip";
            $this->generate_zip_file($file_path, $zip_name);
        }
    }

    //combine the refund and chargeback function into the following function and create the zip file
    public function gen_refund_and_chargeback_invoice($date, $start_date="", $end_date="", $folder_name="")
    {
        $this->flex_model->reverse_refund_invoice_status($date);
        $this->flex_model->get_refund_invoice($date, $date, "R", $folder_name);
        $this->flex_model->get_refund_invoice($date, $date, "CB", $folder_name);

        $file_path = $this->context_config_service->value_of("flex_report_path").$date."/refund/";
        $file_info = $this->get_dir_file_info($file_path);
        $next_zip_file_no = count($file_info["zip"]) + 1;
        $zip_name = "refund_and_chargeback".$date."_". $next_zip_file_no .".zip";

        $this->generate_zip_file($file_path, $zip_name);

        redirect(base_url()."account/flex/refund/".$start_date."/".$end_date);
    }

    public function gen_refund_invoice($date, $start_date="", $end_date="", $folder_name="")
    {
        $this->flex_model->get_refund_invoice($date, $date, "R", $folder_name);
        redirect(base_url()."account/flex/refund/".$start_date."/".$end_date);
    }

    public function gen_chargeback_invoice($date, $start_date="", $end_date="", $folder_name="")
    {
        $this->flex_model->get_refund_invoice($date, $date, "CB", $folder_name);
        redirect(base_url()."account/flex/refund/".$start_date."/".$end_date);
    }

    public function gen_refund_invoice_ignore_status($start_date="", $end_date="", $folder_name="")
    {
        $this->flex_model->get_refund_invoice($start_date, $end_date, "R", $folder_name);
    }

    public function gen_chargeback_invoice_ignore_status($start_date="", $end_date="", $folder_name="")
    {
        $this->flex_model->get_refund_invoice($start_date, $end_date, "CB", $folder_name);
    }

    public function gen_fee_invoice($start_date, $end_date, $gateway_id="")
    {
        $this->gen_so_fee_invoice($start_date, $end_date, $gateway_id);
        $this->gen_gateway_fee_invoice($start_date, $end_date, $gateway_id);
        $this->gen_rolling_reserve_report($start_date, $end_date, $gateway_id);

        DEFINE('FLEX_REPORT_PATH', $this->context_config_service->value_of("flex_report_path"));
        $zip_name = "fee.zip";
        if(is_file(FLEX_REPORT_PATH."so_fee.csv"))
        {
            $file_to_zip[] = "so_fee.csv";
        }
        if(is_file(FLEX_REPORT_PATH."gateway_fee.csv"))
        {
            $file_to_zip[] = "gateway_fee.csv";
        }
        if(is_file(FLEX_REPORT_PATH . Flex_service::ROLLING_RESERVE_REPORT_FILE_NAME))
        {
            $file_to_zip[] = Flex_service::ROLLING_RESERVE_REPORT_FILE_NAME;
        }
        if($file_to_zip)
        {
            @unlink(FLEX_REPORT_PATH.$zip_name);
            $this->create_zip($file_to_zip, $zip_name, FLEX_REPORT_PATH);
            $this->download(FLEX_REPORT_PATH.$zip_name, $zip_name);
        }
    }

    public function gen_so_fee_invoice($start_date, $end_date, $gateway_id="")
    {
        return $this->flex_model->get_so_fee_invoice($start_date, $end_date, $gateway_id);
    }

    public function gen_gateway_fee_invoice($start_date, $end_date, $gateway_id="")
    {
        return $this->flex_model->get_gateway_fee_invoice($start_date, $end_date, $gateway_id);
    }

    public function gen_rolling_reserve_report($start_date, $end_date, $gateway_id="")
    {
        return $this->flex_model->get_rolling_reserve_report($start_date, $end_date, $gateway_id);
    }

    public function get_pending_order_report($ship_date='')
    {
        $this->flex_model->get_pending_order_report($ship_date);
        DEFINE('FLEX_REPORT_PATH', $this->context_config_service->value_of("flex_report_path"));
        $this->download(FLEX_REPORT_PATH."pending_order_report.csv", "pending_order_report.csv");
    }

    public function get_dir_file_info($file_path)
    {
        $file_info = array();

        $file_info['other'] = array();
        if(is_dir($file_path) && ($handle = opendir($file_path)))
        {
            while(false !== ($entity = readdir($handle)))
            {
                if($entity != "." && $entity != "..")
                {
                    $ext = pathinfo($entity, PATHINFO_EXTENSION);
                    if($ext == "zip")
                    {
                        $file_info['zip'][] = $entity;
                    }
                    else
                    {
                        $file_info['other'][] = $entity;
                    }
                }
            }
            closedir($handle);
        }

        return $file_info;
    }

    //this function is to prevent regenerate_exception from executing when the gen_sales_invoice is running.
    public function is_gen_sales_invoice_running($file_path, $flag_file)
    {
        $file_info = $this->get_dir_file_info($file_path);
        if(in_array($flag_file, $file_info["other"]))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function platfrom_order_insert_interface_flex_ria($gateway_id, $so_no_list)
    {
        if (empty($gateway_id) || empty($so_no_list)) {
            $_SESSION['NOTICE'] = 'so_not_found or flex_batch_id_not_found';
            return false;
        }

        if ($this->flex_model->platfrom_order_insert_interface_flex_ria($gateway_id, $so_no_list)) {
            $_SESSION['NOTICE'] = 'add to list successed!';
        } else {
            $_SESSION['NOTICE'] = 'add to list failure!';
        }
    }

    public function platfrom_order_insert_flex_ria($gateway_id = '', $so_no = '')
    {
        if (empty($gateway_id) || empty($so_no)) {
            $_SESSION['NOTICE'] = 'so_not_found or flex_batch_id_not_found';
            return false;
        }

        if ($this->flex_model->platfrom_order_insert_flex_ria($gateway_id, $so_no)) {
            $_SESSION['NOTICE'] = 'confirm successed!';
        } else {
            $_SESSION['NOTICE'] = 'confirm failed!';
        }

        redirect(base_url()."account/flex/get_rakuten_shipped_order");
    }

    public function get_rakuten_shipped_order($page = 'search')
    {
        $sub_app_id = $this->_get_app_id()."06";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;


        if($platform_order_id = $this->input->post("platform_order_id"))
        {
            $data['obj_list'] = $this->flex_model->get_rakuten_shipped_order($platform_order_id);
        }

        if ($page == 'search') {
            $this->load->view('account/flex/search_rakuten_shipped_order_v', $data);
        } elseif ($page == 'list') {
            $data['obj_list'] = $this->flex_model->get_rakuten_shipped_order_from_interface();
            $this->load->view('account/flex/list_rakuten_shipped_order_v', $data);
        }

        if ($this->input->post('add_to_list')) {
            if ($checked = $this->input->post('check')) {
                $this->platfrom_order_insert_interface_flex_ria('rakuten', $checked);
            }
        }

        // $this->load->view('account/flex/search_rakuten_shipped_order_v', $data);
    }

    public function get_rakuten_shipped_order_list()
    {

        if ($this->input->post('comfirm') == 'approve') {
            if ($checked = $this->input->post('check')) {
                $this->platfrom_order_insert_flex_ria_a('rakuten', $checked);
            }
        } elseif ($this->input->post('comfirm') == 'cancel') {
            if ($checked = $this->input->post('check')) {
                $this->platform_order_delete_interface_flex_ria('rakuten', $checked);
            }
        }

        $data['obj_list'] = $this->flex_model->get_rakuten_shipped_order_from_interface();
        $this->load->view('account/flex/order_to_comfirm_v', $data);
    }

    public function platform_order_delete_interface_flex_ria($gateway_id='', $so_no_list)
    {
        if (empty($gateway_id) || empty($so_no_list)) {
            $_SESSION['NOTICE'] = 'so_not_found or flex_batch_id_not_found';
            return false;
        }

        $this->flex_model->platform_order_delete_interface_flex_ria($gateway_id, $so_no_list);
    }

    public function platfrom_order_insert_flex_ria_a($gateway_id = '', $so_no = '')
    {
        if (empty($gateway_id) || empty($so_no)) {
            $_SESSION['NOTICE'] = 'so_not_found or flex_batch_id_not_found';
            return false;
        }

        if (is_array($so_no)) {
            foreach ($so_no as $so) {

                if ($this->flex_model->platfrom_order_insert_flex_ria($gateway_id, $so)) {
                    $_SESSION['NOTICE'] = 'confirm successed!';
                } else {
                    $_SESSION['NOTICE'] = 'confirm failed!';
                }
            }
        }

    }
}
/* End of file flex.php */
/* Location: ./app/controllers/flex.php */
