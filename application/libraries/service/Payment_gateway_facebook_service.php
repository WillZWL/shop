<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "payment_gateway_service.php";

class Payment_gateway_facebook_service extends payment_gateway_service
{
    private $so_srv;
    private $config;
    private $pmgw_srv;

    public function __construct()
    {
        parent::Payment_gateway_service();
        include_once(APPPATH . "libraries/dao/Payment_gateway_dao.php");
        $this->set_dao(new Payment_gateway_dao());
        include_once(APPPATH . "libraries/service/So_service.php");
        $this->set_so_srv(new So_service());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
        include_once(APPPATH . "libraries/dao/Platform_pmgw_dao.php");
        $this->set_pp_dao(new Platform_pmgw_dao());
    }

    public function checkout($payment_gateway, $vars = "", $debug = 0)
    {
        $vars["payment_gateway"] = $payment_gateway;

        if (strpos($payment_gateway, '_iframe_mode') !== FALSE) {
            $extend_from_payment_gateway = substr($payment_gateway, 0, strpos($payment_gateway, '_iframe_mode'));
        } else {
            $extend_from_payment_gateway = $payment_gateway;
        }

        if ($extend_from_payment_gateway != "google") {
            unset($_SESSION["so_no"]);
        }

        $this->init_pmgw_srv($payment_gateway);
        $pmgw = $this->get_pmgw_srv();

        $vars["payment_gateway"] = $extend_from_payment_gateway;
        if ($pmgw->init($vars) !== FALSE) {
            if ($pmgw->so->get_amount()) {
                $pmgw->checkout($debug);
            } else {
                if (!isset($vars["all_virtual"])) {
                    $vars["all_virtual"] = $this->get_so_srv()->get_prod_srv()->check_all_virtual($_SESSION["cart"][PLATFORMID]);
                }

                if (!isset($vars["all_trial"])) {
                    $vars["all_trial"] = $this->get_so_srv()->get_prod_srv()->check_all_trial($_SESSION["cart"][PLATFORMID]);
                }

                if ($vars["all_trial"] && $vars["all_virtual"]) {
                    $this->get_so_srv()->update_complete_order($pmgw->so);
                    $pmgw->fire_success_event();
                    $pmgw->unset_variable();
                    $this->redirect_success($pmgw->so->get_so_no());
                } else {
                    $this->redirect_fail();
                }
            }
        } else {
            $this->redirect_fail();
        }
    }

    public function redirect_success($so_no)
    {
        echo "<script>document.location.href='" . base_url() . "checkout/payment_result/1/{$so_no}';</script>";
    }

    public function redirect_fail()
    {
        $browser = @get_browser(null, true);
        $url = base_url() . "checkout_facebook/payment_result/0";
        /*      if ($browser["javascript"])
                {
                    echo "<script>top.document.location.href='$url';</script>";
                }
                else
                {*/
        echo "<script>document.location.href='" . $url . "';</script>";
//      }
    }
}

/* End of file payment_gateway_facebook_service.php */
/* Location: ./system/application/libraries/service/Payment_gateway_facebook_service.php */