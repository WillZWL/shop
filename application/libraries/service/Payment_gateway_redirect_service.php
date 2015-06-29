<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_voucher.php";

interface Payment_gateway_redirect_service_interface
{
    /**************************************************
     *   return the string/xml that will be sent to payment gateway and pass to $this->get_redirect_url()
     *   $request_data will store the data for logging into database only.
     ***************************************************/
    public function prepare_get_url_request($payment_info = array(), &$request_data);

    /**************************************************
     *   return the url if success, otherwise, return false
     *   $response_data will store the data for logging into database only.
     ***************************************************/
    public function get_redirect_url($request_data, &$response_data);

    /****************************************************
     *   return the standard payment gateway name, unique id name
     *****************************************************/
    public function get_payment_gateway_name();

    /***************************************
     *   process_payment_status, just need to decide if the result is pass or fail.
     *   $general_data will usually be $_POST
     *   $get_data will usually be $_GET
     *   $so_number: so_number from payment gateway
     *   $data_from_pmgw, $data_to_pmgw, for logging into database
     *   $so_data: an output from the function that contain any update to so table
     *   $sops_data: an output from the function that contain any update to so_payment_status table
     *   $socc_data: an output from the function that contain any update to so_credit_chk table
     *   $sor_data: an output from the function that contain any update to so_risk table
     ****************************************/
    public function process_payment_status($general_data = array(), $get_data = array(), &$so_number, &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$sops_data, &$socc_data, &$sor_data);

    /*********************************************************
     *   return the technical support email of this payment gateway
     **********************************************************/
    public function get_technical_support_email();

    /**********************************************************
     *   follow up action after payment failure for different payment gateway
     *   do nothing, if no follow up action
     ***********************************************************/
    public function process_failure_action();

    /**********************************************************
     *   follow up action after payment cancel for different payment gateway
     *   do nothing, if no follow up action
     ***********************************************************/
    public function process_cancel_action();

    /**********************************************************
     *   follow up action after payment success for different payment gateway
     *   e.g. moneybookers ctpe will need to ACK from our server
     *   do nothing, if no follow up action
     *   every payment gateway need to decide when to send order confirmation email,
     *   this is the best place to do this
     ***********************************************************/
    public function process_success_action();

    /**************************************************************
     *   return TRUE if need credit check, otherwise, return false
     ***************************************************************/
    public function is_payment_need_credit_check($is_fraud = false);

    /*************************************************************
     *   query_transaction, the input would be a transaction id client_id-so_no
     *   return would be success, fail or cancel
     *   return PAYMENT_NO_STATUS would means no implementation on that function
     *   inside this function each payment gateway is free to update payment status
     *   $this->so, $this->sops is ready before this function call
     **************************************************************/
    public function query_transaction($input_parameters = array(), &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$socc_data, &$sops_data);

    /*************************************************************
     *   is_need_dm_service
     *   return true or false
     **************************************************************/
    public function is_need_dm_service($is_fraud = false);
}

abstract class Payment_gateway_redirect_service extends Pmgw_voucher implements Payment_gateway_redirect_service_interface
{
    const PAYMENT_STATUS_FAIL = 1;
    const PAYMENT_STATUS_CANCEL = 2;
    const PAYMENT_STATUS_SUCCESS = 3;
    const PAYMENT_STATUS_KEEP_PENDING = 4;
    const PAYMENT_NO_STATUS = 10;
    const ORDER_SUCCESS_TO_SUCESS = 100;
    const ORDER_SUCCESS_TO_FAIL = 101;
    const ORDER_FAIL_TO_SUCESS = 102;
    const ORDER_FAIL_TO_FAIL = 103;

    const REFUND_STATUS_SUCCESS = 1;
    const REFUND_STATUS_REQUIRE_RETRY = 2;
    const REFUND_STATUS_ERROR = 3;

    public $debug = 0;
    public $pmgw_redirect_service;

