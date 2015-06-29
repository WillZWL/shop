<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Customer_extraction_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $CI->load->library('dao/selling_platform_dao');
        $CI->load->library('dao/category_dao');
        $CI->load->library('dao/exchange_rate_dao');
        $CI->load->library('service/pagination_service');
        $this->db = $CI->db;

        $this->set_dao($CI->selling_platform_dao);

        $this->selling_platform_dao = $CI->selling_platform_dao;
        $this->category_dao = $CI->category_dao;
        $this->exchange_rate_dao = $CI->exchange_rate_dao;

        $this->pagination_service = $CI->pagination_service;
    }

    public function get_platform_ex($full_list,$input)
    {
        $rtn = $full_list;

        foreach($input as $key=>$value)
        {
            unset($rtn[$key]);
        }

        return $rtn;
    }

    public function get_platform_list($where=array(), $option=array())
    {
        $rtn = array();
        $option["limit"] = -1;
        $obj_array = $this->selling_platform_dao->get_list($where, $option);
        foreach ($obj_array as $obj)
        {
            $rtn[$obj->get_id()] = $obj->get_name();
        }

        return $rtn;
    }

    public function get_category_ex($full_list,$input)
    {
        $rtn = $full_list;
        if($input){
            foreach($input as $key=>$value)
            {
                unset($rtn[$key]);
            }
        }

        return $rtn;
    }

    public function get_combined_cat_list($where=array(), $option=array())
    {
        $rtn = array();
        $option["limit"] = -1;
        $obj_array = $this->category_dao->get_combined_cat_list($where, $option);
        foreach ($obj_array as $obj)
        {
            $rtn[$obj->get_id()] = $obj->get_name();
        }

        return $rtn;
    }

    public function get_exchange_rate($where=array(), $option=array())
    {
        $rtn = array();
        $option["limit"] = -1;
        $obj_array = $this->exchange_rate_dao->get_list($where, $option);
        foreach ($obj_array as $obj)
        {
            $rtn[$obj->get_from_currency_id()] = $obj->get_rate();
        }

        return $rtn;
    }

}
