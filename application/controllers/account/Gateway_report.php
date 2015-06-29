<?php

class Gateway_report extends MY_Controller
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

    public function index()
    {
        $sub_app_id = $this->_get_app_id() . "01";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $_SESSION["LISTPAGE"] = base_url() . "account/gateway_report/?" . $_SERVER['QUERY_STRING'];
        $data["lang"] = $lang;

        if ($this->input->get("search")) {
            $where = array();
            $option = array();

            $submit_search = 0;

            if ($this->input->get("batch_id") != "") {
                $where["id"] = $this->input->get("batch_id");
            }

            if ($this->input->get("gateway_id") != "") {
                $where["gateway_id"] = $this->input->get("gateway_id");
                $submit_search = 1;
            }

            if ($this->input->get("filename") != "") {
                $where["filename LIKE "] = "%" . $this->input->get("filename") . "%";
                $submit_search = 1;
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $limit = '20';

            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $option["limit"] = $pconfig['per_page'] = $limit;
            if ($option["limit"]) {
                $option["offset"] = $this->input->get("per_page");
            }

            if (empty($sort))
                $sort = "id";

            if (empty($order))
                $order = "desc";

            $option["orderby"] = $sort . " " . $order;

            if ($this->input->get("search")) {
                $data["objlist"] = $this->flex_model->get_flex_batch_list($where, $option);
                $data["total"] = $this->flex_model->get_flex_batch_num_rows($where);
            }

            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->initialize($pconfig);

        }
        $this->load->view('account/gateway_report/index_v', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function upload($pmgw = "")
    {
        $sub_app_id = $this->_get_app_id() . "03";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $_SESSION["LISTPAGE"] = base_url() . "account/gateway_report/upload?" . $_SERVER['QUERY_STRING'];
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
            $file_from = $this->context_config_service->value_of("flex_ftp_location") . "pmgw/" . $pmgw . "/";
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
        $this->load->view('account/gateway_report/upload_v', $data);
    }

    public function process_folder($pmgw)
    {
        $file_from = $this->context_config_service->value_of("flex_ftp_location") . "pmgw/" . $pmgw . "/";
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
                        list($batch_result, $batch_id_list[]) = $this->process_report($pmgw, $new_name);

                        if ($batch_result == FALSE && $result == TRUE) {
                            $result = FALSE;
                        }
                    } else {
                        $result = FALSE;
                        // failed to copy $file
                    }
                }
            }

            //generate feedback report base on $batch_id_list;
            if ($data = $this->generate_feedback_report($batch_id_list)) {
                $data['filename'] = 'flex_batch_' . implode('_', $batch_id_list) . '.zip';
                $this->load->view('output_zip.php', $data);
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

    public function generate_feedback_report(array $batch_id_list)
    {
        if (empty($batch_id_list)) {
            return false;
        }

        if (is_array($batch_id_list)) {
            $batch_id_collect = '(' . implode(',', $batch_id_list) . ')';
            $where = array("`flex_batch_id` IN {$batch_id_collect}" => null);
            $option = array('limit' => -1);

            return $this->flex_model->generate_feedback_report($where, $option);
        }
    }

    public function get_contact_email()
    {
        return 'oswald-alert@eservicesgroup.com';
    }

    public function download_batch($flex_batch_id)
    {
        DEFINE('FLEX_PATH', $this->context_config_service->value_of("flex_path"));
        if ($batch_obj = $this->flex_model->get_flex_batch_obj(array("id" => $flex_batch_id))) {
            $this->download_file(FLEX_PATH . "/pmgw_report/" . $batch_obj->get_gateway_id() . "/complete/", $batch_obj->get_filename());
        } else {
            //consult IT
        }
    }

    public function download_file($file_path, $filename)
    {
        if (is_file($file_path . $filename)) {
            header("Expires: 0");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header("Content-Type: application/octet-stream");
            header('Content-length: ' . filesize($file_path . $filename));
            header('Content-disposition: attachment; filename="' . $filename . '"');
            readfile($file_path . $filename);
            exit;
        }
    }

    public function download_feedback_report($flex_batch_id)
    {
        $batch_id_list = array($flex_batch_id);
        $data = $this->generate_feedback_report($batch_id_list);
        $data['filename'] = 'flex_batch_' . implode('_', $batch_id_list) . '.zip';
        $this->load->view('output_zip.php', $data);
    }
}

/* End of file gateway_report.php */
/* Location: ./app/controllers/gateway_report.php */