    public $successful_page;
    public $failure_page;
    public $cancel_page;
    public $need_ajax_handler;
    public $has_error = 0;
    public $error_message = "";
    public $error_code;
    public $support_email;
    public $socc;
    public $credit_check_3d_amount = array("AU" => 600
    , "GB" => 400
    , "US" => 0
    , "BE" => 600
    , "ES" => 600
    , "FI" => 600
    , "FR" => 600
    , "IE" => 600
    , "IT" => 600
    , "MT" => 600
    , "PT" => 600
    , "NZ" => 700
    , "RU" => 30000
    , "CH" => 700
    , "PL" => 1700
    , "SG" => 800
    , "HK" => 5000
    , "MY" => 1600);
    public $credit_check_3d_amount_level2 = array("PL" => 350);
    public $currency_id_mapping = array("EUR" => 978
    , "HKD" => 344
    , "AUD" => 036
    , "NZD" => 554
    , "PLN" => 985
    , "PHP" => 608
    , "GBP" => 826);
    protected $sopql_service;
    protected $refund_service;
    protected $order_note_dao;
    protected $so_hold_reason_dao;
    protected $so_risk_dao;
    protected $payment_gateway_name;
    protected $payment_response_page = "";
    protected $sitedown_email = "oswald-alert@eservicesgroup.com, jesslyn@eservicesgroup.com, compliance-alerts@eservicesgroup.net";

    public function __construct($debug)
    {
        parent::__construct();
        $this->debug = $debug;
        $this->init_setting();
        $this->support_email = $this->get_technical_support_email();
        include_once(APPPATH . "libraries/service/So_payment_query_log_service.php");
        $this->sopql_service = new So_payment_query_log_service();
        include_once(APPPATH . "libraries/service/Refund_service.php");
        $this->refund_service = new Refund_service();
        include_once(APPPATH . "libraries/dao/Order_notes_dao.php");
        $this->order_note_dao = new Order_notes_dao();
        include_once(APPPATH . "libraries/dao/So_hold_reason_dao.php");
        $this->so_hold_reason_dao = new So_hold_reason_dao();
        include_once(APPPATH . "libraries/service/Auto_refund_service.php");
        $this->auto_refund_service = new Auto_refund_service();
    }

    /**********************************************************
     *   get_payment_response_page could be override to do init setting
     *************************************************************/
    protected function init_setting()
    {
        if ($this->debug)
            $append_debug = "&debug=1";
        else
            $append_debug = "";

//      $this->successful_page = "https://" . $_SERVER['HTTP_HOST'] . "/checkout_redirect_method/payment_success?payment_type=" . $this->get_payment_gateway_name() . $append_debug;
//      $this->failure_page = "https://" . $_SERVER['HTTP_HOST'] . "/checkout_redirect_method/payment_failure?payment_type=" . $this->get_payment_gateway_name() . $append_debug;
        $this->successful_page = "checkout_redirect_method/payment_result/1/";
        $this->failure_page = "checkout_redirect_method/payment_result/0/";
        $this->payment_response_page = base_url() . "checkout_redirect_method/payment_response?payment_type=" . $this->get_payment_gateway_name() . $append_debug;
        //$this->cancel_page = $this->failure_page;
        $this->cancel_page = "checkout_onepage?cancel_from_pmgw=1";
    }

    public function checkout($vars)
    {
        $this->need_ajax_handler = $vars["ajax_handler"];
//      if ($this->get_payment_gateway_name() != "google")
//      {
        unset($_SESSION["so_no"]);
//      }
        if ($this->check_inital_parameters($vars)) {
            if ($this->so->get_amount()) {
                $url_request = $this->prepare_get_url_request($vars, $request_data);
                if (($request_data != null) && (!empty($request_data)))
                    $this->get_sopl_srv()->add_log($this->so->get_so_no(), "O", str_replace("&", "\n&", $request_data));
                $redirect_url = $this->get_redirect_url($url_request, $response_data);
                if (($response_data != null) && (!empty($response_data)))
                    $this->get_sopl_srv()->add_log($this->so->get_so_no(), "I", str_replace("&", "\n&", $response_data));
                if (!$redirect_url) {
                    return $this->checkout_failure_handler($this->error_message);
                } else {
                    if ($vars["payment_gateway"] == 'w_bank_transfer') {
                        $this->sops->set_payment_status('N');
                    } else {
                        $this->sops->set_payment_status('P');
                    }
                    $sops_dao = $this->get_so_srv()->get_sops_dao();
                    $sops_dao->update($this->sops);
                    if ($this->need_ajax_handler)
                        print $redirect_url;
                    elseif ($vars["payment_gateway"] == 'w_bank_transfer') {
                        print $redirect_url;
                    } else
                        redirect($redirect_url);

                    return TRUE;
                }
            }
            return $this->checkout_failure_handler("Amount 0");
        } else {
            return $this->checkout_failure_handler($_SESSION["NOTICE"] . "fail checking parameters");
        }
    }

