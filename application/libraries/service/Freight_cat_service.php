<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Freight_cat_service extends Base_service {

    private $fcc_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Freight_category_dao.php");
        $this->set_dao(new Freight_category_dao());
        include_once(APPPATH."libraries/dao/Freight_cat_charge_dao.php");
        $this->set_fcc_dao(new Freight_cat_charge_dao());
        include_once(APPPATH."libraries/dao/Fulfillment_centre_dao.php");
        $this->set_fc_dao(new Fulfillment_centre_dao());
        include_once(APPPATH."libraries/dao/Platform_biz_var_dao.php");
        $this->set_pbv_dao(new Platform_biz_var_dao());
    }

    public function get_fcc_dao()
    {
        return $this->fcc_dao;
    }

    public function set_fcc_dao(Base_dao $dao)
    {
        $this->fcc_dao = $dao;
    }

    public function get_fcc_w_reg_list($where=array(), $option=array())
    {
        include_once(APPPATH."libraries/service/Courier_service.php");
        include_once(APPPATH."helpers/object_helper.php");
        $courier_service = new Courier_service();

        $option["limit"] = -1;
        $cat_w_reg_list = $this->get_dao()->get_cat_w_region(array(), $option, "Freight_cat_w_region_dto");
        $courier_reg_list = $courier_service->get_crc_dao()->get_list(array("courier_id"=>$where["courier_id"]), array("orderby"=>"region_id ASC", "limit"=>-1));
        $fcc_list = $this->get_fcc_dao()->get_list(array("courier_id"=>$where["courier_id"]), array("orderby"=>"fcat_id ASC, region_id ASC", "limit"=>-1));
        $fcc_vo = $this->get_fcc_dao()->get();

        foreach ($fcc_list as $fcc)
        {
            $new_fcc[$fcc->get_fcat_id()][$fcc->get_region_id()] = $fcc;
        }
        foreach ($cat_w_reg_list as $cat)
        {
            $cat_id = $cat->get_cat_id();
            foreach ($courier_reg_list as $region)
            {
                $region_id = $region->get_region_id();
                if (empty($new_fcc[$cat_id][$region_id]))
                {
                    $vo = clone $fcc_vo;
                    set_value($vo, $region);
                    $vo->set_fcat_id($cat_id);
                    $new_fcc[$cat_id][$region_id] = $vo;
                }
            }
            $cat->set_charge((object) $new_fcc[$cat_id]);
            $new_cat[$cat_id] = $cat;
        }
        return $new_cat;
    }

    public function get_fc_dao()
    {
        return $this->fc_dao;
    }

    public function set_fc_dao(Base_dao $dao)
    {
        $this->fc_dao = $dao;
    }

    public function get_pbv_dao()
    {
        return $this->pbv_dao;
    }

    public function set_pbv_dao(Base_dao $dao)
    {
        $this->pbv_dao = $dao;
    }

    public function get_origin_country_list()
    {
        $list = $this->get_fc_dao()->get_list();
        foreach($list AS $key=>$obj)
        {
            $rs[] = $obj->get_country_id();
        }
        return $rs;
    }

    public function get_full_freight_cat_charge_list($where, $option)
    {
        $fc_name_list = $combine_fcc_list = array();
        $fc_list = $this->get_dao()->get_list(array("status"=>1), array("orderby"=>"id ASC", "LIMIT"=>-1));
        $dest_country_list = $this->get_pbv_dao()->get_unique_dest_country_list();
        $fcc_vo = $this->get_fcc_dao()->get();
        $current_fcc_list = $this->get_fcc_dao()->get_list($where, $option);
        foreach($current_fcc_list AS $fcc_obj)
        {
            $combine_fcc_list[$fcc_obj->get_fcat_id()][$fcc_obj->get_dest_country()] = $fcc_obj;
        }
        foreach($fc_list AS $fc_obj)
        {
            $fc_name_list["frieght_cat_arr"][$fc_obj->get_id()] = $fc_obj->get_name();
            //$combine_fcc_list[$fc_obj->get_id()]['fc_obj'] = $fc_obj;
            foreach($dest_country_list AS $dest_country_arr)
            {
                $fc_name_list["dest_country_arr"][$dest_country_arr['country_id']] = $dest_country_arr['country_name'];
                if(empty($combine_fcc_list[$fc_obj->get_id()][$dest_country_arr['country_id']]))
                {
                    $vo = clone $fcc_vo;
                    $vo->set_fcat_id($fc_obj->get_id());
                    $vo->set_origin_country($fc_obj->get_id());
                    //Frankie confirmed that they will only have HKD as frieght cost
                    $vo->set_currency_id("HKD");
                    $vo->set_amount(0);
                    $combine_fcc_list[$fc_obj->get_id()][$dest_country_arr['country_id']] = $vo;
                }
            }
        }
        $ret = array("value_list"=>$combine_fcc_list, "key_list" => $fc_name_list);
        return $ret;
    }

    public function get_freight_cat_charge_obj($where = array())
    {
        return $this->get_fcc_dao()->get($where);
    }

    public function insert_fcc($obj)
    {
        return $this->get_fcc_dao()->insert($obj);
    }

    public function update_fcc($obj)
    {
        return $this->get_fcc_dao()->update($obj);
    }
}

/* End of file freight_cat_service.php */
/* Location: ./system/application/libraries/service/Freight_cat_service.php */