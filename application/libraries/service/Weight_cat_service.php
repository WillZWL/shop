<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Weight_cat_service extends Base_service
{

    private $wcc_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Weight_category_dao.php");
        $this->set_dao(new Weight_category_dao());
        include_once(APPPATH . "libraries/dao/Weight_cat_charge_dao.php");
        $this->set_wcc_dao(new Weight_cat_charge_dao());
    }

    public function get_pbv_dao()
    {
        return $this->pbv_dao;
    }

    public function set_pbv_dao(Base_dao $dao)
    {
        $this->pbv_dao = $dao;
    }

    public function get_wcc_w_reg_list($where = array(), $option = array())
    {
        include_once(APPPATH . "libraries/service/Courier_service.php");
        include_once(APPPATH . "helpers/object_helper.php");
        $courier_service = new Courier_service();
        $option["limit"] = -1;
        $cat_w_reg_list = $this->get_dao()->get_cat_w_region(array(), $option, "Freight_cat_w_region_dto");
        $courier_reg_list = $courier_service->get_crc_dao()->get_list(array("courier_id" => $where["courier_id"]), array("orderby" => "region_id ASC", "limit" => -1));
        $wcc_list = $this->get_wcc_dao()->get_list($where, array("orderby" => "wcat_id ASC, region_id ASC", "limit" => -1));
        $wcc_vo = $this->get_wcc_dao()->get();

        foreach ($wcc_list as $wcc) {
            $new_wcc[$wcc->get_wcat_id()][$wcc->get_region_id()] = $wcc;
        }
        foreach ($cat_w_reg_list as $cat) {
            $cat_id = $cat->get_cat_id();
            foreach ($courier_reg_list as $region) {
                $region_id = $region->get_region_id();
                if (empty($new_wcc[$cat_id][$region_id])) {
                    $vo = clone $wcc_vo;
                    set_value($vo, $region);
                    $vo->set_wcat_id($cat_id);
                    $new_wcc[$cat_id][$region_id] = $vo;
                }
            }
            if ($new_wcc[$cat_id]) {
                ksort($new_wcc[$cat_id]);
            }
            $cat->set_charge((object)$new_wcc[$cat_id]);
            $new_cat[$cat_id] = $cat;
        }

        return $new_cat;
    }

    public function get_wcc_dao()
    {
        return $this->wcc_dao;
    }

    public function set_wcc_dao(Base_dao $dao)
    {
        $this->wcc_dao = $dao;
    }

    public function get_full_weight_cat_charge_list($where = array(), $option = array())
    {
        if ($objlist = $this->get_wcc_dao()->get_full_weight_cat_charge_list($where, $option)) {
            foreach ($objlist as $obj) {
                $rs[$obj->get_wcat_id()][$obj->get_dest_country()] = $obj;
            }
            return $rs;
        }
        return FALSE;
    }

    public function get_default_delivery_charge($platform_id, $shiptype, $weight)
    {
        $dao = $this->get_dao();
        return $dao->get_default_delivery_charge($platform_id, $shiptype, $weight);
    }

    public function get_courier_country_list($weight)
    {
        $dao = $this->set_wcc_dao();
        return $dao->get_courier_country_list($weight);
    }

    public function get_wc_from_fc($fc = "")
    {
        return $this->get_dao()->get_from_fc($fc);
    }

    public function insert_wcc($obj)
    {
        return $this->get_wcc_dao()->insert($obj);
    }

    public function update_wcc($obj)
    {
        return $this->get_wcc_dao()->update($obj);
    }
}

/* End of file weight_cat_service.php */
/* Location: ./system/application/libraries/service/Weight_cat_service.php */