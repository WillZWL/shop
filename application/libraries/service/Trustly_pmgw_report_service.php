<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_report_service.php";

class Trustly_pmgw_report_service extends Pmgw_report_service
{
    public function __construct()
    {
        parent::__construct();
    }

    public function is_ria_include_so_fee()
    {
        return FALSE;
    }

    public function is_refund_include_so_fee()
    {
        return FALSE;
    }

    public function is_ria_record($dto_obj)
    {
        if (strpos($dto_obj->get_status(), "SUSPENSE_ACCOUNT_CLIENT_FUNDS_SPAIN") !== false) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function is_so_fee_record($dto_obj)
    {
        if (strpos($dto_obj->get_status(), "TRANSACTION_FEE_BANK_DEPOSIT") !== false) {
            return "RIA";
        } else {
            return FALSE;
        }
    }

    public function is_refund_record($dto_obj)
    {
        if (strpos($dto_obj->get_status(), "BANK_WITHDRAWAL_QUEUED") !== false) {
            return 'R';
        } else {
            return FALSE;
        }
    }

    public function is_rolling_reserve_record($dto_obj)
    {
        return FALSE;
    }

    public function is_gateway_fee_record($dto_obj)
    {
        //var_dump($dto_obj);die();
        if ($dto_obj->get_status() == "SUPPORT_FEE_MANUAL_SETTLEMENTS") {
            return 'T_MF';
        } else {
            return FALSE;
        }
    }

    public function get_contact_email()
    {
        return 'nero-alert@eservicesgroup.com';
    }

    public function after_insert_all_interface($batch_id)
    {
        return FALSE;
    }

    public function valid_txn_id($interface_obj)
    {
        return true;
    }

    public function insert_so_fee_from_refund_record($batch_id, $status, $dto_obj)
    {
        return false;
    }

    public function insert_so_fee_from_ria_record($batch_id, $status, $dto_obj)
    {
        return false;
    }

    public function insert_so_fee_from_rolling_reserve_record($batch_id, $status, $dto_obj)
    {
        return false;
    }

    public function get_file_data($filename, $delimiter = ";")
    {
        return parent::get_file_data($filename, $delimiter = ";");
    }

    protected function insert_interface_flex_ria($batch_id, $status, $dto_obj)
    {
        $this->reform_data($dto_obj);
        //var_dump($dto_obj);die();
        $ifr_obj = $this->create_interface_flex_ria($batch_id, $status, $dto_obj, false);
    }

    public function reform_data($dto_obj)
    {
        $txn_time = $dto_obj->get_txn_time();
        if ($index = strpos($txn_time, ".")) {
            $txn_time = substr($txn_time, 0, $index);
        }

        $date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $txn_time)));
        $dto_obj->set_date($date);

        $dto_obj->set_internal_txn_id($dto_obj->get_txn_id());
        $dto_obj->set_ref_txn_id($dto_obj->get_txn_id());
        $dto_obj->set_amount(ereg_replace(",", "", $dto_obj->get_amount()));
        $dto_obj->set_commission($dto_obj->get_amount());

        /***example*********************************
         *   $so_no_string = "Refund 2013-12-09 10:47:09.735365+01 133555";
         *   $so_no_string = "329350";
         ********************************************/

        $so_no_string = $dto_obj->get_so_no();

        $dto_obj->set_so_no("");

        $search_fields = array("so_no", "txn_id");

        if ($index = strrpos($so_no_string, " ")) {
            $last_word = substr($so_no_string, $index + 1);
        } else {
            $last_word = $so_no_string;
        }

        foreach ($search_fields as $field) {
            if ($so_obj = $this->get_so_dao()->get(array($field => $last_word))) {
                $so_no = $so_obj->get_so_no();
                $dto_obj->set_so_no($so_no);
                break;
            }
        }
    }

    protected function insert_interface_flex_refund($batch_id, $status, $dto_obj)
    {
        //var_dump($dto_obj);
        $this->reform_data($dto_obj);
        //var_dump($dto_obj); die();
        $this->create_interface_flex_refund($batch_id, $status, $dto_obj, false);
    }

    protected function insert_interface_flex_so_fee($batch_id, $status, $dto_obj)
    {
        $this->reform_data($dto_obj);
        $this->create_interface_flex_so_fee($batch_id, $status, $dto_obj, false);
        //var_dump($dto_obj);die();
    }

    protected function insert_interface_flex_rolling_reserve($batch_id, $status, $dto_obj)
    {
        return FALSE;
    }

    protected function insert_interface_flex_gateway_fee($batch_id, $status, $dto_obj)
    {
        $this->reform_data($dto_obj);
        if (!$dto_obj->get_ref_txn_id()) {
            $dto_obj->set_ref_txn_id(" ");
        }
        $this->create_interface_flex_gateway_fee($batch_id, $status, $dto_obj, false);
    }

    private function _re_order_output_list($output, $filename)
    {
        if (count((array)$output) > 0) {
            foreach ($output as $dto_obj) {
                if ($dto_obj->get_status() == "TRANSACTION_FEE_BANK_DEPOSIT")
                    $reorderlist["TRANSACTION_FEE_BANK_DEPOSIT"][] = $dto_obj;
                else
                    $reorderlist["MAIN_LIST"][] = $dto_obj;
            }

            if (isset($reorderlist["TRANSACTION_FEE_BANK_DEPOSIT"])) {
                foreach ($reorderlist["TRANSACTION_FEE_BANK_DEPOSIT"] as $obj) {
                    $reorderlist["MAIN_LIST"][] = $obj;
                }
            }

            if (isset($reorderlist["MAIN_LIST"])) {
                return $reorderlist["MAIN_LIST"];
            } else {
                $message = "Payment Gateway: " . $this->get_pmgw() . "\r\n";
                $message .= "File Name: " . $filename . "\r\n";
                mail("oswald-alert@eservicesgroup.com", "[VB] Gateway {$this->get_pmgw()} Report CSV Reorder Error", $message);
                // stop process if reorder fail
                return array();
            }
        } else
            return $output;
    }

    protected function get_pmgw($account = "")
    {
        return "trustly";
    }
}