    /********************************************************************
     *   check_inital_parameters check the checkout parameters
     *   you may override this function if you have more checking
     *********************************************************************/
    protected function check_inital_parameters($vars)
    {
        $pbv_srv = $this->get_pbv_srv();
        $platform_obj = $pbv_srv->get_dao()->get(array("selling_platform_id" => $vars["platform_id"]));
        $vars["currency_id"] = $platform_obj->get_platform_currency_id();
        $so_srv = $this->get_so_srv();
        $so_obj = $so_srv->cart_to_so($vars);

        if ($so_obj === FALSE) {
            return FALSE;
        }
        $this->so = $so_obj;
        $this->store_af_info();
        $this->sops = $vars["sops"];
        $this->promo = $vars["promo"];
        $this->so_item_list = $vars["so_item_list"];

        $this->client = $this->get_client_srv()->get(array("id" => $this->so->get_client_id()));
        if ($this->client === FALSE) {
            return FALSE;
        }
        return TRUE;
    }

    /***************************************************************
     *   checkout_failure_handler could be overrided to set different error reponse during checkout
     *   this handler will only be suitable
     ****************************************************************/
    protected function checkout_failure_handler($message = "")
    {
        $this->has_error = 1;
        $this->error_message = $message;
        if ($this->need_ajax_handler == 1) {
            print "ERROR:" . $message;
            return FALSE;
        } else {
            $_SESSION["NOTICE"] = $message;
            $url = $this->get_failure_page();
            redirect($url);
        }
        $this->has_error = 0;
        $this->error_message = "";
    }

    /**********************************************************
     *   get_failure_page could be override to do init setting
     *   could be overrided to get other failure page
     *************************************************************/
    protected function get_failure_page()
    {
        $url = base_url() . $this->failure_page . (($this->so) ? $this->so->get_so_no() : "");
        return $url;
    }

