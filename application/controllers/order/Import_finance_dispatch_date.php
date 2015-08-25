<?php

class Import_finance_dispatch_date extends MY_Controller
{
    private $appId = "ORD0026";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/import_finance_dispatch_model');
        $this->load->helper(array('url', 'notice', 'object', 'image'));
        $this->load->library('service/context_config_service');
    }

    public function index($batch_id = null)
    {
        $sub_app_id = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        if ($this->input->post('posted')) {
            $_POST["posted"] = 0;
            $import_result = $this->import_finance_dispatch_model->import_data_process();

            $data["batch_id"] = $import_result["batch_id"];
            $data["number_of_successes"] = $import_result["number_of_successes"];
            $data["number_of_failures"] = $import_result["number_of_rows_in_csv"] - $import_result["number_of_successes"];
            $data["is_fail_validation"] = $import_result["is_fail_validation"];

//          var_dump($import_result);
            if (($data["error_code"] == Import_info_model::NO_ERROR)
                && (($import_result["batch_status"] != Import_info_model::BATCH_STATUS_PROCESS_WITH_ERROR))
                && ($import_result["number_of_rows_in_csv"] == $import_result["number_of_successes"])
            ) {
//all pass
                $link = base_url() . "order/import_finance_dispatch_date/index/" . $data["batch_id"] . "?success=1";
                Redirect($link);
            } else if ($data["is_fail_validation"]) {
//cannot pass validation, no record will be in interface table
//              $data["validate_result"] = $import_result["validate_result"];
//              $data["data_from_csv"] = $import_result["data_from_csv"];
                $data["error_message"] = "Fail data validation, no records has been processed";
                $data["combined_validate_result"] = $this->import_finance_dispatch_model->combine_validation_result($import_result["validate_result"], $import_result["data_from_csv"]);
            } else if ($import_result["batch_status"] == Import_info_model::BATCH_STATUS_PROCESS_WITH_ERROR) {
                $link = base_url() . "order/import_finance_dispatch_date/index/" . $data["batch_id"];
                Redirect($link);
            }
        } elseif ($batch_id) {
            $batch_result = $this->import_finance_dispatch_model->prepare_reprocess($batch_id);
//          var_dump($batch_result);
            $data["batch_id"] = $batch_id;
            $data["number_of_successes"] = $batch_result["number_of_successes"];
            $data["number_of_failures"] = $batch_result["number_of_records_in_batch"] - $batch_result["number_of_successes"];
            $data["combined_validate_result"] = $batch_result["data_from_csv"];
            if ($this->input->get("success"))
                $data["show_success"] = true;
        }

        $this->load->view('order/import_finance_dispatch_date/index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function reprocess($batch_id)
    {
        if ($this->input->post('reProcessCheck')) {
            $reprocessList = $this->input->post('reProcessCheck');
            foreach ($reprocessList as $transId) {
                $so_nos = $this->input->post("reProcessSono");
                $so_no = $so_nos[$transId];
                $dispatch_dates = $this->input->post("reProcessDispatch");
                $dispatch_date = $dispatch_dates[$transId];

                $this->import_finance_dispatch_model->reprocess($transId, $so_no, $dispatch_date);
            }
            $this->import_finance_dispatch_model->get_import_service()->process_data($batch_id, true);
        }
//      var_dump($_POST);
        $link = base_url() . "order/import_finance_dispatch_date/index/" . $batch_id;
        Redirect($link);
    }
}
