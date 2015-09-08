<?php
include_once "ExchangeRateHelper.php";

class Exchange_rate extends ExchangeRateHelper
{
    private $appId = "MST0003";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->container['authorizationService']->checkAccessRights($this->getAppId(), "");
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function view($value = "")
    {
        $data = [];
        $data["updated"] = 0;
        $data["editable"] = 0;
        if ($this->input->post('posted')) {
            $obj = $this->container['exchangeRateModel']->getExchangeRate();
            $approval_obj = $this->container['exchangeRateModel']->getBasedRate($value, $this->currency_list, "approval");
            $base = $value;
            if ($this->input->post('type') == 'edit') {
                foreach ($_POST as $key => $exvalue) {
                    if ($key != "posted" && $key != "base" && $key != "type") {
                        $change = $this->container['exchangeRateModel']->compareDifference($base, $key, $exvalue);
                        if ($change) {
                            $remark .= $change;
                        }
                        $ret = $this->container['exchangeRateModel']->alterExchangeRate($base, $key, $exvalue, "approval");
                        if ($ret === "FALSE") {
                            $_SESSION["notice"] = "Update_Failed";
                        } else {
                            unset($_SESSION["notice"]);
                            $data["updated"] = 1;
                        }
                    }
                }
                if ($ret && $remark) {
                    $email_to = $this->container['contextConfigService']->valueOf("alan_email");
                    if ($email_to) {
                        $this->container['exchangeRateModel']->notificationEmail($email_to, $remark);
                    }
                }
            }
            if ($this->input->post('type') == 'approve') {
                foreach ($_POST as $key => $exvalue) {
                    if ($key != "posted" && $key != "base" && $key != "type") {
                        $ret = $this->container['exchangeRateModel']->alterExchangeRate($base, $key, $exvalue);
                        if ($ret === "FALSE") {
                            $_SESSION["notice"] = "Update_Failed";
                        } else {
                            unset($_SESSION["notice"]);
                            $data["updated"] = 1;
                        }
                    }
                }
            }
        }

        $canedit = 1;

        include_once APPPATH . '/language/' . $this->getAppId() . '02_' . $this->getLangId() . '.php';
        $sub_app_id = $this->getAppId() . "02";
        $data["lang"] = $lang;

        if ($canedit) {
            $data["editable"] = 1;
        }
        $data["base"] = $value;
        $data["currency_list"] = $this->currency_list;
        $data["currency_full_list"] = $this->container['exchangeRateModel']->getActiveCurrencyObjList([], ["orderby" => "name ASC"]);
        $data["exchange_rate"] = $this->container['exchangeRateModel']->getBasedRate($value, $this->currency_list);
        $data["exchange_rate_approval"] = $this->container['exchangeRateModel']->getBasedRate($value, $this->currency_list, "approval");
        $approval = $this->container['exchangeRateModel']->getExchangeRateApprovalList(["from_currency_id" => $value], ["orderby" => "approval_status"]);
        $data["approval"] = "1";
        foreach ($approval AS $obj) {
            if ($obj->getApprovalStatus() == 0) {
                $data["approval"] = "0";
                break;
            }
        }
        if ($data["exchange_rate"] === FALSE) {
            unset($data);
            $this->index();
            return;
        }
        $data["notice"] = notice($lang);
        $data["title"] = 'Exchange Rate Manage - Editing';
        $data["header"] = 'Editing ' . $this->currency_list[$value] . ' based exchange rates:';
        if ($this->container['authorizationService']->checkAccessRights($sub_app_id, "Edit", "0")) {
            $data["type"] = "edit";
        } elseif ($this->container['authorizationService']->checkAccessRights($sub_app_id, "Approve", "0")) {
            $data["type"] = "approve";
        }
        $this->load->view('mastercfg/exchange_rate/exchange_rate_view', $data);

    }

    public function index()
    {
        include_once APPPATH . '/language/' . $this->getAppId() . '00_' . $this->getLangId() . '.php';
        $data["lang"] = $lang;
        $data["title"] = "Exchange Rate Management";
        $data["header"] = "Please select the base currency";
        $data["currency_list"] = $this->currency_list;
        $this->load->view('mastercfg/exchange_rate/exchange_rate_index', $data);
    }

    public function insert()
    {
        $this->container['exchangeRateModel']->uploadExchangeRate();

    }
}
