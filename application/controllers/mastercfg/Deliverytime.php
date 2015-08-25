<?php

class Deliverytime extends MY_Controller
{

    private $appId = "MST0018";
    private $lang_id = "en";

    private $default_delivery;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mastercfg/deliverytime_model');
        $this->load->helper(array('url', 'notice', 'object'));
        $this->load->library('service/context_config_service');
        $this->default_delivery = $this->context_config_service->value_of("default_delivery_type");
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";

        if ($this->input->post('posted')) {
            $result = $this->update_form($_POST["postdata"]);
            $data["postdata"] = $_POST["postdata"];
            $_SESSION["NOTICE"] = $result["msg"];
        }

        $data["scenario_list"] = $this->deliverytime_service->get_delivery_scenario_list();
        $data["country_list"] = $this->country_service->get_sell_country_list();
        $del_list = $this->deliverytime_service->get_deliverytime_list();

        $del_list_by_country = array();
        if ($data["country_list"] && $del_list && $data["scenario_list"]) {
            foreach ($data["country_list"] as $countryobj) {
                $countrycode = $countryobj->get_id();
                foreach ($del_list as $delobj) {
                    // consolidate delivery list by country & scenario id
                    $scenarioid = $delobj->get_scenarioid();
                    if ($delobj->get_country_id() == $countrycode) {
                        $del_list_by_country[$countrycode][] = $delobj;
                    }
                }
            }
        }

