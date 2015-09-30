<?php
include_once "ProfitVarHelper.php";
class Profit_var extends ProfitVarHelper
{
    private $appId = "MST0004";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->sc['Authorization']->checkAccessRights($this->getAppId(), "");
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function index()
    {
        $data = [];
        include_once APPPATH . '/language/' . $this->getAppId() . '00_' . $this->getLangId() . '.php';
        $data["lang"] = $lang;
        $data["selling_platform_list"] = $this->sc['profitVarModel']->getSellingPlatformList(["status" => 1], ["limit" => -1]);
        $data['notice'] = notice($lang);
        $this->load->view("mastercfg/profit_var/profit_var_index", $data);
    }

    public function view($value = "")
    {
        $data = [];
        $data["editable"] = 0;
        $data["updated"] = 0;
        if ($this->input->post('posted')) {
            $this->sc['profitVarModel']->autoload();

            $obj = unserialize($_SESSION["profit_obj"]);
            $obj->setSellingPlatformId($this->input->post("id"));
            $obj->setVatPercent($this->input->post("vat"));
            $obj->setForexFeePercent($this->input->post("forex_fee_percent"));
            $obj->setPlatformCurrencyId($_POST["currency"]);
            $obj->setPlatformCountryId($this->input->post('platform_country_id'));
            $obj->setLanguageId($this->input->post('language_id'));
            $obj->setPaymentChargePercent($this->input->post('pcp'));
            $obj->setAdminFee($this->input->post('admin_fee') * 1);
            $obj->setDeliveryType($this->input->post('delivery_type'));
            $obj->setDestCountry($this->input->post('platform_country_id'));
            $obj->setFreeDeliveryLimit($this->input->post('free_dlvry_limit'));

            if ($this->input->post("type") == "update") {
                $ret = $this->sc['profitVarModel']->update($obj);
            } else {
                $ret = $this->sc['profitVarModel']->add($obj);
            }

            // update price_margin tb for all platforms
            $platform_id = $this->input->post("id");
            $this->sc['PriceMargin']->refreshAllPlatformMargin(["id" => $platform_id]);

            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = __LINE__ . " : " . $this->db->_error_message();
            } else {
                unset($_SESSION["NOTICE"]);
                $data["updated"] = 1;
            }
        }

        $canedit = 1;
        if ($canedit) {
            $data["editable"] = 1;
        }

        include_once APPPATH . '/language/' . $this->getAppId() . '02_' . $this->getLangId() . '.php';
        $data["lang"] = $lang;
        $platform = $this->sc['profitVarModel']->checkPlatform($value);
        if (empty($platform)) {
            unset($data);
            $_SESSION["NOTICE"] = __LINE__ . " : " . $this->db->_error_message();
            Redirect(base_url() . "mastercfg/profit_var/index/");
        } else {
            $data["action"] = "update";
            $platform_bizvar_obj = $this->sc['profitVarModel']->getPlatformBizVar($value);
            if (empty($platform_bizvar_obj)) {
                $platform_bizvar_obj = $this->sc['profitVarModel']->getPlatformBizVar();
                $data["action"] = "add";
            }
        }
        $data["profit_obj"] = $platform_bizvar_obj;
        $data["courier_list"] = $this->sc['profitVarModel']->getCourierList();
        $data["delivery_type_list"] = $this->sc['profitVarModel']->getDeliveryTypeList();
        $data["region_list"] = $this->sc['profitVarModel']->getCourierRegionList();
        $data["selling_platform_list"] = $this->sc['profitVarModel']->getSellingPlatformList();
        $data["country_list"] = $this->sc['profitVarModel']->getCountryList([], ["limit" => -1, "orderby" => "name"]);
        $data["active_country_list"] = $this->sc['profitVarModel']->getCountryList(["status" => 1], ["limit" => -1, "orderby" => "name"]);
        $data["language_list"] = $this->sc['Language']->getDao('Language')->getList(["status" => 1], ["limit" => -1, "orderby" => "lang_name"]);
        $_SESSION["profit_obj"] = serialize($data["profit_obj"]);
        $data["currency_list"] = $this->sc['profitVarModel']->getCurrencyList();
        $data["id"] = $value;
        $data["header"] = "";
        $data["title"] = "";
        $data["notice"] = notice($lang);
        $this->load->view("mastercfg/profit_var/profit_var_view", $data);
    }

}
