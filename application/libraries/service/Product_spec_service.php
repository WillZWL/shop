<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Product_spec_service extends Base_service
{

    private $psg_dao;
    private $psd_dao;
    private $unit_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Product_spec_dao.php");
        $this->set_dao(new Product_spec_dao());
        include_once(APPPATH."libraries/dao/Product_spec_group_dao.php");
        $this->set_psg_dao(new Product_spec_group_dao());
        include_once(APPPATH."libraries/dao/Product_spec_details_dao.php");
        $this->set_psd_dao(new Product_spec_details_dao());
        include_once(APPPATH."libraries/dao/Category_product_spec_dao.php");
        $this->set_cps_dao(new Category_product_spec_dao());
        include_once(APPPATH."libraries/dao/Unit_dao.php");
        $this->set_unit_dao(new Unit_dao());
        include_once(APPPATH . 'libraries/service/Language_service.php');
        $this->set_lang_srv(new Language_service());
    }

    public function get_psg_dao()
    {
        return $this->psg_dao;
    }

    public function set_psg_dao(Base_dao $dao)
    {
        $this->psg_dao = $dao;
    }

    public function get_psd_dao()
    {
        return $this->psd_dao;
    }

    public function set_psd_dao(Base_dao $dao)
    {
        $this->psd_dao = $dao;
    }

    public function get_unit_dao()
    {
        return $this->unit_dao;
    }

    public function set_unit_dao(Base_dao $dao)
    {
        $this->unit_dao = $dao;
    }

    public function get_prod_spec_group_list($where, $option)
    {
        return $this->get_psg_dao()->get_list($where, $option);
    }

    public function get_prod_spec_group($where)
    {
        return $this->get_psg_dao()->get($where);
    }

    public function get_prod_spec($where)
    {
        return $this->get_dao()->get($where);
    }

    public function get_prod_spec_list($where, $option)
    {
        return $this->get_dao()->get_list($where, $option);
    }

    public function get_no_of_row_psl($where)
    {
        return $this->get_dao()->get_num_rows($where);
    }

    public function add_prod_spec($prod_spec_obj)
    {
        return $this->get_dao()->insert($prod_spec_obj);
    }

    public function update_prod_spec($prod_spec_obj)
    {
        return $this->get_dao()->update($prod_spec_obj);
    }

    public function get_cps_dao()
    {
        return $this->cps_dao;
    }

    public function set_cps_dao(Base_dao $dao)
    {
        $this->cps_dao = $dao;
    }

    public function get_lang_srv()
    {
        return $this->lang_srv;
    }

    public function set_lang_srv($serv)
    {
        $this->lang_srv = $serv;
    }

    public function get_product_spec_with_sku($sku, $lang_id)
    {
        $data = array();
        if ($ps_list = $this->get_psd_dao()->get_product_spec_with_sku($sku, $lang_id))
        {
            foreach ($ps_list as $obj)
            {
                /*
                if($obj->get_unit_id() != "txt")
                {
                    if($obj->get_end_value())
                    {
                        $obj->set_final_value($obj->get_start_value()." - ".$obj->get_end_value());
                    }
                    else
                    {
                        $obj->set_final_value($obj->get_start_value());
                    }
                }
                */
                $data[$obj->get_psg_func_id()][$obj->get_ps_func_id()] = $obj;
            }
        }
        return $data;
    }

    public function get_cat_prod_spec_list($where, $option)
    {
        return $this->get_cps_dao()->get_list($where, $option);
    }

    public function get_full_cps_list($cat_id)
    {
        return $this->get_cps_dao()->get_full_cps_list($cat_id);
    }

    public function get_cps($where)
    {
        return $this->get_cps_dao()->get($where);
    }

    public function insert_cps($obj)
    {
        return $this->get_cps_dao()->insert($obj);
    }

    public function update_cps($obj)
    {
        return $this->get_cps_dao()->update($obj);
    }

    public function get_full_psd_w_lang($sub_cat_id, $sku, $lang_id)
    {
        return $this->get_psd_dao()->get_full_psd_w_lang($sub_cat_id, $sku, $lang_id);
    }

    public function update_response_psd_list($sku, $sub_cat_id, $response_psd_list)
    {
        if($response_psd_list)
        {
            foreach($response_psd_list AS $lang_id=>$psd_list)
            {
                foreach($psd_list AS $psd_id=>$psd_array)
                {
                    $old_text = $old_start_value = $old_end_value = $psd_action = '';
                    $psd_obj = $this->get_psd_dao()->get(array("ps_id"=>$psd_id, "cat_id"=>$sub_cat_id, "prod_sku"=>$sku, "lang_id"=>$lang_id));
                    if($psd_obj)
                    {
                        $old_text = $psd_obj->get_text();
                        $old_start_value = $psd_obj->get_start_value();
                        $old_end_value = $psd_obj->get_end_value();
                    }
                    else
                    {
                        $psd_obj = $this->get_psd_dao()->get();
                        $psd_obj->set_ps_id($psd_id);
                        $psd_obj->set_cat_id($sub_cat_id);
                        $psd_obj->set_prod_sku($sku);
                        $psd_obj->set_lang_id($lang_id);
                    }
                    foreach($psd_array AS $unit_id=>$psd_value_array)
                    {
                        $unit_obj = $this->get_unit_dao()->get(array('id'=>$unit_id));
                        $unit_standardize_value = $unit_obj->get_standardize_value();
                        foreach($psd_value_array AS $psd_key=>$psd_value)
                        {
                            switch ($psd_key)
                            {
                                case 'text':
                                    if($old_text)
                                    {
                                        if($psd_value)
                                        {
                                            if($old_text != $psd_value)
                                            {
                                                $psd_obj->set_text($psd_value);
                                                $psd_action = 'update';
                                            }
                                        }
                                        else
                                        {
                                            //$psd_obj->set_text(NULL);
                                            $psd_action = 'delete';
                                        }
                                    }
                                    else
                                    {
                                        if($psd_value)
                                        {
                                            $psd_obj->set_text($psd_value);
                                            $psd_obj->set_cps_unit_id($unit_id);
                                            $psd_action = 'insert';
                                        }
                                    }
                                    break;
                                case 'start_value':
                                    if($old_start_value)
                                    {
                                        if($psd_value)
                                        {
                                            if($old_start_value != $psd_value)
                                            {
                                                $psd_obj->set_start_value($psd_value);
                                                $psd_obj->set_start_standardize_value($unit_standardize_value * $psd_value);
                                                $psd_action = 'update';
                                            }
                                        }
                                        else
                                        {
                                            //$psd_obj->set_start_value(NULL);
                                            //$psd_obj->set_start_standardize_value(NULL);
                                            $psd_action = 'delete';
                                        }
                                    }
                                    else
                                    {
                                        if($psd_value)
                                        {
                                            $psd_obj->set_start_value($psd_value);
                                            $psd_obj->set_start_standardize_value($unit_standardize_value * $psd_value);
                                            $psd_obj->set_cps_unit_id($unit_id);
                                            $psd_action = 'insert';
                                        }
                                    }
                                    break;
                                case 'end_value':
                                    if($old_end_value)
                                    {
                                        if($psd_value)
                                        {
                                            if($old_end_value != $psd_value)
                                            {
                                                $psd_obj->set_end_value($psd_value);
                                                $psd_obj->set_end_standardize_value($unit_standardize_value * $psd_value);
                                                $psd_action = 'update';
                                            }
                                        }
                                        else
                                        {
                                            //$psd_obj->set_end_value(NULL);
                                            //$psd_obj->set_end_standardize_value(NULL);
                                            $psd_action = 'delete';
                                        }
                                    }
                                    else
                                    {
                                        if($psd_value)
                                        {
                                            $psd_obj->set_end_value($psd_value);
                                            $psd_obj->set_end_standardize_value($unit_standardize_value * $psd_value);
                                            $psd_obj->set_cps_unit_id($unit_id);
                                            $psd_action = 'insert';
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                    if($psd_action)
                    {
                        if(!$this->get_psd_dao()->$psd_action($psd_obj))
                        {
                            $_SESSION["NOTICE"] = $this->db->_error_message();
                            break;
                        }
                    }
                }
            }
        }
        return;
    }

    public function populate_to_all_lang($ps_id, $sub_cat_id, $sku, $lang_id)
    {
        $lang_list = $this->get_lang_srv()->get_list(array("status"=>1), array());
        if($obj = $this->get_psd_dao()->get(array("ps_id"=>$ps_id, "cat_id"=>$sub_cat_id, "prod_sku"=>$sku, "lang_id"=>$lang_id)))
        {
            foreach($lang_list AS $lang_obj)
            {
                if($lang_obj->get_id() != $lang_id)
                {
                    if($target_obj = $this->get_psd_dao()->get(array("ps_id"=>$ps_id, "cat_id"=>$sub_cat_id, "prod_sku"=>$sku, "lang_id"=>$lang_obj->get_id())))
                    {
                        $action = "update";
                    }
                    else
                    {
                        $target_obj = $this->get_psd_dao()->get();
                        $action = "insert";
                    }
                    set_value($target_obj, $obj);
                    $target_obj->set_lang_id($lang_obj->get_id());
                    $this->get_psd_dao()->$action($target_obj);
                }
            }
        }
        else
        {
            foreach($lang_list AS $lang_obj)
            {
                if($lang_obj->get_id() != $lang_id)
                {
                    if($target_obj = $this->get_psd_dao()->get(array("ps_id"=>$ps_id, "cat_id"=>$sub_cat_id, "prod_sku"=>$sku, "lang_id"=>$lang_obj->get_id())))
                    {
                        $action = "delete";
                        $this->get_psd_dao()->$action($target_obj);
                    }
                }
            }
        }
    }
}

/* End of file product_spec_service.php */
/* Location: ./system/application/libraries/service/Product_spec_service.php */