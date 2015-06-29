<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Region_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $CI->load->library('dao/region_country_dao');
        $CI->load->library('dao/country_dao');
        $CI->load->library('dao/region_dao');
        $CI->load->library('service/pagination_service');
        $this->db = $CI->db;

//      include_once(APPPATH."libraries/dao/Region_dao.php");
        $this->set_dao($CI->region_dao);

        $this->region_country_dao = $CI->region_country_dao;
        $this->country_dao = $CI->country_dao;
        $this->region_dao = $CI->region_dao;
        $this->pagination_service = $CI->pagination_service;
    }

    public function get_region($id = "")
    {
        if ($id != "") {
            $ret = $this->region_dao->get(array('id' => $id));
        } else {
            $ret = $this->region_dao->get();
        }
        return $ret;
    }

    public function update_region($data)
    {
        return $this->region_dao->update($data);
    }

    public function add_region($data)
    {
        return $this->region_dao->insert($data);
    }

    public function delete_region($id)
    {
        return $this->region_dao->q_delete(array("id" => "$id"));
    }

    public function get_all_region($offset = "")
    {
        return $this->region_dao->get(array(), array("offset" => $offset, "limit" => $this->pagination->get_num_records_per_page()));
    }

    public function get_region_by_country_and_name($countryid, $region_name, $classname)
    {
        return $this->region_country_dao->get_region_by_country_and_name($countryid, $region_name, $classname);
    }


    public function get_country_in_region($value)
    {
        $rtn = array();
        $obj_array = $this->region_country_dao->get_regionid_countryname($value, "region_countryname_dto");
        foreach ($obj_array as $obj) {
            $rtn[$obj->get_country_id()] = $obj->get_name();
        }
        return $rtn;
    }

    public function get_country_ex($full_list, $input)
    {
        $rtn = $full_list;
        foreach ($input as $key => $value) {
            unset($rtn[$key]);
        }

        return $rtn;
    }

    public function get_country_list($where = array(), $option = array())
    {
        $rtn = array();
        $option["limit"] = -1;
        $obj_array = $this->country_dao->get_list($where, $option);
        foreach ($obj_array as $obj) {
            $rtn[$obj->get_id()] = $obj->get_name();
        }

        return $rtn;
    }

    public function add_region_country($region_id, $country)
    {
        $result = TRUE;
        $obj = $this->region_country_dao->get();
        $obj->set_region_id($region_id);
        if ($country) {
            foreach ($country as $value) {
                $obj->set_country_id($value);
                $result = $result && $this->region_country_dao->insert($obj);
            }
        }

        return $result;
    }

    public function del_region_country($region_id)
    {
        return $this->region_country_dao->q_delete(array("region_id" => $region_id));
    }

    public function get_region_by_name($region_name = "", $type = "", $option = array())
    {
        return $this->region_dao->get_region_by_name_and_type($region_name, $type, $option);
    }

    public function get_sell_country_list($detail = 1)
    {
        if ($detail) {
            $rs = array();
            if ($objlist = $this->country_dao->get_sell_country_list($detail)) {
                foreach ($objlist as $obj) {
                    $rs[$obj->get_id()] = $obj->get_name();
                }
            }
            return $rs;
        } else {
            return $this->country_dao->get_sell_country_list($detail);
        }
    }

    public function get_full_country_list($detail = 1)
    {
        if ($detail) {
            $rs = array();
            if ($objlist = $this->country_dao->get_full_country_list($detail)) {
                foreach ($objlist as $obj) {
                    $rs[$obj->get_id()] = $obj->get_name();
                }
            }
            return $rs;
        } else {
            return $this->country_dao->get_full_country_list($detail);
        }
    }

    public function get_reg_name_list_w_key($where = array(), $option = array())
    {
        $data = array();
        if ($obj_list = $this->get_list($where, $option)) {
            foreach ($obj_list as $obj) {
                $data[$obj->get_id()] = $obj->get_region_name();
            }
        }
        return $data;
    }

}

?>