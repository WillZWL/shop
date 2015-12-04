<?php

class GatewayReport extends MY_Controller
{
    private $appId = "ACC0001";
    private $lang_id = "en";
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'notice', 'object', 'image'));
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "01";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->lang_id . ".php");
        $_SESSION["LISTPAGE"] = base_url() . "account/GatewayReport/?" . $_SERVER['QUERY_STRING'];
        $data["lang"] = $lang;
        if ($this->input->get("search")) {
            $where = [];
            $option = [];
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
            if (empty($sort)) {
                $sort = "id";
            }
            if (empty($order)) {
                $order = "desc";
            }
            $option["orderby"] = $sort . " " . $order;
            if ($this->input->get("search")) {
                $data["objlist"] = $this->sc['Flex']->getDao('FlexBatch')->getList($where, $option);
                $data["total"] = $this->sc['Flex']->getDao('FlexBatch')->getNumRows($where);
            }
            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $pconfig['total_rows'] = $data['total'];
            $this->sc['Pagination']->initialize($pconfig);
        }
        $this->load->view('account/gateway_report/index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function upload($pmgw = "")
    {
        $sub_app_id = $this->getAppId() . "03";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->lang_id . ".php");
        $_SESSION["LISTPAGE"] = base_url() . "account/GatewayReport/upload?" . $_SERVER['QUERY_STRING'];
        $data["lang"] = $lang;
        if ($pmgw) {
            if ($this->input->post('posted')) {
                unset($_SESSION["NOTICE"]);
                $process_result = $this->processFolder($pmgw);
                if ($process_result == FALSE) {
                    $_SESSION["NOTICE"] = "Error occurs, please visit Edit Failed Record for details.";
                } else {
                    $_SESSION["NOTICE"] = "All success.";
                }
                $data["notice"] = notice($lang);
                redirect(base_url() . "account/GatewayReport/upload");
            }
            $file_from = $this->sc['ContextConfig']->valueOf("flex_ftp_location") . "pmgw/" . $pmgw . "/";
            $file_arr = scandir($file_from);
            $file_list = [];
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

    public function processFolder($pmgw)
    {
        $file_from = $this->sc['ContextConfig']->valueOf("flex_ftp_location") . "pmgw/" . $pmgw . "/";
        $file_to = $this->sc['ContextConfig']->valueOf("flex_pmgw_report_loaction") . $pmgw . "/";
        if (is_dir($file_from)) {
            $file_arr = scandir($file_from);
            $result = TRUE;
            foreach ($file_arr AS $key => $old_name) {
                $batch_result = TRUE;
                if (!in_array($old_name, array(".", "..", ".DS_Store"))) {
                    $new_name = pathinfo(trim($old_name), PATHINFO_FILENAME) . "_" . date("YmdHis") . "." . pathinfo(trim($old_name), PATHINFO_EXTENSION);
                    $extension = pathinfo(trim($old_name), PATHINFO_EXTENSION);
                    if (in_array($extension, array('txt', 'csv'))) {
                        if (copy($file_from . $old_name, $file_to . $new_name)) {
                            @unlink($file_from . $old_name);
                            $pmgw = $this->underscore2camelcase($pmgw);
                            list($batch_result, $batch_id_list[]) = $this->sc['Flex']->processReport($pmgw, $new_name);
                            if ($batch_result == FALSE && $result == TRUE) {
                                $result = FALSE;
                            }
                        } else {
                            $result = FALSE;
                        }
                    }
                }
            }
            if ($batch_id_list) {
                if ($data = $this->generateFeedbackReport($batch_id_list)) {
                    $data['filename'] = 'flex_batch_' . implode('_', $batch_id_list) . '.zip';
                    $this->load->view('output_zip.php', $data);
                }
            }
            return $result;
        } else {
            return FALSE;
        }
        return FALSE;
    }

    public function generateFeedbackReport(array $batch_id_list)
    {
        if (empty($batch_id_list)) {
            return false;
        }
        if (is_array($batch_id_list)) {
            $batch_id_collect = '(' . implode(',', $batch_id_list) . ')';
            $where = array("`flex_batch_id` IN {$batch_id_collect}" => null);
            $option = array('limit' => -1);
            return $this->sc['Flex']->generateFeedbackReport($where, $option);
        }
    }

    public function downloadBatch($flex_batch_id)
    {
        DEFINE('FLEX_PATH', $this->sc['ContextConfig']->valueOf("flex_path"));
        if ($batch_obj = $this->sc['Flex']->getDao('FlexBatch')->get(["id" => $flex_batch_id])) {
            $this->downloadFile(FLEX_PATH . "/pmgw_report/" . $batch_obj->getGatewayId() . "/complete/", $batch_obj->getFileName());
        }
    }

    public function downloadFile($file_path, $filename)
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

    public function downloadFeedbackReport($flex_batch_id)
    {
        $batch_id_list = array($flex_batch_id);
        $data = $this->generateFeedbackReport($batch_id_list);
        $data['filename'] = 'flex_batch_' . implode('_', $batch_id_list) . '.zip';
        $this->load->view('output_zip.php', $data);
    }

    public function underscore2camelcase($name)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
    }
}



