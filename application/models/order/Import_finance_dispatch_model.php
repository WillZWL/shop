<?php
include_once(APPPATH . "models/order/Import_info_model.php");
include_once(APPPATH . "libraries/service/Import_finance_dispatch_service.php");

class Import_finance_dispatch_model extends Import_info_model
{
    public function __construct()
    {
        parent::__construct();
//      $this->load->library('service/context_config_service');
    }

    public function import_data_process($upload_file_field_name = null)
    {
        return parent::import_data_process_common($upload_file_field_name);
    }

    public function reprocess($trans_id, $so_no, $dispatch_date)
    {
        $className = "Import_" . $this->get_function_name() . "_dto";
        include_once(APPPATH . "libraries/dto/" . strtolower($className) . ".php");

        $new_dispatch_dto = new $className;
        $new_dispatch_dto->set_so_no($so_no);
        $new_dispatch_dto->set_finance_dispatch_date($dispatch_date);
        $data = array($new_dispatch_dto);
        $result = $this->get_import_service()->validate_import_data($data);

        if (sizeof($result) == 0) {
            $this->update_interface($trans_id, $so_no, $dispatch_date);
        }
    }

    public function get_function_name()
    {
        return Import_finance_dispatch_service::FUNCTION_NAME;
    }

    public function update_interface($trans_id, $so_no, $dispatch_date)
    {
        $interface_obj = $this->get_import_service()->get_import_interface_info_dao()->get(array("trans_id" => $trans_id));
        $interface_obj->set_so_no($so_no);
        $interface_obj->set_finance_dispatch_date($dispatch_date);
        $interface_obj->set_status("R");
        $this->get_import_service()->get_import_interface_info_dao()->update($interface_obj);
    }

    public function combine_validation_result($validate_result, $data_from_csv)
    {
        $data_from_csv = (array)$data_from_csv;
        for ($i = 0; $i < sizeof($validate_result); $i++) {
            $data_from_csv[$validate_result[$i]["row"]]->set_has_error(true);
            $data_from_csv[$validate_result[$i]["row"]]->set_column($validate_result[$i]["column"]);
            $data_from_csv[$validate_result[$i]["row"]]->set_error_code($validate_result[$i]["error_code"]);
            $error_message = $data_from_csv[$validate_result[$i]["row"]]->get_error_message();
            $error_message = ($error_message) ? $error_message . "<br>" : "";
            if ($validate_result[$i]["column"] == 0) {
                if ($validate_result[$i]["error_code"] == Import_info_service::VALUE_IS_EMPTY)
                    $error_message .= "No Order Number";
                else if ($validate_result[$i]["error_code"] == Import_info_service::VALUE_IS_NOT_AN_INTEGER)
                    $error_message .= "Order Number is not valid";
            } elseif ($validate_result[$i]["column"] == 1) {
                if ($validate_result[$i]["error_code"] == Import_info_service::VALUE_IS_NOT_A_VALID_DATE)
                    $error_message .= "Dispatch Date is not valid";
            }
            $data_from_csv[$validate_result[$i]["row"]]->set_error_message($error_message);
        }
        return $data_from_csv;
    }
}

/* End of file Import_finance_dispatch_model.php */
/* Location: ./system/application/models/order/import_finance_dispatch_model.php */