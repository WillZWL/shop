<?php

interface Import_info_model_interface
{
    public function get_function_name();
}

abstract class Import_info_model extends CI_Model implements Import_info_model_interface
{
    const DEFAULT_IMPORT_PATH = "/var/data/valuebasket.com/import";
    const PROCESSED_FOLDER = "processed";
    const PROCESSED_WITH_ERROR_FOLDER = "processed_with_error";
    const RE_PROCESS_FOLDER = "generated_for_re_process";
    const DEFAULT_INPUT_FILE_FIELD_NAME = "process_file";

    const ALL_DATA_PASS = 1;
    const NO_ERROR = 0;
    const NO_DATA_IN_THE_FILE = 2;
    const ERROR_DURING_IMPORT_TO_INTERFACE = 3;
    const HAS_ERROR_IN_DATA = 4;
    const CANNOT_CREATE_BATCH = 5;

    const BATCH_STATUS_NEW = 0;
    const BATCH_STATUS_ALL_SUCCESS = 1;
    const BATCH_STATUS_PROCESS_WITH_ERROR = 2;
    const BATCH_STATUS_ERROR_AND_WAIT_USER = 3;
    const BATCH_STATUS_CANCELLED = 4;

    public $import_service;
    private $_number_of_records_imported;

    public function __construct()
    {
        parent::__construct();
        $service_name = "Import_" . $this->get_function_name() . "_service";
        include_once(APPPATH . "libraries/service/" . ucfirst($service_name) . ".php");
        $this->set_import_service(new $service_name);
    }

    public function prepare_reprocess($batch_id)
    {
        $className = "Import_" . $this->get_function_name() . "_dto";
        include_once(APPPATH . "libraries/dto/" . strtolower($className) . ".php");
        $where = array("batch_id" => $batch_id);
        $batch_obj_list = $this->get_import_service()->get_import_interface_info_dao()->get_list($where, array("limit" => -1));

        $data_as_csv = array();
        $number_of_successes = 0;
        $number_of_failures = 0;
        $i = 0;
        foreach ($batch_obj_list as $obj) {
            $new_dispatch_dto = new $className;
            set_value($new_dispatch_dto, $obj);
            if ($obj->get_status() == "F") {
                $new_dispatch_dto->set_has_error(TRUE);
                $new_dispatch_dto->set_error_message($obj->get_failed_reason());
                $number_of_failures++;
            } elseif ($obj->get_status() == "S")
                $number_of_successes++;
            $i++;
            array_push($data_as_csv, $new_dispatch_dto);
        }
        return array("number_of_successes" => $number_of_successes
        , "data_from_csv" => (object)$data_as_csv
        , "number_of_records_in_batch" => $i);
    }

    public function combine_validation_result($validate_result, $data_from_csv)
    {
        $data_from_csv = (array)$data_from_csv;
        for ($i = 0; $i < sizeof($validate_result); $i++) {
            $data_from_csv[$validate_result[$i]["row"]]->set_has_error(true);
            $data_from_csv[$validate_result[$i]["row"]]->set_column($validate_result[$i]["column"]);
            $data_from_csv[$validate_result[$i]["row"]]->set_error_code($validate_result[$i]["error_code"]);
        }
        return $data_from_csv;
    }

    protected function import_data_process_common($upload_file_field_name = null)
    {
        $this_year = date('Y');
        $this_month = date('M');
        if ($upload_file_field_name == null)
            $upload_file_field_name = Import_info_model::DEFAULT_INPUT_FILE_FIELD_NAME;

        $upload_file = $_FILES[$upload_file_field_name]["name"];
        $upload_path_with_function_name = Import_info_model::DEFAULT_IMPORT_PATH . "/" . $this->get_function_name();
        $config['upload_path'] = $upload_path_with_function_name . "/" . $this_year . "/" . $this_month;
        $config['allowed_types'] = 'csv';
//firefox will put "" to the mime type, so remove it
        $_FILES[$upload_file_field_name]["type"] = trim($_FILES[$upload_file_field_name]["type"], '"');
        $config['file_name'] = date('Ymd_His');
        $config['overwrite'] = FALSE;
        $config['is_image'] = FALSE;
        $this->load->library('upload', $config);

// check folder exists
        $this->create_folder($upload_path_with_function_name, $this_year, $this_month);

        if (!empty($upload_file)) {
            if ($this->upload->do_upload($upload_file_field_name)) {
                $res = $this->upload->data();
                $error_trans_id = array();
                $batch_id = 0;

                $interface_import_result = $this->import_data_into_interface_common($config['upload_path'], $config['file_name'] . $this->upload->get_extension($upload_file));
                $batch_id = $interface_import_result["batch_id"];

                if (($interface_import_result["number_of_successes"] > 0)
                    /*&& ($interface_import_result["number_of_successes"] == $interface_import_result["number_of_rows_in_csv"])*/
                ) {
// can process all
                    $has_error = $this->get_import_service()->process_data($batch_id);
                    $extension = $this->upload->get_extension($upload_file);
                    $moveFileName = $config['upload_path'] . "/" . $config['file_name'] . $extension;
                    if ($has_error) {
                        $newFileName = $config['upload_path'] . "/" . self::PROCESSED_WITH_ERROR_FOLDER . "/" . $config['file_name'] . $extension;
                        $status = self::BATCH_STATUS_PROCESS_WITH_ERROR;
                    } else {
                        $newFileName = $config['upload_path'] . "/" . self::PROCESSED_FOLDER . "/" . $config['file_name'] . $extension;
                        $status = self::BATCH_STATUS_ALL_SUCCESS;
                    }
                    if (copy($moveFileName, $newFileName)) {
                        unlink($moveFileName);
                    }
                    $this->get_import_service()->update_batch_status($batch_id, $status);
                    $interface_import_result["batch_status"] = $status;
                } /*
                else if ($interface_import_result["error_code"] != self::NO_DATA_IN_THE_FILE)
                {
                    $this->get_import_service()->update_batch_status($batch_id, self::BATCH_STATUS_ERROR_AND_WAIT_USER);
                    $interface_import_result["batch_status"] = self::BATCH_STATUS_ERROR_AND_WAIT_USER;
//don't process, return the result to users
//$interface_import_result["validate_result"]
//$interface_import_result["import_data"]
//$interface_import_result["data_from_csv"]
                }
*/
                else {
                    $this->get_import_service()->update_batch_status($batch_id, self::BATCH_STATUS_CANCELLED);
                    $interface_import_result["batch_status"] = self::BATCH_STATUS_CANCELLED;
                }
            } else {
                $_SESSION["NOTICE"] = $this->upload->display_errors();
            }
        } else {
            $_SESSION["NOTICE"] = "No file is uploaded";
        }

//prepare data for re-process
        /*
                if (isset($interface_import_result["batch_status"])
                    && ($interface_import_result["batch_status"] == self::BATCH_STATUS_PROCESS_WITH_ERROR))
                {
                    $result = $this->prepare_reprocess($batch_id);
                    $interface_import_result["number_of_successes"] = $result["number_of_successes"];
                    $interface_import_result["data_from_csv"] = $result["data_from_csv"];
                }
        */
        return $interface_import_result;
    }

