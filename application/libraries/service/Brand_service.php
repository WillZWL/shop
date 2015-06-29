<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Brand_service extends Base_service {

    private $br_dao;

    public function __construct(){
        parent::__construct();
        include_once(APPPATH."libraries/dao/Brand_dao.php");
        $this->set_dao(new Brand_dao());
        include_once(APPPATH."libraries/dao/Brand_region_dao.php");
        $this->set_br_dao(new Brand_region_dao());
    }

    public function get_br_dao()
    {
        return $this->br_dao;
    }

    public function set_br_dao(Base_dao $dao)
    {
        $this->br_dao = $dao;
    }

    public function get_brand_list_w_region($where=array(), $option=array())
    {
        $data["brandlist"] = $this->get_dao()->get_brand_list_w_region($where, $option, "Brand_w_region_dto");
        $data["total"] = $this->get_dao()->get_brand_list_w_region($where, array("num_rows"=>1));
        return $data;
    }

    public function get_listed_brand_by_cat($cat_id = '')
    {
        return $this->get_dao()->get_listed_brand_by_cat($cat_id);
    }

    public function get_name_list_w_id_key($where=array(), $option=array())
    {
        $option["result_type"] = "array";
        $rslist = array();
        if ($ar_list = $this->get_list($where, $option))
        {
            foreach ($ar_list as $rsdata)
            {
                $rslist[$rsdata["id"]] = $rsdata["brand_name"];
            }
        }
        return $rslist;
    }

    public function get_brand_filter_grid_info($where = array(), $option = array())
    {
        return $this->get_dao()->get_brand_filter_grid_info($where, $option);
    }

}

/* End of file Brand_service.php */
/* Location: ./system/application/libraries/service/Brand_service.php */