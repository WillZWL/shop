<?php

class Template_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/payment_gateway_service');
        $this->load->library('service/client_service');
    }

    public function load_default($region, $preLoadData)
    {
        if ($region == "header")
        {
            return $this->_loadDefaultTemplateHeader($preLoadData);
        }
        else if ($region == "footer")
        {
            return $this->_loadDefaultTemplateFooter($preLoadData);
        }
        return array();
    }

    private function _loadDefaultTemplateHeader($preLoadData)
    {
        $data = array();
//      $data["searchAction"] = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://"
        $data["searchAction"] = "/search/search_by_ss";
        $data["cart"]["item"] = 0;

        $cart_info = $preLoadData["cart_info"];
        if($cart_info["cart"])
        {
            foreach($cart_info["cart"] AS $key => $arr)
            {
                $data["cart"]["item"] += $arr["qty"];
            }
        }
        return $data;
    }

    private function _loadDefaultTemplateFooter($preLoadData)
    {
        $data["back_url"] = urlencode(base_url() . uri_string() . ($_SERVER["QUERY_STRING"] ? "?" . $_SERVER["QUERY_STRING"] : ""));
        $data["domain"] = check_domain();
        $data["site_url"] = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/";
        $data["curreny_str"] = json_encode($platform_currency);
        $data["chk_cart"] = "";
        $data["emptyCart"] = 1;
        $data["controller_path"] = $preLoadData["controller_path"];
        if ($data["controller_path"] == "/common/redirect_controller/index")
            $data["controller_path"] = "";
        $data["query_string"] = urlencode($_SERVER["QUERY_STRING"] ? "?" . $_SERVER["QUERY_STRING"] : "");
        if($_SESSION["cart"][PLATFORMID])
        {
            $data["chk_cart"] = base64_encode(serialize($_SESSION["cart"][PLATFORMID]));
            $data["emptyCart"] = 2;
        }

        return $data;
    }
}