    protected function create_folder($upload_path, $this_year, $this_month)
    {
        if (!file_exists($upload_path . "/" . $this_year)) {
            mkdir($upload_path . "/" . $this_year, 0775, true);
        }
        if (!file_exists($upload_path . "/" . $this_year . "/" . $this_month)) {
            mkdir($upload_path . "/" . $this_year . "/" . $this_month, 0775);
            mkdir($upload_path . "/" . $this_year . "/" . $this_month . "/" . Import_info_model::PROCESSED_FOLDER, 0775);
            mkdir($upload_path . "/" . $this_year . "/" . $this_month . "/" . Import_info_model::PROCESSED_WITH_ERROR_FOLDER, 0775);
//          mkdir($upload_path . "/" . $this_year . "/" . $this_month . "/" . Import_info_model::RE_PROCESS_FOLDER, 0775);
        }
    }

    protected function import_data_into_interface_common($upload_path, $csv_filename)
    {
//verify and validate the data
        $error_code = self::NO_ERROR;
        $batch_id = 0;
        $number_of_successes = 0;
        $error_message = "";
        $result = true;

        $uploadedFile = $upload_path . "/" . $csv_filename;
        $data = $this->get_import_service()->get_file_data($uploadedFile);
        $number_of_rows_in_csv = sizeof((array)$data);

        if (sizeof((array)$data) == 0) {
            $error_code = self::NO_DATA_IN_THE_FILE;
            $error_message = "Upload File is empty";
        } else {
            $data_result = $this->get_import_service()->validate_import_data($data);
        }

        if ((sizeof($data_result) == 0) && ($error_code == self::NO_ERROR)) {
//create a new batch
            if ($batch_id = $this->get_import_service()->create_new_batch($this->get_function_name(), 0, $csv_filename)) {
//insert all record to interface table
                $import_result = $this->import_record($data, $batch_id);
                if ($import_result["result"]) {
                    $number_of_successes = $import_result["number_of_successes"];
                } else {
                    $error_code = self::ERROR_DURING_IMPORT_TO_INTERFACE;
                    $error_message .= "\n " . __METHOD__ . " " . __LINE__ . " Error during Import into interface \n" . $import_result["error_message"];
                }
            } else {
                $error_code = self::CANNOT_CREATE_BATCH;
                $error_message .= "\n create new batch error.";
            }
        }

        return array("batch_id" => $batch_id
        , "error_message" => $error_message
        , "error_code" => $error_code
        , "number_of_successes" => $number_of_successes
        , "number_of_rows_in_csv" => $number_of_rows_in_csv
        , "validate_result" => $data_result
        , "is_fail_validation" => sizeof($data_result) ? TRUE : FALSE
        , "data_from_csv" => $data
        , "import_data" => $import_result["import_data"]);
    }

    public function get_import_service()
    {
        return $this->import_service;
    }

    public function set_import_service($srv)
    {
        $this->import_service = $srv;
    }

    protected function import_record($data, $batch_id)
    {
        $error_message = "";
        $result = TRUE;
        $number_of_success = 0;

        $casted_data = (array)$data;
        if ($data) {
//          foreach ($data as $info_obj)
            for ($i = 0; $i < sizeof($casted_data); $i++) {
                $dao_get = $this->get_import_service()->get_import_interface_info_dao()->get();
                $vo_obj = clone $dao_get;
                set_value($vo_obj, $casted_data[$i]);
                $vo_obj->set_batch_id($batch_id);
                $vo_obj->set_status('R');
                if (!$this->get_import_service()->get_import_interface_info_dao()->insert($vo_obj)) {
                    $error_message = $this->get_import_service()->get_import_interface_info_dao()->db->_error_message();
                    $result = FALSE;
                } else {
                    $casted_data[$i]->set_trans_id($vo_obj->get_trans_id());
                    $number_of_success++;
                }
            }
        } else {
            $result = FALSE;
        }
        return array("result" => $result, "error_message" => $error_message, "number_of_successes" => $number_of_success, "import_data" => (object)$casted_data);
    }
}


