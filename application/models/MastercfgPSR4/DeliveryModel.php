<?php
namespace AtomV2\Models\Mastercfg;

use AtomV2\Service\DeliveryService;
use AtomV2\Service\LanguageService;
use AtomV2\Service\CountryService;

class DeliveryModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->deliveryService = new DeliveryService;
        $this->languageService = new LanguageService;
        $this->countryService = new CountryService;

        // $this->load->library('service/region_service');
    }

    public function updateContent($vo, &$data)
    {
        foreach ($_POST["func_opt"] as $rs_lang_id => $rs_func_list) {
            foreach ($rs_func_list as $rs_func_id => $rs_id_list) {
                foreach ($rs_id_list as $rs_id => $rs_text) {
                    if ($rs_id == "new") {
                        if ($rs_text != "") {
                            $data["func_opt_list"][$rs_lang_id][$rs_func_id] = clone $vo["func_opt"];
                            $data["func_opt_list"][$rs_lang_id][$rs_func_id]->setFuncId($rs_func_id);
                            $data["func_opt_list"][$rs_lang_id][$rs_func_id]->setLangId($rs_lang_id);
                            $data["func_opt_list"][$rs_lang_id][$rs_func_id]->setText($rs_text);
                            if (!$this->deliveryService->funcOptionService->insert($data["func_opt_list"][$rs_lang_id][$rs_func_id])) {
                                $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                                return FALSE;
                            }
                        }
                    } else {
                        if ($data["func_opt_list"][$rs_lang_id][$rs_func_id]->getText() == $rs_text) {
                            continue;
                        } elseif ($rs_text == "") {
                            $fobj = $this->deliveryService->funcOptionService->get(["id" => $rs_id]);
                            if (!$this->deliveryService->funcOptionService->delete($fobj)) {
                                $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                                return FALSE;
                            }
                        } else {
                            $data["func_opt_list"][$rs_lang_id][$rs_func_id]->setText($rs_text);
                            if (!$this->deliveryService->funcOptionService->update($data["func_opt_list"][$rs_lang_id][$rs_func_id])) {
                                $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                                return FALSE;
                            }
                        }
                    }
                }
            }
        }

        foreach ($_POST["del_opt"] as $rs_lang_id => $rs_courier_list) {
            foreach ($rs_courier_list as $rs_courier_id => $rs_id_list) {
                foreach ($rs_id_list as $rs_id => $rs_display_name) {
                    if ($rs_id == "new") {
                        if ($rs_display_name != "") {
                            $data["del_opt_list"][$rs_lang_id][$rs_courier_id] = clone $vo["del_opt"];
                            $data["del_opt_list"][$rs_lang_id][$rs_courier_id]->setCourierId($rs_courier_id);
                            $data["del_opt_list"][$rs_lang_id][$rs_courier_id]->setLangId($rs_lang_id);
                            $data["del_opt_list"][$rs_lang_id][$rs_courier_id]->setDisplayName($rs_display_name);
                            if (!$this->deliveryService->deliveryOptionService->insert($data["del_opt_list"][$rs_lang_id][$rs_courier_id])) {
                                $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                                return FALSE;
                            }
                        }
                    } else {
                        if ($data["del_opt_list"][$rs_lang_id][$rs_courier_id]->getDisplayName() == $rs_display_name) {
                            continue;
                        } elseif ($rs_display_name == "") {
                            $dobj = $this->deliveryService->deliveryOptionService->get(["id" => $rs_id]);
                            if (!$this->deliveryService->deliveryOptionService->delete($dobj)) {
                                $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                                return FALSE;
                            }
                        } else {
                            $data["del_opt_list"][$rs_lang_id][$rs_courier_id]->setDisplayName($rs_display_name);
                            if (!$this->deliveryService->deliveryOptionService->update($data["del_opt_list"][$rs_lang_id][$rs_courier_id])) {
                                $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                                return FALSE;
                            }
                        }
                    }
                }
            }
        }

        return TRUE;
    }

    public function updateDelivery($vo, &$data)
    {
        foreach ($_POST["del"] as $rs_delivery_type_id => $rs_country_list) {
            foreach ($rs_country_list as $rs_country_id => $rs_data) {
                if (!empty($rs_data["status"])) {
                    $ar_status = each($rs_data["status"]);
                    $old_status = $ar_status["key"];
                    $new_status = $ar_status["value"];
                } else {
                    $new_status = 0;
                }

                $ar_min = each($rs_data["min"]);
                $old_min = $ar_min["key"] == '' ? 0 : $ar_min["key"];
                $new_min = $new_status ? $ar_min["value"] : 0;

                $ar_max = each($rs_data["max"]);
                $old_max = $ar_max["key"] == '' ? 0 : $ar_max["key"];
                $new_max = $new_status ? $ar_max["value"] : 0;

                if (!empty($data["delivery_list"][$rs_delivery_type_id][$rs_country_id])) {
                    $data["delivery_list"][$rs_delivery_type_id][$rs_country_id]->setStatus($new_status);
                    $data["delivery_list"][$rs_delivery_type_id][$rs_country_id]->setMinDay($new_min ? $new_min : 0);
                    $data["delivery_list"][$rs_delivery_type_id][$rs_country_id]->setMaxDay($new_max ? $new_max : 0);
                    if (!($old_status == $new_status && $old_min == $new_min && $old_max == $new_max) && !$this->deliveryService->update($data["delivery_list"][$rs_delivery_type_id][$rs_country_id])) {
                        $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                        return FALSE;
                    }
                } else {
                    $delivery_obj = $this->deliveryService->get();
                    $delivery_obj->setDeliveryTypeId($rs_delivery_type_id);
                    $delivery_obj->setCountryId($rs_country_id);
                    $delivery_obj->setMinDay(0);
                    $delivery_obj->setMaxDay(0);
                    $delivery_obj->setStatus(0);
                    $this->deliveryService->insert($delivery_obj);
                }
            }
        }
        return TRUE;
    }

    public function checkSerialize($name, &$data)
    {
        switch ($name) {
            case "func_opt_list":
                if (empty($data["func_opt_list"])) {
                    if (($data["func_opt_list"] = $this->deliveryService->funcOptionService->getListWithKey([], ["limit" => -1])) === FALSE) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    } else {
                        $_SESSION["func_opt_list"] = serialize($data["func_opt_list"]);
                    }
                }
                break;
            case "del_opt_list":
                if (empty($data["del_opt_list"])) {
                    if (($data["del_opt_list"] = $this->deliveryService->deliveryOptionService->getListWithKey([], ["limit" => -1])) === FALSE) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    } else {
                        $_SESSION["del_opt_list"] = serialize($data["del_opt_list"]);
                    }
                }
                break;
            case "delivery_list":
                if (empty($data["delivery_list"])) {
                    if (($data["delivery_list"] = $this->deliveryService->getListWithKey([], ["limit" => -1])) === FALSE) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    } else {
                        $_SESSION["delivery_list"] = serialize($data["delivery_list"]);
                    }
                }
                break;
        }
    }
}
