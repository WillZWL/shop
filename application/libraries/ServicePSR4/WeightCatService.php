<?php
namespace AtomV2\Service;

use AtomV2\Dao\WeightCategoryDao;
use AtomV2\Dao\WeightCatChargeDao;

class WeightCatService extends BaseService
{

    private $wcc_dao;

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new WeightCategoryDao);
        $this->setWccDao(new WeightCatChargeDao);
    }

    public function get_pbv_dao()
    {
        return $this->pbv_dao;
    }

    public function set_pbv_dao($dao)
    {
        $this->pbv_dao = $dao;
    }

    public function get_wcc_w_reg_list($where = [], $option = [])
    {
        include_once(APPPATH . "libraries/service/Courier_service.php");
        include_once(APPPATH . "helpers/object_helper.php");
        $courier_service = new Courier_service();
        $option["limit"] = -1;
        $cat_w_reg_list = $this->getDao()->get_cat_w_region([], $option, "Freight_cat_w_region_dto");
        $courier_reg_list = $courier_service->get_crc_dao()->get_list(["courier_id" => $where["courier_id"]], ["orderby" => "region_id ASC", "limit" => -1]);
        $wcc_list = $this->getWccDao()->getList($where, ["orderby" => "wcat_id ASC, region_id ASC", "limit" => -1]);
        $wcc_vo = $this->getWccDao()->get();

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

    public function getWccDao()
    {
        return $this->wcc_dao;
    }

    public function setWccDao($dao)
    {
        $this->wcc_dao = $dao;
    }

    public function get_full_weight_cat_charge_list($where = [], $option = [])
    {
        if ($objlist = $this->getWccDao()->get_full_weight_cat_charge_list($where, $option)) {
            foreach ($objlist as $obj) {
                $rs[$obj->get_wcat_id()][$obj->get_dest_country()] = $obj;
            }
            return $rs;
        }
        return FALSE;
    }

    public function get_default_delivery_charge($platform_id, $shiptype, $weight)
    {
        $dao = $this->getDao();
        return $dao->get_default_delivery_charge($platform_id, $shiptype, $weight);
    }

    public function get_courier_country_list($weight)
    {
        $dao = $this->setWccDao();
        return $dao->get_courier_country_list($weight);
    }

    public function get_wc_from_fc($fc = "")
    {
        return $this->getDao()->get_from_fc($fc);
    }

    public function insert_wcc($obj)
    {
        return $this->getWccDao()->insert($obj);
    }

    public function update_wcc($obj)
    {
        return $this->getWccDao()->update($obj);
    }
}


