<?php

class Payment_gateway extends MY_Controller
{
    private $app_id = "ACC0001";
    private $lang_id = "en";

    function __construct()
    {
        parent::__construct();
        $this->load->model('account/flex_model');
        $this->load->helper(array('url', 'notice', 'object', 'image'));
        $this->load->library('service/context_config_service');
        $this->load->library('service/pagination_service');
    }

    public function index($pmgw = "")
    {
        $sub_app_id = $this->_get_app_id() . "01";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        if ($pmgw) {
            if ($_POST["posted"]) {
                unset($_SESSION["NOTICE"]);
                $process_result = $this->process_folder($pmgw);
                if ($process_result == FALSE) {
                    $_SESSION["NOTICE"] = "Error occurs, please visit Edit Failed Record for details.";
                } else {
                    $_SESSION["NOTICE"] = "All success.";
                }

                $data["notice"] = notice($lang);
            }
            $file_from = $this->context_config_service->value_of("flex_ftp_loaction") . "pmgw/" . $pmgw . "/";
            $file_arr = scandir($file_from);

            $file_list = array();
            foreach ($file_arr AS $key => $old_name) {
                if (!in_array($old_name, array(".", ".."))) {
                    $file_list[] = $old_name;
                }
            }
            $data["pmgw"] = $pmgw;
            $data["file_list"] = $file_list;
        }

        $this->load->view('account/payment_gateway/index_v', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    /*
    finance want to use upload ftp instead
    public function upload()
    {
        $newfiles = array();
        foreach($_FILES['csv_files']['tmp_name'] AS $key=>$tmp_name)
        {
            if(is_uploaded_file($tmp_name))
            {
                $file_name = $_FILES['csv_file']['name'];
                $file_dir = '/var/data/wms.eservicesgroup.net/pick_list/import';
                if(move_uploaded_file($_FILES['csv_file']['tmp_name'], $file_dir.'/'.$file_name))
                {
                    return $file_name;
                }
            }
        }
        $this->index();
    }
    */

    public function process_folder($pmgw)
    {
        $file_from = $this->context_config_service->value_of("flex_ftp_loaction") . "pmgw/" . $pmgw . "/";
        $file_to = $this->context_config_service->value_of("flex_pmgw_report_loaction") . $pmgw . "/";
        if (is_dir($file_from)) {
            $file_arr = scandir($file_from);
            $result = TRUE;
            foreach ($file_arr AS $key => $old_name) {
                $batch_result = TRUE;
                if (!in_array($old_name, array(".", ".."))) {
                    $new_name = pathinfo(trim($old_name), PATHINFO_FILENAME) . "_" . date("YmdHis") . "." . pathinfo(trim($old_name), PATHINFO_EXTENSION);
                    if (copy($file_from . $old_name, $file_to . $new_name)) {
                        unlink($file_from . $old_name);
                        $batch_result = $this->process_report($pmgw, $new_name);
                        if ($batch_result == FALSE && $result == TRUE) {
                            $result = FALSE;
                        }
                    } else {
                        $result = FALSE;
                        // failed to copy $file
                    }
                }

            }
            return $result;
        } else {
            return FALSE;
            //invalid ftp file path
        }

        return FALSE;
    }

    public function process_report($pmgw, $filename)
    {
        return $this->flex_model->process_report($pmgw, $filename);
    }

    public function pmgw_failed_record($trans_id = "")
    {
        $sub_app_id = $this->_get_app_id() . "02";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $where = array();
        $option = array();

        $limit = '20';

        $pconfig['base_url'] = base_url() . "account/payment_gateway/pmgw_failed_record";
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "trans_id";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = $sort . " " . $order;
        $where = array("batch_status" => "F");
        $data["objlist"] = $this->flex_model->get_pmgw_failed_record($where, $option);
        $data["total"] = $this->flex_model->get_num_pmgw_failed_record($where);

        $data["cmd"] = ($trans_id == "") ? "" : "edit";
        $data["trans_id"] = $trans_id;
        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $this->load->view('account/payment_gateway/pmgw_failed_record_v', $data);
    }

    public function remove_failed_record($trans_id)
    {
        $obj = $this->flex_model->get_interface_flex_pmgw_transactions(array("trans_id" => $trans_id));
        if ($obj) {
            $obj->set_batch_status("I");
            $obj->set_failed_reason("Removed record");
            $this->flex_model->update_interface_flex_pmgw_transactions($obj);
        }
        redirect("account/payment_gateway/pmgw_failed_record");
    }

    public function edit_failed_record($trans_id)
    {
        $obj = $this->flex_model->get_interface_flex_pmgw_transactions(array("trans_id" => $trans_id));
        if ($obj) {
            $obj->set_txn_id($_POST["txn_id"]);
            $obj->set_so_no($_POST["so_no"]);
            if ($this->flex_model->update_interface_flex_pmgw_transactions($obj)) {
                $this->flex_model->reprocess_record($obj);
            }
        }
        redirect("account/payment_gateway/pmgw_failed_record");
    }

    public function get_contact_email()
    {
        return 'oswald-alert@eservicesgroup.com';
    }
}

/* End of file payment_gateway.php */
/* Location: ./app/controllers/payment_gateway.php */
