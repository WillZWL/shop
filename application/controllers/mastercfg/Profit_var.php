<?php
use AtomV2\Service\AuthorizationService;
use AtomV2\Service\PriceMarginService;

include_once "Profit_var_helper.php";

class Profit_var extends Profit_var_helper
{
    private $appId = "MST0004";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->authorizationService = new AuthorizationService;
        $this->authorizationService->checkAccessRights($this->getAppId(), "");
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function index()
    {
        $data = [];
        include_once APPPATH . '/language/' . $this->getAppId() . '00_' . $this->_get_lang_id() . '.php';
        $data["lang"] = $lang;
        $data["selling_platform_list"] = $this->profit_var_model->get_selling_platform_list(["status" => 1], ["limit" => -1]);
        $data['notice'] = notice($lang);
        $this->load->view("mastercfg/profit_var/profit_var_index", $data);
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function view($value = "")
    {
        $data = [];
        $data["editable"] = 0;
        $data["updated"] = 0;
        if ($this->input->post('posted')) {
            $this->profit_var_model->__autoload();
            $this->priceMarginService = new PriceMarginService;

            $obj = unserialize($_SESSION["profit_obj"]);
            $obj->set_selling_platform_id($this->input->post("id"));
            $obj->set_vat_percent($this->input->post("vat"));
            $obj->set_forex_fee_percent($this->input->post("forex_fee_percent"));
            $obj->set_platform_currency_id($_POST["currency"]);
            $obj->set_platform_country_id($this->input->post('platform_country_id'));
            $obj->set_language_id($this->input->post('language_id'));
            $obj->set_payment_charge_percent($this->input->post('pcp'));
            $obj->set_admin_fee($this->input->post('admin_fee') * 1);
            $obj->set_delivery_type($this->input->post('delivery_type'));
            $obj->set_dest_country($this->input->post('platform_country_id'));
            $obj->set_free_delivery_limit($this->input->post('free_dlvry_limit'));

            if ($this->input->post("type") == "update") {
                $ret = $this->profit_var_model->update($obj);
            } else {
                $ret = $this->profit_var_model->add($obj);
            }

            // update price_margin tb for all platforms
            $platform_id = $this->input->post("id");
            $this->priceMarginService->refreshAllPlatformMargin(["id" => $platform_id]);

            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = __LINE__ . " : " . $this->db->_error_message();
            } else {
                unset($_SESSION["NOTICE"]);
                $data["updated"] = 1;
            }
        }
        //determine whether user has the rights to edit
        $canedit = 1;
        if ($canedit) {
            $data["editable"] = 1;
        }
        //end determination
        include_once APPPATH . '/language/' . $this->getAppId() . '02_' . $this->_get_lang_id() . '.php';
        $data["lang"] = $lang;
        $platform = $this->profit_var_model->check_platform($value);
        if (empty($platform)) {
            unset($data);
            $_SESSION["NOTICE"] = __LINE__ . " : " . $this->db->_error_message();
            Redirect(base_url() . "mastercfg/profit_var/index/");
        } else {
            $data["action"] = "update";
            $platform_bizvar_obj = $this->profit_var_model->get_platform_biz_var($value);
            if (empty($platform_bizvar_obj)) {
                $platform_bizvar_obj = $this->profit_var_model->get_platform_biz_var();
                $data["action"] = "add";
            }
        }
        $data["profit_obj"] = $platform_bizvar_obj;
        $data["courier_list"] = $this->profit_var_model->get_courier_list();
        $data["delivery_type_list"] = $this->profit_var_model->get_delivery_type_list();
        $data["region_list"] = $this->profit_var_model->get_courier_region_list();
        $data["selling_platform_list"] = $this->profit_var_model->get_selling_platform_list();
        $data["country_list"] = $this->profit_var_model->get_country_list([], ["limit" => -1, "orderby" => "name"]);
        $data["active_country_list"] = $this->profit_var_model->get_country_list(["status" => 1], ["limit" => -1, "orderby" => "name"]);
        $data["language_list"] = $this->language_service->get_list(["status" => 1], ["limit" => -1, "orderby" => "name"]);
        $_SESSION["profit_obj"] = serialize($data["profit_obj"]);
        $data["currency_list"] = $this->profit_var_model->get_currency_list();
        $data["id"] = $value;
        $data["header"] = "";
        $data["title"] = "";
        $data["notice"] = notice($lang);
        $this->load->view("mastercfg/profit_var/profit_var_view", $data);
    }

}

?>