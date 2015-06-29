<?php

class Currency extends CI_Controller
{

    private $curr_srv;
    private $pbv_srv;

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/Currency_service.php");
        $this->set_curr_srv(new Currency_service());
        include_once(APPPATH . "libraries/service/Platform_biz_var_service.php");
        $this->set_pbv_srv(new Platform_biz_var_service());
    }

    function load_currency()
    {
        $is_pub = (strpos(CTRLPATH, "public_controllers") !== FALSE);

        if (!isset($_SESSION["CURRENCY"][$_SESSION["domain_platform"]["platform_id"]])) {
            $_SESSION["CURRENCY"] = $this->get_curr_srv()->pre_load_currency_list($is_pub ? $_SESSION["domain_platform"]["platform_currency_id"] : NULL);
        }

        if (!isset($_SESSION["PLATFORM_CURRENCY"][$_SESSION["domain_platform"]["platform_id"]])) {
            $_SESSION["PLATFORM_CURRENCY"] = $this->get_pbv_srv()->pre_load_platform_currency_list($is_pub ? $_SESSION["domain_platform"]["platform_id"] : NULL);
        }
    }

    public function get_curr_srv()
    {
        return $this->curr_srv;
    }

    public function set_curr_srv(Base_service $srv)
    {
        $this->curr_srv = $srv;
    }

    public function get_pbv_srv()
    {
        return $this->pbv_srv;
    }

    public function set_pbv_srv(Base_service $srv)
    {
        $this->pbv_srv = $srv;
    }

}