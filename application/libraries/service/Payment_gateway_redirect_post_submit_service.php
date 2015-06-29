<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Payment_gateway_redirect_service.php");

interface Payment_gateway_redirect_post_submit_interface
{
    public function get_form_action();
    public function get_form_data($vars);
}

abstract class Payment_gateway_redirect_post_submit_service extends Payment_gateway_redirect_service implements Payment_gateway_redirect_post_submit_interface
{
    public function __construct($debug)
    {
        parent::__construct($debug);
    }
/******************************
*  override payment_gateway_redirect_service checkout function,
*  because the flow of some payment gateway, like cybersource, moneybookers using form to *  submit directly to their server.
*****************************/
    public function checkout($vars)
    {
        $this->need_ajax_handler = $vars["ajax_handler"];
        unset($_SESSION["so_no"]);
        if ($this->check_inital_parameters($vars))
        {
            if ($this->so->get_amount())
            {
                $redirect_url = $this->get_redirect_url($vars, $response_data);
                if (!$redirect_url)
                {
                    return $this->checkout_failure_handler($this->error_message);
                }
                else
                {
                    if ($this->need_ajax_handler)
                        print $redirect_url;
                    else
                        redirect($redirect_url);
                    return TRUE;
                }
            }
            return $this->checkout_failure_handler("Amount 0");
        }
        else
        {
            return $this->checkout_failure_handler($_SESSION["NOTICE"] . "fail checking parameters");
        }
    }

    public function post_to_payment_gateway($so_no, $input_var)
    {
        $so_srv = $this->get_so_srv();
        if ($this->so = $so_srv->get(array("so_no"=>$so_no)))
        {
            $sops_dao = $so_srv->get_sops_dao();
            $this->sops = $sops_dao->get(array("so_no"=>$this->so->get_so_no()));
            $this->sops->set_payment_status('P');
            $sops_dao->update($this->sops);
            $this->client = $this->get_client_srv()->get(array("id"=>$this->so->get_client_id()));
            if ($this->client === FALSE)
            {
                return FALSE;
            }
            if ($this->so->get_status() > 1)
            {
                return FALSE;
            }
            $form_data = $this->get_form_data($input_var);
            foreach ($form_data as $name => $value)
            {
                $data_to_pmgw .= "<input type='hidden' name='" . $name . "' value=\"" . $value . "\">\n";
            }
            $this->get_sopl_srv()->add_log($this->so->get_so_no(), "O", $data_to_pmgw);
            return $data_to_pmgw;
        }
        else
        {
            return FALSE;
        }
    }
}

/* End of file payment_gateway_redirect_post_submit_service.php */
/* Location: ./system/application/libraries/service/Payment_gateway_redirect_post_submit_service.php */
