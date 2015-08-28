<?php
namespace AtomV2\Service;

use AtomV2\Dao\FreightCategoryDao;
use AtomV2\Dao\FreightCatChargeDao;
use AtomV2\Dao\FulfillmentCentreDao;
use AtomV2\Dao\PlatformBizVarDao;

class FreightCatService extends BaseService
{

    private $fccDao;

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new FreightCategoryDao);
        $this->setFccDao(new FreightCatChargeDao);
        $this->setFcDao(new FulfillmentCentreDao);
        $this->setPbvDao(new PlatformBizVarDao);
    }

    public function setFcDao($dao)
    {
        $this->fcDao = $dao;
    }

    public function setPbvDao($dao)
    {
        $this->pbvDao = $dao;
    }

    public function get_fcc_w_reg_list($where = [], $option = [])
    {
        include_once(APPPATH . "libraries/service/Courier_service.php");
        include_once(APPPATH . "helpers/object_helper.php");
        $courier_service = new Courier_service();

        $option["limit"] = -1;
        $cat_w_reg_list = $this->getDao()->get_cat_w_region([], $option, "Freight_cat_w_region_dto");
        $courier_reg_list = $courier_service->get_crc_dao()->get_list(["courier_id" => $where["courier_id"]], ["orderby" => "region_id ASC", "limit" => -1]);
        $fcc_list = $this->getFccDao()->getList(["courier_id" => $where["courier_id"]], ["orderby" => "fcat_id ASC, region_id ASC", "limit" => -1]);
        $fcc_vo = $this->getFccDao()->get();

        foreach ($fcc_list as $fcc) {
            $new_fcc[$fcc->get_fcat_id()][$fcc->get_region_id()] = $fcc;
        }
        foreach ($cat_w_reg_list as $cat) {
            $cat_id = $cat->get_cat_id();
            foreach ($courier_reg_list as $region) {
                $region_id = $region->get_region_id();
                if (empty($new_fcc[$cat_id][$region_id])) {
                    $vo = clone $fcc_vo;
                    set_value($vo, $region);
                    $vo->set_fcat_id($cat_id);
                    $new_fcc[$cat_id][$region_id] = $vo;
                }
            }
            $cat->set_charge((object)$new_fcc[$cat_id]);
            $new_cat[$cat_id] = $cat;
        }
        return $new_cat;
    }

    public function getFccDao()
    {
        return $this->fccDao;
    }

    public function setFccDao($dao)
    {
        $this->fccDao = $dao;
    }

    public function get_origin_country_list()
    {
        $list = $this->getFcDao()->getList();
        foreach ($list AS $key => $obj) {
            $rs[] = $obj->get_country_id();
        }
        return $rs;
    }

    public function getFcDao()
    {
        return $this->fcDao;
    }

    public function get_full_freight_cat_charge_list($where, $option)
    {
        $fc_name_list = $combine_fcc_list = [];
        $fc_list = $this->getDao()->get_list(["status" => 1], ["orderby" => "id ASC", "LIMIT" => -1]);
        $dest_country_list = $this->getPbvDao()->getUniqueDestCountryList();
        $fcc_vo = $this->getFccDao()->get();
        $current_fcc_list = $this->getFccDao()->getList($where, $option);
        foreach ($current_fcc_list AS $fcc_obj) {
            $combine_fcc_list[$fcc_obj->get_fcat_id()][$fcc_obj->get_dest_country()] = $fcc_obj;
        }
        foreach ($fc_list AS $fc_obj) {
            $fc_name_list["frieght_cat_arr"][$fc_obj->get_id()] = $fc_obj->get_name();
            //$combine_fcc_list[$fc_obj->get_id()]['fc_obj'] = $fc_obj;
            foreach ($dest_country_list AS $dest_country_arr) {
                $fc_name_list["dest_country_arr"][$dest_country_arr['country_id']] = $dest_country_arr['country_name'];
                if (empty($combine_fcc_list[$fc_obj->get_id()][$dest_country_arr['country_id']])) {
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
        $ret = ["value_list" => $combine_fcc_list, "key_list" => $fc_name_list];
        return $ret;
    }

    public function getPbvDao()
    {
        return $this->pbvDao;
    }

    public function get_freight_cat_charge_obj($where = [])
    {
        return $this->getFccDao()->get($where);
    }

    public function insert_fcc($obj)
    {
        return $this->getFccDao()->insert($obj);
    }

    public function update_fcc($obj)
    {
        return $this->getFccDao()->update($obj);
    }
}


