<?php
namespace ESG\Panther\Service;

class FreightCatService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getFulFillmentCentreDao()
    {
        return $this->fulfillmentCentreDao;
    }

    public function setFulFillmentCentreDao($dao)
    {
        $this->fulfillmentCentreDao = $dao;
    }

    public function getFccWithRegList($where = [], $option = [])
    {
        $option["limit"] = -1;
        $cat_w_reg_list = $this->getDao('FreightCategory')->getCatWithRegion([], $option, "Freight_cat_w_region_dto");
        $courier_reg_list = $this->getDao('CourierRegion')->getList(["courier_id" => $where["courier_id"]], ["orderby" => "region_id ASC", "limit" => -1]);
        $fcc_list = $this->getDao('FreightCatCharge')->getList(["courier_id" => $where["courier_id"]], ["orderby" => "fcat_id ASC, region_id ASC", "limit" => -1]);
        $fcc_vo = $this->getDao('FreightCatCharge')->get();

        foreach ($fcc_list as $fcc) {
            $new_fcc[$fcc->getFcatId()][$fcc->getRegionId()] = $fcc;
        }
        foreach ($cat_w_reg_list as $cat) {
            $cat_id = $cat->getCatId();
            foreach ($courier_reg_list as $region) {
                $region_id = $region->getRegionId();
                if (empty($new_fcc[$cat_id][$region_id])) {
                    $vo = clone $fcc_vo;
                    set_value($vo, $region);
                    $vo->setFcatId($cat_id);
                    $new_fcc[$cat_id][$region_id] = $vo;
                }
            }
            $cat->setCharge((object)$new_fcc[$cat_id]);
            $new_cat[$cat_id] = $cat;
        }
        return $new_cat;
    }

    public function getOriginCountryList()
    {
       return $this->getDao('FulfillmentCentre')->getList();
    }

    public function getFullFreightCatChargeList($where, $option)
    {
        $fc_name_list = $combine_fcc_list = [];
        $fc_list = $this->getDao('FreightCategory')->getList(["status" => 1], ["orderby" => "id ASC", "LIMIT" => -1]);
        $dest_country_list = $this->getDao('PlatformBizVar')->getUniqueDestCountryList();
        $fcc_vo = $this->getDao('FreightCatCharge')->get();
        $current_fcc_list = $this->getDao('FreightCatCharge')->getList($where, $option);
        foreach ($current_fcc_list AS $fcc_obj) {
            $combine_fcc_list[$fcc_obj->getFcatId()][$fcc_obj->getDestCountry()] = $fcc_obj;
        }
        foreach ($fc_list AS $fc_obj) {
            $fc_name_list["frieght_cat_arr"][$fc_obj->getId()] = $fc_obj->getName();
            if ($dest_country_list) {
                foreach ($dest_country_list AS $dest_country_arr) {
                    $fc_name_list["dest_country_arr"][$dest_country_arr['country_id']] = $dest_country_arr['country_name'];
                    if (empty($combine_fcc_list[$fc_obj->getId()][$dest_country_arr['country_id']])) {
                        $vo = clone $fcc_vo;
                        $vo->setFcatId($fc_obj->getId());
                        $vo->setOriginCountry($fc_obj->getId());
                        $vo->setCurrencyId("HKD");
                        $vo->setAmount(0);
                        $combine_fcc_list[$fc_obj->getId()][$dest_country_arr['country_id']] = $vo;
                    }
                }
            }
        }
        $ret = ["value_list" => $combine_fcc_list, "key_list" => $fc_name_list];
        return $ret;
    }

    public function saveFreightCatCharge($values = [], $origin_country = "")
    {
        $fcc_vo = $this->getDao('FreightCatCharge')->get();
        foreach ($values AS $fcat_id => $country_value_arr) {
            foreach ($country_value_arr AS $dest_country => $value) {
                $obj = $this->getDao('FreightCatCharge')->get(["origin_country" => $origin_country, "fcat_id" => $fcat_id, "dest_country" => $dest_country]);
                if (!$obj) {
                    $obj = clone $fcc_vo;
                    $obj->setFcatId($fcat_id);
                    $obj->setOriginCountry($origin_country);
                    $obj->setDestCountry($dest_country);
                    $obj->setCurrencyId("HKD");
                    $action = "insertFcc";
                } else {
                    $action = "updateFcc";
                }
                $obj->setAmount($value);

                if (!$this->$action($obj)) {
                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                }
            }
        }
    }

    public function insertFcc($obj)
    {
        return $this->getDao('FreightCatCharge')->insert($obj);
    }

    public function updateFcc($obj)
    {
        return $this->getDao('FreightCatCharge')->update($obj);
    }
}