        $data["del_list_by_country"] = $del_list_by_country;

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $this->load->view('mastercfg/deliverytime/deliverytime_index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    private function update_form($postdata = array())
    {
        $ret = array();
        $ret["status"] = FALSE;
        $deliverytime_dao = $this->deliverytime_service->get_dao();

        if ($postdata) {
            $success = 1;
            $error_msg = "The following could not be updated: ";

            if ($scenario_list = $this->deliverytime_service->get_delivery_scenario_list()) {
                // get scenario names and compile by scenarioid
                foreach ($scenario_list as $obj) {
                    $scenario[$obj->id] = $obj->name;
                }
            }

            foreach ($postdata as $ctry_id => $value) {
                $data_exists = false;
                if (($check_empty = $this->check_empty_fields($value)) === false) {
                    $success = 0;
                    $error_msg .= "\n" . __LINE__ . " All scenarios of country<$ctry_id> must be filled.";
                    continue;
                }

                foreach ($value as $scenarioid => $data) {
                    $ship_min_day = trim($data["ship_min_day"]);
                    $ship_max_day = trim($data["ship_max_day"]);
                    $del_min_day = trim($data["del_min_day"]);
                    $del_max_day = trim($data["del_max_day"]);
                    $margin = trim($data["margin"]);

                    if (($ship_min_day == "" && $ship_max_day == "") && ($del_min_day == "" && $del_max_day == "")) {
                        // skip the loop if both min & max fields are empty for each country-scenario
                        continue;
                    }

                    if (($ship_min_day && $ship_max_day == "") || ($ship_max_day && $ship_min_day == "") || ($ship_max_day < $ship_min_day)) {
                        $success = 0;
                        $error_msg .= "\n" . __LINE__ . " Condition fail country<$ctry_id>, scenario<{$scenario[$scenarioid]}>. Min/Max Ship Day is empty or max smaller than min Ship Day.";
                        continue;
                    }

                    if (($del_min_day && $del_max_day == "") || ($del_max_day && $del_min_day == "") || ($del_max_day < $del_min_day)) {
                        $success = 0;
                        $error_msg .= "\n" . __LINE__ . " Condition fail country<$ctry_id>, scenario<{$scenario[$scenarioid]}>. Min/Max Delivery Day is empty or max smaller than min Delivery Day.";
                        continue;
                    }

                    if ($ship_min_day > $del_min_day) {
                        // delivery days includes ship/dispatch days, so must always be > min_ship_day
                        // in the case of HK, ship days can be = delivery days
                        $success = 0;
                        $error_msg .= "\n" . __LINE__ . " Condition fail country<$ctry_id>, scenario<{$scenario[$scenarioid]}>. Min Delivery Day is smaller than min Ship Day.";
                        continue;
                    }

                    $where["scenarioid"] = $scenarioid;
                    $where["country_id"] = $ctry_id;
                    if ($deliverytime_obj = $deliverytime_dao->get($where)) {
                        // If existing delivery_time exist, record previous numbers to send email
                        $old_ship_min = $deliverytime_obj->get_ship_min_day();
                        $old_ship_max = $deliverytime_obj->get_ship_max_day();
                        $old_del_min = $deliverytime_obj->get_del_min_day();
                        $old_del_max = $deliverytime_obj->get_del_max_day();
                        $old_margin = $deliverytime_obj->get_margin();

                        if (
                            $ship_min_day != $old_ship_min ||
                            $ship_max_day != $old_ship_max ||
                            $del_min_day != $old_del_min ||
                            $del_max_day != $old_del_max ||
                            $margin != $old_margin
                        ) {

                            // only come in here if any data has changed
                            $deliverytime_obj->set_ship_min_day($ship_min_day);
                            $deliverytime_obj->set_ship_max_day($ship_max_day);
                            $deliverytime_obj->set_del_min_day($del_min_day);
                            $deliverytime_obj->set_del_max_day($del_max_day);

                            // only HighMargin scenario gets to update margin
                            if ($scenarioid == 5)
                                $deliverytime_obj->set_margin($margin);

                            if ($deliverytime_dao->update($deliverytime_obj) === FALSE) {
                                $success = 0;
                                $error_msg .= "\n" . __LINE__ . " Update fail country<$ctry_id>, scenario<{$scenario[$scenarioid]}>. DB Error: " . $this->db->_error_message();
                            } else {
                                // upadate success! compile list of changes.
                                $margin_txt = "";
                                if ($scenarioid == 5) {
                                    // if HighMargin, then add in changed margin
                                    $margin_txt = "\n  [Margin: OLD => $old_margin || NEW => $margin]. ";
                                }
                                $email_msg .= "\nUPDATED <$ctry_id>, scenario<{$scenario[$scenarioid]}>: \n  [Ship Days OLD => $old_ship_min - $old_ship_max || NEW => $ship_min_day - $ship_max_day]. \n  [Delivery Days OLD => $old_del_min - $old_del_max || NEW => $del_min_day - $del_max_day]. $margin_txt";
                            }
                        }
                    } else {
                        // no existing record; insert
                        $deliverytime_vo = $deliverytime_dao->get();
                        $deliverytime_vo->set_scenarioid($scenarioid);
                        $deliverytime_vo->set_country_id($ctry_id);
                        $deliverytime_vo->set_ship_min_day($ship_min_day);
                        $deliverytime_vo->set_ship_max_day($ship_max_day);
                        $deliverytime_vo->set_del_min_day($del_min_day);
                        $deliverytime_vo->set_del_max_day($del_max_day);
                        $deliverytime_vo->set_margin($margin);
                        $deliverytime_vo->set_status(1);

                        if ($deliverytime_dao->insert($deliverytime_vo) === FALSE) {
                            $success = 0;
                            $error_msg .= "\n" . __LINE__ . " Add fail country<$ctry_id>, scenario<{$scenario[$scenarioid]}>. DB Error: " . $this->db->_error_message();
                        } else {
                            // insert success! compile list of changes.
                            $margin_txt = "";
                            if ($scenarioid == 5) {
                                // if HighMargin, then add in changed margin
                                $margin_txt = "\n  [Margin (NEW => $margin)]. ";
                            }
                            $email_msg .= "\nADDED <$ctry_id>, scenario<{$scenario[$scenarioid]}>: \n  [Ship Days $ship_min_day - $ship_max_day]. \n  [Delivery Days $del_min_day - $del_max_day].";
                        }
                    }
                }
            }

            if ($success == 0) {
                $ret["msg"] = $error_msg;
            } else {
                $ret["status"] = TRUE;
                $ret["msg"] = "SUCCESS!";
            }

            if ($email_msg) {
                $email_msg = "Changes made to ship/delivery time frames: " . $email_msg;
                $this->send_notification_email("CHG", $email_msg);
            }
        } else {
            $ret["msg"] = "deliverytime LINE: " . __LINE__ . " postdata is empty.";
        }

        return $ret;
    }

    private function check_empty_fields($value = array())
    {
        /* =============================================================
            This function checks postdata to ensure that if one scenarioid
            has data input, all the scenario id in the country must all
            be filled.
        ============================================================= */

        if (is_array($value) && !empty($value)) {
            foreach ($value as $scenarioid => $data) {
                if (
                    trim($data["ship_min_day"]) !== "" ||
                    trim($data["ship_max_day"]) !== "" ||
                    trim($data["del_min_day"]) !== "" ||
                    trim($data["del_max_day"]) !== "" ||
                    trim($data["margin"]) !== ""
                ) {
                    $data_exists = TRUE;
                }
            }

            $success = true;
            if ($data_exists) {
                foreach ($value as $scenarioid => $data) {
                    // if any of the array is empty, then not success
                    if (array_search('', $data) !== false) {
                        $success = false;
                    }
                }
            }

            return $success;
        }
        return false;
    }

    private function send_notification_email($type, $msg = "")
    {
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new phpmailer();
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.net>";

        switch ($type) {
            case "CHG":
                $message = $msg;
                $title = "NOTICE - Delivery time frames have been changed.";
                break;
        }

        $phpmail->AddAddress("csmanager@eservicesgroup.net");
        $phpmail->AddAddress("itsupport@eservicesgroup.net");
        $phpmail->Subject = "$title";
        $phpmail->IsHTML(false);
        $phpmail->Body = $message;

        if (strpos($_SERVER['HTTP_HOST'], 'dev') === FALSE)
            $result = $phpmail->Send();
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
}
