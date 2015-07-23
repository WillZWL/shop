<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_report_service.php";

class Inpendium_pmgw_report_service extends Pmgw_report_service
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_pmgw()
    {
        return "inpendium";
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
        if (($dto_obj->get_payment_type() == "DB") && ($dto_obj->get_status_code() == "90")) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function is_so_fee_record($dto_obj)
    {
        return FALSE;
    }

    public function is_refund_record($dto_obj)
    {
        //error_log("refund");
        if (($dto_obj->get_payment_type() == "RF") && ($dto_obj->get_status_code() == "90")) {
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
        return FALSE;
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

    protected function insert_interface_flex_ria($batch_id, $status, $dto_obj)
    {
        if ($dto_obj->get_credit())
            $dto_obj->set_amount(ereg_replace(",", "", $dto_obj->get_credit()));

        $this->reform_data($dto_obj);

        $ifr_obj = $this->create_interface_flex_ria($batch_id, $status, $dto_obj, false);
    }

    public function reform_data($dto)
    {
        // txn_id is a string like 128702 or 128702-314628
        $txn_id = $dto->get_transaction_id();
        //var_dump($dto);die();
        $temp_arr = explode("-", $txn_id);

        if (array_key_exists(1, $temp_arr)) {
            $so_no = $temp_arr[1];
            $dto->set_so_no($so_no);
        }

        //as a fall back if cannot the the so_no from the report directly,

        if (!$dto->get_so_no()) {
            if ($txn_id = $dto->get_unique_id()) {
                if ($so_obj = $this->get_so_dao()->get(array("trim(txn_id)" => $txn_id))) {
                    $dto->set_so_no($so_obj->get_so_no());
                }
            }
        }

        $dto->set_txn_id($dto->get_unique_id());
        $dto->set_internal_txn_id($dto->get_transaction_id());
        $dto->set_ref_txn_id($dto->get_unique_id());

        $date_w_time = trim($dto->get_request_timestamp());

        //system php version not support DateTime::createFromFormat
        //2013-10-31 18:21:27
        //31-10-13 23:15
        if (preg_match("/(([0-9]{2,4})-([0-9]{2})-([0-9]{2}))\s{1}((\d*)\:(\d*)(\:(\d*))*)*/", $date_w_time, $matches)) {
            if (preg_match("/\d{4}/", $matches[2])) {
                $value = $matches[1] . " " . $matches[5];
            } elseif (preg_match("/\d{2}/", $matches[2])) {
                $value = '20' . $matches[4] . '-' . $matches[3] . '-' . $matches[2] . " " . $matches[5];
            }
        }

        $dto->set_date($value);
    }

    protected function insert_interface_flex_refund($batch_id, $status, $dto_obj)
    {
        $this->reform_data($dto_obj);
        if ($dto_obj->get_debit())
            $dto_obj->set_amount(ereg_replace(",", "", $dto_obj->get_debit()));

        $this->create_interface_flex_refund($batch_id, $status, $dto_obj, false);
    }

    protected function insert_interface_flex_so_fee($batch_id, $status, $dto_obj)
    {
        return FALSE;
    }

    protected function insert_interface_flex_rolling_reserve($batch_id, $status, $dto_obj)
    {
        return FALSE;
    }

    protected function insert_interface_flex_gateway_fee($batch_id, $status, $dto_obj)
    {
        return FALSE;
    }
}


