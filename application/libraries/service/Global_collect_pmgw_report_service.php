<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_report_service.php";

class Global_collect_pmgw_report_service extends Pmgw_report_service
{
    public function __construct()
    {
        parent::__construct();
    }

    public function is_ria_record($dto_obj)
    {
        if (($dto_obj->get_status_id() >= 800) && ($dto_obj->get_status_id() <= 1050))
        {
            return "RIA";
            //return true;
        }
        return false;
    }

    public function is_refund_record($dto_obj)
    {
        if (($dto_obj->get_status_description() == "REFUNDED"))
        {
            return "R";
        }
        elseif($dto_obj->get_status_description() == "CHARGED_BACK_BY_CONSUMER")
        {
            return "CB";
        }
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

    public function is_gateway_fee_record($dto_obj)
    {
        return false;
    }

    public function get_contact_email()
    {
        return 'nero-alert@eservicesgroup.com';
    }

    protected function get_pmgw()
    {
        return "global_collect";
    }

    protected function insert_interface_flex_ria($batch_id, $status, $dto_obj)
    {
// insert interface_flex_ria
        $date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $dto_obj->get_date())));
        $dto_obj->set_date($date);

        if ($dto_obj->get_amount())
            $dto_obj->set_amount(abs(ereg_replace(",", "", $dto_obj->get_amount())));

        if($dto_obj->get_so_no())
            $dto_obj->set_so_no($dto_obj->get_so_no());

        $this->_set_format_object($dto_obj);

        $this->create_interface_flex_ria($batch_id, $status, $dto_obj, $include_fsf=FALSE);
    }

    protected function insert_interface_flex_refund($batch_id, $status, $dto_obj)
    {

        //var_dump($dto_obj);die();
        $date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $dto_obj->get_txn_time())));
        $dto_obj->set_date($date);

        if($dto_obj->get_so_no())
            $dto_obj->set_so_no($dto_obj->get_so_no());

        $this->_set_format_object($dto_obj);

        $this->create_interface_flex_refund($batch_id, $status, $dto_obj, $include_fsf=FALSE);
    }

    protected function insert_interface_flex_so_fee($batch_id, $status, $dto_obj)
    {
        return false;
    }

    protected function insert_interface_flex_rolling_reserve($batch_id, $status, $dto_obj)
    {
        return false;
    }

    protected function insert_interface_flex_gateway_fee($batch_id, $status, $dto_obj)
    {
        return false;
    }

    /*
    public function insert_flex_ria($batch_id)
    {
        $ifr_list = $this->get_ifr_dao()->get_list(array("flex_batch_id"=>$batch_id), array("limit"=>"-1"));
        if($ifr_list)
        {
            $return_result = TRUE;
            foreach($ifr_list AS $ifr_obj)
            {
                if($ifr_obj->get_batch_status() == 'N')
                {
                    $fr_dao = $this->get_fr_dao();
                    $ifr_dao = $this->get_ifr_dao();
                    $fr_vo = $fr_dao->get();

                    if($fr_obj = $fr_dao->get(array("so_no"=>$ifr_obj->get_so_no(), "gateway_id"=>$ifr_obj->get_gateway_id(), "status"=>$ifr_obj->get_status())))
                    {
                        if(($fr_obj->get_amount() == $ifr_obj->get_amount()))
                        {
                            $ifr_obj->set_batch_status("C");
                            $ifr_obj->set_failed_reason("duplicated record");
                            $ifr_dao->update($ifr_obj);
                        }
                        else
                        {
                            $fr_obj->set_flex_batch_id($ifr_obj->get_flex_batch_id());
                            $fr_obj->set_amount($ifr_obj->get_amount());

                            if($fr_dao->update($fr_obj) !== FALSE)
                            {
                                $ifr_obj->set_batch_status("I");
                                $ifr_obj->set_failed_reason("record updated on ". date("Y-m-d H:i:s"));
                                $return_result = FALSE;
                                $ifr_dao->update($ifr_obj);
                            }
                            else
                            {
                                $ifr_obj->set_batch_status("F");
                                $ifr_obj->set_failed_reason("update record error: ".$fr_dao->db->_error_message());
                                $ifr_dao->update($ifr_obj);
                                $return_result = FALSE;
                            }
                        }
                    }
                    else
                    {
                        $fr_obj = clone $fr_vo;
                        set_value($fr_obj, $ifr_obj);

                        //need to overwrite this method as well
                        //valid_txn_id
                        if($this->valid_txn_id($fr_obj))
                        {
                            if($fr_dao->insert($fr_obj) !== FALSE)
                            {
                                $ifr_obj->set_batch_status("S");
                                if($ifr_dao->update($ifr_obj))
                                {
                                }
                            }
                            else
                            {
                                if($failed_reason = $this->valid_so_no($ifr_obj))
                                {
                                    $ifr_obj->set_failed_reason($failed_reason);
                                }
                                else
                                {
                                    $ifr_obj->set_failed_reason($fr_dao->db->_error_message());
                                }
                                $ifr_obj->set_batch_status("F");
                                $ifr_dao->update($ifr_obj);
                                $return_result = FALSE;
                            }
                        }
                        else
                        {
                            $ifr_obj->set_failed_reason("invalid txn_id");
                            $ifr_obj->set_batch_status("F");
                            $ifr_dao->update($ifr_obj);
                            $return_result = FALSE;
                        }
                    }
                }
                elseif($ifr_obj->get_batch_status() == 'F')
                {
                    $return_result = FALSE;
                }
            }
            return $return_result;
        }
        return FALSE;
    }
    */

    //overwrite
    public function valid_txn_id($interface_obj)
    {
        $i_so_no =  $interface_obj->get_so_no();
        $i_txn_id = $interface_obj->get_txn_id();

        if($this->get_so_dao()->get(array("txn_id"=>$i_txn_id)))
        {
            return true;
        }
        elseif($this->get_so_dao()->get(array("so_no"=>$i_so_no)))
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    //declare
    public function is_ria_include_so_fee()
    {
        return false;
    }
    //declare
    public function is_refund_include_so_fee()
    {
        return false;
    }
    //overwrite, no need insert so fee, but a false success signal
    public function insert_flex_so_fee()
    {
        return TRUE;
    }

    public function insert_so_fee_from_ria_record($batch_id, $status, $dto_obj)
    {
        return false;
    }

    public function insert_so_fee_from_rolling_reserve_record($batch_id, $status, $dto_obj)
    {
        return false;
    }

    public function insert_so_fee_from_refund_record($batch_id, $status, $dto_obj)
    {
        return false;
    }

    public function insert_flex_rolling_reserve()
    {
        return TRUE;
    }

    public function insert_flex_gateway_fee()
    {
        return TRUE;
    }

    public function after_insert_all_interface($batch_id)
    {
        return TRUE;
    }

    private function _set_format_object($dto_obj)
    {
        if(!$dto_obj->get_internal_txn_id())
        {
            if($payment_reference = $dto_obj->get_payment_reference())
            {
                $dto_obj->set_internal_txn_id($payment_reference);
            }
            elseif($txn_id = $dto_obj->get_txn_id())
            {
                $dto_obj->set_internal_txn_id($txn_id);
            }
            else
            {
                $dto_obj->set_internal_txn_id(" ");
            }
        }
    }

}

/* End of file global_collect_pmgw_report_service.php */
/* Location: ./system/application/libraries/service/Global_collect_pmgw_report_service.php */