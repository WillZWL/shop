<?php
class Delivery_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/delivery_service');
        $this->load->library('service/language_service');
        $this->load->library('service/region_service');
        $this->load->library('service/country_service');
    }

    public function update_content($vo, &$data)
    {
        foreach ($_POST["func_opt"] as $rs_lang_id=>$rs_func_list)
        {
            foreach ($rs_func_list as $rs_func_id=>$rs_id_list)
            {
                foreach ($rs_id_list as $rs_id=>$rs_text)
                {
                    if ($rs_id == "new")
                    {
                        if ($rs_text != "")
                        {
                            $data["func_opt_list"][$rs_lang_id][$rs_func_id] = clone $vo["func_opt"];
                            $data["func_opt_list"][$rs_lang_id][$rs_func_id]->set_func_id($rs_func_id);
                            $data["func_opt_list"][$rs_lang_id][$rs_func_id]->set_lang_id($rs_lang_id);
                            $data["func_opt_list"][$rs_lang_id][$rs_func_id]->set_text($rs_text);
                            if (!$this->delivery_service->get_func_opt_srv()->insert($data["func_opt_list"][$rs_lang_id][$rs_func_id]))
                            {
                                $_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
                                return FALSE;
                            }
                        }
                    }
                    else
                    {
                        if ($data["func_opt_list"][$rs_lang_id][$rs_func_id]->get_text() == $rs_text)
                        {
                            continue;
                        }
                        elseif ($rs_text == "")
                        {
                            if (!$this->delivery_service->get_func_opt_srv()->q_delete(array("id"=>$rs_id)))
                            {
                                $_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
                                return FALSE;
                            }
                        }
                        else
                        {
                            $data["func_opt_list"][$rs_lang_id][$rs_func_id]->set_text($rs_text);
                            if(!$this->delivery_service->get_func_opt_srv()->update($data["func_opt_list"][$rs_lang_id][$rs_func_id]))
                            {
                                $_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
                                return FALSE;
                            }
                        }
                    }
                }
            }
        }

        foreach ($_POST["del_opt"] as $rs_lang_id=>$rs_courier_list)
        {
            foreach ($rs_courier_list as $rs_courier_id=>$rs_id_list)
            {
                foreach ($rs_id_list as $rs_id=>$rs_display_name)
                {
                    if ($rs_id == "new")
                    {
                        if ($rs_display_name != "")
                        {
                            $data["del_opt_list"][$rs_lang_id][$rs_courier_id] = clone $vo["del_opt"];
                            $data["del_opt_list"][$rs_lang_id][$rs_courier_id]->set_courier_id($rs_courier_id);
                            $data["del_opt_list"][$rs_lang_id][$rs_courier_id]->set_lang_id($rs_lang_id);
                            $data["del_opt_list"][$rs_lang_id][$rs_courier_id]->set_display_name($rs_display_name);
                            if (!$this->delivery_service->get_del_opt_srv()->insert($data["del_opt_list"][$rs_lang_id][$rs_courier_id]))
                            {
                                $_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
                                return FALSE;
                            }
                        }
                    }
                    else
                    {
                        if ($data["del_opt_list"][$rs_lang_id][$rs_courier_id]->get_display_name() == $rs_display_name)
                        {
                            continue;
                        }
                        elseif ($rs_display_name == "")
                        {
                            if (!$this->delivery_service->get_del_opt_srv()->q_delete(array("id"=>$rs_id)))
                            {
                                $_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
                                return FALSE;
                            }
                        }
                        else
                        {
                            $data["del_opt_list"][$rs_lang_id][$rs_courier_id]->set_display_name($rs_display_name);
                            if(!$this->delivery_service->get_del_opt_srv()->update($data["del_opt_list"][$rs_lang_id][$rs_courier_id]))
                            {
                                $_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
                                return FALSE;
                            }
                        }
                    }
                }
            }
        }

        return TRUE;
    }

    public function update_delivery($vo, &$data)
    {
        foreach ($_POST["del"] as $rs_delivery_type_id=>$rs_country_list)
        {
            foreach ($rs_country_list as $rs_country_id=>$rs_data)
            {
                if ($rs_data["status"])
                {
                    $ar_status = each($rs_data["status"]);
                    $old_status = $ar_status["key"];
                    $new_status = $ar_status["value"];
                }
                else
                {
                    $new_status = 0;
                }

                $ar_min = each($rs_data["min"]);
                $old_min = $ar_min["key"]=="null"?"":$ar_min["key"];
                $new_min = $new_status?$ar_min["value"]:null;

                $ar_max = each($rs_data["max"]);
                $old_max = $ar_max["key"]=="null"?"":$ar_max["key"];
                $new_max = $new_status?$ar_max["value"]:null;

                if(!empty($data["delivery_list"][$rs_delivery_type_id][$rs_country_id]))
                {
                    $data["delivery_list"][$rs_delivery_type_id][$rs_country_id]->set_status($new_status);
                    $data["delivery_list"][$rs_delivery_type_id][$rs_country_id]->set_min_day($new_min);
                    $data["delivery_list"][$rs_delivery_type_id][$rs_country_id]->set_max_day($new_max);
                    if(!($old_status == $new_status && $old_min == $new_min && $old_max == $new_max) && !$this->delivery_service->update($data["delivery_list"][$rs_delivery_type_id][$rs_country_id]))
                    {
                        $_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
                        return FALSE;
                    }
                }
                else
                {
                    $delivery_obj = $this->delivery_service->get();
                    $delivery_obj->set_delivery_type_id($rs_delivery_type_id);
                    $delivery_obj->set_country_id($rs_country_id);
                    $delivery_obj->set_min_day(null);
                    $delivery_obj->set_max_day(null);
                    $delivery_obj->set_status(0);
                    $this->delivery_service->insert($delivery_obj);
                }
            }
        }
        return TRUE;
    }

    public function check_serialize($name, &$data)
    {
        switch ($name)
        {
            case "func_opt_list":
                if (empty($data["func_opt_list"]))
                {
                    if (($data["func_opt_list"] = $this->delivery_service->get_func_opt_srv()->get_list_w_key(array(), array("limit"=>-1))) === FALSE)
                    {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                    else
                    {
                        $_SESSION["func_opt_list"] = serialize($data["func_opt_list"]);
                    }
                }
                break;
            case "del_opt_list":
                if (empty($data["del_opt_list"]))
                {
                    if (($data["del_opt_list"] = $this->delivery_service->get_del_opt_srv()->get_list_w_key(array(), array("limit"=>-1))) === FALSE)
                    {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                    else
                    {
                        $_SESSION["del_opt_list"] = serialize($data["del_opt_list"]);
                    }
                }
                break;
            case "delivery_list":
                if (empty($data["delivery_list"]))
                {
                    if (($data["delivery_list"] = $this->delivery_service->get_list_w_key(array(), array("limit"=>-1))) === FALSE)
                    {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                    else
                    {
                        $_SESSION["delivery_list"] = serialize($data["delivery_list"]);
                    }
                }
                break;
        }
    }
}

/* End of file delivery_model.php */
/* Location: ./system/application/models/delivery_model.php */
