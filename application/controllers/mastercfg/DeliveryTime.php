<?php
class Deliverytime extends MY_Controller
{

    private $appId = "MST0018";
    private $langId = "en";

    private $default_delivery;

    public function __construct()
    {
        parent::__construct();
        $this->default_delivery = $this->container['contextConfigService']->valueOf("default_delivery_type");
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";

        if ($this->input->post('posted')) {
            $result = $this->update_form($_POST["postdata"]);
            $data["postdata"] = $_POST["postdata"];
            $_SESSION["NOTICE"] = $result["msg"];
        }

        $data["scenario_list"] = $this->container['deliverytimeModel']->deliverytimeService->getDeliveryScenarioList();
        $data["country_list"] = $this->container['deliverytimeModel']->countryService->getSellCountryList();
        $del_list = $this->container['deliverytimeModel']->deliverytimeService->getDeliverytimeList();

        $del_list_by_country = [];
        if ($data["country_list"] && $del_list && $data["scenario_list"]) {
            foreach ($data["country_list"] as $countryobj) {
                $countrycode = $countryobj->getCountryId();
                foreach ($del_list as $delobj) {
                    $scenarioid = $delobj->getScenarioid();
                    if ($delobj->getCountryId() == $countrycode) {
                        $del_list_by_country[$countrycode][] = $delobj;
                    }
                }
            }
        }

        $data["del_list_by_country"] = $del_list_by_country;

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $this->load->view('mastercfg/deliverytime/deliverytime_index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    private function update_form($postdata = [])
    {
        $ret = [];
        $ret["status"] = FALSE;
        $deliverytimeDao = $this->container['deliverytimeModel']->deliverytimeService->getDao();

        if ($postdata) {
            $success = 1;
            $error_msg = "The following could not be updated: ";
            $email_msg = "";

            if ($scenario_list = $this->container['deliverytimeModel']->deliverytimeService->getDeliveryScenarioList()) {
                foreach ($scenario_list as $obj) {
                    $scenario[$obj->id] = $obj->name;
                }
            }

            foreach ($postdata as $ctry_id => $value) {
                $data_exists = false;
                if (($check_empty = $this->container['deliverytimeModel']->deliverytimeService->checkEmptyFields($value)) === false) {
                    $success = 0;
                    $error_msg .= "\n" . __LINE__ . " All scenarios of country<$ctry_id> must be filled.";
                    continue;
                }

                foreach ($value as $scenarioid => $data) {
                    $ship_min_day = trim($data["ship_min_day"]);
                    $ship_max_day = trim($data["ship_max_day"]);
                    $del_min_day = trim($data["del_min_day"]);
                    $del_max_day = trim($data["del_max_day"]);
                    if (isset($data["margin"])) {
                        $margin = trim($data["margin"]);
                    } else {
                        $margin = "";
                    }

                    if (($ship_min_day == "" && $ship_max_day == "") && ($del_min_day == "" && $del_max_day == "")) {
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
                        $success = 0;
                        $error_msg .= "\n" . __LINE__ . " Condition fail country<$ctry_id>, scenario<{$scenario[$scenarioid]}>. Min Delivery Day is smaller than min Ship Day.";
                        continue;
                    }

                    $where["scenarioid"] = $scenarioid;
                    $where["country_id"] = $ctry_id;
                    if ($deliverytime_obj = $deliverytimeDao->get($where)) {
                        $old_ship_min = $deliverytime_obj->getShipMinDay();
                        $old_ship_max = $deliverytime_obj->getShipMaxDay();
                        $old_del_min = $deliverytime_obj->getDelMinDay();
                        $old_del_max = $deliverytime_obj->getDelMaxDay();
                        $old_margin = $deliverytime_obj->getMargin();

                        if (
                            $ship_min_day != $old_ship_min ||
                            $ship_max_day != $old_ship_max ||
                            $del_min_day != $old_del_min ||
                            $del_max_day != $old_del_max ||
                            $margin != $old_margin
                        ) {
                            $deliverytime_obj->setShipMinDay($ship_min_day);
                            $deliverytime_obj->setShipMaxDay($ship_max_day);
                            $deliverytime_obj->setDelMinDay($del_min_day);
                            $deliverytime_obj->setDelMaxDay($del_max_day);

                            if ($scenarioid == 5 && $margin) {
                                $deliverytime_obj->setMargin($margin);
                            }

                            if ($deliverytimeDao->update($deliverytime_obj) === FALSE) {
                                $success = 0;
                                $error_msg .= "\n" . __LINE__ . " Update fail country<$ctry_id>, scenario<{$scenario[$scenarioid]}>. DB Error: " . $this->db->_error_message();
                            } else {
                                $margin_txt = "";
                                if ($scenarioid == 5) {
                                    $margin_txt = "\n  [Margin: OLD => $old_margin || NEW => $margin]. ";
                                }
                                $email_msg .= "\nUPDATED <$ctry_id>, scenario<{$scenario[$scenarioid]}>: \n  [Ship Days OLD => $old_ship_min - $old_ship_max || NEW => $ship_min_day - $ship_max_day]. \n  [Delivery Days OLD => $old_del_min - $old_del_max || NEW => $del_min_day - $del_max_day]. $margin_txt";
                            }
                        }
                    } else {
                        $deliverytimeVo = $deliverytimeDao->get();
                        $deliverytimeVo->setScenarioid($scenarioid);
                        $deliverytimeVo->setCountryId($ctry_id);
                        $deliverytimeVo->setShipMinDay($ship_min_day);
                        $deliverytimeVo->setShipMaxDay($ship_max_day);
                        $deliverytimeVo->setDelMinDay($del_min_day);
                        $deliverytimeVo->setDelMaxDay($del_max_day);
                        $deliverytimeVo->setMargin($margin);
                        $deliverytimeVo->setStatus(1);

                        if ($deliverytimeDao->insert($deliverytimeVo) === FALSE) {
                            $success = 0;
                            $error_msg .= "\n" . __LINE__ . " Add fail country<$ctry_id>, scenario<{$scenario[$scenarioid]}>. DB Error: " . $this->db->_error_message();
                        } else {
                            $margin_txt = "";
                            if ($scenarioid == 5) {
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
                $this->container['deliverytimeModel']->deliverytimeService->sendNotificationEmail("CHG", $email_msg);
            }
        } else {
            $ret["msg"] = "deliverytime LINE: " . __LINE__ . " postdata is empty.";
        }

        return $ret;
    }

    public function getLangId()
    {
        return $this->langId;
    }
}
