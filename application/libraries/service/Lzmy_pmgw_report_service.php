<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_report_service.php";

class Lzmy_pmgw_report_service extends Pmgw_report_service
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_contact_email()
    {
        return 'handy.hon@eservicesgroup.com';
    }

    public function insert_so_fee_from_refund_record($batch_id, $status, $dto_obj)
    {
    }

    public function insert_so_fee_from_ria_record($batch_id, $status, $dto_obj)
    {
    }

    public function insert_so_fee_from_rolling_reserve_record($batch_id, $status, $dto_obj)
    {
    }

    public function is_ria_include_so_fee()
    {
        return false;
    }

    public function is_refund_include_so_fee()
    {
        return false;
    }

    public function is_so_fee_record($dto_obj)
    {
        return false;
    }

    public function is_rolling_reserve_record($dto_obj)
    {
        return false;
    }

    protected function insert_interface($batch_id, $dto_obj)
    {
        if ($this->is_ria_record($dto_obj)) {
            $this->insert_interface_flex_ria($batch_id, 'RIA', $dto_obj);
        } elseif ($this->is_refund_record($dto_obj)) {
            $this->insert_interface_flex_refund($batch_id, 'R', $dto_obj);
        } elseif ($this->is_gateway_fee_record($dto_obj)) {
            $this->insert_interface_flex_gateway_fee($batch_id, 'PSP', $dto_obj);
        }
    }

    public function is_ria_record($dto_obj)
    {
        if ($dto_obj->get_type() == "Item Price Credit") {
            return true;
        }

        return false;
    }

    protected function insert_interface_flex_ria($batch_id, $status, $dto_obj)
    {
        $this->_set_format_data($dto_obj);

        if ($this->is_ria_include_psp_fee()) {
            $include_psp_fee = true;
        } else {
            $include_psp_fee = false;
        }

        $this->create_interface_flex_ria($batch_id, $status, $dto_obj, $include_psp_fee);
    }

    private function _set_format_data($dto_obj)
    {
        $date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $dto_obj->get_date())));
        $dto_obj->set_date($date);

        if ($dto_obj->get_amount()) {
            $dto_obj->set_amount(abs(ereg_replace(",", "", $dto_obj->get_amount())));
        }

        if ((!$dto_obj->get_so_no() || !$dto_obj->get_currency_id()) && $dto_obj->get_txn_id()) {
            if ($so_obj = $this->get_so_obj(array("txn_id" => $dto_obj->get_txn_id()))) {
                if (!$dto_obj->get_so_no()) {
                    $dto_obj->set_so_no($so_obj->get_so_no());
                }
                if (!$dto_obj->get_currency_id()) {
                    $dto_obj->set_currency_id($so_obj->get_currency_id());
                }
            }
        }
    }

    public function is_ria_include_psp_fee()
    {
        return false;
    }

    protected function create_interface_flex_ria($batch_id, $status, $dto_obj, $include_psp_fee = false)
    {
        $ifr_dao = $this->get_ifr_dao();
        $ifr_obj = $ifr_dao->get();

        $ifr_obj->set_so_no($dto_obj->get_so_no());
        $ifr_obj->set_flex_batch_id($batch_id);
        $ifr_obj->set_gateway_id($this->get_pmgw());
        $ifr_obj->set_txn_id($dto_obj->get_txn_id());
        $ifr_obj->set_txn_time($dto_obj->get_date());
        $ifr_obj->set_currency_id($dto_obj->get_currency_id());
        $ifr_obj->set_amount($dto_obj->get_amount());
        $ifr_obj->set_status($status);
        $ifr_obj->set_batch_status("N");

        if (!$ifr_obj->get_so_no()) {
            $ifr_obj->set_so_no(" ");
            $ifr_obj->set_batch_status("F");
            $ifr_obj->set_failed_reason(Pmgw_report_service::WRONG_TRANSACTION_ID);
        }

        if (!$ifr_obj->get_currency_id()) {
            $ifr_obj->set_currency_id(" ");
            $ifr_obj->set_batch_status("F");
            $ifr_obj->set_failed_reason(Pmgw_report_service::WRONG_CURRENCY_ID);
        }

        // if success insert interface_flex_gateway_fee
        if ($ifr_dao->insert($ifr_obj) && $ifr_obj->get_batch_status() != "F") {
            if ($include_psp_fee) {
                // PSP is payment service provider fee.
                $this->insert_interface_flex_gateway_fee($batch_id, 'PSP', $dto_obj);
            }
        }

        return $ifr_obj;
    }

    protected function get_pmgw()
    {
        return "lzmy";
    }

    protected function insert_interface_flex_gateway_fee($batch_id, $status, $dto_obj)
    {
        $this->_set_format_data($dto_obj);

        return $this->create_interface_flex_gateway_fee($batch_id, $status, $dto_obj);
    }

    public function create_interface_flex_gateway_fee($batch_id, $status, $dto_obj)
    {
        $ifgf_dao = $this->get_ifgf_dao();
        $ifgf_obj = $ifgf_dao->get();

        $ifgf_obj->set_flex_batch_id($batch_id);
        $ifgf_obj->set_gateway_id($this->get_pmgw());
        $ifgf_obj->set_txn_id($dto_obj->get_txn_id());
        $ifgf_obj->set_txn_time($dto_obj->get_date());
        $ifgf_obj->set_currency_id($dto_obj->get_currency_id());
        $ifgf_obj->set_amount($dto_obj->get_amount()); // commission
        $ifgf_obj->set_status($status);
        $ifgf_obj->set_batch_status("N");

        if (!$ifgf_obj->get_currency_id()) {
            $ifgf_obj->set_currency_id(" ");
            $ifgf_obj->set_batch_status("F");
            $ifgf_obj->set_failed_reason(Pmgw_report_service::WRONG_CURRENCY_ID);
        }

        return $ifgf_dao->insert($ifgf_obj);
    }

    public function is_refund_record($dto_obj)
    {
        //TODO: need to confrim
        if ($dto_obj->get_type() == 'not sure') {
            return true;
        }

        return false;
    }

    protected function insert_interface_flex_refund($batch_id, $status, $dto_obj)
    {
        $this->_set_format_data($dto_obj);

        if ($this->is_ria_include_psp_fee()) {
            $include_psp_fee = true;
        } else {
            $include_psp_fee = false;
        }

        $this->create_interface_flex_refund($batch_id, $status, $dto_obj, $include_psp_fee);
    }

    protected function create_interface_flex_refund($batch_id, $status, $dto_obj, $include_psp_fee = false)
    {
        $ifrf_dao = $this->get_ifrf_dao();
        $ifrf_obj = $ifrf_dao->get();

        $ifrf_obj->set_so_no($dto_obj->get_so_no());
        $ifrf_obj->set_flex_batch_id($batch_id);
        $ifrf_obj->set_gateway_id($this->get_pmgw());
        $ifrf_obj->set_txn_id($dto_obj->get_txn_id());
        $ifrf_obj->set_txn_time($dto_obj->get_date());
        $ifrf_obj->set_currency_id($dto_obj->get_currency_id());
        $ifrf_obj->set_amount($dto_obj->get_amount());
        $ifrf_obj->set_status($status);
        $ifrf_obj->set_batch_status("N");

        if (!$ifrf_obj->get_so_no()) {
            $ifrf_obj->set_so_no(" ");
            $ifrf_obj->set_batch_status("F");
            $ifrf_obj->set_failed_reason(Pmgw_report_service::WRONG_TRANSACTION_ID);
        }

        if (!$ifrf_obj->get_currency_id()) {
            $ifrf_obj->set_currency_id(" ");
            $ifrf_obj->set_batch_status("F");
            $ifrf_obj->set_failed_reason(Pmgw_report_service::WRONG_CURRENCY_ID);
        }

        if ($ifrf_dao->insert($ifrf_obj) && $ifrf_obj->get_batch_status() != "F") {
            if ($include_psp_fee) {
                $this->insert_interface_flex_gateway_fee($batch_id, 'PSP', $dto_obj);
            }
        }
    }

    public function is_gateway_fee_record($dto_obj)
    {
        if ($dto_obj->get_type() == 'Commission') {
            return true;
        }

        return false;
    }

    protected function insert_interface_flex_so_fee($batch_id, $status, $dto_obj)
    {
    }

    protected function insert_interface_flex_rolling_reserve($batch_id, $status, $dto_obj)
    {

    }

    protected function after_insert_all_interface($batch_id)
    {
        return true;
    }

}
