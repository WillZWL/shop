<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_report_service.php";

abstract class Paypal_pmgw_report_service extends Pmgw_report_service
{

    //abstract public function get_pmgw();


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
        if (($dto_obj->get_type() == "Mobile Express Checkout Payment Received")
            || ($dto_obj->get_type() == "PayPal Express Checkout Payment Received")
            || ($dto_obj->get_type() == "Mobile Payment Received")
            || ($dto_obj->get_type() == "Payment Received")
        ) {
            //if (in_array($dto_obj->get_status(), array('Completed', 'Cleared', 'Refunded', 'Held', 'Partially Refunded', 'Reversed')))
            //{
            return true;
            //}
        }

        if (($dto_obj->get_type() == "Express Checkout Payment Received")) {
            return true;
        }


        return false;
    }

    public function is_so_fee_record($dto_obj)
    {
        return false;
    }

    public function is_refund_record($dto_obj)
    {
        if (($dto_obj->get_type() == "Refund")
            || ($dto_obj->get_type() == "Reversal")
        ) {
            return 'R';
        } else if ($dto_obj->get_type() == "Chargeback Settlement") {
            return 'CB';
        }

        return false;
    }


    public function is_rolling_reserve_record($dto_obj)
    {
        if ($dto_obj->get_type() == "Reserve Hold")
            return 'RRH';
        else if ($dto_obj->get_type() == "Reserve Release")
            return 'RRR';
        elseif ($dto_obj->get_type() == "Temporary Hold")
            return 'TH';
        else if ($dto_obj->get_type() == "Update to Reversal")
            return 'UTR';
        return false;
    }

    public function is_gateway_fee_record($dto_obj)
    {
        if ($dto_obj->get_type() == "Currency Conversion")
            return 'FX';
        else if ($dto_obj->get_type() == "Payment Sent")
            return 'PS';
        elseif ($dto_obj->get_type() == "Transfer")
            return 'P_TF';
        return false;
    }

    public function get_contact_email()
    {
        return 'nero-alert@eservicesgroup.com';
    }

    public function after_insert_all_interface($batch_id)
    {
        /*
        $dao_list = array("ifr", "ifrf");

        foreach($dao_list as $d)
        {
            $method = "get_".$d."_dao";
            if($dao = $this->$method())
            {
                $where =  array();
                $where["so_no is null or so_no='' "] = null;
                $where["flex_batch_id"] = $batch_id;
                $where["gateway_id"] = $this->get_pmgw();
                if($obj_list = $dao->get_list($where, array("limit"=>-1)))
                {
                    foreach($obj_list as $obj)
                    {
                        if($so_obj = $this->get_so_dao()->get(array("txn_id like '%".$obj->get_txn_id()."%'"=>null)))
                        {error_log(6);
                            $obj->set_so_no($so_obj->get_so_no());
                            $dao->update($obj);
                        }
                    }
                }
            }
        }
        */

        //------if any record miss internal_txn_id or txn_id, assign the no empty one to another one
        //------only need to implement this in flex_refund and flex_rolling_reserve
        $where = array();
        $where["internal_txn_id is null or txn_id is null"] = null;
        $where["gateway_id"] = $this->get_pmgw();
        if ($empty_txn_obj_list = $this->get_ifrr_dao()->get_list($where, array("limit" => -1))) {
            foreach ($empty_txn_obj_list as $empty_txn_obj) {
                if ($txn_id = $empty_txn_obj->get_txn_id()) {
                    if (!$empty_txn_obj->get_internal_txn_id()) {
                        $empty_txn_obj->set_internal_txn_id($txn_id);
                    }
                } elseif ($internal_txn_id = $empty_txn_obj->get_internal_txn_id()) {
                    if (!$empty_txn_obj->get_txn_id()) {
                        $empty_txn_obj->set_txn_id($internal_txn_id);
                    }
                }
            }
        }

        if ($empty_txn_obj_list = $this->get_ifr_dao()->get_list($where, array("limit" => -1))) {
            foreach ($empty_txn_obj_list as $empty_txn_obj) {
                if ($txn_id = $empty_txn_obj->get_txn_id()) {
                    if (!$empty_txn_obj->get_internal_txn_id()) {
                        $empty_txn_obj->set_internal_txn_id($txn_id);
                    }
                } elseif ($internal_txn_id = $empty_txn_obj->get_internal_txn_id()) {
                    if (!$empty_txn_obj->get_txn_id()) {
                        $empty_txn_obj->set_txn_id($internal_txn_id);
                    }
                }
            }
        }

        //----------------------HANDLE INTERNAL_TXN_ID RECORDS-----------------------------------
        //------For rolling reserve, need to check the internal_transaction_id which is the in order to get the order id

        //remove the batch_id, since there is case two related records are not in the same batch file
        //$where["flex_batch_id"] = " ";
        unset($where);
        $where["so_no"] = " ";
        $where["gateway_id"] = $this->get_pmgw();
        $where["failed_reason"] = Pmgw_report_service::WRONG_TRANSACTION_ID;

        if ($rolling_reserve_obj_list = $this->get_ifrr_dao()->get_list($where, array("limit" => -1))) {   //error_log(count($rolling_reserve_obj_list));
            //var_dump($rolling_reserve_obj_list);die();
            foreach ($rolling_reserve_obj_list as $nut_obj) {
                $txn_id = $nut_obj->get_txn_id();
                //$related_record = $this->get_ifrr_dao()->get_list(array("internal_txn_id"=>$txn_id), array("limit"=>1));
                //var_dump( $this->get_ifrr_dao()->db->last_query());die();
                if ($related_record = $this->get_ifrr_dao()->get_list(array("internal_txn_id" => $txn_id), array("limit" => 1))) {
                    if ($so_no = $related_record->get_so_no()) {
                        $nut_obj->set_so_no($so_no);
                        //USE TRANSACTION ID OF PARENT RECORD SO THAT IT'S EASY TO KNOW WHICH RECORD ARE ACTUALL HAVE CONNECTION.
                        $nut_obj->set_txn_id($related_record->get_txn_id());
                        $nut_obj->set_batch_status("N");
                        $nut_obj->set_failed_reason("");
                        $this->get_ifrr_dao()->update($nut_obj);
                    }
                }
            }
        }

        //----------------------MOVE NO SO_NO ROLLING_RESERVE RECORD INTO GATEWAY TABLE-----------------------------------
        if ($rolling_reserve_obj_list = $this->get_ifrr_dao()->get_list($where, array("limit" => -1))) {
            //var_dump($rolling_reserve_obj_list);die();
            //ALL THE ROLLING RESERVE RECORD INTO GATEWAY TABLE ??
            foreach ($rolling_reserve_obj_list as $nut_obj) {
                //var_dump($nut_obj);die();
                $this->RR_to_interface_flex_gateway_fee($batch_id, $nut_obj->get_status(), $nut_obj);
            }
        }
    }

    private function RR_to_interface_flex_gateway_fee($batch_id, $status, $rr_obj)
    {
        $ifgf_dao = $this->get_ifgf_dao();
        $ifgf_obj = $ifgf_dao->get();

        $ifgf_obj->set_flex_batch_id($batch_id);
        $ifgf_obj->set_gateway_id($this->get_pmgw());

        $ifgf_obj->set_txn_id($rr_obj->get_txn_id());

        $ifgf_obj->set_txn_time($rr_obj->get_txn_time());
        $ifgf_obj->set_currency_id($rr_obj->get_currency_id());
        $ifgf_obj->set_amount($rr_obj->get_amount());
        $ifgf_obj->set_status($status);
        $ifgf_obj->set_batch_status("N");
        //var_dump($this->get_ifgf_dao()->db->last_query());die();
        //error_log($dto_obj->get_amount());
        if ($ifgf_dao = $this->get_ifgf_dao()->insert($ifgf_obj)) {
            $rr_obj->set_batch_status("S");
            $rr_obj->set_failed_reason("move to interface_gateway_fee");
            $this->get_ifrr_dao()->update($rr_obj);
        }
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

    //INTERFACE PAYMENT RECEIVE RECORD TO GATEWAY FEE TABLE

    public function insert_so_fee_from_refund_record($batch_id, $status, $dto_obj)
    {
        //if the commission is 0, then don't create the record
        if (!((float)$dto_obj->get_commission())) {
            return true;
        }

        $ifsf_dao = $this->get_ifsf_dao();
        $ifsf_obj = $ifsf_dao->get();

        $ifsf_obj->set_so_no($dto_obj->get_so_no());
        $ifsf_obj->set_flex_batch_id($batch_id);
        $ifsf_obj->set_gateway_id($this->get_pmgw());
        $ifsf_obj->set_txn_id($dto_obj->get_ref_txn_id());
        $ifsf_obj->set_txn_time($dto_obj->get_date());
        $ifsf_obj->set_currency_id($dto_obj->get_currency_id());

        $ifsf_obj->set_amount(ereg_replace(",", "", $dto_obj->get_commission()));
        $ifsf_obj->set_status($status);
        $ifsf_obj->set_batch_status("N");

        if (!$ifsf_obj->get_so_no()) {
            $ifsf_obj->set_so_no(" ");
            $ifsf_obj->set_batch_status("F");
            $ifsf_obj->set_failed_reason(Pmgw_report_service::WRONG_TRANSACTION_ID);
        }
        $ifsf_dao->insert($ifsf_obj);
    }

    //INTERFACE ROLLING RESERVE RECORD TO GATEWAY FEE TABLE

    protected function insert_interface_flex_ria($batch_id, $status, $dto_obj)
    {
        $this->reform_data($dto_obj);
        $this->_get_order_from_txn_id($dto_obj, $type = "ria");
        if ($dto_obj->get_amount())
            $dto_obj->set_amount(ereg_replace(",", "", $dto_obj->get_amount()));

        $ifr_obj = $this->create_interface_flex_ria($batch_id, $status, $dto_obj);

        if ($ifr_obj && trim($ifr_obj->get_so_no()) == "") {
            //if not so_no, move it to  gateway_fee
            if ($dto_obj->get_type() == "Payment Received") {
                $this->PR_to_interface_flex_gateway_fee($batch_id, $ifr_obj->get_status(), "PR", $dto_obj);

                if ($dto_obj->get_commission() != "" && $dto_obj->get_commission() != "..." && abs((float)$dto_obj->get_commission()) > 0) {
                    $dto_obj->set_amount($dto_obj->get_commission());
                    $dto_obj->set_txn_id($dto_obj->get_txn_id());

                    $new_status = "RIA";
                    if ($dto_obj->get_status() == "Completed") {
                        $new_status = "PR_C";

                    } elseif ($dto_obj->get_status() == "Refunded") {
                        $new_status = "PR_R";
                    }

                    $this->PR_to_interface_flex_gateway_fee($batch_id, $ifr_obj->get_status(), $new_status, $dto_obj);
                }
            }
        }
    }

    public function reform_data($dto_obj)
    {
        $date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $dto_obj->get_date()) . " " . $dto_obj->get_time()));
        $dto_obj->set_date($date);

        $dto_obj->set_amount(ereg_replace(",", "", $dto_obj->get_amount()));
        $dto_obj->set_net(ereg_replace(",", "", $dto_obj->get_net()));


        //search in a time range.
        $txn_time_min = date("Y-m-d H:i:s", strtotime($date . " -1 month"));
        $txn_time_max = date("Y-m-d H:i:s", strtotime($date . " +1 month"));

        $where_ria["txn_id"] = "Transaction ID: " . $dto_obj->get_txn_id();
        $where_ria["order_create_date between '" . $txn_time_min . "'and'" . $txn_time_max . "'"] = null;

        $where_refund["txn_id"] = "Transaction ID: " . $dto_obj->get_ref_txn_id();
        $where_refund["order_create_date between '" . $txn_time_min . "'and'" . $txn_time_max . "'"] = null;

        if (!$dto_obj->get_so_no()) {
            if ($so_obj = $this->get_so_obj(array("txn_id" => $dto_obj->get_txn_id()))) {
                $dto_obj->set_so_no($so_obj->get_so_no());
            }
        }
    }

    private function _get_order_from_txn_id($dto_obj, $type = "ria")
    {
        //var_dump($dto_obj);die();
        if ((!preg_match("/^\d{6}$/", $dto_obj->get_so_no()))) {
            $date = $dto_obj->get_date();
            $txn_time_min = date("Y-m-d H:i:s", strtotime($date . " -1 month"));
            $txn_time_max = date("Y-m-d H:i:s", strtotime($date . " +1 month"));
            $where["order_create_date between '" . $txn_time_min . "'and'" . $txn_time_max . "'"] = null;

            if ($type == "ria") {
                $txn_id = $dto_obj->get_txn_id();
            } else {
                $txn_id = $dto_obj->get_ref_txn_id();
            }


            $where_refund["txn_id"] = "Transaction ID: " . $txn_id;

            $search_mode = array(array("txn_id" => $txn_id), array("txn_id like '%" . $txn_id . "%'" => null));

            foreach ($search_mode as $where) {
                if ($so_obj = $this->get_so_obj($where)) {
                    $dto_obj->set_so_no($so_obj->get_so_no());
                    break;
                }
            }
        }
    }

    private function PR_to_interface_flex_gateway_fee($batch_id, $original_status, $destination_status, $dto_obj)
    {
        $ifgf_dao = $this->get_ifgf_dao();
        $ifgf_obj = $ifgf_dao->get();

        $ifgf_obj->set_flex_batch_id($batch_id);
        $ifgf_obj->set_gateway_id($this->get_pmgw());
        $ifgf_obj->set_txn_id($dto_obj->get_txn_id());
        $ifgf_obj->set_txn_time($dto_obj->get_date());
        $ifgf_obj->set_currency_id($dto_obj->get_currency_id());
        $ifgf_obj->set_amount($dto_obj->get_amount());
        $ifgf_obj->set_status($destination_status);
        $ifgf_obj->set_batch_status("N");

        if ($ifgf_dao = $this->get_ifgf_dao()->insert($ifgf_obj)) {
            //update the interface_flex_ria
            if ($ifr_obj = $this->get_ifr_dao()->get(array("txn_id" => $dto_obj->get_txn_id(), "status" => $original_status, "batch_status != 'S'" => NULL))) {
                $ifr_obj->set_batch_status("S");
                $ifr_obj->set_failed_reason("move to interface_gateway_fee");
                $this->get_ifr_dao()->update($ifr_obj);
            }
        }
    }

    protected function insert_interface_flex_refund($batch_id, $status, $dto_obj)
    {
        $this->reform_data($dto_obj);
        $this->_get_order_from_txn_id($dto_obj, $type = "refund");
        $dto_obj->set_internal_txn_id($dto_obj->get_txn_id());

        if (!$dto_obj->get_ref_txn_id()) {
            $dto_obj->set_ref_txn_id($dto_obj->get_txn_id());
        }

        $this->create_interface_flex_refund($batch_id, $status, $dto_obj, true);
    }

    //this function apply to paypal only,(the refund record also includes so_fee) other gateway need no this function.
    //a little different from create_interface_flex_so_fee

    protected function insert_interface_flex_so_fee($batch_id, $status, $dto_obj)
    {
        return false;
    }

    protected function insert_interface_flex_rolling_reserve($batch_id, $status, $dto_obj)
    {
        $this->reform_data($dto_obj);
        $dto_obj->set_internal_txn_id($dto_obj->get_txn_id());

        if (!$dto_obj->get_ref_txn_id()) {
            $dto_obj->set_ref_txn_id($dto_obj->get_txn_id());
        }

        $dto_obj->set_amount($dto_obj->get_net());
        $include_fsf = false;

        $ifrr_obj = $this->create_interface_flex_rolling_reserve($batch_id, $status, $dto_obj, $include_fsf);
    }

    protected function insert_interface_flex_gateway_fee($batch_id, $status, $dto_obj)
    {
        $this->reform_data($dto_obj);

        if (($status == "PS") || ($status == "BTU")) {
            $dto_obj->set_txn_id($dto_obj->get_ref_txn_id());
        }

        if ($status == 'FX') {
            if ($dto_obj->get_amount() > 0) {
                $status = "FXI";
            } else {
                $status = "FXO";
            }
        }

        if (!$dto_obj->get_txn_id()) {
            $dto_obj->set_txn_id(" ");
        }

        if (!$dto_obj->get_ref_txn_id()) {
            $dto_obj->set_ref_txn_id($dto_obj->get_txn_id());
        }

        return $this->create_interface_flex_gateway_fee($batch_id, $status, $dto_obj);
    }
}


