<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_report_service.php";

class Trademe_pmgw_report_service extends Pmgw_report_service
{

    public function get_pmgw()
    {
        return "trademe";
    }


    public function __construct()
    {
        parent::__construct();
    }

    public function is_ria_include_so_fee()
    {
        return true;
    }

    public function is_refund_include_so_fee()
    {
        return true;
    }

    public function is_ria_record($dto_obj)
    {
        //for the money_in column,
        //if value like 12 or 12.00, then this record is treated as RIA
        //if value like 12.10 or 12.01 (decimal part > 0) then this record is treated as refund fee
        if(!$dto_obj->get_purchase())
        {
            return false;
        }
        else
        {
            $money_in = $dto_obj->get_money_in();
            if($money_in > 0)
            {
                if($index = strpos($money_in,"."))
                {
                    $decimal_part = substr($money_in, $index+1);
                    if(preg_match("/[1-9]/", $decimal_part))
                    {
                        return FALSE;
                    }
                    else
                    {
                        return "RIA";
                    }
                }
                else
                {
                    return "RIA";
                }
            }

            return false;
        }
    }

    public function is_so_fee_record($dto_obj)
    {
        //rules & premise
        /*
        1. money_in and money_out, one of them must be one.
        2. if money_in > 0,
            A. if decimal part is ZERO, then it is a RIA
            B. if decimal part is greater than ZERO, then it is a REFUND FEE
        3. if money_out >0
            A. if decimal part is ZERO, then it is a REFUND
            B. if decimal part is greater than ZERO, then it is a RIA FEE
        */
        if(!$dto_obj->get_purchase())
        {
            return false;
        }
        else
        {
            $money_in = $dto_obj->get_money_in();
            $money_out = $dto_obj->get_money_out();
            //var_dump($dto_obj);die();
            if($money_in > 0)
            {
                if($index = strpos($money_in,"."))
                {
                    $decimal_part = substr($money_in, $index+1);
                    if(preg_match("/[1-9]/", $decimal_part))
                    {
                        return "R";
                    }
                    else
                    {
                        return FALSE;
                    }
                }
                else
                {
                    return FALSE;
                }
            }
            elseif($money_out > 0)
            {
                if($index = strpos($money_out,"."))
                {
                    $decimal_part = substr($money_out, $index+1);
                    if(preg_match("/[1-9]/", $decimal_part))
                    {
                        return "RIA";
                    }
                    else
                    {
                        return FALSE;
                    }
                }
                else
                {
                    return FALSE;
                }
            }

            return false;
        }
    }

    public function is_refund_record($dto_obj)
    {
        if(!$dto_obj->get_purchase())
        {
            return false;
        }
        else
        {
            $money_out = $dto_obj->get_money_out();

            if($money_out > 0)
            {
                if($index = strpos($money_out,"."))
                {
                    $decimal_part = substr($money_out, $index+1);
                    if(preg_match("/[1-9]/", $decimal_part))
                    {
                        return FALSE;
                    }
                    else
                    {
                        return "R";
                    }
                }
                else
                {
                    return FALSE;
                }
            }
            return false;
        }
    }


    public function is_rolling_reserve_record($dto_obj)
    {
        return false;
    }

    public function is_gateway_fee_record($dto_obj)
    {
        return false;
    }

    public function get_contact_email()
    {
        return 'nero-alert@eservicesgroup.com';
    }

    protected function insert_interface_flex_ria($batch_id, $status, $dto_obj)
    {
        $this->reform_data($dto_obj);
        $dto_obj->set_amount($dto_obj->get_money_in());
        $this->create_interface_flex_ria($batch_id, $status, $dto_obj, false);
    }

    protected function insert_interface_flex_refund($batch_id, $status, $dto_obj)
    {
        $this->reform_data($dto_obj);
        $dto_obj->set_amount($dto_obj->get_money_out());
        $this->create_interface_flex_refund($batch_id, $status, $dto_obj, false);
    }

    protected function insert_interface_flex_so_fee($batch_id, $status, $dto_obj)
    {
        $this->reform_data($dto_obj);
        $money_in = $dto_obj->get_money_in();
        $money_out = $dto_obj->get_money_out();
        if($money_in > 0)
        {
            $dto_obj->set_commission($money_in);
        }
        else
        {
            $dto_obj->set_commission($money_out);
        }

        $this->create_interface_flex_so_fee($batch_id, $status, $dto_obj, true);
    }

    protected function insert_interface_flex_rolling_reserve($batch_id, $status, $dto_obj)
    {
        return false;
    }

    protected function insert_interface_flex_gateway_fee($batch_id, $status, $dto_obj)
    {
        return false;
    }

    public function after_insert_all_interface($batch_id)
    {
        return false;
    }

    public function valid_txn_id($interface_obj)
    {
        return true;
    }

    public function insert_so_fee_from_ria_record($batch_id, $status, $dto_obj)
    {
        parent:: create_interface_flex_so_fee($batch_id, $status, $dto_obj);
    }

    public function insert_so_fee_from_rolling_reserve_record($batch_id, $status, $dto_obj)
    {
        parent:: create_interface_flex_so_fee($batch_id, $status, $dto_obj);
    }

    //this function apply to paypal only,(the refund record also includes so_fee) other gateway need no this function.
    //a little different from create_interface_flex_so_fee
    public function insert_so_fee_from_refund_record($batch_id, $status, $dto_obj)
    {
        return false;
    }

    public function reform_data($dto_obj)
    {
        //var_dump($dto_obj);die();
        $date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $dto_obj->get_date())));
        $dto_obj->set_date($date);

        //search in a time range.
        $txn_time_min = date("Y-m-d H:i:s", strtotime($date." -12 month"));
        $txn_time_max = date("Y-m-d H:i:s", strtotime($date." +12 month"));


        $txn_id = $dto_obj->get_purchase();
        $dto_obj->set_txn_id($txn_id);
        $dto_obj->set_ref_txn_id($txn_id);
        $dto_obj->set_internal_txn_id($txn_id);
        $dto_obj->set_currency_id("NZD");


        $where["txn_id"] = $txn_id;
        $where["order_create_date between '".$txn_time_min ."'and'" .$txn_time_max."'"] = null;

        if($so_obj = $this->get_so_obj($where))
        {
            $dto_obj->set_so_no($so_obj->get_so_no());
        }
    }
}

/* End of file trademe_pmgw_report_service.php */
/* Location: ./system/application/libraries/service/Trademe_pmgw_report_service.php */