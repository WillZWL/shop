<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_report_service.php";

abstract class Worldpay_parent_pmgw_report_service extends Pmgw_report_service
{
    public function __construct()
    {
        parent::__construct();
    }

    /*
    public function insert_interface($batch_id, $dto_obj)
    {
        switch($dto_obj->get_status())
        {
            case "SETTLED":
                $this->insert_interface_flex_ria($batch_id, 'RIA', $dto_obj);
                break;
            case "REFUNDED":
                $this->insert_interface_flex_refund($batch_id, 'R', $dto_obj);
                break;
            case "CHARGED_BACK":
                $this->insert_interface_flex_refund($batch_id, 'CB', $dto_obj);
                break;
            default:
        }
    }

    public function get_contact_email()
    {
        return 'oswald-alert@eservicesgroup.com';
    }

    protected function get_pmgw($account = "")
    {
        return "worldpay";
    }


    protected function insert_interface_flex_ria($batch_id, $status, $dto_obj)
    {
        // insert interface_flex_ria
        $ifr_dao = $this->get_ifr_dao();
        $ifr_obj = $ifr_dao->get();

        list($client_id, $so_no) = explode("-",$dto_obj->get_full_so_no());

        $ifr_obj->set_so_no($so_no);
        $ifr_obj->set_flex_batch_id($batch_id);
        $ifr_obj->set_gateway_id($this->get_pmgw());
        $ifr_obj->set_txn_id($dto_obj->get_full_so_no());
        $ifr_obj->set_txn_time($dto_obj->get_txn_time());
        $ifr_obj->set_currency_id($dto_obj->get_currency_id());
        $ifr_obj->set_amount(abs(ereg_replace(",", "", $dto_obj->get_amount())) + abs(ereg_replace(",", "", $dto_obj->get_commission())));
        $ifr_obj->set_status($status);
        $ifr_obj->set_batch_status("N");

        if(!$ifr_obj->get_so_no())
        {
            $ifr_obj->set_so_no(" ");
            $ifr_obj->set_batch_status("F");
            $ifr_obj->set_failed_reason("Wrong transaction id / so_no");
        }
        elseif(!$this->valid_date_format($dto_obj->get_txn_time()))
        {
            $ifr_obj->set_batch_status("F");
            $ifr_obj->set_failed_reason(implode(";", array("Invalid Date Time format", $ifr_obj->get_failed_reason())));
        }

        // if success insert interface_flex_so_fee
        if($ifr_dao->insert($ifr_obj) && $ifr_obj->get_batch_status() != "F")
        {
            $ifsf_dao = $this->get_ifsf_dao();
            $ifsf_obj = $ifsf_dao->get();

            $ifsf_obj->set_so_no($so_no);
            $ifsf_obj->set_flex_batch_id($batch_id);
            $ifsf_obj->set_gateway_id($this->get_pmgw());
            $ifsf_obj->set_txn_id($dto_obj->get_full_so_no());
            $ifsf_obj->set_txn_time($dto_obj->get_txn_time());
            $ifsf_obj->set_currency_id($dto_obj->get_currency_id());
            $ifsf_obj->set_amount(abs(ereg_replace(",", "", $dto_obj->get_commission())));
            $ifsf_obj->set_status($status);
            $ifsf_obj->set_batch_status("N");

            if(!$ifsf_obj->get_so_no())
            {
                $ifsf_obj->set_so_no(" ");
                $ifsf_obj->set_batch_status("F");
                $ifsf_obj->set_failed_reason("Wrong transaction id / so_no");
            }
            elseif(!$this->valid_date_format($dto_obj->get_txn_time()))
            {
                $ifsf_obj->set_batch_status("F");
                $ifsf_obj->set_failed_reason(implode(";", array("Invalid Date Time format", $ifsf_obj->get_failed_reason())));
            }

            $ifsf_dao->insert($ifsf_obj);
        }
    }

    protected function insert_interface_flex_refund($batch_id, $status, $dto_obj)
    {
        $ifrf_dao = $this->get_ifrf_dao();
        $ifrf_obj = $ifrf_dao->get();

        list($client_id, $so_no) = explode("-",$dto_obj->get_full_so_no());

        $ifrf_obj->set_so_no($so_no);
        $ifrf_obj->set_flex_batch_id($batch_id);
        $ifrf_obj->set_gateway_id($this->get_pmgw());
        $ifrf_obj->set_txn_id($dto_obj->get_full_so_no());
        $ifrf_obj->set_txn_time($dto_obj->get_txn_time());
        $ifrf_obj->set_currency_id($dto_obj->get_currency_id());
        $ifrf_obj->set_amount(abs(ereg_replace(",", "", $dto_obj->get_amount())));
        $ifrf_obj->set_status($status);
        $ifrf_obj->set_batch_status("N");

        if(!$ifrf_obj->get_so_no())
        {
            $ifrf_obj->set_so_no(" ");
            $ifrf_obj->set_batch_status("F");
            $ifrf_obj->set_failed_reason("Wrong transaction id / so_no");
        }
        elseif(!$this->valid_date_format($dto_obj->get_txn_time()))
        {
            $ifrf_obj->set_batch_status("F");
            $ifrf_obj->set_failed_reason(implode(";", array("Invalid Date Time format", $ifrf_obj->get_failed_reason())));
        }

        // if success insert interface_flex_so_fee
        if($ifrf_dao->insert($ifrf_obj) && $ifrf_obj->get_batch_status() != "F")
        {
            $ifsf_dao = $this->get_ifsf_dao();
            $ifsf_obj = $ifsf_dao->get();

            $ifsf_obj->set_so_no($so_no);
            $ifsf_obj->set_flex_batch_id($batch_id);
            $ifsf_obj->set_gateway_id($this->get_pmgw());
            $ifsf_obj->set_txn_id($dto_obj->get_full_so_no());
            $ifsf_obj->set_txn_time($dto_obj->get_txn_time());
            $ifsf_obj->set_currency_id($dto_obj->get_currency_id());
            $ifsf_obj->set_amount(abs(ereg_replace(",", "", $dto_obj->get_commission())));
            $ifsf_obj->set_status($status);
            $ifsf_obj->set_batch_status("N");

            if(!$ifsf_obj->get_so_no())
            {
                $ifsf_obj->set_so_no(" ");
                $ifsf_obj->set_batch_status("F");
                $ifsf_obj->set_failed_reason("Wrong transaction id / so_no");
            }
            elseif(!$this->valid_date_format($dto_obj->get_txn_time()))
            {
                $ifsf_obj->set_batch_status("F");
                $ifsf_obj->set_failed_reason(implode(";", array("Invalid Date Time format", $ifsf_obj->get_failed_reason())));
            }

            $ifsf_dao->insert($ifsf_obj);
        }
    }

    public function valid_txn_id($interface_obj)
    {
        $i_txn_id = $interface_obj->get_txn_id();
        list($client_id, $so_no) = explode("-",$i_txn_id);

        if($this->get_so_dao()->get(array("so_no"=>$so_no)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function valid_date_format($txn_time)
    {
        if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) ([0-2][0-9]):([0-5][0-9]):([0-5][0-9])$/', $txn_time))
        {
            return true;
        }
        return false;
    }
    */

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
        if($dto_obj->get_status() == "SETTLED")
        {
            return "RIA";
        }
        else
        {
            return FALSE;
        }
    }

    public function is_so_fee_record($dto_obj)
    {
        return false;
    }

    public function is_refund_record($dto_obj)
    {
        if($dto_obj->get_status() == "REFUNDED")
        {
            return "R";
        }
        elseif($dto_obj->get_status() == "CHARGED_BACK")
        {
            return "CB";
        }
        else
        {
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

    protected function insert_interface_flex_ria($batch_id, $status, $dto_obj)
    {
        $this->reform_data($dto_obj);
        $dto_obj->set_amount($dto_obj->get_amount() + $dto_obj->get_commission());
        $ifr_obj = $this->create_interface_flex_ria($batch_id, $status, $dto_obj, true);
    }

    protected function insert_interface_flex_refund($batch_id, $status, $dto_obj)
    {
        $this->reform_data($dto_obj);
        //var_dump($dto_obj); die();
        //commission for a refund record is always 0, so no need to create fee
        $this->create_interface_flex_refund($batch_id, $status, $dto_obj, false);
    }

    protected function insert_interface_flex_so_fee($batch_id, $status, $dto_obj)
    {
        return false;
    }

    protected function insert_interface_flex_rolling_reserve($batch_id, $status, $dto_obj)
    {
        return FALSE;
    }

    protected function insert_interface_flex_gateway_fee($batch_id, $status, $dto_obj)
    {
        return FALSE;
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
        $this->create_interface_flex_so_fee($batch_id, $status, $dto_obj);
    }

    public function insert_so_fee_from_ria_record($batch_id, $status, $dto_obj)
    {
        $this->create_interface_flex_so_fee($batch_id, $status, $dto_obj);
    }

    public function insert_so_fee_from_rolling_reserve_record($batch_id, $status, $dto_obj)
    {
        return false;
    }

    public function reform_data($dto_obj)
    {
        $date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $dto_obj->get_txn_time())));
        $dto_obj->set_date($date);

        if(strpos($dto_obj->get_full_so_no(), "-"))
        {
            list($client_id, $so_no) = explode("-",$dto_obj->get_full_so_no());
        }
        else
        {
            $so_no = $dto_obj->get_full_so_no();
        }

        if(preg_match("/\d{6}/", $so_no, $matches))
        {
            $dto_obj->set_so_no($matches[0]);
        }
        else
        {
            $dto_obj->set_so_no($so_no);
        }


        //var_dump($dto_obj);die();
        //return true;
        $dto_obj->set_internal_txn_id($so_no);
        $dto_obj->set_txn_id($so_no);
        $dto_obj->set_ref_txn_id($so_no);
        $dto_obj->set_amount(ereg_replace(",", "", $dto_obj->get_amount()));
        $dto_obj->set_commission(ereg_replace(",", "", $dto_obj->get_commission()));

    }
}

/* End of file worldpay_parent_pmgw_report_service.php */
/* Location: ./system/application/libraries/service/Worldpay_parent_pmgw_report_service.php */