    /****************************************************
     * query_payment_status_in_general may not be useful in general
     * so, different payment gateway may override this function to process
     * to another flow
     *******************************/
    public function query_payment_status_in_general($so_no)
    {
        $take_action = FALSE;
        $message = "";
        $so_srv = $this->get_so_srv();
        if ($this->so = $so_srv->get(array("so_no" => $so_no))) {
            $sops_dao = $so_srv->get_sops_dao();
            $this->sops = $sops_dao->get(array("so_no" => $this->so->get_so_no()));
            $transaction_id = $this->so->get_client_id() . "-" . $so_no;
            $result = $this->query_transaction(array("transaction_id" => $transaction_id, "so_no" => $this->so->get_so_no()), $data_from_pmgw, $data_to_pmgw, $so_data, $socc_data, $sops_data);

            if ($data_to_pmgw)
                $this->get_sopql_service()->add_log($this->so->get_so_no(), "O", $data_to_pmgw);
            if ($data_from_pmgw)
                $this->get_sopql_service()->add_log($this->so->get_so_no(), "I", str_replace("&", "\n&", $data_from_pmgw));

            if ($socc_data)
                $this->create_socc($socc_data);

//see if the status match
            $take_action = $this->is_payment_stauts_correct($result, $message);
            if ($take_action == Payment_gateway_redirect_service::ORDER_FAIL_TO_SUCESS) {
//send email
//could also be correct payment status by each payment gateway service
                $this->payment_success_operation($so_data, $sops_data);
                $this->fire_success_event();
                mail($this->get_support_email(), "[VB] " . $this->get_payment_gateway_name() . " order from fail to success, so_no:" . $so_no, $message, 'From: website@valuebasket.com');
                return TRUE;
            } else if ($take_action == Payment_gateway_redirect_service::ORDER_SUCCESS_TO_FAIL) {
                if ((($this->so->get_status() >= 2) && ($this->so->get_status() < 6))
                    && ($this->so->get_refund_status() == 0)
                    && ($this->so->get_hold_status() == 0)
                ) {
                    $holdAuto = "Auto hold by system, from success to cancel";

                    $order_note_obj = $this->order_note_dao->get();
                    $order_note_obj->set_so_no($this->so->get_so_no());
                    $order_note_obj->set_type('O');
                    $order_note_obj->set_note('Order payment status changed from success to fail by ' . $this->get_payment_gateway_name() . ', need compliance to verify');
                    $this->order_note_dao->insert($order_note_obj);

                    $hold_reason_obj = $this->so_hold_reason_dao->get();
                    $hold_reason_obj->set_so_no($this->so->get_so_no());
                    $hold_reason_obj->set_reason("change_of_address");
                    $this->so_hold_reason_dao->insert($hold_reason_obj);

                    $this->so->set_status(2);
                    $this->so->set_hold_status(1);
                    if (!$so_srv->update($this->so)) {
                        mail($this->get_technical_support_email(), '[VB] ' . $this->get_payment_gateway_name() . ' order from success to fail:' . $this->so->get_client_id() . '-' . $so_no, $holdAuto, 'From: website@valuebasket.com');
                    }
                } else {
                    $holdAuto = "No change in status in the system, order was hold before";
                }
//if yes, put order on hold and send email to compliance to verify
                mail('compliance@valuebasket.com', '[VB] ' . $this->get_payment_gateway_name() . ' order from success to fail:' . $this->so->get_client_id() . '-' . $so_no, $message, 'From: website@valuebasket.com');
                return FALSE;
            } else
                return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_sopql_service()
    {
        return $this->sopql_service;
    }

    protected function create_socc($socc_data)
    {
        if (is_array($socc_data)) {
            $socc_dao = $this->get_so_srv()->get_socc_dao();
            $this->socc = $socc_dao->get(array("so_no" => $this->so->get_so_no()));

            if (empty($this->socc)) {
                $this->socc = $socc_dao->get();
                $this->socc->set_so_no($this->so->get_so_no());
                $insert = true;
            } else {
                $insert = false;
            }

            include_once(APPPATH . "helpers/object_helper.php");
            set_value($this->socc, $socc_data);

            if ($insert) {
                $socc_dao->insert($this->socc);
            } else {
                $socc_dao->update($this->socc);
            }
        }
    }

    /**************************************************************
     *   is_payment_stauts_correct will check if payment status is correct or not
     *   but this->so & this->sops should already be set.
     *   return TRUE if correct, otherwise FALSE
     *   $message will contain error message if return FALSE
     ***************************************************************/
    public function is_payment_stauts_correct($result, &$message)
    {
        $message = "";
        if (($result == Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS)
            && ($this->so->get_status() > 1)
            && ($this->sops->get_payment_status() == 'S')
        ) {
            return Payment_gateway_redirect_service::ORDER_SUCCESS_TO_SUCESS;
        } else {
            if (($result == Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS)
                && (($this->so->get_status() <= 1)
                    || ($this->sops->get_payment_status() != 'S'))
            ) {
                $message = $this->so->get_so_no() . "\nPayment_gateway query return PAID, system return FAIL";
                return Payment_gateway_redirect_service::ORDER_FAIL_TO_SUCESS;
            } else if (($result != Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS)
                && (($this->so->get_status() > 1)
                    || ($this->sops->get_payment_status() == 'S'))
            ) {
//success to fail case
                $message = $this->so->get_so_no() . "\nPayment_gateway query return FAIL, system return PAID";
                return Payment_gateway_redirect_service::ORDER_SUCCESS_TO_FAIL;
            } else {
                return Payment_gateway_redirect_service::ORDER_FAIL_TO_FAIL;
            }
        }
    }

    /********************************************************************************
     *   payment_success_operation could be overrided to do special sucess operation
     *********************************************************************************/
    protected function payment_success_operation($so_para = array(), $sops_para = array(), $socc_para = array(), $sor_data = array())
    {
        include_once(APPPATH . "helpers/object_helper.php");

        set_value($this->sops, $sops_para);
        $this->sops->set_payment_status('S');
        if (!$this->sops->get_pay_date())
            $this->sops->set_pay_date(date('Y-m-d H:i:s'));
        $sops_dao = $this->get_so_srv()->get_sops_dao();
        $sops_dao->update($this->sops);

#2494 do the fraud oder checking
        //$this->get_so_srv()->process_fraud_order($this->so);
        if ($is_fraud = $this->get_so_srv()->is_fraud_order($this->so)) {
            $this->get_so_srv()->process_fraud_order($this->so);
        } else {
//check if this order pass before
            if ($this->so->get_status() <= 1) {
//need credit check handling
                if ($this->is_payment_need_credit_check($is_fraud)) {
                    $this->so->set_status(2);
                } else
                    $this->so->set_status(3);
                set_value($this->so, $so_para);

                $this->get_so_srv()->get_dao()->update($this->so);
                $this->get_so_srv()->update_website_display_qty($this->so);
//CYBS decision manager
                if ($this->is_need_dm_service($is_fraud)) {
                    if (sizeof($sor_data) > 0) {
                        $insert_data = $sor_data;
                    } else {
                        $insert_data = array("risk_requested" => 0);
                    }
                    $this->sor_add($insert_data);
                }
//update promotion code
                if ($promo_code = $this->so->get_promotion_code()) {
                    $this->update_promo($promo_code);
                }
                $this->is_random_markup_order();
            } else if ($this->so->get_status() == 2) {
//status from 2 to 3 because of 3D info
                if (!$this->is_payment_need_credit_check($is_fraud)) {
                    $this->so->set_status(3);
                    set_value($this->so, $so_para);
                    $this->get_so_srv()->get_dao()->update($this->so);

//cc and dm are related
                    if (!$this->is_need_dm_service($is_fraud)) {
                        if (sizeof($sor_data) > 0) {
                            $sor_data["risk_requested"] = 2;
                            $update_data = $sor_data;
                        } else {
                            $update_data = array("risk_requested" => 2);
                        }
//we don't add, update only, before if no record, meaning that no dm
                        $this->sor_update($update_data);
                    }
                }
            }
        }

        $this->create_socc($socc_para);
        $this->unset_variable();
    }

    public function is_payment_need_credit_check($is_fraud = false)
    {
        return $this->require_credit_check($is_fraud);
    }

    protected function is_random_markup_order()
    {
        include_once(APPPATH . "libraries/service/Class_factory_service.php");
        $cf_srv = new Class_factory_service();
        if (!($price_srv = $cf_srv->get_platform_price_service($this->so->get_platform_id()))) {
            return FALSE;
        }

        $so_srv = $this->get_so_srv();
        $soi_obj = $so_srv->get_soid_dao()->get_list(array("so_no" => $this->so->get_so_no()));
        if ($soi_obj) {
            $order_total = 0;
            $real_total = 0;
            $gst_total = 0;

            foreach ($soi_obj as $soi) {
                $order_total += $soi->get_amount();
                $gst_total += $soi->get_gst_total();
                $discount = $soi->get_promo_disc_amt();

                $pobj = $price_srv->get_dao()->get_list_with_bundle_checking($soi->get_item_sku(), $this->so->get_platform_id(), "Product_cost_dto");
                if ($pobj) {
                    foreach ($pobj as $o) {
                        $real_total += ($o->get_price() * $soi->get_qty()) - $discount;
                    }
                }
            }

            // Don't alert if GST order
            if (($gst_total == 0) && (((abs($real_total - $order_total) / $real_total) > 0.12) || ((abs($real_total - $order_total) / $order_total) > 0.12))) {
                mail('compliance@valuebasket.com', '[VB] ' . $this->so->get_so_no() . ' over 12% difference after markup', $this->so->get_so_no() . ' over 12% difference after markup');
            }
        }
    }

    public function get_support_email()
    {
        return $this->support_email;
    }

    /******************************
     * we cannot do any page redirection here, even it got fail
     * CTPE require to ACK and success or fail url
     *******************************/
    public function process_payment_status_in_general($general_data = array(), $get_data = array())
    {
        $result = $this->process_payment_status($general_data, $get_data, $so_no_from_pmgw, $data_from_pmgw, $data_to_pmgw, $so_data, $sops_data, $socc_data, $sor_data);
        $so_srv = $this->get_so_srv();
        if ($this->so = $so_srv->get(array("so_no" => $so_no_from_pmgw))) {
//save the log first
            if (($data_from_pmgw != null) && (!empty($data_from_pmgw)))
                $this->get_sopl_srv()->add_log($this->so->get_so_no(), "I", str_replace("&", "\n&", $data_from_pmgw));
            if (($data_to_pmgw != null) && (!empty($data_to_pmgw)))
                $this->get_sopl_srv()->add_log($this->so->get_so_no(), "O", str_replace("&", "\n&", $data_to_pmgw));

            $sops_dao = $so_srv->get_sops_dao();
            $this->sops = $sops_dao->get(array("so_no" => $this->so->get_so_no()));
            if ($result == Payment_gateway_redirect_service::PAYMENT_STATUS_CANCEL) {
                $this->payment_cancel_operation($so_data, $sops_data, $socc_data);
                $this->process_cancel_action();
            } else if ($result == Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS) {
                $this->payment_success_operation($so_data, $sops_data, $socc_data, $sor_data);
                $this->process_success_action();
            } else if ($result == Payment_gateway_redirect_service::PAYMENT_STATUS_KEEP_PENDING) {
//failure first, but no fail operation, in this case, it will be still pending and wait for the cron to update the payment status
                $this->process_failure_action();
            } else {
                $this->payment_fail_operation($so_data, $sops_data, $socc_data);
                $this->process_failure_action();
            }
        } else {
//probably invalid so number, so, cannot update database
//email to technical
            $subject = "[" . $this->get_payment_gateway_name() . "]" . "fatal error";
            $message = "";
            if (is_array($general_data))
                $message .= $this->array_implode('=', ',', $general_data);
            else if (!empty($general_data))
                $message .= $general_data;
            if (is_array($get_data))
                $message .= "," . $this->array_implode('=', ',', $get_data);
            else if (!empty($get_data))
                $message .= $get_data;
            $message .= $this->get_so_srv()->get_dao()->db->_error_message();
            mail($this->get_support_email(), $subject, $message);
            $this->process_failure_action();
        }
    }

    /********************************************************************************
     *   payment_cancel_operation could be overrided to do special sucess operation
     *********************************************************************************/
    protected function payment_cancel_operation($so_para = array(), $sops_para = array(), $socc_para = array())
    {
        include_once(APPPATH . "helpers/object_helper.php");

        set_value($this->sops, $sops_para);
        $this->sops->set_payment_status('C');
        $sops_dao = $this->get_so_srv()->get_sops_dao();
        $sops_dao->update($this->sops);

        set_value($this->so, $so_para);
        $this->so->set_status(0);
        $this->get_so_srv()->get_dao()->update($this->so);

        $this->create_socc($socc_para);
        /*
                $subject = "[" . $this->get_payment_gateway_name() . "]" . "cancel error";
                $message = $this->array_implode('=', ',', $general_data) . "," . $this->array_implode('=', ',', $get_data);
                mail($this->get_support_email(), $subject, $message);
        */
    }

    /********************************************************************************
     *   payment_fail_operation could be overrided to do special sucess operation
     *********************************************************************************/
    protected function payment_fail_operation($so_para = array(), $sops_para = array(), $socc_para = array())
    {
        if ($this->so->get_status() >= 2) {
            mail($this->get_technical_support_email() . ",compliance@valuebasket.com", '[VB] ' . $this->get_payment_gateway_name() . ' Order try to come from success to fail:' . $this->so->get_client_id() . '-' . $this->so->get_so_no(), "Please check the payment and notify IT to manually update the status", 'From: website@valuebasket.com');
        } else {
            if ($sops_para)
                set_value($this->sops, $sops_para);
            $this->sops->set_payment_status('F');
            $sops_dao = $this->get_so_srv()->get_sops_dao();
            $this->so->set_status(0);
            $this->get_so_srv()->get_dao()->update($this->so);
            $sops_dao->update($this->sops);

            if ($socc_para) {
                $this->create_socc($socc_para);
            }
        }
    }

    protected function array_implode($glue, $separator, $array)
    {
        if (!is_array($array))
            return $array;
        $string = array();
        foreach ($array as $key => $val) {
            if (is_array($val))
                $val = implode(',', $val);
            $string[] = "{$key}{$glue}{$val}";
        }
        return implode($separator, $string);
    }

    /**********************************************************
     *   get_notification_url is in general if psp extends payment_gateway_redirect_service
     *   could be overrided to get other notification url
     *   currently only Trustly use this, because Trustly don't use their admin to put notification url
     *************************************************************/
    public function get_notification_url()
    {
        $debug_string = ($this->debug) ? "&debug=1" : "";
        $url = base_url() . "checkout_redirect_method/payment_notification?payment_type=" . $this->get_payment_gateway_name() . $debug_string;
        return $url;
    }

    public function std_obj_to_string($input, $array_key = "")
    {
        $string_to_return = "";
        foreach ($input as $key => $value) {
            if (is_object($value)) {
                $string_to_return .= $this->std_obj_to_string($value, "    " . $array_key . ($key . "_"));
            } else if (is_array($value)) {
                foreach ($value as $second_key => $second_value) {
                    $string_to_return .= $this->std_obj_to_string($second_value, "    " . $array_key . ($key . "_" . $second_key . "_"));
                }
            } else {
                $string_to_return .= $array_key . $key . "=" . $value . "\n";
            }
        }
        return $string_to_return;
    }

    public function is_payment_need_credit_check_3D($is_fraud = false)
    {
        if ($is_fraud) {
            return false;
        } else {
            $amount = $this->so->get_amount();
            $eci = $this->sops->get_risk_ref4();
            $country_id = $this->so->get_bill_country_id();

            if ((array_key_exists($country_id, $this->credit_check_3d_amount))
                && ($amount > $this->credit_check_3d_amount[$country_id])
            ) {
                return true;
            } else if ($this->is_eci_level_one($eci)) {
                if ((array_key_exists($country_id, $this->credit_check_3d_amount))
                    && ($amount > $this->credit_check_3d_amount[$country_id])
                )
                    return true;
                else
                    return false;
            } else if ($this->is_eci_level_two($eci)) {
                if ((array_key_exists($country_id, $this->credit_check_3d_amount_level2))
                    && ($amount < $this->credit_check_3d_amount_level2[$country_id])
                )
                    return false;
                else
                    return true;
            } else if ($this->is_eci_level_three($eci)) {
                return true;
            }
        }
        return true;
    }

    public function is_eci_level_one($eci)
    {
        if (($eci == "05") || ($eci == "02")
            || ($eci == "5") || ($eci == "2")
        )
            return true;
        return false;
    }

    public function is_eci_level_two($eci)
    {
        if (($eci == "06") || ($eci == "01")
            || ($eci == "6") || ($eci == "1")
        )
            return true;
        return false;
    }

    public function is_eci_level_three($eci)
    {
        if (($eci == "07") || ($eci == "00")
            || ($eci == "7") || ($eci == "0")
            || is_null($eci) || ($eci == "")
        )
            return true;
        return false;
    }

    protected function get_failure_page_top()
    {
        $url = base_url() . "checkout_redirect_method/payment_result_top/0/" . (($this->so) ? $this->so->get_so_no() : "");
        return $url;
    }

    /**********************************************************
     *   get_successful_page_page could be override to do init setting
     *   could be overrided to get other failure page
     *************************************************************/
    protected function get_successful_page($so_no = null)
    {
        $debug_string = ($this->debug) ? "?debug=1" : "";

        if ($so_no == null)
            $put_so_no = $this->so->get_so_no();
        else
            $put_so_no = $so_no;

        $url = base_url() . $this->successful_page . $put_so_no . $debug_string;
        return $url;
    }

    protected function get_successful_page_top($so_no = null)
    {
        $debug_string = ($this->debug) ? "?debug=1" : "";

        if ($so_no == null)
            $put_so_no = $this->so->get_so_no();
        else
            $put_so_no = $so_no;

        $url = base_url() . "checkout_redirect_method/payment_result_top/1/" . $put_so_no . $debug_string;
        return $url;
    }

    /**********************************************************
     *   get_cancel_page_page could be override to do init setting
     *   could be overrided to get other failure page
     *************************************************************/
    protected function get_cancel_page()
    {
        return base_url() . $this->cancel_page;
    }

    protected function get_cancel_page_top()
    {
        //return base_url() . "checkout_redirect_method/payment_result_top/0/";
        return base_url() . "checkout_redirect_method/payment_cancel_top";
    }

    /***************************************************************
     *   get_payment_response_page could be overrided to set payment response page, but not success, or fail page.
     *   this reponse page is to get the redirect URL from payment gateway.
     ****************************************************************/
    protected function get_payment_response_page()
    {
        $debug_string = ($this->debug) ? "&debug=1" : "";
// double check the url with lang_id_countryID pair
// this is to prevent redirection
        $check_url = base_url();
        if ((substr($check_url, -6, 2) != get_lang_id())
            || (strtoupper(substr($check_url, -3, 2)) != PLATFORMCOUNTRYID)
        )
            $check_url = base_url() . get_lang_id() . "_" . PLATFORMCOUNTRYID . "/";

        return $this->payment_response_page = $check_url . "checkout_redirect_method/payment_response?payment_type=" . $this->get_payment_gateway_name() . $debug_string;
    }

    /***************************************************************
     *   keep_error_message, just store the error for later to print
     ****************************************************************/
    protected function keep_error_message($message = "")
    {
        $this->has_error = 1;
        $this->error_message = $message;
        return FALSE;
    }

    /***************************************************************
     *   rewrite_base_url could be overrided to set the domain/xx_XX
     *   e.g. cyber source doesn't support sending successful page to them,
     *   a redirect base on the order country to create lang_country pair is safer than do nothing
     ****************************************************************/
    protected function rewrite_base_url()
    {
        if (isset($this->so) && (!is_null($this->so)) && (!empty($this->so))) {
            return $_SERVER['HTTP_HOST'] . "/" . $this->so->get_lang_id() . "_" . $this->so->get_bill_country_id() . "/";
        } else
            return base_url();
    }

    protected function check_split_name(&$firstname, &$prefixsurname, &$surname)
    {
        $ar_firstname = explode(" ", trim($firstname));

        if (($name_count = count($ar_firstname)) > 1) {
            switch ($name_count) {
                case 3:
                    $firstname = $ar_firstname[0];
                    $prefixsurname = substr($ar_firstname[1], 0, 15);
                    $surname = substr($ar_firstname[2], 0, 35);
                    break;
                case 2:
                    $firstname = $ar_firstname[0];
                    $surname = substr($ar_firstname[1], 0, 35);
                    break;
                default:
                    $firstname = $ar_firstname[0];
                    $prefixsurname = substr($ar_firstname[1], 0, 15);
                    array_shift($ar_firstname);
                    array_shift($ar_firstname);
                    $surname = substr(@implode(" ", $ar_firstname), 0, 35);
                    break;

            }
        }

        if (strlen($firstname) > 15) {
            $firstname = substr($firstname, 0, 15);
        }
    }

    protected function text_logging()
    {
        if ($fp = @fopen($path . $this->get_payment_gateway_name(), 'w')) {
            @fwrite($fp, $file_content);
            @fclose($fp);

            return $filename;
        }
    }

    protected function get_auto_refund_requirement($refundObj, $total_order_amount)
    {
        $where = array();
        $where["payment_gateway_id"] = "yandex";
        $where["refund_id"] = $refundObj->get_refund_id();

        $autoRefundObj = $this->auto_refund_service->get_dao()->get($where);
        if ($autoRefundObj) {
            try {
                $so_no = $autoRefundObj->get_so_no();
            } catch (Exception $ex) {
                return false;
            }

            $refund_list = $this->refund_service->get_dao()->get_list(array("so_no" => $so_no, "status" => "C"), array("limit" => -1));
            $checkAmount = 0;
            foreach ($refund_list as $refund) {
                $checkAmount += $refund->get_total_refund_amount();
            }
            if ($checkAmount <= $total_order_amount) {
//we check total refund amount <=, because auto refund is done before the refund cron job, all the refund data, the status in the refund table are completed
                return $autoRefundObj;
            } else
                return false;
        } else {
//not valid to do auto refund, because no auto refund object
            return false;
        }
    }

    protected function refund_so($amount, $notes)
    {
        $db_refund_result = $this->refund_service->quick_refund($this->so->get_so_no(), $amount, $notes);
        if ($db_refund_result) {
            $sops_dao = $this->get_so_srv()->get_sops_dao();
            $this->sops = $sops_dao->get(array("so_no" => $this->so->get_so_no()));
            $this->sops->set_payment_status('S');
            $sops_dao->update($this->sops);
            return true;
        }
        return false;
    }
}

/* End of file payment_gateway_redirect_service.php */
/* Location: ./system/application/libraries/service/Payment_gateway_redirect_service.php */
