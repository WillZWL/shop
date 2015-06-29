<?php

class Display_qty_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/display_qty_service');
    }

    public function update_display_qty(&$data)
    {
        unset($_SESSION["NOTICE"]);
        if ($_POST["class"]) {
            foreach ($_POST["class"] as $class_id => $class_data) {
                if ($data["class_list"][$class_id]->get_drop_qty() != $class_data["drop_qty"] || $data["class_list"][$class_id]->get_default_factor() != $class_data["default_factor"]) {
                    $data["class_list"][$class_id]->set_drop_qty($class_data["drop_qty"]);
                    $data["class_list"][$class_id]->set_default_factor($class_data["default_factor"]);

                    if (!isset($_SESSION["NOTICE"])) {
                        if (!$this->display_qty_service->get_display_qty_class_dao()->update($data["class_list"][$class_id])) {
                            $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                        }
                    }
                }
            }
        }
        if ($_POST["factor"]) {
            foreach ($_POST["factor"] as $cat_id => $class_factor) {
                foreach ($class_factor as $class_id => $factor) {
                    if ($factor !== "") {
                        if (!isset($data["factor_list"][$cat_id][$class_id])) {
                            $data["factor_list"][$cat_id][$class_id] = $this->display_qty_service->get_display_qty_factor_dao()->get();
                            $action = "insert";
                            $data["factor_list"][$cat_id][$class_id]->set_cat_id($cat_id);
                            $data["factor_list"][$cat_id][$class_id]->set_class_id($class_id);
                        } else {
                            $action = "update";
                        }

                        if ($data["factor_list"][$cat_id][$class_id]->get_factor() != $factor) {
                            $data["factor_list"][$cat_id][$class_id]->set_factor($factor);

                            if (!isset($_SESSION["NOTICE"])) {
                                if (!$this->display_qty_service->get_display_qty_factor_dao()->$action($data["factor_list"][$cat_id][$class_id])) {
                                    $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($_POST["category"]) {
            foreach ($_POST["category"] as $cat_id => $min_display_qty) {
                if ($data["cat_list"][$cat_id]->get_min_display_qty() != $min_display_qty) {
                    $data["cat_list"][$cat_id]->set_min_display_qty($min_display_qty);

                    if (!isset($_SESSION["NOTICE"])) {
                        if (!$this->display_qty_service->get_cat_srv()->update($data["cat_list"][$cat_id])) {
                            $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                        }
                    }
                }
            }
        }
        if ($_POST["default_min_display_qty"]) {
            if ($data["default_min_display_qty"] != $_POST["default_min_display_qty"]) {
                $data["default_min_display_qty"]->set_value($_POST["default_min_display_qty"]);

                if (!isset($_SESSION["NOTICE"])) {
                    if (!$this->display_qty_service->get_config()->update($data["default_min_display_qty"])) {
                        $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                    }
                }
            }
        }
        if (isset($_SESSION["NOTICE"])) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}


