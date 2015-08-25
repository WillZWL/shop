<?php
include_once "ExchangeRateHelper.php";

class Exchange_rate extends ExchangeRateHelper
{
    private $appId = "MST0003";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->authorization_service->check_access_rights($this->getAppId(), "");
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function view($value = "")
    {
        $data = array();
        $data["updated"] = 0;
        $data["editable"] = 0;
        if ($this->input->post('posted')) {
            $obj = $this->exchange_rate_model->get_exchange_rate();
            $approval_obj = $this->exchange_rate_model->get_based_rate($value, $this->currency_list, "approval");
            $base = $value;
            if ($this->input->post('type') == 'edit') {
                foreach ($_POST as $key => $exvalue) {
                    if ($key != "posted" && $key != "base" && $key != "type") {
                        $change = $this->exchange_rate_model->compare_difference($base, $key, $exvalue);
                        if ($change) {
                            $remark .= $change;
                        }
                        $ret = $this->exchange_rate_model->alter_exchange_rate($base, $key, $exvalue, "approval");
                        if ($ret === "FALSE") {
                            $_SESSION["notice"] = "Update_Failed";
                        } else {
                            unset($_SESSION["notice"]);
                            $data["updated"] = 1;
                        }
                    }
                }
                if ($ret && $remark) {
                    $email_to = $this->context_config_service->value_of("alan_email");
                    if ($email_to) {
                        $this->exchange_rate_model->notification_email($email_to, $remark);
                    }
                }
            }
            if ($this->input->post('type') == 'approve') {
                foreach ($_POST as $key => $exvalue) {
                    if ($key != "posted" && $key != "base" && $key != "type") {
                        $ret = $this->exchange_rate_model->alter_exchange_rate($base, $key, $exvalue);
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

        include_once APPPATH . '/language/' . $this->getAppId() . '02_' . $this->_get_lang_id() . '.php';
        $sub_app_id = $this->getAppId() . "02";
        $data["lang"] = $lang;

        if ($canedit) {
            $data["editable"] = 1;
        }
        $data["base"] = $value;
        $data["currency_list"] = $this->currency_list;
        $data["currency_full_list"] = $this->exchange_rate_model->get_active_currency_obj_list(array(), array("orderby" => "name ASC"));
        $data["exchange_rate"] = $this->exchange_rate_model->get_based_rate($value, $this->currency_list);
        $data["exchange_rate_approval"] = $this->exchange_rate_model->get_based_rate($value, $this->currency_list, "approval");
        $approval = $this->exchange_rate_model->get_exchange_rate_approval_list(array("from_currency_id" => $value), array("orderby" => "approval_status"));
        $data["approval"] = "1";
        foreach ($approval AS $obj) {
            if ($obj->get_approval_status() == 0) {
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
        if ($this->authorization_service->check_access_rights($sub_app_id, "Edit", "0")) {
            $data["type"] = "edit";
        } elseif ($this->authorization_service->check_access_rights($sub_app_id, "Approve", "0")) {
            $data["type"] = "approve";
        }
        $this->load->view('mastercfg/exchange_rate/exchange_rate_view', $data);

    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function index()
    {
        include_once APPPATH . '/language/' . $this->getAppId() . '00_' . $this->_get_lang_id() . '.php';
        $data["lang"] = $lang;
        $data["title"] = "Exchange Rate Management";
        $data["header"] = "Please select the base currency";
        $data["currency_list"] = $this->currency_list;
        $this->load->view('mastercfg/exchange_rate/exchange_rate_index', $data);
    }

    public function insert()
    {
        $this->exchange_rate_model->upload_exchange_rate();

    }
}

?>