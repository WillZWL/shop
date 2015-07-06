<?php

class Domain_platform extends CI_Controller
{
    private $dp_srv;
    private $cur_country_id;
    private $cur_domain_type;

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/Domain_platform_service.php");
        $this->set_dp_srv(new Domain_platform_service());
    }

    function update_doamin_platform()
    {
        $this->cur_country_id = (isset($_SESSION['country_code_from_hook']) ? $_SESSION['country_code_from_hook'] : '');
        $this->cur_domain_type = $this->get_dp_srv()->get_config()->value_of("is_dev_site") ? -1 : 1;
        // TODO
        // need to understand PLATFORM_TYPE
        //
        // if (defined('PLATFORM_TYPE')) {
        //  if ($_SESSION["domain_platform"]["type"] != PLATFORM_TYPE) {
        //      $_SESSION["domain_platform"] = $this->get_dp_srv()->get_by_country_id($this->cur_country_id, PLATFORM_TYPE, $this->cur_domain_type, "array");
        //  }
        // } elseif ((!isset($_SESSION["domain_platform"])) && ($_SESSION["domain_platform"]["type"] != "WEBSITE")) {
        //  $_SESSION["domain_platform"] = $this->get_dp_srv()->get_domain_platform_w_lang("array");
        // } elseif (isset($_SESSION["domain_platform"])) {
        //  if ($_SESSION["domain_platform"]["platform_country_id"] != $this->cur_country_id) {
        //      $_SESSION["domain_platform"] = $this->get_dp_srv()->get_by_country_id($this->cur_country_id, $_SESSION["domain_platform"]["type"], $this->cur_domain_type, "array");
        //  }
        // }
        if (!$_SESSION["domain_platform"]) {
            if ($this->cur_country_id == '') {
                $_SESSION["domain_platform"] = $this->get_dp_srv()->get_domain_platform_w_lang("array");
            } else {
                $_SESSION["domain_platform"] = $this->get_dp_srv()->get_by_country_id($this->cur_country_id, "WEBSITE", $this->cur_domain_type, "array");
                // If the domain platform cannot be get by some reason, use the default
                if (!$_SESSION["domain_platform"]) {
                    $_SESSION["domain_platform"] = $this->get_dp_srv()->get_domain_platform_w_lang("array");
                }
            }
        }

        if (strpos(CTRLPATH, "public_controllers") !== FALSE && ($_SESSION["domain_platform"]["type"] == "WEBSITE" || (defined('CHECK_REDIRECT_DOMAIN') && CHECK_REDIRECT_DOMAIN))) {
            $this->redirect_domain();
        }
    }

    public function redirect_domain()
    {
        include_once(APPPATH . "helpers/string_helper.php");
        $domain = check_domain();
        $back_url = urlencode(current_url() . ($_SERVER['QUERY_STRING'] ? "?" . $_SERVER['QUERY_STRING'] : ""));

        if (!isset($_POST["custom_country_id"]) && (isset($_COOKIE["back_url"]) && $back_url == $_COOKIE["back_url"])) {
            setcookie("back_url", "", time() - 86400, "/", "." . $domain);
            unset($_COOKIE["back_url"]);

            if (isset($_COOKIE["custom_country_id"])) {
                setcookie("custom_country_id", "", time() - 86400, "/", "." . $domain);
            }
        } else {
            if ($this->get_dp_srv()->get_config()->value_of("redirect_domain")) {
                if (!isset($_SESSION["ip_info"]["platform_country_id"])) {
                    $ar_platform = $this->get_dp_srv()->get_country_platform_domain($this->cur_country_id, $this->cur_domain_type);
                    $platform_domain = $ar_platform["domain"];
                    $platform_currency = $ar_platform["currency"];

                    if (isset($_POST["custom_country_id"])) {
                        setcookie("custom_country_id", $this->cur_country_id, 0, "/", "." . $domain);
                        $_COOKIE["custom_country_id"] = $this->cur_country_id;
                    }

                    $_SESSION["ip_info"] = array("ip" => $_SERVER["REMOTE_ADDR"], "platform_country_id");
                }
            }
        }
    }

    public function get_dp_srv()
    {
        return $this->dp_srv;
    }

    public function set_dp_srv($srv)
    {
        $this->dp_srv = $srv;
    }

}
